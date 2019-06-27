<?php
require './../includes/admin_init.php';
admin_check(4);

$cmd = get_param('cmd');

switch ($cmd) {
    case 'save':
        $featured = get_param('featured');
        sql_query("UPDATE ".$db_prefix."config SET config_value='$featured' WHERE config_id='featured_product' AND group_id='cart' LIMIT 1");
        admin_die('admin_ok');
    break;


    default:
        // get featured
        $foo = explode(',', $config['cart']['featured_product']);
        $mm = array(); $i = 0;
        if ($config['cart']['featured_product']) {
            foreach ($foo as $k => $v) {
                $i++;
                $mem = sql_qquery("SELECT idx, title FROM ".$db_prefix."products WHERE idx='$v' LIMIT 1");
                $mm[] = array('id' => $mem['idx'], 'name' => $mem['title']);
            }
        }
        $txt['featured_product_preset'] = $i ? json_encode($mm) : 'null';

        $tpl = load_tpl('adm', 'featured.tpl');
        $txt['featured_product'] = $config['cart']['featured_product'];
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
}
