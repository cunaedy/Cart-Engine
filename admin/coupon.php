<?php
// called after add/edit/del anything
function post_func($cmd, $id, $savenew = false)
{
    global $db_prefix, $config, $lang;
    $inf = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE idx='$id' LIMIT 1");

    // auto generate code?
    if (empty($inf['gift_code'])) {
        $c = random_str(8);
        $ok = false;
        while (!$ok) {
            $row = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE gift_code = '$c' LIMIT 1");
            if (empty($row)) {
                $ok = true;
            }
            $c = random_str(8);
        }
        sql_query("UPDATE ".$db_prefix."gift SET gift_code='$c' WHERE idx='$id' LIMIT 1");
        $inf['gift_code'] = $c;
    }

    // when removed -> done
    if ($cmd == 'remove_item') {
        redir($config['site_url'].'/'.$config['admin_folder'].'/coupon.php');
    }

    // send email
    if ($inf['redeem_email'] && !$inf['redeem_sent']) {
        $inf['gift_value'] = $inf['gift_pct'] ? $inf['gift_value'].'%' : num_format($inf['gift_value'], 0, 1);
        $inf['min_purchase'] = num_format($inf['min_purchase'], 0, 1);
        $inf['site_name'] = $config['site_name'];
        $inf['site_url'] = $config['site_url'];
        $tpl = quick_tpl(load_tpl('var', $inf['redeem_msg']), $inf);
        email($inf['redeem_email'], sprintf($lang['l_mail_coupon'], $config['site_name']), $tpl, 1, 1);
        sql_query("UPDATE ".$db_prefix."gift SET redeem_sent='1' WHERE idx='$id' LIMIT 1");
    }

    if ($savenew) {
        redir($config['site_url'].'/'.$config['admin_folder'].'/coupon.php?qadmin_cmd=new');
    } else {
        redir($config['site_url'].'/'.$config['admin_folder'].'/coupon.php?id='.$id);
    }
    die;
}


require './../includes/admin_init.php';
admin_check(4);
$id = get_param('id');
if (empty($id)) {
    $id = post_param('primary_val');
}
if ($id == 'dummy') {
    $id = '';
}

$coupon_type_def = array('once' => 'One Use', 'multi' => 'Multiple Use');
$discount_def = array(0 => 'Fixed Value ('.$lang['l_cur_name'].')', 1 => 'Percentage (%)');
$redeem = false;
$email_sent = false;
$redeem_date = false;

if ($id) {
    $inf = sql_qquery("SELECT * FROM ".$db_prefix."gift WHERE idx='$id' LIMIT 1");
    if ($inf['redeem_user_id']) {
        $redeem = true;
    }
    if ($inf['redeem_sent']) {
        $email_sent = true;
    }
    if ($inf['redeem_date']) {
        $redeem_date = convert_date(date('Y-m-d', $inf['redeem_date'])).' @ '.date('H:i', $inf['redeem_date']);
    }
}

// idx :: int :: 10
$qadmin_def['idx']['title'] = 'ID';
$qadmin_def['idx']['field'] = 'idx';
$qadmin_def['idx']['type'] = 'echo';
$qadmin_def['idx']['size'] = 10;
$qadmin_def['idx']['value'] = 'sql';

// master_code :: string :: 75
$qadmin_def['master_code']['title'] = 'Master Code';
$qadmin_def['master_code']['field'] = 'master_code';
$qadmin_def['master_code']['type'] = 'echo';
$qadmin_def['master_code']['size'] = 25;
$qadmin_def['master_code']['value'] = 'sql';
$qadmin_def['master_code']['help'] = 'For multiple use coupon, CE will create multiple coupons using information from the original coupon. This should contain the original coupon code.';

// gift_code :: string :: 96
$qadmin_def['gift_code']['title'] = 'Gift Code';
$qadmin_def['gift_code']['field'] = 'gift_code';
$qadmin_def['gift_code']['type'] = ($id) ? 'echo' : 'varchar';
$qadmin_def['gift_code']['size'] = 25;
$qadmin_def['gift_code']['value'] = 'sql';
$qadmin_def['gift_code']['help'] = 'Leave empty to auto generate the code.';
$qadmin_def['gift_code']['unique'] = true;

