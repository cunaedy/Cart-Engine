<?php
require './includes/user_init.php';

if (!$isLogin) {
    redir($config['site_url']);
}

$cmd = get_param('cmd');
$item_id = get_param('item_id');

// send message to admin
switch ($cmd) {
    case 'download':
        // get private file owned
        $row = sql_qquery("SELECT * FROM ".$db_prefix."user_file WHERE (user_id='$current_user_id') AND (item_id='$item_id') LIMIT 1");
        if (empty($row)) {
            msg_die($lang['msg']['no_file']);
        }

        // check file
        $prdo = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx='$item_id' LIMIT 1");
        if (empty($prdo['digital_file'])) {
            msg_die($lang['msg']['no_file']);
        }
        
        $source = './public/private/'.$prdo['digital_file'];
        $ss = pathinfo($source);
        $fn = $ss['basename'];

        // file not available (HOW COME?!)
        if (!file_exists($source)) {
            msg_die(sprintf($lang['msg']['internal_error'], 'File not available.'));
        }

        // update number of download
        sql_query("UPDATE ".$db_prefix."user_file SET total_download=total_download+1, last_download=UNIX_TIMESTAMP() WHERE (user_id='$current_user_id') AND (item_id='$item_id') LIMIT 1");

        // redir
        $content_len = (int) filesize($source);
        @ini_set('zlib.output_compression', 'Off');
        header('ETag: '.md5($source));
        header('Pragma: public', false);
        header('Expires: 0', false);
        header('Cache-Control: private', false);
        header('Last-Modified: '.gmdate('D, d M Y H:i:s') . ' GMT', false);
        header('Content-Type: application/octet-stream', false);
        header('Content-Disposition: attachment; filename="'.$fn.'"', false);
        header('Accept-Ranges: bytes', false);
        header('Content-Length: '.$content_len, false);
        readfile($source);
        die;
    break;


    default:
        $priv_avail = true;
        $tpl = load_tpl('user_file.tpl');
        $txt['block_priv_list'] = '';
        $num_p = 0;

        // get private file owned
        $res = sql_query("SELECT * FROM ".$db_prefix."user_file WHERE user_id='$current_user_id' ORDER BY idx");
        while ($row = sql_fetch_array($res)) {
            $num_p++;
            $prod = sql_qquery("SELECT * FROM ".$db_prefix."products WHERE idx='$row[item_id]' LIMIT 1");

            if ($config['enable_adp'] && $prod['permalink']) {
                $row['url'] = $prod['permalink'];
            } else {
                $row['url'] = "detail.php?item_id=$row[item_id]";
            }
            $row['idx'] = $row['item_id'];
            $row['file_title'] = $prod['title'].' '.$lang['l_digital_icon_small'];
            $row['file_details'] = line_wrap(strip_tags($prod['details']))." [ <a href=\"$config[site_url]/$row[url]\">$lang[l_detail]</a> ]";
            $row['file_thumb'] = make_thumb($row['item_id'].'_1', 'small');
            $txt['block_priv_list'] .= quick_tpl($tpl_block['priv_list'], $row);
        }

        // output
        if (!$num_p) {
            $priv_avail = false;
        }
        $tpl = load_tpl('user_file.tpl');
        $txt['main_body'] = quick_tpl($tpl, $txt);
        generate_html_header("$config[site_name] $config[cat_separator] My Files");
        flush_tpl();
    break;
}
