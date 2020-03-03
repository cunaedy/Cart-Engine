<?php
// create custom SQL
// for: admin/product_process.php
// link_id = 0 => add; > 0 => edit
function do_custom_sql($item_id)
{
    global $db_prefix, $config, $ce_cache;
    $output = $err = array();

    $ffolder = './../public/file';
    $ifolder = './../public/image';
    $tfolder = './../public/thumb';
    $pfolder = './../public/private';

    $output = array();
    $old_val = sql_qquery("SELECT * FROM ".$db_prefix."product_cf_value WHERE item_id='$item_id' LIMIT 1");
    if (!$old_val) {
        sql_query("INSERT INTO ".$db_prefix."product_cf_value SET item_id='$item_id'");
    }

    foreach ($ce_cache['cf_define'] as $row) {
        $key = 'cf_'.$row['idx'];
        $old = $old_val[$key];
        $val = post_param($key);

        switch ($row['cf_type']) {
            case 'varchar':
            case 'textarea':
            case 'rating':
            case 'select':
                // actually do NOTHING!
                // but, register all your custom "custom field type" here
            break;

            case 'multi':
                $faa = checkbox_param($key, 'post', true);
                $val = implode("\r\n", $faa);
            break;

            case 'wysiwyg':
                $val = post_param($key, '', 'rte');
            break;

            case 'img':
                if (!empty($_FILES[$key]['name'])  && (!$config['demo_mode'])) {
                    // upload
                    image_optimizer($_FILES[$key]['tmp_name'], "$ifolder/".$_FILES[$key]['name'], $config['optimizer']);
                    if (!empty($config['watermark_file'])) {
                        image_watermark("$ifolder/".$_FILES[$key]['name'], './../public/image/'.$config['watermark_file']);
                    }

                    // create thumb
                    image_optimizer($_FILES[$key]['tmp_name'], "$tfolder/".$_FILES[$key]['name'], $config['thumb_quality'], 'thumb');

                    unlink($_FILES[$key]['tmp_name']);
                    $val = $_FILES[$key]['name'];
                } else {
                    $val = $old;
                }
            break;

            case 'video':
                // unfortunately, we can not store 'cleaned' youtube/vimeo URL, as cleaned URL will be marked as invalid by the following checker
                if (preg_match("/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/", $val, $matches)) {
                    $video = true;
                } elseif (preg_match('~^(?:https?://)?(?:www[.])?(?:youtube[.]com/watch[?]v=|youtu[.]be/)([^&]{11})~x', $val, $matches)) {
                    $video = true;
                } else {
                    $video = false;
                }
                if (!$video) {
                    $val = '';
                }
            break;

            case 'file':
            case 'private':
                if (!empty($_FILES[$key]['name']) && (!$config['demo_mode'])) {
                    if ($row['cf_type'] == 'file') {
                        $s = upload_file($key, "$ffolder/".$_FILES[$key]['name'], true);
                    } else {
                        $s = upload_file($key, "$pfolder/".$_FILES[$key]['name'], true);
                    }
                    if ($s['success']) {
                        $val = $s[0]['filename'];
                    } else {
                        $val = $old;
                    }
                } else {
                    $val = $old;
                }
            break;

            default:
                msg_die("Unknown custom field type: $foo");
            break;
        }

        // add/edit cf val
        if (!empty($val)) {
            $output[] = "$key='$val'";
        } else {	// remove cf val
            $output[] = "$key=''";
        }
    }

    $sql = implode(', ', $output);
    if ($sql) {
        sql_query("UPDATE ".$db_prefix."product_cf_value SET $sql WHERE item_id='$item_id' LIMIT 1");
    }
}


require './../includes/admin_init.php';
admin_check(4);
AXSRF_check();

