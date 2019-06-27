<?php
// called after add/edit/del anything
function post_func($cmd, $id, $savenew = false)
{
    global $db_prefix, $config, $cat_structure, $cat_structure_id, $cat_name_def, $dbh;

    // update menu_item
    $f = sql_qquery("SELECT * FROM ".$db_prefix."menu_set WHERE menu_id='product' LIMIT 1");
    $mid = $config['cart']['menu_cat_idx'];

    // if new cat -> add to menu
    if (($cmd == 'new') || ($cmd == 'update')) {
        $row = sql_qquery("SELECT * FROM ".$db_prefix."product_cat WHERE idx='$id' LIMIT 1");
        $row['cat_name'] = addslashes($row['cat_name']);
        $url = '__SITE__/shop_search.php?cmd=list&amp;cat_id='.$row['idx'];
        $permalink = '__SITE__/'.$row['permalink'];

        // get parent
        if (!empty($row['parent_id'])) {
            $p = sql_qquery("SELECT * FROM ".$db_prefix."product_cat WHERE idx='$row[parent_id]' LIMIT 1");
            $parent_mid = $p['menu_mid'];
        } else {
            $parent_mid = 0;
        }

        if ($cmd == 'new') {
            sql_query("INSERT INTO ".$db_prefix."menu_item SET menu_id='$mid', menu_parent='$parent_mid', menu_item='$row[cat_name]', menu_url='$url', menu_permalink='$permalink', menu_order='999999'");
            $mmid = mysqli_insert_id($dbh);
            sql_query("UPDATE ".$db_prefix."product_cat SET menu_mid='$mmid' WHERE idx='$id' LIMIT 1");
        } else {
            sql_query("UPDATE ".$db_prefix."menu_item SET menu_id='$mid', menu_parent='$parent_mid', menu_item='$row[cat_name]', menu_url='$url', menu_permalink='$permalink' WHERE idx='$row[menu_mid]' LIMIT 1");
        }

        if ($savenew) {
            $return_url = safe_send($config['site_url'].'/'.$config['admin_folder'].'/product_cat.php?qadmin_cmd=new');
        } else {
            $return_url = safe_send($config['site_url'].'/'.$config['admin_folder'].'/product_cat.php?id='.$id);
        }
        redir($config['site_url'].'/'.$config['admin_folder'].'/menu_man.php?cmd=reorder3&midx='.$mid.'&return_url='.$return_url);
    }

    // if delete
    if ($cmd == 'remove_item') {
        sql_query("UPDATE ".$db_prefix."products SET cat_id='999999' WHERE cat_id='$id'");
        sql_query("DELETE FROM ".$db_prefix."menu_item WHERE menu_id='$mid' AND menu_url LIKE '%cat_id=$id' LIMIT 1");
        redir($config['site_url'].'/'.$config['admin_folder'].'/menu_man.php?cmd=reorder3&midx='.$mid.'&return_url='.safe_send($config['site_url'].'/'.$config['admin_folder'].'/product_cat.php'));
    }

    if ($savenew) {
        redir($config['site_url'].'/'.$config['admin_folder'].'/product_cat.php?qadmin_cmd=new');
    } else {
        redir($config['site_url'].'/'.$config['admin_folder'].'/product_cat.php?id='.$id);
    }
    die;
}

// important files
require "./../includes/admin_init.php";
admin_check(4);

$id = get_param('id');
if (empty($id)) {
    $id = get_param('primary_val');
}
$cmd = get_param('cmd');
$q = get_param('query');

if ($id) {
    $row = sql_qquery("SELECT * FROM ".$db_prefix."product_cat WHERE idx='$id' LIMIT 1");
    // get featured product
    $foo = explode(',', $row['cat_featured']);
    $mm = array();
    $i = 0;
    if ($row['cat_featured']) {
        foreach ($foo as $k => $v) {
            $i++;
            $mem = sql_qquery("SELECT idx, title FROM ".$db_prefix."products WHERE idx='$v' LIMIT 1");
            $mm[] = array('id' => $mem['idx'], 'name' => $mem['title']);
        }
    }
    $cat_featured_preset = $i ? json_encode($mm) : 'null';

    // get page
    if ($row['cat_page']) {
        $foo = sql_qquery("SELECT page_title FROM ".$db_prefix."page WHERE page_id='$row[cat_page]' LIMIT 1");
        $page_preset = json_encode(array(array('id' => $row['cat_page'], 'name' => $foo[0])));
    } else {
        $page_preset = 'null';
    }
} else {
    $cat_featured_preset = $page_preset = 'null';
}

// idx :: int :: 10
$qadmin_def['idx']['title'] = 'ID';
$qadmin_def['idx']['field'] = 'idx';
$qadmin_def['idx']['type'] = 'echo';
$qadmin_def['idx']['size'] = 10;
$qadmin_def['idx']['value'] = 'sql';

// parent_id :: int :: 10
$f = sql_qquery("SELECT * FROM ".$db_prefix."menu_set WHERE menu_id='product' LIMIT 1");
$mid = $f['idx'];

$qadmin_def['parent_id']['title'] = 'Parent';
$qadmin_def['parent_id']['field'] = 'parent_id';
$qadmin_def['parent_id']['type'] = 'select';
$qadmin_def['parent_id']['option'] = $ce_cache['cat_structure'];
$qadmin_def['parent_id']['value'] = 'sql';
$qadmin_def['parent_id']['suffix'] = "[ <a href=\"menu_man.php?cmd=design&amp;midx=$mid\">Reorder</a> ]";

