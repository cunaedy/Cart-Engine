<?php
// part of qEngine

/******************************************************************

 LOCAL FUNCTIONS

******************************************************************/

// get child cats from a cat
// ps. we can use mysql query, but that would be the purpose of caching
// $everything = true to get all descendant (children, grand children, great grand children, ...); false: only get children
// returns array of child (flat array)
function get_cat_child($cat_id, $everything = false)
{
    global $ce_cache;
    $list = array();

    // get current structure, eg: 1,2,
    $me = $ce_cache['cat_structure_id'][$cat_id].',';
    $l = strlen($me);
    $cc = substr_count($me, ',');

    // find any other structure having: 1,2,nnn; but not 1,2,nnn,3
    foreach ($ce_cache['cat_structure_id'] as $k => $v) {
        if (substr($v, 0, $l) == $me) {
            if ($everything) {
                $list[] = $k;
            } elseif (substr_count($v, ',') == $cc) {
                $list[] = $k;
            }
        }
    }

    return $list;
}


// create list of cats under a specific dir & cat
// $dir_id = dir ID
// $cat_id = cat ID, if empty, returns top cats for dir ID
// returns array of necessary contents for building cat_list (see listing_search.php & index.php)
// *) returns stored in cache
function create_cat_list($cat_id = 0)
{
    global $ce_cache, $db_prefix, $config;
    $list = array();

    $output = qcache_get("catlist_.$cat_id", false);
    if (!$output) {
        if ($cat_id) {
            $list = get_cat_child($cat_id);
        } else {
            // if no 'cat_id', get top cats
            $list = array_keys($ce_cache['cat_structure_top']);
        }

        // default thumb
        $cat_default_img = "$config[site_url]/$config[skin]/images/nothumb_small.png";

        // get its children & grand children
        foreach ($list as $child) {
            $row = array();
            $img = sql_qquery("SELECT cat_image FROM ".$db_prefix."product_cat WHERE idx='$child' LIMIT 1");

            $row['cat_image'] = $img['cat_image'] ? $config['site_url'].'/public/image/'.$img['cat_image'] : $cat_default_img;
            $row['cat_url'] = $ce_cache['cat_url'][$child];
            $row['cat_name'] = '<a href="'.$ce_cache['cat_url'][$child].'">'.$ce_cache['cat_name_def'][$child].'</a>';
            $output[] = $row;
        }

        qcache_update("catlist_.$cat_id", serialize($output), false);
    } else {
        $output = unserialize($output);
    }
    if (!is_array($output)) {
        $output = array();
    }
    return $output;
}


function get_distro($distro_id = '')
{
    global $config, $db_prefix, $ce_cache;

    if (is_numeric($distro_id)) {
        return isset($ce_cache['distro'][$distro_id]) ? $ce_cache['distro'][$distro_id] : '';
    } else {
        return $ce_cache['distro'];
    }
}


// create thumbnail
// image_id = ITEMID_i, eg 1_1, 1_2, 123_1
// mode = 'list' => medium size, non clickable
//        'detail' => medium size, clickable
//        'feature' => medium size, non clickable
//        'small' => small (50px)
//        'raw' => return actual file name of big image, caution if not exists -> return false
function make_thumb($image_id, $mode, $admin = false)
{
    global $config, $tpl_section, $in_admin_cp;
    $thumb_size = $config['thumb_size'];
    $quality = $config['thumb_quality'];
    if ($in_admin_cp) {
        $admin = true;
    } else {
        $admin = false;
    }

    $img_fn = "$image_id.jpg";
    ;
    $folder = $config['abs_path'].'/public/products'; // : './public/products';
    $tolder = $config['abs_path'].'/public/products_thumbs'; // : './public/products_thumbs';
    $img_src_url = $config['site_url']."/public/products/$img_fn";
    $img_th_url = $config['site_url']."/public/products_thumbs/$img_fn";
    $img_sm_url = $config['site_url']."/public/products_thumbs/small_$img_fn";
    $img_src = "$folder/$img_fn";
    $img_th  = "$tolder/$img_fn";
    $img_sm  = "$tolder/small_$img_fn";

    if (!file_exists($img_src)) {   // if file not found
        if (($mode == 'feature') || ($mode == 'gallery')) {
            $mode = 'detail';
        }
        if ($mode == 'raw') {
            return false;
        }

        if ($mode == 'newsletter') {
            return '';
        } else {
            return "<img border=\"0\" src=\"$config[site_url]/$config[skin]/images/nothumb_$mode.png\" title=\"No thumbnail\" alt=\"No thumbnail\" />";
        }
    } else {
        // if thumbnail image not exists -> create it
        if ($mode == 'small') {
            $img_th = $img_sm;
            $size = 50;
        } else {
            $size = 'thumb';
        }
        if (!file_exists($img_th)) {
            image_optimizer($img_src, $img_th, $quality, $size);
        }
    }

    // display it ...
    if (($mode == 'list') || ($mode == 'feature')) {
        $img_txt = "<img border=\"0\" src=\"$img_th_url\" alt=\"image\" />";
    } elseif ($mode == 'small') {
        $img_txt = "<img border=\"0\" src=\"$img_sm_url\" alt=\"image\" />";
    } elseif ($mode == 'newsletter') {	// for newsletter (need absolute url)
        $img_th = substr($img_th, 5);
        $img_txt = "<img border=\"0\" src=\"$img_th_url\" alt=\"image\" />";
    } elseif ($mode == 'detail') {	// for detail.
        $item_id = substr($image_id, 0, (strpos($image_id, '_')));
        $j = strpos($image_id, '_');
        $x = substr($image_id, $j+1);
        $row = array();
        $row['img_txt'] = "<img border=\"0\" src=\"$img_th_url\" alt=\"image\" />";
        $row['img_fn'] = $img_src_url;
        if (!$admin) {
            $img_txt = quick_tpl($tpl_section['detail_gallery'], $row);
        } else {
            $img_txt = "<a href=\"$row[img_fn]\">$row[img_txt]</a>";
        }
    } else {
        $img_txt = $img_src_url;
    }

    return $img_txt;
}


