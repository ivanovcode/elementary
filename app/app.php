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
    /*ini_set('error_reporting', 0);
    ini_set('display_errors', 0);*/

    $config = parse_ini_file(dirname(__FILE__).'/app.ini', true);

    if(isset($_GET['m']) && $_GET['m']=='admin') {
        if($config['admin']['email']!='demo@demo.com') {
            session_start();
            if(!empty($_SESSION['user_session']) && $_SESSION['user_session'] == $config['admin']['session']) {
                $tpl = new template(dirname(__FILE__).'/template/list.tpl');
                $list = "";
                foreach ( $config['phones'] as $k => $phone )    {

                    $list .= '                     
                       <tr><td>'.$phone.'</td><td>'.(count($config['phones'])>1?'<span data-value="'.$phone.'">[удалить]</span>':'').'</td></tr>
                    ';
                }
                $tpl->set('list', $list);
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

    if(isset($_GET['m']) && $_GET['m']=='addphone') {
            session_start();
            if(!empty($_SESSION['user_session']) && $_SESSION['user_session'] == $config['admin']['session']) {
                $_POST['phone'] = preg_replace("/[^0-9]/", "", $_POST['phone']);
                if(empty($_POST['phone'])) {
                    header('HTTP/1.1 401 Unauthorized');
                    exit;
                }
                $config['phones'][count($config['phones'])+1] = $_POST['phone'];
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
            foreach ( $config['phones'] as $k => $phone )    {

                if($phone==$_POST['phone']) {
                    unset($config['phones'][$k]);
                } else {
                    $i++;
                    $phones[$i] = $config['phones'][$k];
                }
            }
            $config['phones'] = $phones;
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


    $current = ($current+1>count($config['phones'])?1:$current+1);
    $config['cache']['phone'] = $current;


    $key = array_search($config['phones'][$current], $config['phones']);
    $phones = $config['phones'];
    shift_in_right($phones);
    shift_in_left($phones);
    $x=0; if($key>1) while ($x++<$key-1) shift_in_right($phones);
    array_combine(range(1, count($phones)), $phones);

    $phone = $phones[1];

    if(isset($_GET['m']) && $_GET['m']=="echo") echo $phone;
    write_ini_file(dirname(__FILE__).'/app.ini', $config);