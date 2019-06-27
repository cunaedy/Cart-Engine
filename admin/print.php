<?php
// part of qEngine
require './../includes/admin_init.php';
admin_check(1);

$cmd = get_param('cmd');
$w = get_param('w');
$h = get_param('h');
$redir = get_param('redir');
$axsrf = axsrf_value();

// buat print
switch ($cmd) {
    case 'invoice':
        $txt['src'] = "trx.php?cmd=print&amp;order_id=$w";
        $txt['redir'] = "window.location='$config[site_url]/".$qe_admin_folder."/trx.php?order_id=$w'";
    break;


    case 'address':
        $txt['src'] = 'print.php?cmd=print_address&amp;w='.$w;
        $txt['redir'] = "window.location='$config[site_url]/".$qe_admin_folder."/trx.php?order_id=$w'";
    break;


    case 'print_address':
        $row = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id='$w' LIMIT 1");
        $row['site_address'] = format_address();
        $row['company_logo'] = $txt['company_logo'];

        echo quick_tpl(load_tpl('adm', 'trx_address.tpl'), $row);
        die;
    break;


    default:
        $txt['src'] = $txt['redir'] = '';
    break;
}

$tpl = load_tpl('adm', 'print.tpl');
$txt['main_body'] = quick_tpl($tpl, $txt);
flush_tpl('adm');
