<?php
require './../includes/admin_init.php';
admin_check(1);

$cmd = get_param('cmd');
$item_id = get_param('item_id');
if (empty($item_id)) {
    popup_die('Invalid item_id! Please try again!');
}

$tpl = load_tpl('adm', 'product_sub.tpl');

switch ($cmd) {
    case 'del':
        $idx = get_param('idx');
        $old = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
        $sub = unserialize($old['sub_product']);
        unset($sub[$idx]);

        $new = addslashes(serialize($sub));
        sql_query("UPDATE ".$db_prefix."products SET sub_product='$new' WHERE idx='$item_id' LIMIT 1");
    // no break!

    case 'save':
        // save members
        $result = array();
        if (empty($item_id)) {
            popup_die('Invalid item_id! Please try again!');
        }
        foreach ($_GET as $k => $v) {
            // for group
            if (substr($k, 0, 6) == 'group_') {
                $kk = substr($k, 6);
                $va = get_param($k);
                $mm = get_param('members_'.$kk);
                if (!empty($va)) {
                    $result[] = array('title' => $va, 'member' => $mm);
                }
            }

            // new group
            if ($k == 'new_group') {
                $va = get_param($k);
                if (!empty($va)) {
                    $result[] = array('title' => $va, 'member' => '');
                }
            }
        }
        $res = addslashes(serialize($result));
        if ($cmd == 'save') {
            sql_query("UPDATE ".$db_prefix."products SET sub_product='$res' WHERE idx='$item_id' LIMIT 1");
            echo $tpl_section['sp_saved'];
        }

        qcache_clear();
    // no break!

    default:
        // sub list
        $ss = 0;
        $txt['block_list'] = $txt['block_list_js'] = '';
        $row = sql_qquery("SELECT sub_product FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
        $sub = unserialize($row['sub_product']);
        if (!$sub) {
            $sub = array();
        }
        foreach ($sub as $key => $group) {
            $ss++;
            $row = array();
            $foo = explode(',', $group['member']);
            $mm = array();
            $i = 0;
            if ($group['member']) {
                foreach ($foo as $k => $v) {
                    $i++;
                    $mem = sql_qquery("SELECT idx, title FROM ".$db_prefix."products WHERE idx='$v' LIMIT 1");
                    $mm[] = array('id' => $mem['idx'], 'name' => $mem['title']);
                }
            }
            $row['idx'] = $key;
            $row['group_name'] = $group['title'];
            $row['group_members'] = $group['member'];
            $row['preset'] = $i ? json_encode($mm) : 'null';
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
            $txt['block_list_js'] .= quick_tpl($tpl_block['list_js'], $row);
        }


        // output
        $txt['item_id'] = $item_id;
        echo quick_tpl($tpl, $txt);
        // $txt['main_body'] = quick_tpl ($tpl, $txt); flush_tpl ('adm');
    break;
}
