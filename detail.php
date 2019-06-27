<?php
// part of qEngine
require_once './includes/user_init.php';

$add = get_param('add');
$item_id = get_param('item_id');
if ($isPermalink) {
    $item_id = $original_idx;
}

$item = sql_qquery("SELECT *, idx AS item_id FROM ".$db_prefix."products WHERE idx = '$item_id' LIMIT 1");

// is available?
if (!$item) {
    msg_die($lang['msg']['item_not_found']);
}
process_product_info($item);

// load tpl
$multier = $sub_product = $see_also = $qty_more = $call_price = $is_stock = false;
$custom_field = true;
$cat_id = $item['cat_id'];

// digital product?
if ($item['digital_file']) {
    $digital_product = true;
}

// stock
if ($item['stock']) {
    $is_stock = true;
}

// discount
if (($item['price'] < $item['price_msrp']) && !$item['is_call_for_price']) {
    $discount_status = true;
}

// subproducts
if ($item['sub_product']) {
    $sub_product = $item['is_sub_product'] = true;
}

// min max buy
$min_buy = $item['min_buy'];
$max_buy = $item['max_buy'];
$item['min_buy'] = num_format($min_buy);
$item['max_buy'] = empty($max_buy) ? '&infin;' : ($item['stock'] < $max_buy ? num_format($item['stock']) : num_format($max_buy));
if (!empty($max_buy) || ($min_buy != 1)) {
    $qty_more = true;
}

// multier price
$pq = unserialize($item['price_qty']);
if (!empty($pq)) {
    $multier = true;
}

// call for price
if ($item['is_call_for_price']) {
    $call_price = true;
    $item['price'] = $lang['l_detail_call_price'];
}

// BUY button
if ($item['stock'] && !$call_price) {
    $item['buy'] = $lang['l_add_buy'];
} elseif ($call_price) {
    $item['buy'] = $lang['l_detail_call_price'];
} else {
    $item['buy'] = $lang['l_cant_buy'];
}

