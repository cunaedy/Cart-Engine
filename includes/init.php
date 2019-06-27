<?php
// part of qEngine
// the following syntaxes will be automatically executed EVERYTIME (both for user & admin UI)

// configure script to server's environment
// part of qEngine

// init database
if (!$dbh = mysqli_connect($db_hostname, $db_username, $db_password)) {
    echo mysqli_error();
    exit;
}
mysqli_select_db($dbh, $db_name);

// compatibility with MySQL 5 Strict Mode
$mysql_ver = substr(mysqli_get_server_info($dbh), 0, 1);
if ($mysql_ver > 4) {
    mysqli_query($dbh, "SET @@global.sql_mode=''");
    mysqli_query($dbh, "SET NAMES 'utf8'");
    mysqli_query($dbh, "SET sql_mode = 'NO_UNSIGNED_SUBTRACTION'");
}

// READ CONFIG DB
// Reason why we seperate shop config from qe_config...... because we are lazy :D This way we can re-use
// the same table, tpl & php for qe_config without mod anything.
$module_config = $config = array();
$res = mysqli_query($dbh, "SELECT * FROM ".$db_prefix."config WHERE group_id !='var'");
if (!$res) {
    die("<h1>Fatal Error!</h1><p>Can not connect to configuration table. Please verify your database configuration.</p><p><b>MySQL Respond:</b> ".mysqli_error($dbh)."</p>");
}
while ($row = mysqli_fetch_array($res)) {
    if ($row['group_id']) {
        if (substr($row['group_id'], 0, 4) == 'mod_') {
            $mid = substr($row['group_id'], 4);
            $module_config[$mid][$row['config_id']] = $row['config_value'];
        } else {
            $config[$row['group_id']][$row['config_id']] = $row['config_value'];
        }
    } else {
        $config[$row['config_id']] = $row['config_value'];
    }
}

// server dependent config
if (!get_magic_quotes_gpc()) {
    $config['gpc_quotes'] = 0;
} else {
    $config['gpc_quotes'] = 1;
}	// detect magic quote gpc
if (substr(php_uname(), 0, 7) == "Windows") {
    $config['under_windows'] = 1;
} else {
    $config['under_windows'] = 0;
}

// power config (DO NOT CHANGE IF YOU DON'T UNDERSTAND IT)
$config['isNowPermalink'] = false;
$config['short_query'] = 0;
$config['multi_rte'] = 0;
$config['multi_code_editor'] = 0;
$config['total_mysql_query'] = 0;
$config['force_redir'] = 1;						// still redir after header sent?
$config['list_ppp'] = 10;						// num of pagination per page * REQUIRED IN PAGINATION() *
$config['abs_path'] = str_replace('\\', '/', $config['abs_path']);	// for Windows
$config['original_default_lang'] = $config['default_lang'];
if (isset($_SESSION[$db_prefix.'override_skin'])) {
    $config['skin'] = 'skins/'.$_SESSION[$db_prefix.'override_skin'];
}
if (isset($_SESSION[$db_prefix.'language'])) {
    $config['default_lang'] = $_SESSION[$db_prefix.'language'];
}

// sub-scripts
$config['fman_path'] = $config['site_url'].'/'.$qe_admin_folder.'/fman';	// location of fMan script
$config['fman_skin'] = 'skins/_fman';				// location of fMan skin
$config['fman_imagelib_enable'] = true;				// enable/disable imagelib
$config['fman_imagelib_folder'] = '../../public/image';	// location of images to store (relative to fman)
$config['fman_imagelib_url']    = 'public/image';		// location of images to store (relative to site url)
$config['fman_imagelib_admin']  = '1';				// minimum admin level

// demo mode (as seen in our demo site)
$config['demo_mode'] = false;					// enable demo mode => caution! everything will be reset every 24 hours (u: admin, p: admin)
$config['demo_path'] = './install';				// demo mode support files location

// SMTP CRLF
$config['smtp_crlf'] = "\r\n";

// social media
$enable_facebook_like = $config['facebook_like'];
$enable_facebook_comment = $config['facebook_comment'];
$enable_twitter_share = $config['twitter_share'];

// debug mode (show error)
$debug_info = array('sql' => array(), 'mod' => array(), 'tpl' => array());
if ($config['debug_mode']) {
    error_reporting(E_ALL);
    ini_set('error_reporting', E_ALL);
    ini_set('opcache.enable', false);
}

// auto initialize

// debug info
if (function_exists('memory_get_usage')) {
    $memory_when_start = memory_get_usage();
} else {
    $memory_when_start = 0;
}
$config['time_start'] = getmicrotime();			// start time