// cat_name :: string :: 300
$qadmin_def['cat_name']['title'] = 'Category Name';
$qadmin_def['cat_name']['field'] = 'cat_name';
$qadmin_def['cat_name']['type'] = 'varchar';
$qadmin_def['cat_name']['size'] = 300;
$qadmin_def['cat_name']['value'] = 'sql';

// permalink :: string :: 255
$qadmin_def['permalink']['title'] = 'Permalink';
$qadmin_def['permalink']['field'] = 'permalink';
$qadmin_def['permalink']['type'] = 'permalink';
$qadmin_def['permalink']['size'] = 255;
$qadmin_def['permalink']['value'] = 'sql';

// cat_details :: blob :: 196605
$qadmin_def['cat_details']['title'] = 'Description';
$qadmin_def['cat_details']['field'] = 'cat_details';
$qadmin_def['cat_details']['type'] = 'wysiwyg';
$qadmin_def['cat_details']['size'] = '500,200';
$qadmin_def['cat_details']['value'] = 'sql';

// cat_image :: string :: 63
$qadmin_def['cat_image']['title'] = 'Image';
$qadmin_def['cat_image']['field'] = 'cat_image';
$qadmin_def['cat_image']['type'] = 'thumb';
$qadmin_def['cat_image']['value'] = 'sql';

// cat_keywords :: blob :: 765
$qadmin_def['cat_keywords']['title'] = 'Keywords';
$qadmin_def['cat_keywords']['field'] = 'cat_keywords';
$qadmin_def['cat_keywords']['type'] = 'varchar';
$qadmin_def['cat_keywords']['size'] = 255;
$qadmin_def['cat_keywords']['value'] = 'sql';

// cat_featured :: string :: 765
$qadmin_def['cat_featured']['title'] = 'Featured Products';
$qadmin_def['cat_featured']['field'] = 'cat_featured';
$qadmin_def['cat_featured']['type'] = 'varchar';
$qadmin_def['cat_featured']['size'] = 255;
$qadmin_def['cat_featured']['value'] = 'sql';

// cat_page
$qadmin_def['cat_page']['title'] = 'Show this page instead';
$qadmin_def['cat_page']['field'] = 'cat_page';
$qadmin_def['cat_page']['type'] = 'varchar';
$qadmin_def['cat_page']['size'] = 255;
$qadmin_def['cat_page']['value'] = 'sql';
$qadmin_def['cat_page']['help'] = 'Instead of displaying category information, list of products &amp; featured products; you can display a page.';

// general configuration ( * = optional )
$qadmin_cfg['table'] = $db_prefix.'product_cat';					// table name
$qadmin_cfg['primary_key'] = 'idx';						// table's primary key
$qadmin_cfg['primary_val'] = 'dummy';						// primary key value
$qadmin_cfg['template'] = 'default';						// template to use
$qadmin_cfg['permalink_script'] = 'shop_search.php';				// script name for permalink
$qadmin_cfg['permalink_source'] = 'cat_name';				// script name for permalink
$qadmin_cfg['permalink_folder'] = 'category';				// script name for permalink
$qadmin_cfg['post_process'] = 'post_func';
$qadmin_cfg['rebuild_cache'] = true;

// folder configuration (qAdmin only stores filename.ext without folder location), ends without slash '/' - optional
$qadmin_cfg['file_folder'] = './../public/file';					// folder to place file upload (relative to /admin folder)
$qadmin_cfg['img_folder'] = './../public/image';				// folder to place image upload
$qadmin_cfg['thumb_folder'] = './../public/thumb';			// folder to place thumb (auto generated)

// search configuration
$cat_structure = $ce_cache['cat_structure'];
$qadmin_cfg['search_key'] = 'idx,parent_id,cat_name';		// list other key to search
$qadmin_cfg['search_key_mask'] = 'ID,Parent,Category Name';	// mask other key
$qadmin_cfg['search_result_mask'] = ",cat_structure,";

// enable qadmin functions, which are: search, list, new, update & remove
$qadmin_cfg['cmd_default'] = 'list';						// if this script called without ANY parameter
$qadmin_cfg['cmd_search_enable'] = true;
$qadmin_cfg['cmd_list_enable'] = true;
$qadmin_cfg['cmd_new_enable'] = true;
$qadmin_cfg['cmd_update_enable'] = true;
$qadmin_cfg['cmd_remove_enable'] = true;
$qadmin_cfg['footer'] =
"<script type=\"text/javascript\">
//<![CDATA[
var sss = '';
$('#".$db_prefix."product_cat-cat_featured>input').tokenInput('admin_ajax.php?cmd=product', { queryParam:'query', preventDuplicates:true, prePopulate:$cat_featured_preset});
// $('#".$db_prefix."product_cat-cat_page>input').autocomplete({ serviceUrl:'admin_ajax.php', params:{cmd:'page'}, onSelect: function(result){  $('#".$db_prefix."product_cat-cat_page>input').val(result.data) } });
$('#".$db_prefix."product_cat-cat_page>input').tokenInput('admin_ajax.php?cmd=related_page', { queryParam:'query', preventDuplicates:true, tokenLimit:1, prePopulate:$page_preset});
//]]>
</script>";


// security *** qADMIN CAN NOT RUN IF admin_level NOT DEFINED ***
$qadmin_cfg['admin_level'] = '4';

// form title
$qadmin_title['new'] = 'Add New Category';
$qadmin_title['update'] = 'Update Category';
$qadmin_title['search'] = 'Search Category';
$qadmin_title['list'] = 'Category List';
qadmin_manage($qadmin_def, $qadmin_cfg, $qadmin_title);