// verify selected values for multi select & select CF, it automatically removes invalid options.
// $selected = string or array of selected values (* safe_send'd *)
// $options = string or array of available options. If string, each options must be separated by \r\n (* plain text *)
// returns = string or array of verified value(s). If no valid selected value, returns false. (* plain text *)
function verify_selected($selected, $options)
{
    $is_arr = true;

    if (!is_array($selected)) {
        $selected = array($selected);
        $is_arr = false;
    }
    if (!is_array($options)) {
        $options = explode("\r\n", $options);
    }

    // convert options to safe_send as keys
    $opts = safe_send($options, true);
    $foo = array_pair($opts, $options);

    // eliminate mismatched values
    $verified = array();
    foreach ($selected as $k => $v) {
        $vv = str_replace('=', '%3D', $v);
        if (array_key_exists($vv, $foo)) {
            $verified[] = $foo[$vv];
        }
    }

    if (empty($verified)) {
        return false;
    }

    if (!$is_arr) {
        return $verified[0];
    } else {
        return $verified;
    }
}


// get custom field values from DB
// for: list.php & detail.php
function get_custom_field($cf_arr, $is_detail = true)
{
    global $db_prefix, $ce_cache, $config;
    $output = array();
    $site_url = $config['site_url'];
    $ffolder = './public/file';
    $tfolder = './public/thumb';
    $ifolder = './public/image';
    $pfolder = './public/private';
    $i = 0;

    $cf_custom_sort = $ce_cache['cf_custom_sort'];
    foreach ($ce_cache['cf_define'] as $row) {
        $cid = 'cf_'.$row['idx'];
        $val = $cf_arr[$cid];
        $cf_search = $cid.'='.$val;
        $custom = '';

        switch ($row['cf_type']) {
            case 'file':
                if (!empty($val) && $is_detail) {
                    $custom = "<a href=\"$ffolder/$val\">$val</a>";
                }
            break;

            case 'img':
                if (!empty($val) && $is_detail) {
                    $opt = explode('|', $row['cf_option']);
                    $custom = "<a href=\"$ifolder/$val\" class=\"lightbox\"><img src=\"$tfolder/$val\" alt=\"thumb\" /></a>";
                }
            break;

            case 'rating':
                if (!empty($val)) {
                    if ($is_detail) {
                        $custom = rating_img($val);
                    } else {
                        $custom = rating_img($val, 10);
                    }
                    if ($row['is_searchable']) {
                        $custom = "<a href=\"$site_url/shop_search.php?$cf_search\">$custom</a>";
                    }
                }
            break;

            case 'select':
            case 'radio':
                if ($val) {
                    $fii = safe_send($val, true);
                    $custom = $val;
                    if ($row['is_searchable']) {
                        $custom = "<a href=\"$site_url/shop_search.php?$cid=$fii\">$custom</a>";
                    }
                }
            break;

            case 'multi':
                if ($val) {
                    $foo = explode("\r\n", $val);
                    array_shift($foo);
                    array_pop($foo);
                    $custom = implode(', ', $foo);
                    if ($row['is_searchable']) {
                        $fii = array();
                        foreach ($foo as $v) {
                            $k = safe_send($v, true);
                            $fii[] = "<a href=\"$site_url/shop_search.php?$cid=$k\">$v</a>";
                        }
                        $custom = implode(', ', $fii);
                    }
                }
            break;

            default:
                if (!empty($val)) {
                    $custom = $val;
                    if ($row['is_searchable']) {
                        $custom = "<a href=\"$site_url/shop_search.php?cfonly=1&amp;$cf_search\">$val</a>";
                    }
                }
            break;
        }

        // create design (auto)
        $row['value'] = $custom;
        if (empty($row['value'])) {
            unset($custom);
        } else {
            $i++;
            if ($row['is_list'] || $is_detail) {
                $output[$cid] = array('cf_idx' => $cid, 'cf_title' => $row['cf_title'], 'cf_value' => $custom, 'cf_type' => $row['cf_type'], 'cf_raw' => $val);
            }
        }
    }

    if (!$i) {
        return false;
    } else {
        return $output;
    }
}


