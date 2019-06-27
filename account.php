<?php
// part of qEngine
require_once "./includes/user_init.php";

// close site?
if (!$isLogin) {
    redir($config['site_url'].'/profile.php?mode=login');
}

//
$txt['main_body'] = quick_tpl(load_tpl('account.tpl'), $txt);
generate_html_header("$config[site_name] $config[cat_separator] My Account");
flush_tpl();
