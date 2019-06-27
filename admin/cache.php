<?php
// part of qEngine
require_once "./../includes/admin_init.php";
admin_check('site_setting');

$cmd = get_param('cmd');
$all_start = getmicrotime();


switch ($cmd) {
    case 'do_cache':
        html_header();
        echo '<div style="float:left; width:80px"><img src="../skins/_common/images/loading.gif" alt="loading" /></div><h1>Please Wait...</h1>';

        // 1. remove local caches
        echo '<p><b>[Removing Cache]</b><br />';
        $start = getmicrotime();
        sql_query("TRUNCATE TABLE ".$db_prefix."cache");
        sql_query("UPDATE ".$db_prefix."language SET lang_value='' WHERE lang_key='_config:cache'");
        $finish = getmicrotime();
        echo num_format($finish - $start, 3).'s</p>';

        // 2. optimize tables
        $table_prefix = $db_prefix;
        $len_prefix = strlen($table_prefix);
        echo '<p><b>[Optimizing Tables]</b><br />';
        $start = getmicrotime();
        $res = sql_query("SHOW TABLES");
        while ($row = sql_fetch_array($res)) {
            $t = $row[0];
            if (substr($t, 0, $len_prefix) == $table_prefix) {
                echo '. ';
                sql_query('OPTIMIZE TABLE `$t`');
            }
        }
        $finish = getmicrotime();
        echo num_format($finish - $start, 3).'s</p>';

        // 4. complete cache
        $finish = getmicrotime();
        echo '</div><h2 style="clear:both">Done in '.num_format($finish - $all_start, 3).'s. Please close this window.</h2>';
        html_footer();
    break;

    default:
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'cache.tpl'), 0);
        flush_tpl('adm');
    break;
}