function format_billing_address($addr)
{
    $addr['address'] = $addr['bill_address'];
    $addr['address2'] = empty($addr['bill_address2']) ? '' : $addr['bill_address2'];
    $addr['city'] = $addr['bill_city'];
    $addr['state'] = $addr['bill_state'];
    $addr['country'] = $addr['bill_country'];
    $addr['zip'] = $addr['bill_zip'];
    $addr['district'] = $addr['bill_district'];
    return format_address($addr);
}


function format_shipping_address($addr)
{
    $addr['address'] = $addr['ship_address'];
    $addr['address2'] = empty($addr['ship_address2']) ? '' : $addr['ship_address2'];
    $addr['city'] = $addr['ship_city'];
    $addr['state'] = $addr['ship_state'];
    $addr['country'] = $addr['ship_country'];
    $addr['zip'] = $addr['ship_zip'];
    $addr['district'] = $addr['ship_district'];
    return format_address($addr);
}


function process_product_info(&$row, $mode = 'list')
{
    global $config, $lang, $ce_cache, $isLogin;

    // tax
    $row['tax'] = '';
    $tax = 0;
    $row['price_tax'] = ($lang['l_with_tax_guest'] == '-') ? '' : $lang['l_with_tax_guest'];
    if ($isLogin) {
        $tax = get_tax($row['tax_class'], $row['price']);
        if ($config['cart']['display_price_tax'] == 'separate') {
            $row['tax'] = $tax > 0 ? num_format($tax, 0, 1) : $lang['l_free'];
            $row['price_tax'] = $row['price'] > 0 ? sprintf($lang['l_with_tax'], num_format($row['price'] + $tax, 0, 1)) : '';
        } elseif ($config['cart']['display_price_tax'] == 'merge') {
            $row['price'] = $row['price'] + $tax;
        }
    }

    // discount
    $diskon_pct = 0;
    if ($row['price_msrp'] > 0) {
        $diskon_pct = round((($row['price_msrp'] - $row['price']) / $row['price_msrp']) * 100);
        if ($isLogin) {
            if ($config['cart']['display_price_tax'] == 'separate') {
                $row['price_msrp'] = $row['price_msrp'] + get_tax($row['tax_class'], $row['price_msrp']);
                $diskon_pct = round((($row['price_msrp'] - $row['price'] - $tax) / $row['price_msrp']) * 100);
            } elseif ($config['cart']['display_price_tax'] == 'merge') {
                $row['price_msrp'] = $row['price_msrp'] + get_tax($row['tax_class'], $row['price_msrp']);
                $diskon_pct = round((($row['price_msrp'] - $row['price']) / $row['price_msrp']) * 100);
            }
        } else {
            $diskon_pct = round((($row['price_msrp'] - $row['price']) / $row['price_msrp']) * 100);
        }
        $row['price_msrp'] = '<s>'.num_format($row['price_msrp'], 0, 1).'</s>';
    } else {
        $row['price_msrp'] = '';
    }

    if ($diskon_pct > 0) {
        $row['discount'] = sprintf($lang['l_discount'], $diskon_pct);
    } else {
        $row['discount'] = '';
    }

    // stock
    if ($row['stock']) {
        $row['stock_status'] = sprintf($lang['l_stock_ready'], $row['stock']);
    } else {
        $row['stock_status'] = $lang['l_stock_empty'];
    }


    // the rest
    $row['cat_structure'] = $ce_cache['cat_structure'][$row['cat_id']];
    $row['cat_name'] = $ce_cache['cat_name_def'][$row['cat_id']];
    $row['cat_link'] = $ce_cache['cat_structure_link'][$row['cat_id']];
    $row['details'] = (empty($row['details']))? $lang['l_no_description'] : $row['details'];
    $row['short_details'] = line_wrap(strip_tags($row['details']), 255);
    $row['details'] = convert_smilies($row['details']);
    $row['price'] = $row['price'] > 0 ? num_format($row['price'], 0, 1) : $lang['l_free'];
    $row['weight'] = num_format($row['weight'], 1);
    $row['distro_id'] = $row['distro'];
    $row['distro'] = get_distro($row['distro']);
    $row['image'] = make_thumb($row['item_id'].'_1', $mode);
    $row['image_raw'] = make_thumb($row['item_id'].'_1', 'raw');
    $row['image_small'] = make_thumb($row['item_id'].'_1', 'small');
    if ($row['digital_file']) {
        $row['digital'] = $lang['l_digital_icon_small'];
    } else {
        $row['digital'] = '';
    }
    if ($row['is_call_for_price']) {
        $row['price'] = $lang['l_list_call_price'];
    }
    if ($config['enable_adp'] && $row['permalink']) {
        $row['url'] = $row['permalink'];
    } else {
        $row['url'] = "detail.php?item_id=$row[item_id]";
    }

    // breadcrumbs
    $breadcat = array();
    $foo = explode(',', $ce_cache['cat_structure_id'][$row['cat_id']]);
    foreach ($foo as $k => $v) {
        $breadcat[] = array('bc_link' => $ce_cache['cat_url'][$v], 'bc_title' => $ce_cache['cat_name_def'][$v]);
    }
    $breaditem = $breadcat;
    $breaditem[] = array('bc_link' => $row['url'], 'bc_title' => $row['title']);
    $row['bread_cat'] = $breadcat;
    $row['bread_item'] = $breaditem;

    return $row;
}