// gift_value :: real :: 12
$qadmin_def['gift_value']['title'] = 'Gift Value';
$qadmin_def['gift_value']['field'] = 'gift_value';
$qadmin_def['gift_value']['type'] = ($redeem) ? 'echo' : 'varchar';
$qadmin_def['gift_value']['size'] = 12;
$qadmin_def['gift_value']['value'] = 'sql';
$qadmin_def['gift_value']['required'] = ($redeem) ? false : true;

// gift_pct :: real :: 12
$qadmin_def['gift_pct']['title'] = 'Discount Type';
$qadmin_def['gift_pct']['field'] = 'gift_pct';
$qadmin_def['gift_pct']['type'] = ($redeem) ? 'mask' : 'radiov';
$qadmin_def['gift_pct']['option'] = $discount_def;
$qadmin_def['gift_pct']['value'] = 'sql';


// min_purchase :: real :: 12
$qadmin_def['min_purchase']['title'] = 'Minimum Purchase';
$qadmin_def['min_purchase']['field'] = 'min_purchase';
$qadmin_def['min_purchase']['type'] = ($redeem) ? 'echo' : 'varchar';
$qadmin_def['min_purchase']['size'] = 12;
$qadmin_def['min_purchase']['value'] = 'sql';
$qadmin_def['min_purchase']['required'] = ($redeem) ? false : true;

// valid_date :: date :: 10
$qadmin_def['valid_date']['title'] = 'Valid Until';
$qadmin_def['valid_date']['field'] = 'valid_date';
$qadmin_def['valid_date']['type'] = ($redeem) ? 'echo' : 'date';
$qadmin_def['valid_date']['value'] = 'sql';

// coupon_type :: string :: 15
$qadmin_def['coupon_type']['title'] = 'Coupon Type';
$qadmin_def['coupon_type']['field'] = 'coupon_type';
$qadmin_def['coupon_type']['type'] = ($redeem) ? 'mask' : 'radiov';
$qadmin_def['coupon_type']['option'] = $coupon_type_def;
$qadmin_def['coupon_type']['value'] = $id ? 'sql' : 'once';

$qadmin_def['div1']['title'] = 'Email Coupon';
$qadmin_def['div1']['field'] = 'div1';
$qadmin_def['div1']['type'] = 'div';

// redeem_email :: string :: 765
$qadmin_def['redeem_email']['title'] = 'Email Address';
$qadmin_def['redeem_email']['field'] = 'redeem_email';
$qadmin_def['redeem_email']['type'] = ($redeem || $email_sent) ? 'echo' : 'varchar';
$qadmin_def['redeem_email']['size'] = 255;
$qadmin_def['redeem_email']['value'] = 'sql';
$qadmin_def['redeem_email']['help'] = 'Enter an email address to send this coupon to a person.';

// redeem_msg :: blob :: 196605
$qadmin_def['redeem_msg']['title'] = 'Message';
$qadmin_def['redeem_msg']['field'] = 'redeem_msg';
$qadmin_def['redeem_msg']['type'] = ($redeem || $email_sent) ? 'echo' : 'wysiwyg';
$qadmin_def['redeem_msg']['size'] = '500,200';
$qadmin_def['redeem_msg']['value'] = ($id || $email_sent) ? 'sql' : load_tpl('mail', 'coupon');

$qadmin_def['div2']['title'] = 'Redeemer Information';
$qadmin_def['div2']['field'] = 'div2';
$qadmin_def['div2']['type'] = 'div';

// redeem_order_id :: string :: 48
$qadmin_def['redeem_order_id']['title'] = 'Redeem for Order ID';
$qadmin_def['redeem_order_id']['field'] = 'redeem_order_id';
$qadmin_def['redeem_order_id']['type'] = 'echo';
$qadmin_def['redeem_order_id']['value'] = $redeem ? "<a href=\"trx.php?order_id=$inf[redeem_order_id]\">$inf[redeem_order_id]</a>" : 'sql';