// contents are cached, but since we still need some dynamic contents (eg. Wish List), thus the cache stores only variables, not the whole output.
// .. therefore, not some template vars must be recreated even in cached content (see the 'else' condition below).
$content = qcache_get('detail_'.$item_id);
if (!$content) {
    // init tpl
    $tpl = load_tpl('user', 'detail.tpl');

    // image gallery
    $i = 0;
    $ok = false;
    $item['block_thumb'] = '';
    while (!$ok) {
        $i++;
        $fn = $item_id.'_'.$i;
        $folder = './public/products';
        if (file_exists($folder.'/'.$fn.'.jpg')) {
            $item['image'] = make_thumb($fn, 'detail');
            $item['block_thumb'] .= quick_tpl($tpl_block['thumb'], $item);
        } else {
            $ok = true;
        }
    }

    // if no image
    if ($i == 1) {
        $item['block_thumb'] = "<img border=\"0\" src=\"$config[skin]/images/nothumb_detail.png\" alt=\"image\" />";
    }

    // bread crumb
    $item['block_cat_bread_crumb'] = '';
    foreach ($item['bread_cat'] as $k => $v) {
        $item['block_cat_bread_crumb'] .= quick_tpl($tpl_block['cat_bread_crumb'], $v);
    }

    // multi tier
    $item['multier_list'] = '';
    if ($multier) {
        foreach ($pq as $k => $v) {
            $foo = array('min' => $k, 'price' => num_format($v, 0, true));
            $item['multier_list'] .= quick_tpl($tpl_section['multier_list'], $foo);
        }
    }

    // sub products
    $item['block_sp_list'] = '';
    $sub = unserialize($item['sub_product']);
    $i = 0;
    if ($sub) {
        foreach ($sub as $group) {
            $i++;
            $row = $subpr = array();
            $foo = explode(',', $group['member']);
            if ($group['member']) {
                foreach ($foo as $k => $v) {
                    $mem = sql_qquery("SELECT idx, title, stock, price FROM ".$db_prefix."products WHERE idx='$v' LIMIT 1");
                    if ($mem['stock']) {
                        $subpr[$mem['idx']] = $mem['title'].' +'.num_format($mem['price'], 0, 1);
                    }
                }
            }
            $row['idx'] = $i;
            $row['group_name'] = $group['title'];
            $row['group_members'] = $group['member'];
            $row['sp_select'] = create_select_form('item_id[]', $subpr, false, '&nbsp;');
            if ($subpr) {
                $item['block_sp_list'] .= quick_tpl($tpl_block['sp_list'], $row);
            }
        }
    }

    // custom fields
    $item['cf_list'] = '';
    $cf_arr = sql_qquery("SELECT * FROM ".$db_prefix."product_cf_value WHERE item_id='$item_id' LIMIT 1");
    $cf = get_custom_field($cf_arr);

    if ($cf) {
        foreach ($cf as $k => $v) {
            // CF pre-process goes here
            // Place your custom CF pre-processor here, see below explanation
            // See also: /module/ke_core/window.php, /listing_search.php & /detail.php

            // cf standard output, for custom output see below
            $v['class'] = 'cf_tr_cell';
            if ($v['cf_type'] == 'div') {
                $item['cf_list'] .= quick_tpl($tpl_section['cf_list_div'], $v)."\n";
            } else {
                $item['cf_list'] .= quick_tpl($tpl_section['cf_list'], $v)."\n";
            }

            /* =============================================================================================
               Custom Design & Pre-Processor For Custom Fields
               ---------------------------------------------------------------------------------------------
               You can create a custom design for any CF, first use:
                    print_r ($v);

               You will see that cf structure consists of: cf_idx, cf_title, cf_value, cf_type & cf_raw

               Let say you have: short_text cf, with idx = cf_99, to create a custom design:

               The easiest:
               { after: // CF pre-process goes here, place }
                    if ($v['cf_idx'] == 'cf_99') $v['cf_value'] = do_something_with_cf ($v['cf_raw']);

               Advanced example:
               { after: // CF pre-process goes here, place }
                    if ($v['cf_idx'] == 'cf_99')
                        $item['my_cf_design'] = do_something_with_cf ($v['cf_raw']);
                    else
                        $item['block_cf_list'] .= quick_tpl ($tpl_block['cf_list'], $v);

               { in detail.tpl }
                    <p>Calling Name: {$my_cf_design}</p>
               =============================================================================================
            */
        }
    } else {
        $custom_field = false;
    }

    // wish?
    $current_f = array();
    if (!empty($current_user_info['user_wish'])) {
        $current_f = explode(',', $current_user_info['user_wish']);
    }
    if (!in_array($item_id, $current_f)) {
        $add_wish = true;
    }

    // save tpl to cache
    $tpl = load_tpl('user', 'detail.tpl', false, true);
    $item['tpl'] = $tpl;
    qcache_update('detail_'.$item_id, serialize($item));
    $tpl = load_tpl('var', $tpl);
} else {
    $foo = unserialize($content);
    unset($foo['stock_status']);
    $item = array_merge($foo, $item);

    // some template vars can't be cached
    // - wish?
    $current_f = array();
    if (!empty($current_user_info['user_wish'])) {
        $current_f = explode(',', $current_user_info['user_wish']);
    }
    if (!in_array($item_id, $current_f)) {
        $add_wish = true;
    }

    $tpl = load_tpl('var', $item['tpl']);
}

// update stat_hits
sql_query("UPDATE ".$db_prefix."products SET stat_hits=stat_hits+1, stat_last_hit='$sql_now' WHERE idx = '$item_id' LIMIT 1");

// flush
$item['l_share_title'] = sprintf($lang['l_share_title'], $item['title']);
$item['min_qty'] = $min_buy;
$item['current_url'] = $config['site_url'].'/'.($config['enable_adp'] ? $item['permalink'] : 'detail.php?item_id='.$item_id);

$txt = array_merge($txt, $item);
$txt['main_body'] = quick_tpl(load_tpl('detail.tpl'), $txt);
$wai = strip_tags($item['cat_structure']);
generate_html_header("$wai $config[cat_separator] $item[title]", strip_tags($item['short_details']), $item['keywords']);

if ($current_admin_level) {
    $txt['main_body'] = sprintf($lang['edit_product_in_acp'], $item_id).$txt['main_body'];
}
flush_tpl();