$owner = post_param('owner');
$cat_id = post_param('cat_id');
$item_id = post_param('item_id');
$sku = post_param('sku');
$title = post_param('title');
$price = post_param('price');
$price_msrp = post_param('price_msrp');
$details = post_param('details', '', 'rte');
$weight = post_param('weight');
$distro = post_param('distro');
$stock = post_param('stock');
$keywords = post_param('keywords');
$list_date = date_param('list_date', 'post');
$tax_class = post_param('tax_class');
$see_also = post_param('see_also');
$call_price = post_param('call_price');
$min_buy = post_param('min_buy');
$max_buy = post_param('max_buy');
$del_item = post_param('del_item');
$copy_item = post_param('copy_item');
$copy_cf = post_param('copy_cf');
$copy_switch = post_param('copy_switch');
$copy_img = post_param('copy_img');
$permalink = post_param('permalink');
$is_invisible = post_param('is_invisible');
$add_category = checkbox_param('add_category', 'post');

// multiple cats
if (!empty($add_category)) {
    $add_category = ','.$add_category.',';
}

// qty price
$ppp = array();
foreach ($_POST as $k => $v) {
    if (substr($k, 0, 10) == 'price_qty_') {
        $i = substr($k, 11);
        $qpq = post_param('price_qty_q'.$i);
        $qpp = post_param('price_qty_p'.$i);
        if (!empty($qpq)) {
            $ppp[$qpq] = $qpp;
        }
    }
}
ksort($ppp);
$price_qty = serialize($ppp);

// create sql
do_custom_sql($item_id);
if (!$item_id) {
    $mode = 'new';
} else {
    $mode = 'edit';
}
if ($del_item) {
    $mode = 'del_item';
}

// min max
if ($min_buy < 1) {
    $min_buy = 1;
}
if ($max_buy < 0) {
    $max_buy = 0;
}

// invisible?
if (!$is_invisible) {
    $is_invisible='0';
}

$sql = "sku = '$sku', cat_id = '$cat_id', add_category = '$add_category', title = '$title', price = '$price', price_msrp = '$price_msrp',
details = '$details', weight = '$weight', distro = '$distro', stock = '$stock', min_buy = '$min_buy', max_buy = '$max_buy', keywords = '$keywords',
see_also = '$see_also', tax_class = '$tax_class', list_date = '$list_date', is_call_for_price='$call_price', is_invisible='$is_invisible', price_qty='$price_qty'";

switch ($mode) {
    case 'new':
        sql_query("INSERT INTO ".$db_prefix."products SET $sql");
        $item_id = mysqli_insert_id($dbh);
    break;

    case 'edit':
        // get old vals
        $old = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx = '$item_id' LIMIT 1");

        // update
        sql_query("UPDATE ".$db_prefix."products SET $sql WHERE idx = '$item_id' LIMIT 1");
    break;


    case 'del_item':
        delete_item($item_id);
        admin_die('admin_ok', $config['site_url'].'/'.$config['admin_folder'].'/product_list.php');
    break;
}

// refresh smart search db
create_search_cache($item_id);

// permalink
if (($mode == 'new') || (($mode == 'edit') && ($old['permalink'] != $permalink)) || (empty($permalink))) {
    if (!empty($permalink)) {
        $permalink = generate_permalink($permalink, 'detail.php', $item_id, '', '', false, true);
    } else {
        $permalink = generate_permalink($title, 'detail.php', $item_id, '', '', true, true);
    }
    sql_query("UPDATE ".$db_prefix."products SET permalink='$permalink' WHERE idx='$item_id' LIMIT 1");
}

// digital product
if ($_FILES['digital_file']['name']) {
    $folder = './../public/private';
    $f = upload_file('digital_file', $folder);
    if (!$f['success']) {
        admin_die($lang['msg']['can_not_upload']);
    }
    sql_query("UPDATE ".$db_prefix."products SET digital_file='{$f[0]['filename']}' WHERE idx='$item_id' LIMIT 1");
}


