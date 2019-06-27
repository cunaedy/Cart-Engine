<?php
require './includes/user_init.php';

$cmd = get_param('cmd');
$q = get_param('query');
$limit = 20;

switch ($cmd) {
    case 'userOk':
        if (!empty($q)) {
            $foo = sql_qquery("SELECT user_id FROM ".$db_prefix."user WHERE user_id='$q' LIMIT 1");
            if (!empty($foo) || !preg_match("/^[[:alnum:]]+$/", $q)) {
                flush_json(0) ;
            } else {
                flush_json(1);
            }	// 1 = username is ok
        }
    break;


    case 'emailOk':
        if (!empty($q)) {
            if ($isLogin) {
                $foo = sql_qquery("SELECT user_email FROM ".$db_prefix."user WHERE (user_email='$q') AND (user_id!='$current_user_id') LIMIT 1");
            } else {
                $foo = sql_qquery("SELECT user_email FROM ".$db_prefix."user WHERE user_email='$q' LIMIT 1");
            }
            if (!empty($foo) || !validate_email_address($q)) {
                flush_json(0);
            } else {
                flush_json(1);
            }	// 1 = email is ok
        }
    break;


    case 'search_filter':
        $cat_id = get_param('cat_id');
        $price = get_param('price');
        $distro_id = get_param('distro_id');
        $search_mode = get_param('search_mode');

        $output = $row = $txt = array();

        // category
        if ($search_mode == 'list') {
            $cat_list = false;
            $txt['cat_select'] = $ce_cache['cat_structure'][$cat_id];
        } else {
            $cat_list = true;
            $txt['cat_select'] = create_select_form('cat_id', $ce_cache['cat_structure_top'], $cat_id, '('.$lang['l_all'].')');
        }

        // price
        $txt['price_max'] = $max = $ce_cache['cfg']['max_price'];
        $txt['num_currency'] = $config['num_currency'];

        $foo = explode(';', $price);
        $price_from = empty($foo[0]) ? 0 : $foo[0];
        $price_to = empty($foo[1]) ? $txt['price_max'] : $foo[1];
        $txt['price_from'] = ($price_from < 0) ? 0 : $price_from;
        $txt['price_to'] = ($price_to > $max) ? $max : $price_to;

        // brands
        $txt['distro_select'] = create_select_form('distro_id', $ce_cache['distro'], $distro_id, '('.$lang['l_all'].')');

        // cf
        $res = sql_query("SELECT * FROM ".$db_prefix."product_cf_define WHERE ((cf_category='') OR (cf_category LIKE '%,$cat_id,%')) AND is_searchable='1' ORDER BY cf_type");		// all categories cf
        while ($row = sql_fetch_array($res)) {
            $key = 'cf_'.$row['idx'];
            $val = stripslashes(get_param($key));
            switch ($row['cf_type']) {
                case 'select':
                    $foo = explode("\r\n", $row['cf_option']);
                    $fii = safe_send($foo, true);
                    $val = str_replace('=', '%3D', $val);	// as browser replace = with %3D, we need to restore the value
                    $foo = array_pair($fii, $foo, '('.$lang['l_all'].')');
                    $field = create_select_form($key, $foo, $val);
                break;

                case 'multi':
                    // definition
                    $foo = explode("\r\n", $row['cf_option']);
                    $foo = array_pair(safe_send($foo, true), $foo);

                    // value
                    if (empty($val)) {
                        $fii = checkbox_param($key, 'get', true);
                        if (!empty($fii)) {
                            $val = implode("\r\n", $fii);
                        }
                    } else {
                        $fii = array(str_replace('=', '%3D', $val));
                    }

                    // form
                    $field = create_checkbox_form($key, $foo, $fii, 1);
                break;

                case 'rating':
                    $field = create_radio_form($key, $rating_def, $val);
                break;

                default:
                    $field = false;
                break;
            }

            if ($field) {
                $row['field'] = $field;
                $output[] = quick_tpl($tpl_section['cf_list'], $row);
            }
        }

        $txt['cat_id'] = $cat_id;
        $txt['cf_list'] = implode($output, "\n");
        echo quick_tpl(load_tpl('var', $tpl_section['cf_form']), $txt);
    break;
}
