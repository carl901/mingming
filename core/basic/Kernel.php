<?php

namespace core\basic;
class Kernel
{
    private static $krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array;

    public static function run()
    {
        self:: verifySNCode();
        self:: tplCache();
        $columnInfo = self:: checkColumn();
        $columnInfo = self:: domainBindColumn($columnInfo);
        $columnInfo = self:: routeBindColumn($columnInfo);
        $moduleList = self:: moduleColumn($columnInfo);
        $controllerPath = self:: appPathColumn($moduleList);
        self:: initCommonFunc();
        self:: checkPage($controllerPath);
    }

    private static function checkColumn()
    {
        if (isset($_SERVER['PATH_INFO']) && !mb_check_encoding($_SERVER['PATH_INFO'], 'UTF-8')) {
            $_SERVER['PATH_INFO'] = mb_convert_encoding($_SERVER['PATH_INFO'], 'utf-8', 'GBK');
        }
        $columnInfo = '';
        if (isset($_SERVER['PATH_INFO']) && $_SERVER['PATH_INFO']) {
            $columnInfo = $_SERVER['PATH_INFO'];
        } elseif (isset($_SERVER["REDIRECT_URL"]) && $_SERVER["REDIRECT_URL"]) {
            $columnInfo = str_replace('/' . basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['REDIRECT_URL']);
            $columnInfo = str_replace(SITE_DIR, '', $columnInfo);
            $_SERVER['PATH_INFO'] = $columnInfo;
        }
        if (!$columnInfo) {
            if (isset($_GET['p']) && $_GET['p']) {
                $columnInfo = $_GET['p'];
            } elseif (isset($_GET['s']) && $_GET['s']) {
                $columnInfo = $_GET['s'];
            }
        }
        if ($columnInfo) {
            $matchRule = '{^\/?([\w\-\/\.' . Config::get('url_allow_char') . ']+?)?\/?$}';
            if (preg_match($matchRule, $columnInfo)) {
                $columnInfo = preg_replace($matchRule, '$1', $columnInfo);
                $urlRuleSuffix = Config::get('url_rule_suffix');
                if (!!$lastPoint = strripos($columnInfo, $urlRuleSuffix)) {
                    $columnInfo = substr($columnInfo, 0, $lastPoint);
                }
            } else {
                $isPass = true;
            }
        }
        if (isset($_SERVER["QUERY_STRING"]) && !!$queryStr = $_SERVER["QUERY_STRING"]) {
            if (!mb_check_encoding($queryStr, 'UTF-8')) {
                $queryStr = mb_convert_encoding($queryStr, 'UTF-8', 'GBK');
            }
            parse_str($queryStr, $queryList);
            $matchRule1 = '{^\/?([\x{4e00}-\x{9fa5}\w\-\/\.\s\|:=,@\?%，。；《》—' . Config::get('url_allow_char') . ']+?)?\/?$}u';
            $defaultQuery = array('nsukey', 'form', 'isappinstalled');
            foreach ($queryList as $key => $value) {
                if (!in_array($key, $defaultQuery) && (!preg_match('/^[\w\-\.\/]+$/', $key) || !preg_match($matchRule1, $value))) {
                    $isPass = true;
                    break;
                }
            }
        }
        if (isset($isPass) && $isPass) {
            header('HTTP/1.1 404 Not Found');
            header('status: 404 Not Found');
            $defendUrl = ROOT_PATH . '/defend.html';
            if (file_exists($defendUrl)) {
                require $defendUrl;
                exit();
            } else {
                error('您访问路径含有非法字符，防注入系统提醒您请勿尝试非法操作！');
            }
        }
        define('P', $columnInfo);
        return $columnInfo;
    }

    private static function domainBindColumn($moduleInfo)
    {
        $bindUrl = '';
        if (!!$appDomain = Config::get('app_domain_bind')) {
            $hostUrl = get_http_host();
            if (isset($appDomain[$hostUrl])) {
                $bindUrl = $appDomain[$hostUrl];
            }
        }
        if (defined('URL_BIND')) {
            if ($bindUrl && URL_BIND != $bindUrl) {
                error('系统配置的模块域名绑定与入口文件绑定冲突，请核对！');
            } else {
                $bindUrl = URL_BIND;
            }
        }
        return $bindUrl ? trim_slash($bindUrl) . '/' . $moduleInfo : $moduleInfo;
    }

