<?php
require_once "./includes/user_init.php";
$output = "<ul class=\"list_2\">\n";

// pages
$gres = sql_query("SELECT * FROM ".$db_prefix."page_group WHERE cat_list='1' ORDER BY group_title");
while ($grow = sql_fetch_array($gres)) {
    $output .= "<li><a href=\"#\">$grow[group_title]</a>\n<ul>\n";
    $res = sql_query("SELECT * FROM ".$db_prefix."page WHERE page_list='1' AND group_id='$grow[group_id]' ORDER BY page_title");
    while ($row = sql_fetch_array($res)) {
        if ($config['enable_adp'] && $row['permalink']) {
            $url = $row['permalink'];
        } else {
            $url = "page.php?pid=$row[page_id]";
        }
        $output .= "<li><a href=\"$url\">$row[page_title]</a>\n<ul>\n";
        $output .= "</ul>\n</li>\n";
    }
    $output .= "</ul>\n</li>\n";
}

// end
$output .= "</ul>\n";

// categories
$output .= '<p>Products</p>';
$output .= $cat_structure_html;


$txt['main_body'] = str_replace("<ul>\n</ul>", '', $output);
generate_html_header("$config[site_name] $config[cat_separator] Site Map");
flush_tpl('site');
