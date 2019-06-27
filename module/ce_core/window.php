<?php
$output = '';
global $tpl_mode, $ce_cache;
if (empty($mod_ini['mode'])) {
    $mod_ini['mode'] = '';
}

switch ($mod_ini['mode']) {
    case 'product_list':
        // init vars
        $items = empty($mod_ini['items']) ? 'random' : $mod_ini['items'];
        $out_of_stock = empty($mod_ini['out_of_stock']) ? false : $mod_ini['out_of_stock'];
        $limit = empty($mod_ini['limit']) ? 10 : $mod_ini['limit'];
        $display = empty($mod_ini['display']) ? 'grid' : $mod_ini['display'];
        $tag = empty($mod_ini['tag']) ? false : $mod_ini['tag'];
        $random = empty($mod_ini['random']) ? false : $mod_ini['random'];
        $csswrapper = empty($mod_ini['csswrapper']) ? false : $mod_ini['csswrapper'];
        $div_id = empty($mod_ini['div_id']) ? false : $mod_ini['div_id'];

        // overwrite display mode
        if (!empty($mod_ini['display_overwrite'])) {
            $display = $mod_ini['display_overwrite'];
        }
        if (!empty($mod_ini['csswrapper_grid']) && $display == 'grid') {
            $csswrapper = $mod_ini['csswrapper_grid'];
        }
        if (!empty($mod_ini['csswrapper_list']) && $display == 'list') {
            $csswrapper = $mod_ini['csswrapper_list'];
        }

        // init sql
        $sql_where = $sql_order = '';
        if ($items == 'random') {
            $sql_order = "RAND()";
        } elseif ($items == 'best') {
            $sql_order = "stat_purchased DESC";
        } elseif ($items == 'newest') {
            $sql_order = "list_date DESC";
        } elseif ($items == 'see_also') {
            if ($isPermalink) {
                $_GET['item_id'] = $original_idx;
            }
            $item_id = get_param('item_id');
            $foo = sql_qquery("SELECT see_also FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
            if (empty($foo['see_also'])) {
                if ($random) {
                    $sql_order = "RAND()";
                } else {
                    $sql_where = '1=2';
                }
            } else {
                $sql_where = "t1.idx IN ($foo[see_also])";
            }
        } elseif ($items == 'site_featured') {
            if (empty($config['cart']['featured_product'])) {
                if ($random) {
                    $sql_order = "RAND()";
                } else {
                    $sql_where = '1=2';
                }
            } else {
                $sql_where = "t1.idx IN (".$config['cart']['featured_product'].")";
                $limit = substr_count($config['cart']['featured_product'], ',') + 1;
            }
        } elseif ($items == 'cat_featured') {
            if ($isPermalink) {
                $_GET['cat_id'] = $original_idx;
            }
            $cat_id = get_param('cat_id');
            $foo = sql_qquery("SELECT cat_featured FROM ".$db_prefix."product_cat WHERE idx='$cat_id' LIMIT 1");
            if (empty($foo['cat_featured'])) {
                if ($random) {
                    $sql_order = "RAND()";
                } else {
                    $sql_where = '1=2';
                }
            } else {
                $sql_where = "t1.idx IN ($foo[cat_featured])";
                $limit = substr_count($foo['cat_featured'], ',') + 1;
            }
        } elseif ($items == 'history') {
            $history = empty($_COOKIE[$db_prefix.'history']) ? array() : $_COOKIE[$db_prefix.'history'];

            $i = 0;
            $foo = array();
            $count = count($history);
            foreach ($history as $k => $v) {
                if (is_numeric($v)) {
                    $foo[] = $v;
                }
            }
            $history = implode(',', array_slice(array_unique($foo), -1 * $limit, $limit));
            if (empty($history)) {
                $history = 0;
            }

            $sql_where = "t1.idx IN ($history)";
            $sql_order = "FIELD(t1.idx,$history) DESC";

            // update cookies
            $item_id = get_param('item_id');
            if ($item_id && !in_array($item_id, $foo) && is_numeric($item_id)) {
                setcookie($db_prefix."history[$count]", $item_id, 0, '/', cookie_domain());
            }
        } elseif ($items == 'wish') {
            if ($isLogin) {
                global $current_user_info;
                $fave = $current_user_info['user_wish'];
                if (!$fave) {
                    $fave = 0;
                }
                $sql_where = "t1.idx IN ($fave)";
            } else {
                $output = '<!-- user may not login -->';
                return false;
            }
        } else {
            $sql_where = "t1.idx IN ($items)";
        }

        // load tpl
        $tpl = load_tpl('mod', 'module_ce_core_list.tpl');
        if ($display == 'grid') {
            $tpl = $tpl_section['list_gridbox'];
        } elseif ($display == 'list') {
            $tpl = $tpl_section['list_listbox'];
        } else {
            $tpl = $tpl_section['list_list'];
        }

        // execute sql
        $sql = "SELECT *, t1.idx AS item_id FROM ".$db_prefix."products AS t1 LEFT JOIN ".$db_prefix."product_cf_value AS t2 ON (t1.idx=t2.item_id)";

        $where = array();
        $where[] = "(is_invisible != '1')";
        if (!$out_of_stock) {
            $where[] = '(stock > 0)';
        }
        if ($sql_where) {
            $where[] = "($sql_where)";
        }
        $sql = $sql.' WHERE '.implode(' AND ', $where);
        if ($sql_order) {
            $sql .= " ORDER BY $sql_order";
        }
        $sql .= " LIMIT $limit";

        $res = sql_query($sql);
        $i = 0;
        while ($row = sql_fetch_array($res)) {
            $i++;
            $row = process_product_info($row);
            $row['csswrapper'] = $csswrapper;
            $row['tag'] = $tag;
            $row['pprice'] = strip_tags($row['price']);
            if (!$row['image_raw']) {
                $row['image_feat'] = "<img src=\"$config[site_url]/$config[skin]/images/nothumb_list.png\" alt=\"$row[title]\" title=\"$row[title] &mdash; $row[pprice]\" />";
            } else {
                $row['image_feat'] = "<img src=\"$row[image_raw]\" alt=\"$row[title]\" title=\"$row[title] &mdash; $row[pprice]\" />";
            }

            // custom fields
            $row['cf_list'] = '';
            $cf = get_custom_field($row, false);

            if ($cf) {
                foreach ($cf as $k => $v) {
                    // CF pre-process goes here
                    // Place your custom CF pre-processor here, see /detail.php for explanation
                    // See also: /module/ce_core/window.php, /shop_search.php & /detail.php

                    // cf standard output, for custom output see below
                    $row['cf_list'] .= quick_tpl($tpl_section['cf_list'], $v);
                }
            }
            $output .= quick_tpl($tpl, $row);
        }

        if (!$i) {
            $output = '<!-- no items to display -->';
        } else {
            if ($div_id) {
                $mod_ini_str = str_replace('%', '%%', safe_send($mod_raw));
                $js = "$('#$div_id').load('$config[site_url]/task.php?mod=ce_core&amp;mod_ini=$mod_ini_str&amp;mode=%s')";
                $nav = "<div style=\"text-align:right; width:100%\">\n";
                if ($display == 'list') {
                    $nav .= "<a href=\"javascript:void(0)\" onclick=\"".sprintf($js, 'grid')."\"><span class=\"glyphicon glyphicon-th\"></span></a>\n";
                } else {
                    $nav .= "<span class=\"glyphicon glyphicon-th\"></span>\n";
                }

                if ($display == 'grid') {
                    $nav .= "<a href=\"javascript:void(0)\" onclick=\"".sprintf($js, 'list')."\"><span class=\"glyphicon glyphicon-th-list\"></span></a>\n";
                } else {
                    $nav .= "<span class=\"glyphicon glyphicon-th-list\"></span>";
                }

                $nav .= "</div>";

                $output = $nav.$output;
            }
        }
    break;
}