// upload images
// -- default image
if ($_FILES['image']['name']) {
    // find folder
    $folder = './../public/products';
    $tolder = './../public/products_thumbs';

    // search lastest index file for image
    $ok = false;
    $i = 0;
    while (!$ok) {
        $i++;
        $fn = $item_id.'_'.$i;
        if (!file_exists("$folder/$fn.jpg")) {
            $ok = true;
        }
    }

    // create image
    $image_id = $item_id.'_'.$i;
    $target = "$folder/$image_id.jpg";

    // optimize image
    if ($config['optimizer']) {
        $img = getimagesize($_FILES['image']['tmp_name']);
        image_optimizer($_FILES['image']['tmp_name'], $target, $config['optimizer'], $img[0], $img[1]);
        if (!empty($config['watermark_file'])) {
            image_watermark($target, './../public/image/'.$config['watermark_file']);
        }
        if (!file_exists($target)) {
            admin_die($lang['msg']['can_not_upload']);
        }
        @chmod($target, 0644);
    } else {
        if (!$config['demo_mode']) {
            if (!@upload_file('image', $target)) {
                admin_die($lang['msg']['can_not_upload']);
            }
            if (!empty($config['watermark_file'])) {
                image_watermark($target, './../public/image/'.$config['watermark_file']);
            }
            @chmod($target, 0644);
        }
    }
    @unlink("$tolder/$image_id.jpg");
    @unlink("$tolder/small_$fn.jpg");
    make_thumb($image_id, 'detail', true);
    make_thumb($image_id, 'small', true);
}


// verify SKU
if (!empty($sku)) {
    $back = $config['site_url'].'/'.$config['admin_folder'].'/products.php?cmd=edit&item_id='.$item_id;
    $row = sql_qquery("SELECT idx, sku FROM ".$db_prefix."products WHERE sku='$sku' AND idx != '$item_id' LIMIT 1");
    if (!empty($row['idx'])) {
        sql_query("UPDATE ".$db_prefix."products SET sku='' WHERE idx='$item_id' LIMIT 1");
        admin_die('SKU has been used. Please try another. Product information has been saved.', $back);
    }
}


// copy?
if ($copy_item) {
    // copy product db
    sql_query("INSERT INTO ".$db_prefix."products SET $sql");
    $new_item = mysqli_insert_id($dbh);

    // fix several things
    sql_query("UPDATE ".$db_prefix."products SET sku='', permalink='' WHERE idx='$new_item' LIMIT 1");

    // sub product
    $spr = sql_query("SELECT * FROM ".$db_prefix."product_sub WHERE item_id='$item_id'");
    while ($spv = sql_fetch_array($spr)) {
        $spv['group_name'] = addslashes($spv['group_name']);
        sql_query("INSERT INTO ".$db_prefix."product_sub SET item_id='$new_item', group_name='$spv[group_name]', group_members='$spv[group_members]'");
    }

    // copy cf
    if ($copy_cf) {
        $cfcr = sql_query("SELECT * FROM ".$db_prefix."product_cf_value WHERE item_id='$item_id'");
        while ($cfcv = sql_fetch_array($cfcr)) {
            $cfcv['cf_value'] = addslashes($cfcv['cf_value']);
            sql_query("INSERT INTO ".$db_prefix."product_cf_value SET item_id='$new_item', cf_id='$cfcv[cf_id]', cf_value='$cfcv[cf_value]'");
        }
    }

    // copy images?
    if ($copy_img) {
        // find folder
        $sfolder = '../public/products';
        $tfolder = '../public/products_thumbs';

        // search all files
        $ok = false;
        $i = 0;
        while (!$ok) {
            $i++;
            $fn =
            $sf = $sfolder.'/'.$item_id.'_'.$i.'.jpg';
            $st = $tfolder.'/'.$item_id.'_'.$i.'.jpg';
            $tf = $sfolder.'/'.$new_item.'_'.$i.'.jpg';
            $tt = $tfolder.'/'.$new_item.'_'.$i.'.jpg';
            if (file_exists($sf)) {
                copy($sf, $tf);
                copy($st, $tt);
                chmod($tf, 0644);
                chmod($tt, 0644);
            } else {
                $ok = true;
            }
        }
    }

    // redir?
    if ($copy_switch) {
        admin_die('Product has been copied succesfully. You are now editing <b>the copied</b> product.', $config['site_url'].'/'.$config['admin_folder'].'/product.php?cmd=edit&item_id='.$new_item);
    }
}

qcache_clear();

if ($copy_item) {
    admin_die('Product has been copied succesfully. You are now editing <b>the original</b> product.', $config['site_url'].'/'.$config['admin_folder'].'/product.php?cmd=edit&item_id='.$item_id);
} else {
    admin_die('admin_ok', $config['site_url'].'/'.$config['admin_folder'].'/product.php?cmd=edit&item_id='.$item_id);
}
