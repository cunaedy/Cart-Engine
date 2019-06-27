<?php
// part of qEngine
/*	Store small variables to config table, so we don't need to create separate small tables to do it.
    Call with: edit_opt.php?fid=[var_group_id]&title=[human friendly title]&clear_cache=[-none-/yes/everything]

    Eg: edit_opt.php?fid=brand --> to create list of brands.
    Later you need to create a function to read config table by using:
    SELECT * FROM qe_config WHERE group_id='var' AND config_id='brand'

    Optional: edit_opt.php?fid=&clear_cache=
    Where:
        clear_cache = 	empty (default) = do not clear cache
                        yes = clear normal cache
                        everything = clear all cache including static cache

    You can retreive stored values by using: get_editable_option ('brand'), see function.php
*/

require './../includes/admin_init.php';
admin_check('4');

$cmd = get_param('cmd');
$fid = get_param('fid');
$title = get_param('title');
$clear_cache = get_param('clear_cache');

if (empty($cmd)) {
    $cmd = post_param('cmd');
}
if (empty($fid)) {
    $fid = post_param('fid');
}
if (empty($title)) {
    $title = $fid;
}

switch ($cmd) {
    case 'del':
        $idx = get_param('idx');
        AXSRF_check();
        sql_query("DELETE FROM ".$db_prefix."config WHERE idx='$idx' LIMIT 1");
        redir();
    break;


    case 'save':
        AXSRF_check();
        foreach ($_POST as $key => $val) {
            if (substr($key, 0, 6) == 'value_') {
                $oid = substr($key, 6);

                // update
                if (is_numeric($oid)) {
                    $val = post_param($key);
                    if (!empty($val)) {
                        sql_query("UPDATE ".$db_prefix."config SET config_value='$val' WHERE idx='$oid' LIMIT 1");
                    } else {
                        sql_query("DELETE FROM ".$db_prefix."config WHERE idx='$oid' LIMIT 1");
                    }
                }
                // insert
                else {
                    $val = post_param($key);
                    if (!empty($val)) {
                        sql_query("INSERT INTO ".$db_prefix."config SET group_id='var', config_id='$fid', config_value='$val'");
                    }
                }
            }
        }
        redir();
    break;


    default:
        // clear cache first?
        if ($clear_cache == 'yes') {
            qcache_clear();
        } elseif ($clear_cache == 'everything') {
            qcache_clear('everything');
        }

        // load tpl
        $txt['block_list'] = $txt['block_new'] = '';
        $tpl = load_tpl('adm', 'edit_opt.tpl');
        $axsrf = AXSRF_value();

        // fill with db
        $i = 0;
        $res = sql_query("SELECT * FROM ".$db_prefix."config WHERE group_id='var' AND config_id='$fid' ORDER BY config_value");
        while ($row = sql_fetch_array($res)) {
            $i++;
            $row['fid'] = $fid;
            $row['axsrf'] = $axsrf;
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        // fill the rest with blank
        for ($i = 1; $i <= 5; $i++) {
            $row['fid'] = $fid;
            $row['idx'] = 'new_'.$i;
            $row['config_value'] = '';
            $row['axsrf'] = $axsrf;
            $txt['block_new'] .= quick_tpl($tpl_block['new'], $row);
        }

        // output
        $txt['title'] = $title;
        $txt['fid'] = $fid;
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'edit_opt.tpl'), $txt);
        flush_tpl('adm');
    break;
}
