<?php
// part of qEngine
require './../includes/admin_init.php';
admin_check(4);

$cmd = get_param('cmd');
$p = get_param('p');
$start = date_param('start', 'get');
$rnd = random_str(16);
$chart_avail = true;

switch ($cmd) {
    case 'detail_year':
        $tpl_mode = 'detail_year';
        $txt['block_list'] = ''; $tfreq = $total = 0;
        $chart_x = array(date('Y') - 1);
        $chart_y1 = array(0);
        $tpl = load_tpl('adm', 'rev_report.tpl');

        // date
        $res = sql_query("SELECT MAX(order_date) AS max_year, MIN(order_date) AS min_year FROM ".$db_prefix."order_summary LIMIT 1");
        $row = sql_fetch_array($res);
        $min = substr($row['min_year'], 0, 4);
        $max = substr($row['max_year'], 0, 4);
        if (empty($min)) {
            $min = date('Y');
        }
        if (empty($max)) {
            $max = date('Y');
        }

        for ($ye = $min; $ye <= $max; $ye++) {
            $ye_end = "$ye-12-31";
            $ye_start = "$ye-1-1";
            $row = sql_qquery("SELECT SUM(order_total) AS sales, COUNT(*) AS freq FROM ".$db_prefix."order_summary WHERE 
							   order_date >= '$ye_start' AND order_date <= '$ye_end' AND (order_status='S' OR order_status='D' OR order_status='C') LIMIT 1");
            $total = $total + $row['sales'];
            $tfreq = $tfreq + $row['freq'];
            $row['freq'] = num_format($row['freq']);
            $row['order_date'] = $ye;
            $chart_x[] = '"'.$row['order_date'].'"';
            $chart_y1[] = empty($row['sales']) ? 0 : $row['sales'];
            $row['sales'] = num_format($row['sales'], 0, 1);
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }
     
        $txt['chart_x'] = implode(',', $chart_x);
        $txt['chart_y1'] = implode(',', $chart_y1);
        $txt['start_date'] = date_form('start', date('Y'), 0, 0, $start);
        $txt['total'] = num_format($total, 0, 1);
        $txt['tfreq'] = num_format($tfreq);
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
    
    
    case 'detail_month':
        $tpl_mode = 'detail_month';
        $txt['block_list'] = ''; $tfreq = $total = 0; $chart_x = $chart_y1 = array();
        $tpl = load_tpl('adm', 'rev_report.tpl');

        // date
        if (empty($start)) {
            $start = date('Y');
        }

        for ($mo = 1; $mo <= 12; $mo++) {
            $mo_end = "$start-$mo-31";
            $mo_start = "$start-$mo-1";
            $row = sql_qquery("SELECT SUM(order_total) AS sales, COUNT(*) AS freq FROM ".$db_prefix."order_summary WHERE 
							   order_date >= '$mo_start' AND order_date <= '$mo_end' AND (order_status='S' OR order_status='D' OR order_status='C') LIMIT 1");
            $total = $total + $row['sales'];
            $tfreq = $tfreq + $row['freq'];
            $row['freq'] = num_format($row['freq']);
            $row['mo'] = $mo;
            $row['ye'] = substr($mo_start, 0, 4);
            $row['order_date'] = date('F', mktime(0, 0, 0, $mo, 1, 2000));
            $chart_x[] = '"'.$row['order_date'].'"';
            $chart_y1[] = empty($row['sales']) ? 0 : $row['sales'];
            $row['sales'] = num_format($row['sales'], 0, 1);
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        $txt['chart_x'] = implode(',', $chart_x);
        $txt['chart_y1'] = implode(',', $chart_y1);
        $txt['total'] = num_format($total, 0, 1);
        $txt['tfreq'] = num_format($tfreq);
        $txt['start_date'] = date_form('start', date('Y'), 0, 0, $start);
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;
    
    
    case 'detail_day':
        $tpl_mode = 'detail_day';
        $txt['block_list'] = ''; $tfreq = $total = 0; $chart_x = $chart_y1 = array();
        $tpl = load_tpl('adm', 'rev_report.tpl');

        // date
        if (empty($start)) {
            $start = date('Y-m');
        }
        $ed = date('t', mktime(0, 0, 0, substr($start, 5, 2), 1, substr($start, 0, 4)));

        for ($dd = 1; $dd <= $ed; $dd++) {
            $date = $start.'-'.$dd;
            $row = sql_qquery("SELECT SUM(order_total) AS sales, COUNT(*) AS freq FROM ".$db_prefix."order_summary WHERE order_date = '$date' AND (order_status='S' OR order_status='D' OR order_status='C') GROUP BY(order_date) LIMIT 1");
            $total = $total + $row['sales'];
            $tfreq = $tfreq + $row['freq'];
            $row['ye'] = substr($date, 0, 4);
            $row['mo'] = substr($date, 5, 2);
            $row['da'] = substr($date, 8, 2);
            $row['order_date'] = date('M d', mktime(0, 0, 0, $row['mo'], $row['da'], $row['ye']));
            $chart_x[] = '"'.$row['da'].'"';
            $chart_y1[] = empty($row['sales']) ? 0 : $row['sales'];
            $row['sales'] = empty($row['sales']) ? 0 : num_format($row['sales'], 0, 1);
            $row['freq'] = empty($row['freq']) ? 0 : num_format($row['freq']);
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        $txt['chart_x'] = implode(',', $chart_x);
        $txt['chart_y1'] = implode(',', $chart_y1);
        $txt['start_date'] = date_form('start', date('Y'), 1, 0, $start);
        $txt['total'] = num_format($total, 0, 1);
        $txt['tfreq'] = num_format($tfreq);
        $txt['main_body'] = quick_tpl($tpl, $txt);
        flush_tpl('adm');
    break;


    default:
        $chart_avail = false;
        $start = date_param('start', 'get');
        $end = date_param('end', 'get');

        $tpl_mode = 'detail';
        $txt['block_list'] = ''; $tfreq = $total = 0;
        $tpl = load_tpl('adm', 'rev_report.tpl');

        // date
        if (empty($start)) {
            $start = $sql_today;
        }
        if (empty($end)) {
            $end = $sql_today;
        }

        $foo = sql_multipage($db_prefix.'order_summary', 'user_id, order_id, order_total, order_date', "order_date >= '$start' AND order_date <= '$end' AND (order_status='S' OR order_status='D' OR order_status='C')", 'order_date ASC', $p);
        foreach ($foo as $row) {
            $total = $total + $row['order_total'];
            $tfreq++;
            $row['order_date'] = convert_date($row['order_date']);
            $row['order_total'] = num_format($row['order_total'], 0, 1);
            $txt['block_list'] .= quick_tpl($tpl_block['list'], $row);
        }

        $txt['start_date'] = date_form('start', date('Y'), 1, 1, $start);
        $txt['total'] = num_format($total, 0, 1);
        $txt['tfreq'] = num_format($tfreq);
        $txt['end_date'] = date_form('end', date('Y'), 1, 1, $end);
        $txt['chart_x'] = $txt['chart_y1'] = $txt['chart_y2'] = "null";
        $txt['main_body'] = quick_tpl(load_tpl('adm', 'rev_report.tpl'), $txt);
        flush_tpl('adm');
    break;
}