// redeem_user_id :: string :: 240
$qadmin_def['redeem_user_id']['title'] = 'User ID';
$qadmin_def['redeem_user_id']['field'] = 'redeem_user_id';
$qadmin_def['redeem_user_id']['type'] = 'echo';
$qadmin_def['redeem_user_id']['size'] = 240;
$qadmin_def['redeem_user_id']['value'] = 'sql';

// redeem_date :: int :: 10
$qadmin_def['redeem_date']['title'] = 'Date';
$qadmin_def['redeem_date']['field'] = 'redeem_date';
$qadmin_def['redeem_date']['type'] = 'echo';
$qadmin_def['redeem_date']['value'] = $redeem_date;

// redeem_purchase :: real :: 12
$qadmin_def['redeem_purchase']['title'] = 'Purchase Value';
$qadmin_def['redeem_purchase']['field'] = 'redeem_purchase';
$qadmin_def['redeem_purchase']['type'] = 'echo';
$qadmin_def['redeem_purchase']['size'] = 12;
$qadmin_def['redeem_purchase']['value'] = $id ? num_format($inf['redeem_purchase'], 0, 1) : 'sql';

// general configuration ( * = optional )
$qadmin_cfg['table'] = $db_prefix.'gift';					// table name
$qadmin_cfg['primary_key'] = 'idx';						// table's primary key
$qadmin_cfg['primary_val'] = 'dummy';						// primary key value
$qadmin_cfg['ezf_mode'] = false;							// TRUE to use EZF mode (see ./_qadmin_ez_mode.txt for more info), FALSE to use QADMIN *
$qadmin_cfg['ezd_mode'] = false;							// TRUE to use ezDesign mode (see ./qadmin_ez_mode.txt for more info), FALSE to use QADMIN *
$qadmin_cfg['template'] = 'default';						// template to use
$qadmin_cfg['post_process'] = 'post_func';

// logging
$qadmin_cfg['enable_log'] = true;			// log all changes (add/edit/remove), default = from qe_config
$qadmin_cfg['detailed_log'] = true;			// store modification values (may be big!), default = from qe_config
$qadmin_cfg['log_title'] = 'gift_code';	// qadmin field to be used as log title (empty = disable log, no matter other cfg's)

// folder configuration (qAdmin only stores filename.ext without folder location), ends without slash '/' - optional
$qadmin_cfg['file_folder'] = './../public/file';					// folder to place file upload (relative to /admin folder)
$qadmin_cfg['img_folder'] = './../public/image';				// folder to place image upload
$qadmin_cfg['thumb_folder'] = './../public/thumb';			// folder to place thumb (auto generated)

// search configuration
$qadmin_cfg['search_key'] = 'idx,gift_code,gift_value,redeem_user_id';		// list other key to search
$qadmin_cfg['search_key_mask'] = 'ID,Gift Code,Value,Reedemed By';	// mask other key

$qadmin_cfg['search_date_field'] = 'valid_date';				// search by date field name *
$qadmin_cfg['search_start_date'] = true;					// show start date *
$qadmin_cfg['search_end_date'] = true;						// show end date *

// enable qadmin functions, which are: search, list, new, update & remove
$qadmin_cfg['cmd_default'] = 'list';						// if this script called without ANY parameter
$qadmin_cfg['cmd_search_enable'] = true;
$qadmin_cfg['cmd_list_enable'] = true;
$qadmin_cfg['cmd_new_enable'] = true;
$qadmin_cfg['cmd_update_enable'] = true;
$qadmin_cfg['cmd_remove_enable'] = true;
$qadmin_cfg['admin_level'] = '4';

// form title
$qadmin_title['new'] = 'Add a Coupon';
$qadmin_title['update'] = 'Update a Coupon';
$qadmin_title['search'] = 'Search a Coupon';
$qadmin_title['list'] = 'Coupon List';
qadmin_manage($qadmin_def, $qadmin_cfg, $qadmin_title);
