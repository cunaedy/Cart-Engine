<?php
function post_process($cmd, $id, $savenew)
{
    global $config, $db_prefix;

    // if user removed -> redir to user list
    if ($cmd == 'remove_item') {
        sql_query("DELETE FROM ".$db_prefix."user WHERE user_id='$id' LIMIT 1");
        admin_die('admin_ok', $config['site_url'].'/'.$config['admin_folder'].'/user.php');
    }

    if ($savenew) {
        admin_die('admin_ok', $config['site_url'].'/'.$config['admin_folder'].'/user.php?qadmin_cmd=new');
    } else {
        admin_die('admin_ok', $config['site_url'].'/'.$config['admin_folder'].'/user.php?id='.$id);
    }
}

// part of qEngine
require './../includes/admin_init.php';

// params
$id = get_param('id');
$cmd = get_param('cmd');
$qadmin_cmd = get_param('qadmin_cmd');
if (empty($id)) {
    $id = post_param('primary_val');
}
if (empty($qadmin_cmd)) {
    $qadmin_cmd = post_param('qadmin_cmd');
}

// rights view
$lvl = admin_check(4);

// you can't defined higher level!
if ($lvl == 4) {
    unset($admin_level_def[5]);
    unset($user_level_def[5]);
}

// you can't edit/view higher level!
if (!empty($id)) {
    $foo = sql_qquery("SELECT admin_level FROM ".$db_prefix."user WHERE user_id='$id' LIMIT 1");
    if ($foo['admin_level'] > $lvl) {
        admin_die($lang['msg']['no_level']);
    }
}

// data definitions
// user_id :: string :: 80
$qadmin_def['user_id']['title'] = 'User ID';
$qadmin_def['user_id']['field'] = 'user_id';
$qadmin_def['user_id']['type'] = $qadmin_cmd == 'new' ? 'varchar' : 'echo';
$qadmin_def['user_id']['size'] = 80;
$qadmin_def['user_id']['value'] = 'sql';

// user_email :: string :: 255
$qadmin_def['user_email']['title'] = 'Email';
$qadmin_def['user_email']['field'] = 'user_email';
$qadmin_def['user_email']['type'] = 'email';
$qadmin_def['user_email']['size'] = 255;
$qadmin_def['user_email']['value'] = 'sql';

// password only for new
$qadmin_def['user_passwd']['title'] = 'Password';
$qadmin_def['user_passwd']['field'] = 'user_passwd';
$qadmin_def['user_passwd']['type'] = 'password';
$qadmin_def['user_passwd']['size'] = 255;
$qadmin_def['user_passwd']['value'] = 'sql';
if ($qadmin_cmd != 'new') {
    $qadmin_def['user_passwd']['value'] = '';
    $qadmin_def['user_passwd']['help'] = 'Enter a new password to reset password, or leave empty.';
}

// user_level :: string :: 3
$qadmin_def['user_level']['title'] = 'User Level';
$qadmin_def['user_level']['field'] = 'user_level';
$qadmin_def['user_level']['type'] = 'select';
$qadmin_def['user_level']['option'] = $user_level_def;
if ($qadmin_cmd == 'new') {
    $qadmin_def['user_level']['value'] = 1;
} else {
    $qadmin_def['user_level']['value'] = 'sql';
}

// admin_level :: string :: 3
$qadmin_def['admin_level']['title'] = 'Admin Level';
$qadmin_def['admin_level']['field'] = 'admin_level';
$qadmin_def['admin_level']['type'] = 'select';
$qadmin_def['admin_level']['option'] = $admin_level_def;
$qadmin_def['admin_level']['value'] = 'sql';

// user_since :: date :: 10
$qadmin_def['user_since']['title'] = 'Registered on';
$qadmin_def['user_since']['field'] = 'user_since';
$qadmin_def['user_since']['type'] = 'date';
$qadmin_def['user_since']['value'] = 'sql';

// fullname :: string :: 300
$qadmin_def['fullname']['title'] = 'Full Name';
$qadmin_def['fullname']['field'] = 'fullname';
$qadmin_def['fullname']['type'] = 'varchar';
$qadmin_def['fullname']['size'] = 100;
$qadmin_def['fullname']['value'] = 'sql';

