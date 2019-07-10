<?php
    /*
     * В этом файле ничего править не нужно.
     */
    class template
    {
        private $tags = [];

        private $template;

        public function __construct($templateFile)
        {
            $this->template = $this->getFile($templateFile);

            if(!$this->template) {
                return "Error! Can't load the template file $templateFile";
            }

        }

        public function render()
        {
            $this->replaceTags();

            echo $this->template;
        }

        public function set($tag, $value)
        {
            $this->tags[$tag] = $value;
        }

        public function getFile($file)
        {
            if(file_exists($file))
            {
                $file = file_get_contents($file);
                return $file;
            }
            else
            {
                return false;
            }
        }

        private function replaceTags()
        {
            foreach ($this->tags as $tag => $value) {
                $this->template = str_replace('{'.$tag.'}', $value, $this->template);
            }

            return true;
        }
    }

    function shift_in_left (&$arr) {
        $item = array_shift($arr);
        array_push ($arr,$item);
    }

    function shift_in_right (&$arr) {
        $item = array_pop($arr);
        array_unshift ($arr,$item);
    }

    function explode_phones($list) {
        $result = array("phones"=>[], "streams"=>[]);
        foreach ($list as $key=>$item) {
            if(stristr($item, ':') === false) {
                $result["phones"][$key] = $item;
            } else {
                $item = explode(":", $item);
                $result["phones"][$key] = $item[0];
                $result["streams"][$key] =  explode(",", $item[1]);
            }
        }
        return $result;
    }

    if (!function_exists('write_ini_file')) {

        function write_ini_file($file, $array = []) {
            if (!is_string($file)) {
                throw new \InvalidArgumentException('');
            }

            if (!is_array($array)) {
                throw new \InvalidArgumentException('');
            }

            $data = array();
            foreach ($array as $key => $val) {
                if (is_array($val)) {
                    $data[] = "[$key]";
                    foreach ($val as $skey => $sval) {
                        if (is_array($sval)) {
                            foreach ($sval as $_skey => $_sval) {
                                if (is_numeric($_skey)) {
                                    $data[] = $skey.'[] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
                                } else {
                                    $data[] = $skey.'['.$_skey.'] = '.(is_numeric($_sval) ? $_sval : (ctype_upper($_sval) ? $_sval : '"'.$_sval.'"'));
                                }
                            }
                        } else {
                            $data[] = $skey.' = '.(is_numeric($sval) ? $sval : (ctype_upper($sval) ? $sval : '"'.$sval.'"'));
                        }
                    }
                } else {
                    $data[] = $key.' = '.(is_numeric($val) ? $val : (ctype_upper($val) ? $val : '"'.$val.'"'));
                }
                $data[] = null;
            }

            $fp = fopen($file, 'w');
            $retries = 0;
            $max_retries = 100;

            if (!$fp) {
                return false;
            }

            do {
                if ($retries > 0) {
                    usleep(rand(1, 5000));
                }
                $retries += 1;
            } while (!flock($fp, LOCK_EX) && $retries <= $max_retries);

            if ($retries == $max_retries) {
                return false;
            }

            fwrite($fp, implode(PHP_EOL, $data).PHP_EOL);

            flock($fp, LOCK_UN);
            fclose($fp);

            return true;
        }
    }

    ini_set('error_reporting', E_ALL);
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);

    $config = parse_ini_file(dirname(__FILE__).'/app.ini', true);

    if($config['setting']['debug']=="false") {
        ini_set('error_reporting', 0);
        ini_set('display_errors', 0);
    }

    if(isset($_GET['m']) && $_GET['m']=='admin') {
        if($config['admin']['email']!='demo@demo.com') {
            session_start();
            if(!empty($_SESSION['user_session']) && $_SESSION['user_session'] == $config['admin']['session']) {
                $tpl = new template(dirname(__FILE__).'/template/list.tpl');
                $tr = "";
                $list = explode_phones($config['phones']);
                foreach ( $list['phones'] as $k => $phone )    {
                    $tr .= '                     
                       <tr><td data-key="Phone">'.$phone.'</td><td data-key="Streams">'.(!empty($list['streams'][$k])?"<span>".implode("</span>,<span>", $list['streams'][$k]):"-").'</td><td data-key="streams">'.(count($list['phones'])>1?'<span data-mode="edit">[настроить]</span> <span data-mode="delete">[удалить]</span>':'').'</td></tr>
                    ';
                }
                $tpl->set('list', $tr);
                $tpl->render();
            } else {
                $tpl = new template(dirname(__FILE__).'/template/login.tpl');

                //$tpl->set('header', $tpl->getFile('header.tpl'));
                $tpl->render();
            }

        } else {
            die('Функция администраторской панели выключена. Обратитесь к Администратору.');
        }
        die();
    }

    if(isset($_GET['m']) && $_GET['m']=='api') {

        $list = explode_phones($config['phones']);
        $current_stream = $_GET["stream"];
        $current_phone = $config['streams'][$current_stream];
        $total_phones = count($list['phones']);
        $available_phones = [];
        foreach ( $list['streams'] as $k => $streams )    {
            $position = array_search($current_stream, $streams);
            if(is_numeric($position)) {
                //echo "Для потока № ".$current_stream. " доступен телефон № ".$k."\n";
                array_push($available_phones, $k);
            }
        }
        $last_phone = end($available_phones);
        $index = array_search($current_phone, $available_phones);
        if($current_phone==$last_phone) {
            $next_phone = $available_phones[0];
        } else {
            if($index !== false && $index < count($available_phones)-1) $next_phone = $available_phones[$index+1];
            if(empty($next_phone)) {
                $next_phone = $available_phones[0];
            }
        }
        if($config['setting']['debug']=="true") {
            echo "current_index_stream: " . $current_stream . "\n";
            echo "current_phone: " . $current_phone . "\n";
            echo "total_phones: " . $total_phones . "\n";
            echo "next_phone: " . $next_phone . "\n";
            echo "last_phone: " . $last_phone . "\n";

            echo "available_phones: " . "\n";
            print_r($available_phones);
            echo "\n";
            echo "list: " . "\n";
            print_r($list);
            echo "\n";
        }

        $config['streams'][$current_stream] = $next_phone;
        write_ini_file(dirname(__FILE__).'/app.ini', $config);

        die();
    }

    if(isset($_GET['m']) && $_GET['m']=='addphone') {
            session_start();
            if(!empty($_SESSION['user_session']) && $_SESSION['user_session'] == $config['admin']['session']) {
                $_POST['phone'] = preg_replace("/[^0-9]/", "", $_POST['phone']);
                if(empty($_POST['phone'])) {
                    header('HTTP/1.1 401 Unauthorized');
                    exit;
                }

                /*Поиск существования номера*/

                $list = explode_phones($config['phones']);
                $edit_index = array_search($_POST['phone'], $list['phones']);

                $_POST['streams'] = preg_replace('/[^0-9,:()-]/', '', $_POST['streams']);
                $_POST['streams'] = explode(",", $_POST['streams']);

                /* Получение списка идентификаторов потоков */
                $_streams = [];
                foreach ( $list['streams'] as $k => $_item ) {
                    $_streams = array_merge($_streams, $_item);
                }
                $_streams = array_unique($_streams);


                /* Обработка новых добавляемых потоков */
                $streams = [];
                foreach ($_POST['streams'] as $k => $steam )    {
                    if(intval($steam)>=1 && intval($steam)<=99 ) {
                        array_push($streams, $steam);
                    }
                }

                $index = (intval($edit_index)>0?$edit_index:count($config['phones'])+1);

                /* Выявление различия и добавление новых */
                $adds = array_diff_key(array_flip($streams), array_flip($_streams));
                foreach ( $adds as $k => $add ) {
                    $config['streams'][$k]=$index;
                }

                $streams = implode($streams, ",");

                $config['phones'][$index] = $_POST['phone'].(!empty($streams)?":".$streams:"");

                write_ini_file(dirname(__FILE__).'/app.ini', $config);
                die();
            } else {
                header('HTTP/1.1 401 Unauthorized');
                exit;
            }
    }

    if(isset($_GET['m']) && $_GET['m']=='delphone') {
        session_start();
        if(!empty($_SESSION['user_session']) && $_SESSION['user_session'] == $config['admin']['session']) {

            $_POST['phone'] = preg_replace("/[^0-9]/", "", $_POST['phone']);
            if(empty($_POST['phone']) || count($config['phones'])==1) {
                header('HTTP/1.1 401 Unauthorized');
                exit;
            }

            $phones = [];
            $i=0;
            $list = explode_phones($config['phones']);

            foreach ( $list['phones'] as $k => $phone )    {

                if($phone==$_POST['phone']) {
                    unset($list['phones'][$k]);
                } else {
                    $i++;
                    $phones[$i] = $list['phones'][$k].(!empty($list['streams'][$k])?":".implode($list['streams'][$k],","):"");
                }
            }
            $config['phones'] = $phones;

            $list = explode_phones($config['phones']);

            /* Получение списка идентификаторов потоков */
            $streams = [];
            foreach ( $list['streams'] as $k => $item ) {
                $streams = array_merge($streams, $item);
            }
            $streams = array_unique($streams);
            $streams= array_flip($streams);

            /* Удаление */
            $deletes = array_diff_key($config['streams'], $streams);
            foreach ( $deletes as $k => $delete ) {
                unset($config['streams'][$k]);
            }

            write_ini_file(dirname(__FILE__).'/app.ini', $config);
            die();
        } else {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
    }

    if(isset($_GET['m']) && $_GET['m']=='signout') {
        session_start();
        unset($_SESSION['user_session']);
        if(session_destroy()) {
            $uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);
            $config['admin']['session'] = "";
            write_ini_file(dirname(__FILE__).'/app.ini', $config);
            header("Location: http://$_SERVER[HTTP_HOST]" . $uri_parts[0] . "?m=admin");
            die();
        }
    }

    if(isset($_GET['m']) && $_GET['m']=='signin') {
        if (!(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
            && $_SERVER['PHP_AUTH_USER'] == $config['admin']['email']
            && md5($_SERVER['PHP_AUTH_PW']) == $config['admin']['password'])) {
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }
        session_start();
        $_SESSION['user_session'] = md5(rand());
        $config['admin']['session'] = $_SESSION['user_session'];
        write_ini_file(dirname(__FILE__).'/app.ini', $config);
        die();
    }

    $current = intval($config['cache']['phone']);

    $list = explode_phones($config['phones']);

    $current = ($current+1>count($list['phones'])?1:$current+1);
    $config['cache']['phone'] = $current;

    $key = array_search($list['phones'][$current], $list['phones']);
    $phones = $list['phones'];
    shift_in_right($phones);
    shift_in_left($phones);
    $x=0; if($key>1) while ($x++<$key-1) shift_in_right($phones);
    array_combine(range(1, count($phones)), $phones);

    $phone = $phones[1];

    /* $_phone */
    foreach ( $config['streams'] as $k => $stream )    {
        $_current = intval($stream);
        $_phone[$k] = $list['phones'][$_current];
        if($config['setting']['debug']=="true") {
            echo "_curent_stream: " . $k . "<br>";
            echo "_current: " . $_current . "<br>";
            echo "list[phones][_current]: " . $list['phones'][$_current] . "<br><br>";
        }
    }

    /* $_link */
    foreach ( $config['streams'] as $k => $stream )    {
        $_link[$k] = ' elementary-stream-id="'.$k.'" ';
    }


    if(isset($_GET['m']) && $_GET['m']=="echo") echo $phone;
    write_ini_file(dirname(__FILE__).'/app.ini', $config);

    echo '<script type="text/javascript">'.file_get_contents(dirname(__FILE__).'/app.js').'</script>';