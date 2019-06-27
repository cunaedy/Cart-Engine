<?php
// search item by cf from table cf_value
// $query: string to search (for varchar), other cf uses get_param ($cf_key)
// return: list of item_id with matched cf
function filter_by_cf($query = '', $cat_id)
{
    global $db_prefix, $ce_cache;
    $sql = $sql_query = array();

    $k = 0;
    foreach ($ce_cache['cf_define'] as $row) {
        $key = 'cf_'.$row['idx'];
        $val = get_param($key);
        $k++;

        switch ($row['cf_type']) {
            case 'varchar':
            case 'textarea':
                if (!empty($val) && $row['is_searchable']) {
                    $sql[$k] = "(t2.$key='$val')";
                }
            break;

            // for rating we need to convert, eg: 2 = 2.00 ~ 2.90
            case 'rating':
                $foo = rating_sql('t2.'.$key, $val);
                if ($foo) {
                    $sql[$k] = $foo;
                }
            break;

            case 'select':
                $val = verify_selected($val, $ce_cache['cf_define'][$key]['cf_option']);
                if ($val) {
                    $sql[$k] = "(t2.$key='$val')";
                }
            break;

            case 'multi':
                if (!empty(get_param($key))) {
                    $selected = array(get_param($key));
                } else {
                    $selected = checkbox_param($key, 'get', true);
                }

                if (!empty($selected)) {
                    $opts = $dir_info[$dir_id]['cf_define'][$key]['cf_option'];
                    $selected = verify_selected($selected, $opts);
                    if ($selected) {
                        $daa = array();
                        foreach ($selected as $k => $v) {
                            $daa[] = "(t2.$key LIKE '%\r\n$v\r\n%')";
                        }
                        $sql[$k] = '('.implode(' AND ', $daa).')';
                    }
                }
            break;
        }
    }

    // $sql = sql for all CF types except string & text
    // $sql_query = sql only for string & text CF
    if (!empty($sql) || !empty($sql_query)) {
        return array('sql' => implode(' AND ', $sql), 'sql_query' => implode(' OR ', $sql_query));
    } else {
        return false;
    }
}

require_once './includes/user_init.php';

// get parameters
if ($isPermalink) {
    $_GET['cat_id'] = $original_idx;
    $_GET['cmd'] = 'list';
}
$cmd = get_param('cmd', 'list');
$query = get_param('query');
$cat_id = get_param('cat_id', 0);
$distro_id = get_param('distro_id');
$price = get_param('price');
$sort = get_param('sort', 'ta');
$cfonly = get_param('cfonly');
$mode = get_param('mode', 'grid');
$showall = get_param('showall');
$p = get_param('p', 1);
$ajax = get_param('ajax');
//print_r ($_GET);
// filter non UTF-8 alnum
$query = preg_replace('/[^\p{L}\p{N}\s]/u', '', strtolower($query));

// force listview || gridview && listmode || searchmode
$query_url = html_unentities(clean_get_query(array('p', 'AXSRF_token', 'mod_id', 'ajax', 'cmd'), false));
if ($cmd != 'search') {
    $cmd = 'list';
}
if (!empty($query)) {
    $cmd = 'search';
}
if (empty($query) && empty($cat_id)) {
    $cmd = 'search';
}

// init when mode is list
if ($cmd == 'list') {
    $tpl_mode = 'list';
    $cat_inf = sql_qquery("SELECT * FROM ".$db_prefix."product_cat WHERE idx='$cat_id' LIMIT 1");
    if (empty($cat_inf)) {
        msg_die($lang['msg']['cat_error']);
    }
    if ($cat_inf['cat_page']) {
        $_GET = array('pid' => $cat_inf['cat_page']);
        $isPermalink = false;
        require './page.php';
        die;
    }

    // default view
    $ds = 't';
    $do = 'a';
    $dv = 'grid';
} else {
    $ds = 'x';
    $do = 'd';
    $dv = '';
}

// determine view mode
if (empty($mode)) {
    $mode = $dv;
}
if ($mode != 'list') {
    $mode = 'grid';
}

// load tpl
$featured_listing = false;
$tpl = load_tpl('shop_search_'.$mode.'.tpl');

// 1.0 search by cats
$sql = array();
$list_only = true;

// 1.1 by category
if ($cat_id) {
    if (empty($cat_inf)) {
        $cat_inf = sql_qquery("SELECT * FROM ".$db_prefix."product_cat WHERE idx='$cat_id' LIMIT 1");
    }
    $sql[] = "((cat_id = '$cat_id') OR (add_category LIKE '%,$cat_id,%'))";
}

// 1.2 filter by visibility
$sql[] = "(is_invisible = '0')";

// 1.3 display out of stock?
if (!$showall) {
    $sql[] = '(stock > 0)';
} else {
    $search_url .= "&amp;showall=1";
}

// 1.4 sql to limit by price, $price should be in: "from;to" format, eg: "10;1000"
if ($price) {
    $max = $ce_cache['cfg']['max_price'];

    $foo = explode(';', $price);
    $price_from = empty($foo[0]) ? 0 : $foo[0];
    $price_to = empty($foo[1]) ? 0 : $foo[1];
    if ($price_from <= 0) {
        $price_from = 0;
    }
    if ($price_to > $max) {
        $price_to = $max;
    }
    $sql[] = "(price >= '$price_from' AND price <= '$price_to')";
}

// 1.5 by brands
if ($distro_id) {
    $sql[] = "distro='$distro_id'";
}

