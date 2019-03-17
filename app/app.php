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

    ini_set('error_reporting', 0);
    ini_set('display_errors', 0);

    $config = parse_ini_file(dirname(__FILE__).'/app.ini', true);

    if($_GET['m']=='admin') {
        $tpl = new template(dirname(__FILE__).'/template/login.tpl');
        //$tpl->set('username', 'Alexander');
        //$tpl->set('header', $tpl->getFile('header.tpl'));
        $tpl->render();
        die();
    }

    if($_GET['m']=='signin') {
        if (!(isset($_SERVER['PHP_AUTH_USER'], $_SERVER['PHP_AUTH_PW'])
            && $_SERVER['PHP_AUTH_USER'] == 'demo@demo.com'
            && $_SERVER['PHP_AUTH_PW'] == 'demo')) {
            //header('WWW-Authenticate: Basic realm="Restricted area"');
            header('HTTP/1.1 401 Unauthorized');
            exit;
        }

        die();
    }

    $current = intval($config['cache']['phone']);
    $current = ($current+1>count($config['phones'])?1:$current+1);
    $config['cache']['phone'] = $current;
    $phone = $config['phones'][$current];
    if($_GET['m']=="echo")echo $phone;
    write_ini_file(dirname(__FILE__).'/app.ini', $config);