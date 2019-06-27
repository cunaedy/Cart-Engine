<?php
// part of qEngine

require './../includes/admin_init.php';
admin_check(1);
$tpl = load_tpl('adm', 'about.tpl');

// cart
$f = explode('/', $config['cart']['xc_version']);
$txt['xc_ver'] = $f[0];
$txt['xc_build'] = $f[1];

// qe
$f = explode('/', $config['qe_version']);
$txt['qe_ver'] = $f[0];
$txt['qe_build'] = $f[1];

$txt['main_body'] = quick_tpl($tpl, $txt);
flush_tpl('adm');
