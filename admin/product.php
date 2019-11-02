<?php
// format cf value
// $dir_id = dir_id
// $cf_val = array of item_info & raw cf_value
function get_cf($cat_id, $cf_val)
{
    global $db_prefix, $tpl_section, $rating_def, $ce_cache;

    $ffolder = './../public/listing';
    $ifolder = './../public/listing';
    $tfolder = './../public/listing_thumb';
    $axsrf = AXSRF_value();

    $output = array();

    // cat specific cf
    $cf_define = array();
    foreach ($ce_cache['cf_define'] as $row) {
        $foo = explode(',', $row['cf_category']);
        foreach ($foo as $k => $v) {
            if (($v == $cat_id) || (!$row['cf_category']) || ($row['cf_category'] == ',,')) {
                $cf_define[] = 'cf_'.$row['idx'];
            }
        }
    }

    foreach ($cf_define as $cat_cf) {
        $row = $ce_cache['cf_define'][$cat_cf];
        if ($row['cf_help']) {
            $row['cf_help'] = '<span class="glyphicon glyphicon-info-sign help tips" title="'.$row['cf_help'].'"></span>';
        }
        $key = 'cf_'.$row['idx'];
        $val = isset($cf_val[$key]) ? $cf_val[$key] : '';

        switch ($row['cf_type']) {
            case 'varchar':
                $field = "<input type=\"text\" name=\"$key\" size=\"50\" value=\"$val\" />";
            break;

            case 'video':
                $field = "<input type=\"text\" name=\"$key\" size=\"50\" value=\"$val\" placeholder=\"Paste a video URL from Youtube or Vimeo\" /> ";
                if ($val) {
                    $field .= "<a href=\"$val\" target=\"_blank\"><span class=\"glyphicon glyphicon-film\"></span></a>";
                }
            break;

            case 'textarea':
                $field = "<textarea name=\"$key\" cols=\"50\" rows=\"5\">$val</textarea>";
            break;

            case 'file':
                if (empty($val)) {
                    $field = "<input type=\"file\" name=\"$key\" size=\"37\" />";
                } else {
                    $fs = num_format(filesize("$ffolder/$val") / 1024);
                    $field = "<a href=\"$ffolder/$val\">$val</a> ($fs KB)<br /><a href=\"listing.php?cmd=del_cf&amp;cf_id=$key&amp;item_id=$cf_val[item_id]&amp;AXSRF_token=$axsrf\"><span class=\"glyphicon glyphicon-remove\"></span> Remove</a>";
                }
            break;

            case 'img':
                if (empty($val)) {
                    $field = "<input type=\"file\" name=\"$key\" />";
                } else {
                    $field = "<a href=\"$ifolder/$val\" class=\"lightbox\"><img src=\"$tfolder/$val\" alt=\"thumb\" /></a><br /><a href=\"listing.php?cmd=del_cf&amp;cf_id=$key&amp;item_id=$cf_val[item_id]&amp;AXSRF_token=$axsrf\"><span class=\"glyphicon glyphicon-remove\"></span> Remove</a>";
                }
            break;

            case 'select':
                $foo = explode("\r\n", $row['cf_option']);
                $foo = array_pair($foo, $foo, 'n/a');
                $field = create_select_form($key, $foo, $val);
            break;

            case 'multi':
                // selected vals
                $val = explode("\r\n", $val);
                $val = safe_send($val, true);

                $foo = explode("\r\n", $row['cf_option']);
                $fii = safe_send($foo, true);
                $foo = array_pair($fii, $foo);
                $field = create_checkbox_form($key, $foo, $val, 3);
            break;

            case 'rating':
                $field = create_select_form($key, $rating_def, $val);
            break;

            case 'gmap':
                $field = "<input type=\"text\" name=\"$key\" id=\"$key\" size=\"50\" value=\"$val\" class=\"width-md\"/> <a href=\"../gmap_picker.php?cmd=picker&amp;mode=latlon1&amp;fid=$key&amp;latlon=$val\" class=\"popiframe_sp\">Locate</a>";
            break;

            case 'div':
                $field = '<b>'.$row['cf_title'].'</b>';
            break;

            case 'wysiwyg':
                $field = rte_area($key, $val);
            break;

            default:
                $field = 'Unknown type: '.$row['cf_type'];
            break;
        }

        $row['cf_field'] = $field;
        $output[] = quick_tpl($tpl_section['cf_list'], $row);
    }

    return implode($output, "\n");
}