// send digital product to customer (ie. send email to download the product in My Account)
// $auto_complete = true to allow the function to send the email automatically, and mark the order as complete if the order contains only digital products
// return: array ('any_digital' => is there any digital products bought, 'all_digital' => true if all products are digital, list of digital products)
function send_digital_products($order_id, $auto_complete = false)
{
    global $db_prefix, $tpl_block, $lang, $sql_today, $config;

    // summary
    $summary = sql_qquery("SELECT * FROM ".$db_prefix."order_summary WHERE order_id='$order_id' LIMIT 1");

    // get orders
    $result = array('any_digital' => false, 'all_digital' => false);
    $prod_list = array();
    $all_digital = true;
    $res = sql_query("SELECT * FROM ".$db_prefix."order_final AS o JOIN ".$db_prefix."products AS p WHERE p.idx=o.item_id AND o.order_id = '$order_id'");
    while ($row = sql_fetch_array($res)) {
        if ($row['digital_file']) {
            $result[] = $row['item_id'];
            $result['any_digital'] = true;
            $prod_list[$row['item_id']] = $row;

            // get current library
            $foo = sql_qquery("SELECT * FROM ".$db_prefix."user_file WHERE (user_id='$summary[user_id]') AND (item_id='$row[item_id]') LIMIT 1");
            if (!$foo) {
                sql_query("INSERT INTO ".$db_prefix."user_file SET user_id='$summary[user_id]', item_id='$row[item_id]', order_idx='$order_id'");
            }
        } else {
            $all_digital = false;
        }
    }
    if ($all_digital) {
        $result['all_digital'] = true;
    }

    //  --------------------------------
    if ($auto_complete && $result['any_digital']) {
        $mail_body = load_tpl('mail', 'digital');
        $summary['block_list'] = '';
        foreach ($prod_list as $k => $v) {
            $summary['block_list'] .= quick_tpl($tpl_block['list'], $v);
        }

        $mail_body = quick_tpl($mail_body, $summary);
        email($summary['user_email'], sprintf($lang['l_mail_order_digital_subject'], $config['site_name'], $order_id), $mail_body, 1, 1);

        if ($result['all_digital']) {
            sql_query("UPDATE ".$db_prefix."order_summary SET order_status='C', order_shipped = '$sql_today', order_delivered='$sql_today', order_completed='$sql_today' WHERE order_id = '$order_id' LIMIT 1");
        }
    }

    if ($result['any_digital']) {
        return $result;
    } else {
        return false;
    }
}