// div1 :: string :: 300
$qadmin_def['div1']['title'] = 'Billing Address';
$qadmin_def['div1']['field'] = 'div1';
$qadmin_def['div1']['type'] = 'div';

// bill_address :: string :: 255
$qadmin_def['bill_address']['title'] = 'Address';
$qadmin_def['bill_address']['field'] = 'bill_address';
$qadmin_def['bill_address']['type'] = 'varchar';
$qadmin_def['bill_address']['size'] = 255;
$qadmin_def['bill_address']['value'] = 'sql';

// bill_address2 :: string :: 255
$qadmin_def['bill_address2']['title'] = '';
$qadmin_def['bill_address2']['field'] = 'bill_address2';
$qadmin_def['bill_address2']['type'] = 'varchar';
$qadmin_def['bill_address2']['size'] = 255;
$qadmin_def['bill_address2']['value'] = 'sql';

// bill_district :: string :: 255
$qadmin_def['bill_district']['title'] = 'District';
$qadmin_def['bill_district']['field'] = 'bill_district';
$qadmin_def['bill_district']['type'] = 'varchar';
$qadmin_def['bill_district']['size'] = 255;
$qadmin_def['bill_district']['value'] = 'sql';

// bill_city :: string :: 255
$qadmin_def['bill_city']['title'] = 'City';
$qadmin_def['bill_city']['field'] = 'bill_city';
$qadmin_def['bill_city']['type'] = 'varchar';
$qadmin_def['bill_city']['size'] = 255;
$qadmin_def['bill_city']['value'] = 'sql';

// bill_state :: string :: 240
$qadmin_def['bill_state']['title'] = 'State/County';
$qadmin_def['bill_state']['field'] = 'bill_state';
$qadmin_def['bill_state']['type'] = 'varchar';
$qadmin_def['bill_state']['size'] = 80;
$qadmin_def['bill_state']['value'] = 'sql';

// bill_country :: string :: 240
$qadmin_def['bill_country']['title'] = 'Country';
$qadmin_def['bill_country']['field'] = 'bill_country';
$qadmin_def['bill_country']['type'] = 'varchar';
$qadmin_def['bill_country']['size'] = 80;
$qadmin_def['bill_country']['value'] = 'sql';

// bill_zip :: string :: 45
$qadmin_def['bill_zip']['title'] = 'Zip Code/Post Code';
$qadmin_def['bill_zip']['field'] = 'bill_zip';
$qadmin_def['bill_zip']['type'] = 'varchar';
$qadmin_def['bill_zip']['size'] = 15;
$qadmin_def['bill_zip']['value'] = 'sql';

// phone :: string :: 60
$qadmin_def['phone']['title'] = 'Phone';
$qadmin_def['phone']['field'] = 'phone';
$qadmin_def['phone']['type'] = 'varchar';
$qadmin_def['phone']['size'] = 20;
$qadmin_def['phone']['value'] = 'sql';

// div1 :: string :: 300
$qadmin_def['div2']['title'] = 'Shipping Address';
$qadmin_def['div2']['field'] = 'div2';
$qadmin_def['div2']['type'] = 'div';

// ship_address :: string :: 255
$qadmin_def['ship_address']['title'] = 'Address';
$qadmin_def['ship_address']['field'] = 'ship_address';
$qadmin_def['ship_address']['type'] = 'varchar';
$qadmin_def['ship_address']['size'] = 255;
$qadmin_def['ship_address']['value'] = 'sql';

// ship_address2 :: string :: 255
$qadmin_def['ship_address2']['title'] = '';
$qadmin_def['ship_address2']['field'] = 'ship_address2';
$qadmin_def['ship_address2']['type'] = 'varchar';
$qadmin_def['ship_address2']['size'] = 255;
$qadmin_def['ship_address2']['value'] = 'sql';

// ship_district :: string :: 255
$qadmin_def['ship_district']['title'] = 'District';
$qadmin_def['ship_district']['field'] = 'ship_district';
$qadmin_def['ship_district']['type'] = 'varchar';
$qadmin_def['ship_district']['size'] = 255;
$qadmin_def['ship_district']['value'] = 'sql';