// 2.0 search: get custom field filtering result
$cf_sql = filter_by_cf($query, $cat_id);
if (!empty($cf_sql['sql'])) {
    $list_only = false;
    $sql[] = '('.$cf_sql['sql'].')';
}

// 2.1 search by query or not
$item = array();
if ($query) {
    $list_only = false;
    $w = array();
    if (!$cfonly) {
        $w[] = create_where('smart_search', $query);
    }
    if (!empty($cf_sql['sql_query'])) {
        $w[] = $cf_sql['sql_query'];
    }
    $sql[] = '('.implode(' OR ', $w).')';
}

// 3.0 determine sorting method
if (!$sort) {
    $sort = $ds.$do;
}
$s = substr($sort, 0, 1);
$o = substr($sort, 1, 1);

$sortby = $sortorder = array();
$sortby['t'] = 'title';
$sortby['d'] = 'list_date';
$sortby['p'] = 'price';
$sortorder['a'] = 'asc';
$sortorder['d'] = 'desc';

if (empty($s) or !array_key_exists($s, $sortby)) {
    $s = $ds;
}
if (empty($o) or !array_key_exists($o, $sortorder)) {
    $o = $do;
}

// 4.0 search: combine search by cat_id (or title etc) + cf search + other filters (price, etc)
$txt['block_search_item'] = '';
$ssql = implode(' AND ', $sql);

// 5.0 finally, display the result!
$i = 0;
unset($_GET['ajax']);
if ($isPermalink) {
    unset($_GET['mod_id'], $_GET['cmd'], $_GET['cat_id']);
}

$foo = sql_multipage($db_prefix.'products AS t1 LEFT JOIN '.$db_prefix.'product_cf_value AS t2 ON (t1.idx=t2.item_id)', '*, t1.idx AS item_id', "$ssql", "$sortby[$s] $sortorder[$o]", $p);
foreach ($foo as $row) {
    $i++;
    process_product_info($row);

    // custom fields
    $cf = get_custom_field($row, false);
    $row['cf_list'] = '';
    if ($cf) {
        foreach ($cf as $k => $v) {
            // CF pre-process goes here
            // Place your custom CF pre-processor here, see /detail.php for explanation
            // See also: /module/ke_core/window.php, /listing_search.php & /detail.php

            // cf standard output, for custom output see below
            $row['cf_list'] .= quick_tpl($tpl_section['cf_list'], $v);
        }
    }

    $txt['block_search_item'] .= quick_tpl($tpl_block['search_item'], $row);
}

if (!$i) {
    $no_search_result = true;
} else {
    $no_search_result = false;
}

// display category list on list mode (aka not search mode)
if ($cmd == 'list') {
    $tpl_mode = 'list';
    $txt['cat_name'] = $cat_name = $cat_inf['cat_name'];
    $txt['cat_details'] = $cat_details = $cat_inf['cat_details'];
    $txt['cat_keywords'] = $cat_keywords = $cat_inf['cat_keywords'];
    $txt['block_cat_list'] = '';
    if (!empty($cat_inf['cat_image'])) {
        $txt['cat_image'] = "<img src=\"$config[site_url]/public/image/$cat_inf[cat_image]\" alt=\"image\" />";
    } else {
        $txt['cat_image'] = $cat_inf['cat_name'];
    }

    // cat list
    $txt['block_cat_list'] = '';
    $foo = create_cat_list($cat_id);
    foreach ($foo as $val) {
        $txt['block_cat_list'] .= quick_tpl($tpl_block['cat_list'], $val);
    }

    // bread crumb
    $txt['block_cat_bread_crumb'] = '';
    $foo = explode(',', $ce_cache['cat_structure_id'][$cat_id]);
    foreach ($foo as $v) {
        $bc = array();
        $bc['bc_link'] = $ce_cache['cat_url'][$v];
        $bc['bc_title'] = $ce_cache['cat_name_def'][$v];
        if ($v != $cat_id) {
            $txt['block_cat_bread_crumb'] .= quick_tpl($tpl_block['cat_bread_crumb'], $bc);
        }
    }

    // show feat listing on first page && when list only (no filtering, search, etc)
    if ((!empty($cat_inf['cat_featured'])) && ($list_only) && ($p == 1)) {
        $featured_listing = true;
    }
    generate_html_header("$config[site_name] $config[cat_separator] $cat_name", $cat_details, $cat_keywords);
} else {
    $tpl_mode = 'search';
    generate_html_header("$config[site_name] $config[cat_separator] ".ucwords(strtolower($query)));
}

// output
$txt['cmd'] = $cmd;
$txt['cat_id'] = $cat_id;
$txt['distro_id'] = $distro_id;
$txt['query'] = stripslashes($query);
$txt['query_url'] = $query_url;
$txt['search_sort'] = create_select_form('sort', $search_sort, $sort);
$txt['mode_select'] = create_select_form('mode', $list_mode, $mode);
$txt['showall_status'] = $showall ? 'checked' : '';
$txt['main_body'] = quick_tpl(load_tpl('shop_search_'.$mode.'.tpl'), $txt);

// add acp shortcut
if (($current_admin_level) && empty($query) && !empty($cat_inf)) {
    $txt['main_body'] = sprintf($lang['edit_product_cat_in_acp'], $cat_id).$txt['main_body'];
}

if ($ajax) {
    flush_tpl('ajax');
} else {
    flush_tpl('body_list.tpl');
}