// get category structure, like: cat 1; cat 1 > cat 1-1; cat 1 > cat 1-1 > cat 1-1-1
// returns: GLOBAL vars of:
// $cat_structure = array of each cat ID & its name with its PARENT ([1] => 'Abc'; [2] => 'Abc > Cde'; [3] => 'Abc > Cde > Fgh');
// $cat_structure_top = array of all TOP LEVEL ONLY cat ID & its name
// $cat_structure_id = array of PATH of cat ID ([1] => 1; [2] => 1,2; [3] => 1,2,3)
// $cat_name_def = array of category name only with out parent name ([1] => 'Abc'; [2] => 'Cde'; [3] => 'Fgh');
// $cat_permalink_def = array of permalink
// $cat_structure_html = string of category structure in HTML (unordered list <ul><li>)
// all values will be stored in cache (if cache enabled)
function get_cat_structure($cat_id = 0, $level = 0, $prefix = '', $prefix_id = '')
{
    global $ce_cache, $config, $db_prefix;
    $cat_sort = $ce_cache['cat_sort'];
    $exists = false;

    if (!isset($ce_cache['cat_structure_html'])) {
        $ce_cache['cat_structure_html'] = '';
    }
    $res = sql_query("SELECT * FROM ".$db_prefix."product_cat WHERE parent_id='$cat_id' ORDER BY FIELD(idx,$cat_sort)");
    while ($row = sql_fetch_array($res)) {
        if (!$exists) {
            if ($level == 0) {
                $ce_cache['cat_structure_html'] .= str_repeat("\t", $level)."<ul id=\"myID\" class=\"myCLASS\">\n";
            } else {
                $ce_cache['cat_structure_html'] .= str_repeat("\t", $level)."<ul>\n";
            }
            $exists = true;
        }

        $path = (empty($prefix)) ? $row['cat_name'] : strip_tags($prefix).' &raquo; '.$row['cat_name'];
        $path_id = (empty($prefix_id)) ? $row['idx'] : $prefix_id.','.$row['idx'];
        $ce_cache['cat_structure'][$row['idx']] = $path;
        if (!$level) {
            $ce_cache['cat_structure_top'][$row['idx']] = $row['cat_name'];
        }
        if ($config['enable_adp'] && $row['permalink']) {
            $row['url'] = $config['site_url'].'/'.$row['permalink'];
        } else {
            $row['url'] = "$config[site_url]/shop_search.php?cat_id=$row[idx]";
        }
        $path_link = (empty($prefix)) ? "<a href=\"$row[url]\">$row[cat_name]</a>" : $prefix.' &raquo; '."<a href=\"$row[url]\">$row[cat_name]</a>";
        $ce_cache['cat_name_def'][$row['idx']] = $row['cat_name'];
        $ce_cache['cat_url'][$row['idx']] = $row['url'];
        $ce_cache['cat_structure_link'][$row['idx']] = $path_link;
        $ce_cache['cat_structure_id'][$row['idx']] = $path_id;
        $ce_cache['cat_permalink_def'][$row['idx']] = $row['permalink'];
        $ce_cache['cat_structure_html'] .= str_repeat("\t", $level + 1)."<li><a href=\"$row[url]\">$row[cat_name]</a>\n";
        get_cat_structure($row['idx'], $level+1, $path_link, $path_id);
    }
    if ($exists) {
        $ce_cache['cat_structure_html'] .= str_repeat("\t", $level)."</ul>\n";
    }
}


// create smart search cache
function create_search_cache($item_id)
{
    global $db_prefix, $ce_cache;
    $row = sql_qquery("SELECT *, t1.idx AS item_id FROM ".$db_prefix."products AS t1 LEFT JOIN ".$db_prefix."product_cf_value AS t2 ON (t1.idx=t2.item_id) WHERE item_id='$item_id' LIMIT 1");

    // cf type to store as smart cache
    $cfsc = array('varchar', 'textarea', 'country', 'select', 'multi');
    $cfsc_str = '';
    foreach ($ce_cache['cf_define'] as $k => $v) {
        if (in_array($v['cf_type'], $cfsc) && ($v['is_searchable'])) {
            $cfsc_str .= $row[$k].' ';
        }
    }

    // search_cache
    $string = strip_tags($row['sku'].' '.$row['title'].' '.$row['details'].' '.$cfsc_str);
    $string = str_replace('&#039;', '\'', $string);
    $string = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($string));	// remove non alphanumeric, but keep UTF-8 chars
    $string = preg_replace('!\s+!', ' ', $string);								// replace tab & spaces as single space
    $string = addslashes(implode(' ', array_unique(explode(' ', $string))));// unique
    sql_query("UPDATE ".$db_prefix."products SET smart_search='$string' WHERE idx='$row[item_id]' LIMIT 1");
}


// to compare two string, by eliminating: spaces, new lines & case insensitive; useful to compare city, state & country name (hence the name: csn)
function comparecsn($one, $two)
{
    $one = preg_replace('/\s*/m', '', strtolower($one));
    $two = preg_replace('/\s*/m', '', strtolower($two));
    if ($one == $two) {
        return true;
    } else {
        return false;
    }
}


// get tax for customer
// $c = city; $s = state; $y = country ===> of shipping/billing address
function get_tax($tax_class, $subtotal)
{
    global $config, $tax_cache, $current_user_info;

    $rate = get_tax_rate($tax_class);

    if ($config['cart']['tax_base'] == 'shipping') {
        $tbase = 'ship';
    } else {
        $tbase = 'bill';
    }
    if (comparecsn($config['site_city'], $current_user_info[$tbase.'_city'])) {
        $tax_rate = $rate['tax_city'];
    } elseif (comparecsn($config['site_state'], $current_user_info[$tbase.'_state'])) {
        $tax_rate = $rate['tax_state'];
    } elseif (comparecsn($config['site_country'], $current_user_info[$tbase.'_country'])) {
        $tax_rate = $rate['tax_nation'];
    } else {
        $tax_rate = $rate['tax_world'];
    }

    return ($tax_rate / 100) * $subtotal;
}


