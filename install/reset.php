<?php
// part of qEngine

// ATTENTION!
// RESET.PHP is used to reset all setting to original setting (first installation), it will remove current product,
// category, review, and other databases. Never call this file if you don't need it! -only works if demo_mode on

if ($config['demo_mode']) {
    // insert data
    $sql = '';
    $cmd_sql = array();
    $zp = gzopen($config['demo_path'].'/reset.sql', "r");
    while ($j = gzgets($zp, 4096)) {
        $sql .= $j;
    }
    gzclose($zp);

    splitSqlFile($cmd_sql, $sql);
    foreach ($cmd_sql as $val) {
        $val = str_replace('__PREFIX__', $db_prefix, $val);
        sql_query($val);
    }

    // delete uploaded files
    $folders = array('file', 'image', 'thumb', 'private', 'products', 'products_thumbs', 'cfile');
    foreach ($folders as $val) {
        $files = get_file_list("./_public/$val");
        foreach ($files as $v2) {
            if ($v2 != 'index.html') {
                unlink("./_public/$val/$v2");
            }
        }
    }

    // copy demo files
    $folder = array('image', 'thumb', 'private', 'products');
    foreach ($folder as $fk => $fv) {
        $foo = get_file_list($config['demo_path'].'/_public/'.$fv);
        foreach ($foo as $k => $v) {
            copy($config['demo_path'].'/_public/'.$fv.'/'.$v, './public/'.$fv.'/'.$v);
            @chmod('./../public/'.$fv.'/'.$v, 0644);
        }
    }

    // create user
    $admin_passwd = qhash('admin');
    sql_query("INSERT INTO ".$db_prefix."user SET user_id = 'admin', user_passwd = '$admin_passwd', user_email = 'demo@c97.net',
	fullname = 'Administrator',	bill_city = 'My City', bill_state = 'My State', bill_country = 'United States', ship_city = 'My City', ship_state = 'My State', ship_country = 'United States',
	user_level = '5', user_since = '$today', admin_level = '5'");

    // update menu
    sql_query("UPDATE ".$db_prefix."menu_set SET menu_cache=REPLACE(menu_cache, '__SITE__', '$config[site_url]')");

    // update autoexec
    sql_query("UPDATE ".$db_prefix."config SET config_value='$sql_today' WHERE config_id='last_autoexec' LIMIT 1");

    // redirect
    redir($config['site_url']);
}
