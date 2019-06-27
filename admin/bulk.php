<?php
require "./../includes/admin_init.php";
admin_check(4);

$cmd = post_param('cmd');
$title = post_param('title');
if (empty($cmd)) {
    $cmd = get_param('cmd');
}

switch ($cmd) {
    case 'help':
        $tpl = load_tpl('adm', 'bulk_guide.tpl');
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;

    case 'cat_list':
        $tpl_mode = 'cat_list';
        $tpl = load_tpl('adm', 'bulk.tpl');
        $txt['block_list'] = '';
        $i = 0;

        foreach ($ce_cache['cat_name_def'] as $k => $v) {
            $row = array();
            $row['idx'] = $k;
            $row['cat_name'] = $v;
            $row['cat_structure'] = $ce_cache['cat_structure'][$k];
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
            $i++;
        }
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;


    case 'prod_list':
        $tpl_mode = 'prod_list';
        $tpl = load_tpl('adm', 'bulk.tpl');
        $txt['block_list'] = '';
        $i = 0;

        $res = sql_query("SELECT cat_id, idx, title FROM ".$db_prefix."products ORDER BY cat_id, title");
        while ($row = sql_fetch_array($res)) {
            $row['cat_name'] = $ce_cache['cat_name_def'][$row['cat_id']];
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
            $i++;
        }
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;


    case 'distro_list':
        $tpl_mode = 'distro_list';
        $tpl = load_tpl('adm', 'bulk.tpl');
        $txt['block_list'] = '';

        // get distro list
        foreach ($ce_cache['distro'] as $k=>$r) {
            // products
            $row = array('idx' => $k, 'distro_name' => $r);
            $row['product'] = '';
            $res2 = sql_query("SELECT idx, title FROM ".$db_prefix."products WHERE distro='$k'");
            while ($row2 = sql_fetch_array($res2)) {
                $row['product'] .= $row2['title'].', ';
            }
            $row['product'] = substr($row['product'], 0, -2);

            // list
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        // no distro
        $row = array('idx' => '(none)', 'distro_name' => '(none)');

        // products
        $row['product'] = '';
        $res2 = sql_query("SELECT idx, title FROM ".$db_prefix."products WHERE distro='0'");
        while ($row2 = sql_fetch_array($res2)) {
            $row['product'] .= $row2['title'].', ';
        }
        $row['product'] = substr($row['product'], 0, -2);
        $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);

        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;


    case 'tax_list':
        $tpl_mode = 'tax_list';
        $tpl = load_tpl('adm', 'bulk.tpl');
        $txt['block_list'] = '';

        // get distro list
        foreach ($ce_cache['tax_class'] as $k=>$r) {
            $row = array('idx' => $k, 'tax_name' => $r);

            // rate
            $foo = unserialize($ce_cache['tax_rate'][$k]);
            $row['tax_rate'] = "Local: $foo[city]%, State: $foo[state]%, National: $foo[nation]%, International: $foo[world]%";
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;



    case 'do':
        if (empty($_FILES['csv_file']['tmp_name'])) {
            admin_die('echo', 'Please supply a CSV file!');
        }
        if (!is_uploaded_file($_FILES['csv_file']['tmp_name'])) {
            admin_die('echo', 'Please supply a CSV file!');
        }
        $input = $_FILES['csv_file']['tmp_name'];

        // open file
        $handle = fopen($input, "r");

        // verify that each line not longer than 4096 characters
        if ($handle) {
            while (!feof($handle)) {
                $buffer = fgets($handle);
                if (strlen($buffer > 4096)) {
                    admin_die('echo', 'Each line must be less than 4,096 characters!');
                }
            }
            fclose($handle);
        } else {
            admin_die('echo', 'File not found!');
        }

        // reopen
        $handle = fopen($input, "r");
        if ($handle) {
            $row = 0;
            while (($data = fgetcsv($handle, 4096, ",")) !== false) {
                echo '. ';
                $ok = true;

                $row++;
                $num = count($data);
                $sql = '';

                // fix some stuff
                if (!empty($data[1])) {
                    $foo = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE sku='$data[1]' LIMIT 1");
                    if ($foo) {
                        $data[1] = '';
                    }
                }
                if (empty($data[2])) {
                    $ok = false;
                }	// title
                if ($title && ($row == 1)) {
                    $ok = false;
                }	// first line is title
                foreach ($data as $val) {
                    $val = addslashes($val);
                }

                // create sql
                $sql = "cat_id='$data[0]', sku='$data[1]', title='$data[2]', price='$data[3]', price_msrp='$data[4]', details='$data[5]', weight='$data[6]',
				list_date='$sql_today', distro='$data[7]', stock='$data[8]', tax_class='$data[9]'";

                if ($ok) {
                    sql_query("INSERT INTO ".$db_prefix."products SET $sql");
                    $item_id = mysqli_insert_id($dbh);
                    $permalink = generate_permalink($data[2], 'detail.php', $item_id, '', '', true, true);
                    sql_query("INSERT INTO ".$db_prefix."product_cf_value SET item_id='$item_id'");
                    sql_query("UPDATE ".$db_prefix."products SET permalink='$permalink' WHERE idx='$item_id' LIMIT 1");
                } else {
                    if (!$title || ($row > 1)) {
                        echo "<br />Row #$row contains invalid information! Please ignore if it's a title row.<br />";
                    }
                }
            }
            fclose($handle);
        } else {
            admin_die('File not found!');
        }

        // done
        admin_die($lang['msg']['cache']);
    break;


    default:
        $tpl_mode = 'default';
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'bulk.tpl'), $txt);
        flush_tpl('adm');
    break;
}