// get tax rates
function get_tax_rate($tid)
{
    global $ce_cache;
    $row = array();
    $row['tax_city'] = $row['tax_state'] = $row['tax_nation'] = $row['tax_world'] = 0;
    if (!empty($ce_cache['tax_rate'][$tid])) {
        $rate = unserialize($ce_cache['tax_rate'][$tid]);
        $row['tax_city'] = $rate['city'];
        $row['tax_state'] = $rate['state'];
        $row['tax_nation'] = $rate['nation'];
        $row['tax_world'] = $rate['world'];
    }
    return $row;
}


// delete featured product from all featured features
function delete_feat($item_id)
{
    global $config, $db_prefix;

    $search[] = ";$item_id;";
    $replace[] = ';';
    $search[] = ";$item_id";
    $replace[] = '';
    $search[] = "$item_id;";
    $replace[] = '';
    $search[] = $item_id;
    $replace[] = '';

    // top category
    $t = str_replace($search, $replace, $config['cart']['featured_product']);
    sql_query("UPDATE ".$db_prefix."config SET config_value='$t' WHERE config_id='featured_product' LIMIT 1");

    // other categories
    $res = sql_query("SELECT idx, cat_featured FROM ".$db_prefix."product_cat WHERE cat_featured LIKE '%$item_id%'");
    while ($row = sql_fetch_array($res)) {
        $t = str_replace($search, $replace, $row['cat_featured']);
        sql_query("UPDATE ".$db_prefix."product_cat SET cat_featured = '$t' WHERE idx = '$row[idx]' LIMIT 1");
    }
}