    private static function routeBindColumn($routeInfo)
    {
        if (!!$urlRoute = Config::get('url_route')) {
            if (!$routeInfo && isset($urlRoute['/'])) {
                return $urlRoute['/'];
            }
            foreach ($urlRoute as $key => $value) {
                $key = trim_slash($key);
                $tmpKey = "{" . $key . "}i";
                if (preg_match($tmpKey, $routeInfo)) {
                    $value = trim_slash($value);
                    $routeInfo = preg_replace($tmpKey, $value, $routeInfo);
                    break;
                }
            }
        }
        return $routeInfo;
    }

    private static function moduleColumn($krlr84d42fa73e6dd4f551b05b7aeae3a6b9Info)
    {
        $rkkm986c958efd44270420dfb35b847f7209 = Config::get('public_app', true);
        if ($krlr84d42fa73e6dd4f551b05b7aeae3a6b9Info) {
            $columnInfo = trim_slash($krlr84d42fa73e6dd4f551b05b7aeae3a6b9Info);
            $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array = explode('/', $columnInfo);
            self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array;
            $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count = count($krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array);
            if ($krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count >= 3) {
                $moduleList['m'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[0];
                $moduleList['c'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[1];
                $moduleList['f'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[2];
            } elseif ($krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count == 2) {
                $moduleList['m'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[0];
                $moduleList['c'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[1];
            } elseif ($krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count == 1) {
                $moduleList['m'] = $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_array[0];
            }
        }
        if (!isset($moduleList['m'])) {
            $moduleList['m'] = $rkkm986c958efd44270420dfb35b847f7209[0];
        }
        if (!isset($moduleList['c'])) {
            $moduleList['c'] = 'Index';
        }
        if (!isset($moduleList['f'])) {
            $moduleList['f'] = 'index';
        }
        if (!in_array(strtolower($moduleList['m']), $rkkm986c958efd44270420dfb35b847f7209)) {
            error('您访问的模块' . $moduleList['m'] . '未开放,请核对后重试！');
        }
        return $moduleList;
    }

    private static function appPathColumn($rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path)
    {
        define('M', strtolower($rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['m']));
        define('APP_MODEL_PATH', APP_PATH . '/' . M . '/model');
        define('APP_CONTROLLER_PATH', APP_PATH . '/' . M . '/controller');
        if (($lka_uph600640f3ff4884b3d602ce09a1d3c991 = Config::get('tpl_dir')) && array_key_exists(M, $lka_uph600640f3ff4884b3d602ce09a1d3c991)) {
            if (strpos($lka_uph600640f3ff4884b3d602ce09a1d3c991[M], ROOT_PATH) === false) {
                define('APP_VIEW_PATH', ROOT_PATH . $lka_uph600640f3ff4884b3d602ce09a1d3c991[M]);
            } else {
                define('APP_VIEW_PATH', $lka_uph600640f3ff4884b3d602ce09a1d3c991[M]);
            }
        } else {
            define('APP_VIEW_PATH', APP_PATH . '/' . M . '/view');
        }
        if (strpos($rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['c'], '.') > 0) {
            $controllerPath = str_replace('.', '/', $rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['c']);
            $controller = ucfirst(basename($controllerPath));
            $controllerPath = dirname($controllerPath) . '/' . $controller;
        } else {
            $controller = ucfirst($rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['c']);
            $controllerPath = $controller;
        }
        $tarmm_mpad_krlr1ccd1dd966e88d0d596c30cd058c8de9 = APP_CONTROLLER_PATH . '/' . $controllerPath . 'Controller.php';
        $mrwr_tzllhzaadhdb4ec4474186da643be12af58dfd9a4b = array('List', 'Content', 'About', 'Search', 'Form', 'Message');
        if (M == 'home' && (!file_exists($tarmm_mpad_krlr1ccd1dd966e88d0d596c30cd058c8de9) || in_array($controller, $mrwr_tzllhzaadhdb4ec4474186da643be12af58dfd9a4b))) {
            $controller = 'Index';
            $controllerPath = 'Index';
            define('F', $rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['c']);
            $rusimlb225b536e8e68badc44cf3fa7c9d282e = -1;
        } else {
            define('F', $rttdmmf3d6f2930e2ffb2403cde4d5ec9e8966Path['f']);
            $rusimlb225b536e8e68badc44cf3fa7c9d282e = 0;
        }
        define('C', $controller);
        if (isset($_SERVER["REQUEST_URI"])) {
            define('URL', $_SERVER["REQUEST_URI"]);
        } else {
            define('URL', $_SERVER["ORIG_PATH_INFO"] . '?' . $_SERVER["QUERY_STRING"]);
        }
        $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count = count(self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array);
        for ($i = 3 + $rusimlb225b536e8e68badc44cf3fa7c9d282e; $i < $krlr84d42fa73e6dd4f551b05b7aeae3a6b9_count; $i = $i + 2) {
            if (isset(self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array[$i + 1])) {
                $_GET[self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array[$i]] = self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array[$i + 1];
            } else {
                $_GET[self::$krlr84d42fa73e6dd4f551b05b7aeae3a6b9Array[$i]] = null;
            }
        }
        return $controllerPath;
    }

    private static function initCommonFunc()
    {
        Config::get('debug') ? Check::checkAppFile() : '';
        if (M == 'api') {
            if (!!$mpu29973a4ae997545edfa1cfd001fe7a4c = request('sid')) {
                session_id($mpu29973a4ae997545edfa1cfd001fe7a4c);
                session_start();
            }
            header("Access-Control-Allow-Origin: *");
        } else {
            Check::checkBs();
            Check::checkOs();
        }
        if (file_exists(APP_PATH . '/common/function.php')) {
            require APP_PATH . '/common/function.php';
        }
        $rkk_tzlmpx0b7817b980811b44957b6c3ddbd350ae = APP_PATH . '/' . M . '/config/config.php';
        if (file_exists($rkk_tzlmpx0b7817b980811b44957b6c3ddbd350ae)) {
            Config::assign($rkk_tzlmpx0b7817b980811b44957b6c3ddbd350ae);
        }
        $rkk_miltlpzl96ed9c9f4504a84b87478ee8699b99ac = APP_PATH . '/' . M . '/function/function.php';
        if (file_exists($rkk_miltlpzl96ed9c9f4504a84b87478ee8699b99ac)) {
            require $rkk_miltlpzl96ed9c9f4504a84b87478ee8699b99ac;
        }
        if (file_exists(APP_PATH . '/common/' . ucfirst(M) . 'Controller.php')) {
            $tzqq_tarmm_lrqd60ba3b1bd1d06992359d64e05e01fe72 = '\\app\\common\\' . ucfirst(M) . 'Controller';
            $tzqq_tarmm65c12c5d0694c9ba9669198d95955d39 = new $tzqq_tarmm_lrqd60ba3b1bd1d06992359d64e05e01fe72();
        }
    }

    private static function checkPage($controllerPath)
    {
        $tarmm_mpadd9c1a5af65bb107b28114fa85eb11848 = $controllerPath . 'Controller.php';
        $tarmm_mpad_krlr1ccd1dd966e88d0d596c30cd058c8de9 = APP_CONTROLLER_PATH . '/' . $tarmm_mpadd9c1a5af65bb107b28114fa85eb11848;
        $tarmm_lrqd44493979f05df72f37d3564207ee3887 = '\\app\\' . M . '\\controller\\' . str_replace('/', '\\', $controllerPath) . 'Controller';
        $miltlpzl_lrqd29d06b3d5214508610e7f0998ccfcbee = F;
        if (!file_exists($tarmm_mpad_krlr1ccd1dd966e88d0d596c30cd058c8de9)) {
            header('HTTP/1.1 404 Not Found');
            header('status: 404 Not Found');
            $mpad_b6d472addd936c14e2accd858b06d45b404 = ROOT_PATH . '/404.html';
            if (file_exists($mpad_b6d472addd936c14e2accd858b06d45b404)) {
                require $mpad_b6d472addd936c14e2accd858b06d45b404;
                exit();
            } else {
                error('对不起，您访问的页面类文件不存在，请核对后再试！');
            }
        }
        if (!class_exists($tarmm_lrqd44493979f05df72f37d3564207ee3887)) {
            error('类文件中不存在访问的类名！' . $tarmm_lrqd44493979f05df72f37d3564207ee3887);
        }
        $controller = new $tarmm_lrqd44493979f05df72f37d3564207ee3887();
        if (method_exists($tarmm_lrqd44493979f05df72f37d3564207ee3887, $miltlpzl_lrqd29d06b3d5214508610e7f0998ccfcbee)) {
            if (strtolower($tarmm_lrqd44493979f05df72f37d3564207ee3887) != strtolower($miltlpzl_lrqd29d06b3d5214508610e7f0998ccfcbee)) {
                $hdlihl720a39eaced227ca71297eec74d069cd = $controller->$miltlpzl_lrqd29d06b3d5214508610e7f0998ccfcbee();
            } else {
                $hdlihl720a39eaced227ca71297eec74d069cd = $controller;
            }
        } else {
            if (method_exists($tarmm_lrqd44493979f05df72f37d3564207ee3887, '_empty')) {
                $hdlihl720a39eaced227ca71297eec74d069cd = $controller->_empty();
            } else {
                error('不存在您调用的类或方法' . $miltlpzl_lrqd29d06b3d5214508610e7f0998ccfcbee . '，可能正在开发中，请耐心等待！');
            }
        }
        if ($hdlihl720a39eaced227ca71297eec74d069cd !== null) {
            print_r($hdlihl720a39eaced227ca71297eec74d069cd);
            exit();
        }
    }

    private static function tplCache()
    {
        if (!Config::get('tpl_html_cache') || URL_BIND == 'api' || get('nocache', 'int') == 1) {
            return;
        }
        $ax_trtrd2064f63f57d35a149e582e502ee7a526 = RUN_PATH . '/config/' . md5('area') . '.php';
        if (!file_exists($ax_trtrd2064f63f57d35a149e582e502ee7a526)) {
            return;
        } else {
            Config::assign($ax_trtrd2064f63f57d35a149e582e502ee7a526);
        }
        $axm2adf50bd3506f5aa710aa3468876eea9 = Config::get('lgs');
        if (count($axm2adf50bd3506f5aa710aa3468876eea9) > 1) {
            $uzqrpl07af8998d3ed0fbe3217130703f0cad2 = get_http_host();
            foreach ($axm2adf50bd3506f5aa710aa3468876eea9 as $value) {
                if ($value['domain'] == $uzqrpl07af8998d3ed0fbe3217130703f0cad2) {
                    cookie('lg', $value['acode']);
                }
            }
        }
        if (!isset($_COOKIE['lg'])) {
            $udmrialf3a944ca582a412d5b588be8246d91a7 = current(Config::get('lgs'));
            cookie('lg', $udmrialf3a944ca582a412d5b588be8246d91a7['acode']);
        }
        $tzlmpx_trtrd73542ac326435e961c4b207a297ac5c3 = RUN_PATH . '/config/' . md5('config') . '.php';
        if (!Config::assign($tzlmpx_trtrd73542ac326435e961c4b207a297ac5c3)) {
            return;
        }
        if (Config::get('open_wap') && (is_mobile() || Config::get('wap_domain') == get_http_host())) {
            $xrk32a1e148f9e48d81400f3818c5452be6 = 'wap';
        } else {
            $xrk32a1e148f9e48d81400f3818c5452be6 = '';
        }
        $trtrd_mpad1c0b9ab1b72ae60d3507b46f141e9a5b = RUN_PATH . '/cache/' . md5(get_http_url() . $_SERVER["REQUEST_URI"] . cookie('lg') . $xrk32a1e148f9e48d81400f3818c5452be6) . '.html';
        if (file_exists($trtrd_mpad1c0b9ab1b72ae60d3507b46f141e9a5b) && time() - filemtime($trtrd_mpad1c0b9ab1b72ae60d3507b46f141e9a5b) < Config::get('tpl_html_cache_time')) {
            ob_start();
            include $trtrd_mpad1c0b9ab1b72ae60d3507b46f141e9a5b;
            $tzlldllddf794baf782d6f8decad5a8e14941ed = ob_get_contents();
            ob_end_clean();
            if (Config::get('gzip') && !headers_sent() && extension_loaded("zlib") && strstr($_SERVER["HTTP_ACCEPT_ENCODING"], "gzip")) {
                $tzlldllddf794baf782d6f8decad5a8e14941ed = gzencode($tzlldllddf794baf782d6f8decad5a8e14941ed, 6);
                header("Content-Encoding: gzip");
                header("Vary: Accept-Encoding");
                header("Content-Length: " . strlen($tzlldllddf794baf782d6f8decad5a8e14941ed));
            }
            echo $tzlldllddf794baf782d6f8decad5a8e14941ed;
            exit();
        }
    }

    private static function verifySNCode()
    {
        if (defined('URL_BIND') && URL_BIND == 'admin') {
            return;
        }
        $mpk1bd907873123179eb2f7ce8ac9d33f64 = isset($_SERVER['LOCAL_ADDR']) ? $_SERVER['LOCAL_ADDR'] : $_SERVER['SERVER_ADDR'];
        if ($mpk1bd907873123179eb2f7ce8ac9d33f64 == '::1') {
            $mpk1bd907873123179eb2f7ce8ac9d33f64 = '127.0.0.1';
        }
        if (filter_var($mpk1bd907873123179eb2f7ce8ac9d33f64, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $pkrhh450050e6c676cdd52b9a371904283fad = explode('.', $mpk1bd907873123179eb2f7ce8ac9d33f64);
            $xpk622e93339ce98a8e43709132974ed6b5 = array('127.0.0.1/8', '192.168.0.0/16', '10.135.122.170', '10.144.74.153', '10.0.200.188', '160.19.49.0/24');
            foreach ($xpk622e93339ce98a8e43709132974ed6b5 as $key => $value) {
                if (self:: ldlxzhksrltrcabcd52034a597b9cfa67841d66d1f95($mpk1bd907873123179eb2f7ce8ac9d33f64, $value)) {
                    return;
                }
            }
        }
        $mpad_b6d472addd936c14e2accd858b06d45bsn = ROOT_PATH . '/sn.html';
        if (!!$ml300f03cfb3dee5d216da60d1ed47fd35 = Config::get('sn', true)) {
            $rzml4495f87a864c05c74ddd54df1af5aa9d = $_SERVER['HTTP_HOST'];
            $key_domain = strtoupper(substr(md5(substr(sha1($rzml4495f87a864c05c74ddd54df1af5aa9d), 0, 10)), 10, 10));
            $key_host = strtoupper(substr(md5(substr(sha1($mpk1bd907873123179eb2f7ce8ac9d33f64), 0, 15)), 10, 10));
            $ml300f03cfb3dee5d216da60d1ed47fd35_user = Config::get('sn_user');
            $key_user = strtoupper(substr(md5(substr(sha1($ml300f03cfb3dee5d216da60d1ed47fd35_user), 0, 20)), 10, 10));
            if (!in_array($key_domain, $ml300f03cfb3dee5d216da60d1ed47fd35) && !in_array($key_host, $ml300f03cfb3dee5d216da60d1ed47fd35) && !in_array($key_user, $ml300f03cfb3dee5d216da60d1ed47fd35)) {
                if (file_exists($mpad_b6d472addd936c14e2accd858b06d45bsn)) {
                    require $mpad_b6d472addd936c14e2accd858b06d45bsn;
                    exit();
                } else {
                    error('未匹配到本域名有效授权码，请到PbootCMS官网获取，并填写到网站后台"全局配置>>配置参数"中。');
                }
            }
        } else {
            if (file_exists($mpad_b6d472addd936c14e2accd858b06d45bsn)) {
                require $mpad_b6d472addd936c14e2accd858b06d45bsn;
                exit();
            } else {
                error('配置文件中授权码为空，请到PbootCMS官网获取，并填写到网站后台"全局配置>>配置参数"中。');
            }
        }
    }

    private static function ldlxzhksrltrcabcd52034a597b9cfa67841d66d1f95($pk5401b7dea82e5a94a5e006bb07827031, $ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b)
    {
        if (strpos($ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b, '/') > 0) {
            $ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b = explode('/', $ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b);
            $qzwde439078dcface293551337530dc5247c = 32 - $ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b[1];
            return ((ip2long($pk5401b7dea82e5a94a5e006bb07827031) >> $qzwde439078dcface293551337530dc5247c) == (ip2long($ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b[0]) >> $qzwde439078dcface293551337530dc5247c));
        } elseif ($ldlxzhk407b36362f0ad40bf2b0c9bd0ba3434b == $pk5401b7dea82e5a94a5e006bb07827031) {
            return true;
        } else {
            return false;
        }
    }
}