require './../includes/admin_init.php';
admin_check(4);

$cmd = get_param('cmd');
$keywords = get_param('keywords');
$search_by = get_param('search_by');
$item_id = get_param('item_id');
$popup = get_param('popup');

// data def
$search_def = array('key' => 'Keyword', 'item_id' => 'Item ID');
$call_def = array('0' => '', '1' => 'Call for Price');
$andor = array(0 => 'OR', 1 => 'AND');
$distro_def = get_distro();

switch ($cmd) {
    case 'del_digital':
        $row = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx = '$item_id' LIMIT 1");
        if (empty($row['idx'])) {
            admin_die('item_id_not_found');
        }
        if (empty($row['digital_file'])) {
            admin_die('echo', 'This item doesn\'t have digital download!');
        }
        $f = @unlink('./../public/private/'.$row['digital_file']);
        sql_query("UPDATE ".$db_prefix."products SET digital_file='' WHERE idx = '$item_id' LIMIT 1");
        qcache_clear();
        if (!$f) {
            admin_die('<h1>File Not Found!</h1><p>File not found or can not be deleted!</p>');
        } else {
            admin_die('admin_ok');
        }
    break;


    case 'del_img':
        $x = get_param('x');
        $ok = false; $i = $x;
        $fn = $item_id.'_'.$x;
        $folder = './../public/products';
        $tolder = './../public/products_thumbs';

        @unlink("$folder/$fn.jpg");
        @unlink("$tolder/$fn.jpg");
        @unlink("$tolder/small_$fn.jpg");

        while (!$ok) {
            $i++;
            $fn_old = $item_id.'_'.$i;
            $fn_new = $item_id.'_'.($i-1);

            if (file_exists("$folder/$fn_old.jpg")) {
                @rename("$folder/$fn_old.jpg", "$folder/$fn_new.jpg");
                @rename("$tolder/$fn_old.jpg", "$tolder/$fn_new.jpg");
                @rename("$tolder/small_$fn_old.jpg", "$tolder/small_$fn_new.jpg");
            } else {
                $ok = true;
            }
        }

        qcache_clear();
        admin_die('admin_ok');
    break;


    case 'edit':
        $tpl = load_tpl('adm', 'product.tpl');
        $row = sql_qquery("SELECT *, t1.idx AS idx FROM ".$db_prefix."products AS t1 LEFT JOIN ".$db_prefix."product_cf_value AS t2 ON (t1.idx=t2.item_id) WHERE t1.idx='$item_id' LIMIT 1");
        if (empty($row['idx'])) {
            admin_die('<h1>Error!</h1><p>Item Not Found!</p>');
        }

        // get images
        $ok = false; $i = 0; $txt['block_thumb'] = '';
        $tolder = './../public/products_thumbs';
        $polder = './../public/products';
        while (!$ok) {
            $i++;
            $fn = $item_id.'_'.$i;
            $img_src = "$tolder/$fn.jpg";
            if (file_exists($img_src)) {   // if thumbs avail
                $row['thumb'] = $img_src;
                $row['image'] = "$polder/$fn.jpg";
                $row['x'] = $i;
                $txt['block_thumb'] .= quick_tpl($tpl_block['thumb'], $row);
            } else {
                $ok = true;
            }
        }
        if ($i == 1) {
            $no_image = true;
        } else {
            $no_image = false;
        }

        // get see also
        $i = 0;
        $foo = explode(',', $row['see_also']);
        $mm = array();
        if ($row['see_also']) {
            foreach ($foo as $k => $v) {
                $i++;
                $mem = sql_qquery("SELECT idx, title FROM ".$db_prefix."products WHERE idx='$v' LIMIT 1");
                $mm[] = array('id' => $mem['idx'], 'name' => $mem['title']);
            }
        }
        $row['see_also_preset'] = $i ? json_encode($mm) : 'null';

        // get digital product?
        if ($row['digital_file']) {
            $digital_product = true;
        } else {
            $digital_product = false;
        }

        $add_cat = empty($row['add_category']) ? '' : substr($row['add_category'], 1, strlen($row['add_category']) - 2);

        // multi tier
        $pq = unserialize($row['price_qty']);
        $i = 0;
        if ($pq) {
            foreach ($pq as $k => $v) {
                $i++;
                $row['price_qty_q'.$i] = $k;
                $row['price_qty_p'.$i] = $v;
            }
        }
        for ($j = $i + 1; $j <= 5; $j++) {
            $row['price_qty_q'.$j] = $row['price_qty_p'.$j] = '';
        }

        $row['category_form'] = create_select_form('cat_id', $ce_cache['cat_structure'], $row['cat_id']);
        $row['add_category_form'] = create_checkbox_form('add_category', $ce_cache['cat_structure'], $row['add_category'], 1);
        $row['distro_select'] = create_select_form('distro', $distro_def, $row['distro'], '(None)');
        $row['details'] = rte_area('details', $row['details']);
        $row['tax_select'] = create_select_form('tax_class', get_editable_option('tax'), $row['tax_class'], '&nbsp;');
        $row['list_date'] = date_form('list_date', 2005, 1, 1, $row['list_date']);
        $row['call_price_select'] = create_select_form('call_price', $call_def, $row['is_call_for_price']);
        $row['cf_form'] = get_cf($row['cat_id'], $row);
        $row['stat_last_purchased'] = convert_date($row['stat_last_purchased']);
        $row['stat_last_hit'] = convert_date($row['stat_last_hit']);
        $row['preview_url'] = $config['enable_adp'] ? "../$row[permalink]" : "../detail.php?item_id=$item_id";
        $row['invisible_item_checked'] = $row['is_invisible'] ? 'checked="checked"' : '';
        $txt = array_merge($txt, $row);
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'product.tpl'), $txt);
        if ($popup) {
            flush_tpl('adm_popup');
        } else {
            flush_tpl('adm');
        }
    break;


    default:
        $tpl = load_tpl('adm', 'product.tpl');
        $row = create_blank_tbl($db_prefix.'products');
        $row['image'] = "<img border=\"0\" src=\"../$config[skin]/images/nothumb_detail.gif\" alt=\"No thumbnail\" />";
        $row['more_images'] = "<img border=\"0\" src=\"../$config[skin]/images/nothumb_detail.gif\" alt=\"No thumbnail\" />";
        $row['distro_select'] = create_select_form('distro', $ce_cache['distro'], 0, '(None)');
        $row['item_id'] = '(new)';
        $row['list_date'] = $sql_today;
        $row['stock'] = 1;
        $row['category_form'] = create_select_form('cat_id', $ce_cache['cat_structure']);
        $row['add_category_form'] = create_checkbox_form('add_category', $ce_cache['cat_structure'], '', 1);
        $row['details'] = rte_area('details', $row['details']);
        $row['list_date'] = date_form('list_date', date('Y'), 1, 1, $sql_today);
        $row['tax_select'] = create_select_form('tax_class', get_editable_option('tax'), 0, '&nbsp;');
        $row['call_price_select'] = create_select_form('call_price', $call_def);
        $row['block_thumb'] = $row['digi_check'] = $row['digital'] = $row['critical'] = '';
        $row['see_also_preset'] = 'null';
        $row['cf_form'] = '';
        $row['min_buy'] = 1;
        $row['max_buy'] = 0;
        $row['preview_url'] = '#';
        $row['invisible_item_checked'] = '';

        for ($i = 1; $i <= 8; $i++) {
            $row['price_qty_q'.$i] = $row['price_qty_p'.$i] = '';
        }
        $txt['main_body'] = quick_tpl($tpl, $row);
        flush_tpl('adm');
    break;
}