// browser's cache control
if ($config['disable_browser_cache']) {
    header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
    header('Pragma: no-cache');
    header('Expires: -1');
}

// preload
srand(make_seed());
mt_srand(make_seed());

// other variables
$isPermalink = $permalink_param = false;
$sql_today = date('Y-m-d');
$sql_now = date('Y-m-d H:i:s');

// user init start
$current_user_info = isMember();
if ($current_user_info) {
    $isLogin = true;
    $current_user_id = $txt['current_user_id'] = $current_user_info['user_id'];
    $current_user_level = $current_user_info['user_level'];
    $current_admin_level = $current_user_info['admin_level'];
} else {
    $isLogin = false;
    $current_user_id = session_param($db_prefix.'user_id');
    $current_user_level = $current_admin_level = 0;
}

// init module
$txt['module_css_list'] = $txt['module_js_list'] = '';
if ($config['enable_module_engine']) {
    // Get enabled modules
    $res = sql_query("SELECT * FROM ".$db_prefix."module WHERE mod_type='general'");
    while ($row = sql_fetch_array($res)) {
        // Add css & js if necessary
        $module_enabled[$row['mod_id']] = $row['mod_enabled'];
        $module_css_list = $module_js_list = array();
        $css = explode("\n", $row['mod_css']);
        $js = explode("\n", $row['mod_js']);
        foreach ($css as $k => $v) {
            if (!empty($v)) {
                $module_css_list[] = "<link rel=\"stylesheet\" type=\"text/css\" href=\"$config[site_url]/skins/_module/$v\" />";
            }
        }
        foreach ($js as $k => $v) {
            if (!empty($v)) {
                $module_js_list[] = "<script type=\"text/javascript\" src=\"$config[site_url]/skins/_module/$v\"></script>";
            }
        }
        if (!empty($module_css_list)) {
            $txt['module_css_list'] .= implode("\n", $module_css_list)."\n";
        }
        if (!empty($module_js_list)) {
            $txt['module_js_list'] .= implode("\n", $module_js_list)."\n";
        }
    }
}


// READ LANGUAGE DB (ENGLISH IS REQUIRED)
$lang = array();

if ($config['default_lang'] == 'en') {
    $site_lang = array('en');
} else {
    $config['multi_lang'] = true;
    $foo = sql_qquery("SELECT * FROM ".$db_prefix."language WHERE lang_id='$config[default_lang]' AND lang_key='_config:cache' LIMIT 1");
    if (!$foo['lang_value']) {
        $site_lang = array('en', $config['default_lang']);
    } else {
        $lang = unserialize(gzuncompress(base64_decode($foo['lang_value'])));
        $site_lang = array();
    }
}

foreach ($site_lang as $skv) {
    $lang['l_lang_id'] = $skv;
    $foo = sql_qquery("SELECT * FROM ".$db_prefix."language WHERE lang_id='$skv' AND lang_key='_config:cache' LIMIT 1");
    if (!$foo['lang_value']) {
        $res = sql_query("SELECT * FROM ".$db_prefix."language WHERE lang_id='$skv'");
        while ($row = sql_fetch_array($res)) {
            if ($row['lang_key'][0] != '_') {
                $f = explode('.', $row['lang_key']);
                if (!empty($f[2])) {
                    $lang[$f[0]][$f[1]][$f[2]] = str_replace('__SITE__', $config['site_url'], $row['lang_value']);
                } elseif (!empty($f[1])) {
                    $lang[$f[0]][$f[1]] = str_replace('__SITE__', $config['site_url'], $row['lang_value']);
                } else {
                    $lang[$row['lang_key']] = str_replace('__SITE__', $config['site_url'], $row['lang_value']);
                }
            }
        }
        $c = base64_encode(gzcompress(serialize($lang)));
        sql_query("UPDATE ".$db_prefix."language SET lang_value='$c' WHERE lang_id='$skv' AND lang_key='_config:cache' LIMIT 1");
    } else {
        $lang = unserialize(gzuncompress(base64_decode($foo['lang_value'])));
    }
}

// system message
$sys_msg = ip_config_value('system_msg');
if (!empty($sys_msg)) {
    if (substr($sys_msg, 0, 6) == 'mini//') {
        $sys_msg = substr($sys_msg, 6);
        $txt['system_message'] = $sys_msg;
        $mini_message = true;
        ip_config_update('system_msg', '');
    } elseif (substr($sys_msg, 0, 4) != 'MSG|') {
        $system_message = true;
        $txt['system_message'] = $sys_msg;
        ip_config_update('system_msg', '');
    } else {
        $system_message = false;
    }
}
