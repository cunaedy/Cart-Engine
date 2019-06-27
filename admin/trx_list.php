<?php
require './../includes/admin_init.php';

// order_id :: string :: 48
$qadmin_def['order_id']['title'] = 'Order Id';
$qadmin_def['order_id']['field'] = 'order_id';
$qadmin_def['order_id']['type'] = 'varchar';
$qadmin_def['order_id']['size'] = 48;
$qadmin_def['order_id']['value'] = 'sql';

// user_id :: string :: 240
$qadmin_def['user_id']['title'] = 'User Id';
$qadmin_def['user_id']['field'] = 'user_id';
$qadmin_def['user_id']['type'] = 'varchar';
$qadmin_def['user_id']['size'] = 240;
$qadmin_def['user_id']['value'] = 'sql';

// order_date :: date :: 10
$qadmin_def['order_date']['title'] = 'Order Date';
$qadmin_def['order_date']['field'] = 'order_date';
$qadmin_def['order_date']['type'] = 'date';
$qadmin_def['order_date']['value'] = 'sql';

// order_total :: real :: 12
$qadmin_def['order_total']['title'] = 'Order Total';
$qadmin_def['order_total']['field'] = 'order_total';
$qadmin_def['order_total']['type'] = 'varchar';
$qadmin_def['order_total']['size'] = 12;
$qadmin_def['order_total']['value'] = 'sql';
$qadmin_def['order_total']['format'] = 'currency';

// order_paystat :: string :: 3
$qadmin_def['order_paystat']['title'] = 'Order Paystat';
$qadmin_def['order_paystat']['field'] = 'order_paystat';
$qadmin_def['order_paystat']['type'] = 'varchar';
$qadmin_def['order_paystat']['size'] = 3;
$qadmin_def['order_paystat']['value'] = 'sql';

// order_status :: string :: 3
$qadmin_def['order_status']['title'] = 'Order Status';
$qadmin_def['order_status']['field'] = 'order_status';
$qadmin_def['order_status']['type'] = 'varchar';
$qadmin_def['order_status']['size'] = 3;
$qadmin_def['order_status']['value'] = 'sql';

// general configuration ( * = optional )
$qadmin_cfg['table'] = $db_prefix.'order_summary';					// table name
$qadmin_cfg['primary_key'] = 'order_id';						// table's primary key
$qadmin_cfg['primary_val'] = 'dummy';						// primary key value
$qadmin_cfg['template'] = 'default';						// template to use

// logging
$qadmin_cfg['enable_log'] = true;			// log all changes (add/edit/remove), default = from qe_config
$qadmin_cfg['detailed_log'] = true;			// store modification values (may be big!), default = from qe_config
$qadmin_cfg['log_title'] = 'order_id';	// qadmin field to be used as log title (empty = disable log, no matter other cfg's)

// folder configuration (qAdmin only stores filename.ext without folder location), ends without slash '/' - optional
$qadmin_cfg['file_folder'] = './../public/file';					// folder to place file upload (relative to /admin folder)
$qadmin_cfg['img_folder'] = './../public/image';				// folder to place image upload
$qadmin_cfg['thumb_folder'] = './../public/thumb';			// folder to place thumb (auto generated)

// search configuration
$qadmin_cfg['search_key'] = 'order_id,user_id,order_total,order_paystat,order_status';		// list other key to search
$qadmin_cfg['search_key_mask'] = 'Order ID,User ID,Total,Payment Status,Order Status';	// mask other key

$qadmin_cfg['search_date_field'] = 'order_date';				// search by date field name *
$qadmin_cfg['search_start_date'] = true;					// show start date *
$qadmin_cfg['search_end_date'] = true;						// show end date *

$qadmin_cfg['search_filterby'] = "order_status='E',order_status='P',order_status='S',order_status='D',order_status='C',order_status='X',";	// filter by sql_query (use , to separate queries) *
$qadmin_cfg['search_filtermask'] = 'Received,Processed,Shipped,Delivered,Completed,Cancelled';				// mask filter *
$qadmin_cfg['search_edit'] = 'trx.php?order_id=__KEY__';		// edit using qadmin or other external editor
$qadmin_cfg['search_result_mask'] = ",,,payment_status_def,order_status_def";					// mask result by array (title_def must be array,

// enable qadmin functions, which are: search, list, new, update & remove
$qadmin_cfg['cmd_default'] = 'list';						// if this script called without ANY parameter
$qadmin_cfg['cmd_search_enable'] = true;
$qadmin_cfg['cmd_list_enable'] = true;
$qadmin_cfg['cmd_new_enable'] = false;
$qadmin_cfg['cmd_update_enable'] = true;
$qadmin_cfg['cmd_remove_enable'] = false;
$qadmin_cfg['admin_level'] = '4';

// form title
$qadmin_title['new'] = 'Add Transaction';
$qadmin_title['update'] = 'Update Transaction';
$qadmin_title['search'] = 'Search Transaction';
$qadmin_title['list'] = 'Transaction List';

qadmin_manage($qadmin_def, $qadmin_cfg, $qadmin_title);
