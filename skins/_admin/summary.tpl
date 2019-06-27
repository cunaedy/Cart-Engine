<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-stats"></span> Visitor Statistics</div>
	<div class="panel-body">
		<!-- BEGINIF $qstat_module -->
		<div style="max-width:1900px;height:100%;overflow:hidden">
			<canvas id="canvas" height="300" width="1900"></canvas>
		</div>
		<!-- ELSE -->
		<p><a href="module.php">qStats module is disabled or not installed.</a></p>
		<!-- ENDIF -->
	</div>
</div>

<div class="row">
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="glyphicon glyphicon-bullhorn"></span> C97.net Updates <a href="https://www.c97.net" target="_blank" title="visit C97.net"><span style="font-size:12pt;color:#999"  class="glyphicon glyphicon-link"></span></a></div>
			<div class="panel-body">
				<iframe src="index.php?cmd=feed" style="border:none;padding:0;margin:0;width:100%;height:100%" name="rssfeed"></iframe>
			</div>
		</div>
	</div>
	<div class="col-md-6">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="glyphicon glyphicon-dashboard"></span> Summary</div>
			<div class="panel-body">
				<ul class="list_1" style="margin-bottom: 10px">
					<li>Last login: {$log_user_id} at {$log_time} from <a href="iplog.php"><span class="badge">{$log_ip_addr}</span></a></li>
					<li>Registered users at this site: <a href="user.php"><span class="badge">{$total_user}</span></a></li>
					<li>New members in the last  7 days: <span class="badge">{$total_user_7}</span></li>
					<li>New comments to approve: <a href="task.php?mod=qcomment&run=edit.php&qadmin_cmd=list&filter_by=2"><span class="badge">{$num_rev}</span></a></li>
					<li>Hard disk space: {$free_space} MB free of {$max_space} MB</li>
					<li>Database space: {$db_size} MB</li>
					<li>Number of entries in <a href="qadmin_log.php">qe_qadmin_log</a>: {$qadmin_log_qty} items, {$qadmin_log_size} KB</li>
					<li>Number of entries in <a href="mailog.php">qe_mailog</a>: {$mailog_qty} items, {$mailog_size} KB</li>
					<li>Number of entries in <a href="iplog.php">qe_ip_log</a>: {$ip_log_qty} items, {$ip_log_size} KB</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading"><span class="glyphicon glyphicon-shopping-cart"></span> Last 5 Transactions</div>
			<div class="list-group">
				<a href="trx_list.php?filter_by=1" class="list-group-item">Order Pending <span class="badge">{$num_e}</span></a>
				<a href="trx_list.php?filter_by=2" class="list-group-item">Paid <span class="badge">{$num_p}</span></a></a>
				<a href="trx_list.php?filter_by=3" class="list-group-item">Shipped <span class="badge">{$num_s}</span></a>
				<a href="trx_list.php?filter_by=4" class="list-group-item">Delivered <span class="badge">{$num_d}</span></a>
				<a href="trx_list.php?filter_by=5" class="list-group-item">Completed <span class="badge">{$num_c}</span></a>
				<a href="trx_list.php?filter_by=6" class="list-group-item">Cancelled <span class="badge">{$num_x}</span></a>
				<!-- BEGINBLOCK trx_item -->
				<a href="trx.php?order_id={$order_id}" class="list-group-item">
					<h5><span class="glyphicon glyphicon-calendar"></span> {$order_id} @ {$order_date}</h5>
					<p><b>Username:</b> {$user_id}<br />
					<b>Total:</b> {$order_total}<br />
					<b>Paid via:</b> {$order_payment} [{$order_paystat}]<br />
					<b>Order status:</b> {$order_status}</p></a>
				<!-- ENDBLOCK -->
			</div>
		</div>
	</div>
</div>


<script src="{$site_url}/misc/js/chart.min.js"></script>
<script type="text/javascript">
$(document).ready(function () {
	<!-- BEGINIF $qstat_module -->
	var lineChartData = {
		labels : [{$chart_x}],
		datasets : [
			{
				label: "Hits",
				fillColor : "rgba(220,220,220,0.2)",
				strokeColor : "rgba(220,220,220,1)",
				pointColor : "rgba(220,220,220,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(220,220,220,1)",
				data : [{$chart_y1}]
			},
			{
				label: "Visits",
				fillColor : "rgba(151,187,205,0.2)",
				strokeColor : "rgba(151,187,205,1)",
				pointColor : "rgba(151,187,205,1)",
				pointStrokeColor : "#fff",
				pointHighlightFill : "#fff",
				pointHighlightStroke : "rgba(151,187,205,1)",
				data : [{$chart_y2}]
			}
		]
	}
	var ctx = document.getElementById("canvas").getContext("2d");
	window.myLine = new Chart(ctx).Line(lineChartData, { responsive: true });
	<!-- ENDIF -->
});
</script>