// delete item
function delete_item($item_id)
{
    global $db_prefix, $config;
    delete_feat($item_id);

    // delete other attributes
    sql_query("DELETE FROM ".$db_prefix."product_cf_value WHERE item_id='$item_id' LIMIT 1");
    sql_query("DELETE FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
    sql_query("DELETE FROM ".$db_prefix."permalink WHERE target_script='detail.php' AND target_idx='$item_id' LIMIT 1");

    // delete images
    $i = 0;
    $ok = false;
    while (!$ok) {
        $i++;
        $fn = $item_id.'_'.$i.'.jpg';
        if (!@unlink("$config[abs_path]/public/products/$fn")) {
            $ok = true;
        }
        if (!@unlink("$config[abs_path]/public/products_thumbs/$fn")) {
            $ok = true;
        }
    }
}

// create sql for rating (0-5), eg: rating 1.1, will search between 1.0-1.49. Rating 2.5: 2.5-2.99
// $field = field name
// $val = rating value to search
function rating_sql($field, $val)
{
    if (!is_numeric($val) || (($val < 0) || ($val > 5))) {
        return false;
    }
    if (!$val) {
        return false;
    }
    $s = $val - 0.5;
    $e = $val + 0.49;
    if ($s < 0.51) {
        $s = 0.1;
    }
    if ($e > 5) {
        $e = 5;
    }
    return "(($field >= $s) AND ($field <= $e))";
}


function local_init()
{
    global $ce_cache, $config, $db_prefix;

    if (!empty($ce_cache['ok'])) {
        return;
    }
    $ce_cache = array();

    // GENERATE CATEGORY
    $cat_cache_def = array('cat_structure_top', 'cat_structure', 'cat_structure_id', 'cat_name_def', 'cat_structure_html', 'cat_structure_link', 'cat_permalink_def', 'cf_custom_sort', 'cf_define', 'cat_sort', 'cat_url', 'distro', 'cfg', 'tax_class', 'tax_rate');
    $foo = qcache_get($cat_cache_def);
    $ok = true;
    foreach ($cat_cache_def as $k => $v) {
        if (empty($foo[$v])) {
            $ok = false;
        }
    }
    if (!$ok) {
        // cat sorting
        $ce_cache['cat_structure_html'] = '';
        $ce_cache['cf_define'] = $ce_cache['cat_sort'] = $ce_cache['cat_structure'] = $ce_cache['cat_structure_top'] = $ce_cache['cat_name_def'] =
        $ce_cache['cat_url'] = $ce_cache['cat_structure_link'] = $ce_cache['cat_structure_id'] = $ce_cache['cat_permalink_def'] = array();

        $cat_sort = array();
        $res = sql_query("SELECT * FROM ".$db_prefix."menu_item AS t1 JOIN ".$db_prefix."product_cat AS t2 WHERE t1.idx=t2.menu_mid ORDER BY t1.menu_order");
        while ($row = sql_fetch_array($res)) {
            $cat_sort[] = $row['idx'];
        }
        $cat_sort = implode(',', $cat_sort);
        if (empty($cat_sort)) {
            $cat_sort = 0;
        }
        $ce_cache['cat_sort'] = $cat_sort;
        get_cat_structure();

        // cf sorting
        $cf_custom_sort = array();
        $res = sql_query("SELECT * FROM ".$db_prefix."menu_item AS t1 JOIN ".$db_prefix."product_cf_define AS t2 WHERE t1.idx=t2.menu_idx ORDER BY t1.menu_order");
        while ($row = sql_fetch_array($res)) {
            $cf_custom_sort[] = $row['idx'];
        }
        $cf_custom_sort = implode(',', $cf_custom_sort);
        if (empty($cf_custom_sort)) {
            $ce_cache['cf_custom_sort'] = $cf_custom_sort = 0;
        } else {
            $ce_cache['cf_custom_sort'] = $cf_custom_sort;
        }

        // cf definitions
        $res = sql_query("SELECT * FROM ".$db_prefix."product_cf_define ORDER BY FIELD(idx,$cf_custom_sort)");
        while ($row = sql_fetch_array($res)) {
            foreach ($row as $k => $v) {
                if (is_numeric($k)) {
                    unset($row[$k]);
                }
            }
            $ce_cache['cf_define']['cf_'.$row['idx']] = $row;
        }

        // distro (brands)
        $ce_cache['distro'] = get_editable_option('distro');

        // cfg
        $foo = sql_qquery("SELECT MAX(price) FROM ".$db_prefix."products");
        $ce_cache['cfg']['max_price'] = $foo[0];

        // tax
        $res = sql_query("SELECT * FROM ".$db_prefix."config WHERE group_id='var' AND config_id='tax'");
        while ($row = sql_fetch_array($res)) {
            $ce_cache['tax_class'][$row['idx']] = $row['config_value'];
            $tid = $row['idx'];
            $r = sql_qquery("SELECT * FROM ".$db_prefix."config WHERE group_id='var' AND config_id='tax_$tid' LIMIT 1");
            $ce_cache['tax_rate'][$row['idx']] = $r['config_value'];
        }

        foreach ($cat_cache_def as $k => $v) {
            qcache_update($v, serialize($ce_cache[$v]));
        }
    } else {
        foreach ($foo as $k => $v) {
            $ce_cache[$k] = unserialize($foo[$k]);
        }
    }
    $ce_cache['ok'] = true;
}


/******************************************************************

 LOCAL DEFINITIONS

******************************************************************/


// Local lang
$local_lang = array();
$local_lang['special'] = array('l_new_item', 'l_site_news', 'l_ask_us_title', 'l_share_title', 'l_my_wishlist', 'l_my_wishlist_why', 'l_trx_history', 'l_trx_history_why',
'l_my_files', 'l_my_files_why',
'l_my_address', 'l_my_address_why', 'l_confirm_address', 'l_bill_detail', 'l_ship_detail', 'l_copy_bill', 'l_change_address', 'l_phone_number',
'l_address', 'l_district', 'l_city', 'l_state', 'l_country', 'l_zipcode',  'l_district_empty', 'l_city_empty', 'l_state_empty', 'l_country_empty',
'l_select_district', 'l_select_city', 'l_select_state', 'l_select_country', 'l_if_applicable',
'l_my_cart', 'l_xe_help', 'l_last_item', 'l_best_seller',
'l_quantity', 'l_subtotal', 'l_total', 'l_no_item_cart', 'l_checkout', 'l_category', 'l_price_range', 'l_update_cart', 'l_update_cart_why',
'l_gift_coupon_input', 'l_gift_coupon_warning', 'l_ship_method', 'l_ship_method_why', 'l_method', 'l_pay_method', 'l_pay_method_why', 'l_order_note',
'l_order_note_why', 'l_fee', 'l_next', 'l_coupon_disc', 'l_shop_disc', 'l_tax_based_wo_ship', 'l_ship_fee', 'l_payment_fee', 'l_tax', 'l_place_order', 'l_product_added',
'l_distro_info', 'l_distro_product', 'l_invalid_distro_id', 'l_sort_by', 'l_list_style', 'l_include_out_of_stock', 'l_product_search', 'l_how_to_pay',
'l_pay_redir', 'l_pay_redir_3s', 'l_price', 'l_share_wishlist', 'l_ones_wishlist', 'l_empty_wishlist', 'l_description', 'l_detail', 'l_my_files_none',
'l_pay_status', 'l_order_status', 'l_my_order', 'l_my_order_title', 'l_ship_fee_for', 'l_ship_date', 'l_order_date', 'l_print_invoice', 'l_invoice',
'l_issue_date', 'l_trx_print_footer', 'l_sku', 'l_manufacturer', 'l_stock_status', 'l_ship_weight', 'l_min_buy', 'l_max_buy', 'l_customize_product',
'l_specification', 'l_buy_together', 'l_review', 'l_toolbox', 'l_notify_available', 'l_contact_product', 'l_add_wishlist', 'l_see_also', 'l_all_news',
'l_courier_free', 'l_express_checkout', 'l_express_checkout_why', 'l_list', 'l_grid', 'l_title_asc', 'l_title_dsc', 'l_price_asc', 'l_price_dsc',
'l_date_asc', 'l_date_dsc', 'l_digital_not_login', 'l_digital_icon', 'l_digital_icon_small', 'l_xpress_tag', 'l_default_address', 'l_discount',
'l_stock_empty', 'l_stock_ready', 'l_item_in_cart', 'l_cart_empty', 'l_free', 'l_add_buy', 'l_cant_buy', 'l_add_wish', 'l_view_file', 'l_list_call_price',
'l_detail_call_price', 'l_cart_call_price', 'l_min_max_buy_err', 'l_mail_order_subject', 'l_mail_order_digital_subject', 'l_mail_order_admin_subject',
'l_mail_order_pending', 'l_mail_order_process', 'l_mail_order_ship', 'l_mail_order_cancel', 'l_mail_order_cancel_request', 'l_mail_coupon', 'l_coupon_info',
'l_tell_us_subject','l_notify_us','l_bill_address','l_distro','l_express_checkout_tips','l_mycart_text','l_purchase','l_purchase_summary','l_remove_wishlist','l_ship_address',
'l_with_tax','l_with_tax_guest','l_promo');
$local_lang['msg'] = array('msg.not_queued','msg.order_id_null','msg.no_item_in_cart','msg.payment_not_selected','msg.shipper_not_selected','msg.courier_not_set',
'msg.item_id_not_found','msg.no_stock','msg.order_id_not_found','msg.no_item','msg.tax_info','msg.internal_error','msg.no_file','msg.cancel_not_allow','msg.coupon_ok',
'msg.coupon_err','msg.sub_type_err','msg.cancel_ok','mail.ask_product');
$local_lang['mail'] = array('mail.tell_product','mail.ship','mail.process','mail.pending','mail.notify','mail.digital','mail.coupon','mail.checkout_admin','mail.tell_us',
'mail.cancel','mail.checkout');

// Payment status
$payment_status_def = array('E' => 'Pending',
    'P' => 'Paid/Approved',
    'X' => 'Failed/Denied');

// Order status
$order_status_def = array('E' => 'Order Received',		// queued (order just accepted by server, initial status!)
    'P' => 'Processing',								// still processing (preparing goods, etc)
    'S' => 'Shipped',									// shipped to post office
    'D' => 'Delivered',								// delivered from post office to buyer
    'C' => 'Completed',								// all done
    'X' => 'Denied');									// cancelled (e.g fraud)

// detail icons
$listing_visible_icon = array('A' => '',
    'M' => '<span class="glyphicon glyphicon-lock"></span>',
    'H' => '<span class="glyphicon glyphicon-eye-close"></span>');

// product search sort
$search_sort = array('ta' => $lang['l_title_asc'],
    'td' => $lang['l_title_dsc'],
    'pa' => $lang['l_price_asc'],
    'pd' => $lang['l_price_dsc'],
    'da' => $lang['l_date_asc'],
    'dd' => $lang['l_date_dsc']);

// list mode
$list_mode = array('list' => $lang['l_list'],
    'grid' => $lang['l_grid']);

// Other vars
$lang['edit_product_in_acp'] = "<div class=\"edit_in_acp\"><a href=\"$config[site_url]/$config[admin_folder]/product.php?cmd=edit&amp;item_id=%s\" target=\"acp\" class=\"btn btn-xs btn-default\">Edit Product</a></div>";
$lang['edit_product_cat_in_acp'] = "<div class=\"edit_in_acp\"><a href=\"$config[site_url]/$config[admin_folder]/product_cat.php?id=%s\" target=\"acp\" class=\"btn btn-xs btn-default\">Edit Category</a></div>";
$lang['l_digital_icon'] = sprintf($lang['l_digital_icon'], $config['site_url']);
$lang['l_digital_icon_small'] = sprintf($lang['l_digital_icon_small'], $config['site_url']);
$config['cart']['menu_nav_idx'] = 8;
$config['cart']['menu_cf_idx'] = 8;
$config['cart']['menu_cat_idx'] = 7;


/******************************************************************

 LOCAL INIT

******************************************************************/


// my cart
$row = sql_qquery("SELECT SUM(qty) AS qty, SUM(qty*price) AS total FROM ".$db_prefix."orders AS o JOIN ".$db_prefix."products AS p WHERE p.idx=o.item_id AND user_id = '$current_user_id' LIMIT 1");
$txt['mycart_count'] = empty($row['qty']) ? 0 : $row['qty'];
$txt['mycart_total'] = num_format($row['total'], 0, 1);
$lang['l_mycart_text'] = sprintf($lang['l_mycart_text'], $txt['mycart_count'], $txt['mycart_total']);
local_init();