// ship_city :: string :: 255
$qadmin_def['ship_city']['title'] = 'City';
$qadmin_def['ship_city']['field'] = 'ship_city';
$qadmin_def['ship_city']['type'] = 'varchar';
$qadmin_def['ship_city']['size'] = 255;
$qadmin_def['ship_city']['value'] = 'sql';

// ship_state :: string :: 240
$qadmin_def['ship_state']['title'] = 'State/County';
$qadmin_def['ship_state']['field'] = 'ship_state';
$qadmin_def['ship_state']['type'] = 'varchar';
$qadmin_def['ship_state']['size'] = 80;
$qadmin_def['ship_state']['value'] = 'sql';

// ship_country :: string :: 240
$qadmin_def['ship_country']['title'] = 'Country';
$qadmin_def['ship_country']['field'] = 'ship_country';
$qadmin_def['ship_country']['type'] = 'varchar';
$qadmin_def['ship_country']['size'] = 80;
$qadmin_def['ship_country']['value'] = 'sql';

// ship_zip :: string :: 45
$qadmin_def['ship_zip']['title'] = 'Zip Code/Post Code';
$qadmin_def['ship_zip']['field'] = 'ship_zip';
$qadmin_def['ship_zip']['type'] = 'varchar';
$qadmin_def['ship_zip']['size'] = 15;
$qadmin_def['ship_zip']['value'] = 'sql';

// fullname :: string :: 300
$qadmin_def['div3']['title'] = 'Notes';
$qadmin_def['div3']['field'] = 'div3';
$qadmin_def['div3']['type'] = 'div';

// user_notes :: date :: 10
$qadmin_def['user_notes']['title'] = 'Notes';
$qadmin_def['user_notes']['field'] = 'user_notes';
$qadmin_def['user_notes']['type'] = 'text';
$qadmin_def['user_notes']['value'] = 'sql';

// general configuration ( * = optional )
$qadmin_cfg['table'] = $db_prefix.'user';				// table name
$qadmin_cfg['primary_key'] = 'user_id';					// table's primary key
$qadmin_cfg['primary_val'] = 'dummy';					// primary key value
$qadmin_cfg['template'] = 'default';					// template to use
$qadmin_cfg['post_process'] = 'post_process';
$qadmin_cfg['log_title'] = 'user_id';					// qadmin field to be used as log title (REQUIRED even if you don't use log)

// folder configuration (qAdmin only stores filename.ext without folder location), ends without slash '/' - optional
$qadmin_cfg['file_folder'] = './../public';					// folder to place file upload (relative to /admin folder)
$qadmin_cfg['img_folder'] = './../public/img';				// folder to place image upload
$qadmin_cfg['thumb_folder'] = './../public/thumb';			// folder to place thumb (auto generated)

// search configuration
$qadmin_cfg['search_key'] = 'user_id,user_email,admin_level';			// list other key to search
$qadmin_cfg['search_key_mask'] = 'User ID,Email Address,Admin?';	// mask other key
$qadmin_cfg['search_result_mask'] = ',,admin_level_def';	// mask other key

$qadmin_cfg['search_filterby'] = 'admin_level>0';	// filter by sql_query (use , to separate queries) *
$qadmin_cfg['search_filtermask'] = 'Administrators Only';				// mask filter *

// enable qadmin functions, which are: search, list, new, update & remove
$qadmin_cfg['cmd_default'] = 'list';					// if this script called without ANY parameter
$qadmin_cfg['cmd_search_enable'] = true;
$qadmin_cfg['cmd_list_enable'] = true;
$qadmin_cfg['cmd_new_enable'] = true;
$qadmin_cfg['cmd_update_enable'] = true;
$qadmin_cfg['cmd_remove_enable'] = true;

// security *** qADMIN CAN NOT RUN IF admin_level NOT DEFINED ***
$qadmin_cfg['admin_level'] = 'manage_user';

// form title
$qadmin_title['new'] = 'New User';
$qadmin_title['update'] = 'User Edit';
$qadmin_title['search'] = 'User Search';
$qadmin_title['list'] = 'User List';
qadmin_manage($qadmin_def, $qadmin_cfg, $qadmin_title);
