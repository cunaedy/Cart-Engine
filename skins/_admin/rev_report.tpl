<div class="panel panel-default">
	<div class="panel-heading"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span> Revenue Reports</div>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form">
		<tr>
			<td>
				<ul class="quick_info" style="margin-bottom:15px">
					<li><a href="rev_report.php?cmd=detail">Detailed</a></li>
					<li><a href="rev_report.php?cmd=detail_day">Daily</a></li>
					<li><a href="rev_report.php?cmd=detail_month">Monthly</a></li>
					 <li><a href="rev_report.php?cmd=detail_year">Yearly</a></li>
				</ul>
			</td>
		</tr>
	<!-- BEGINIF $tpl_mode == 'detail' -->
		<tr>
			<td>
			<form method="get" action="rev_report.php">
			<input type="hidden" name="cmd" value="detail" />
			Display reports from {$start_date} to {$end_date}
			<input type="submit" value="Go!" />
			</form>
			</td>
		</tr>
	</table>
	<div style="width:0;height:0">
		<canvas id="canvas" height="0" width="0"></canvas>
	</div>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form" id="result">
		<tr>
			<td class="adminbg_h" colspan="4">Revenue</td>
		</tr>
		<tr>
			<td class="adminbg_c">Date</td>
			<td class="adminbg_c">User ID</td>
			<td class="adminbg_c" style="text-align:right">Sales</td>
		</tr>
		<!-- BEGINBLOCK list -->
		<tr>
			<td><a href="trx.php?order_id={$order_id}">{$order_date}</a></td>
			<td>{$user_id}</td>
			<td align="right">{$order_total}</td>
		</tr>
		<!-- ENDBLOCK -->
		<tr>
			<td class="adminbg_r" align="right">Total</td>
			<td class="adminbg_r" style="text-align:right">{$tfreq}</td>
			<td class="adminbg_r" style="text-align:right">{$total}</td>
		</tr>
	</table>
	{$pagination}
	<!-- ENDIF -->

	<!-- BEGINIF $tpl_mode == 'detail_day' -->
		<tr>
			<td>
				<form method="get" action="rev_report.php">
					<input type="hidden" name="cmd" value="detail_day" />
					Display reports for month of {$start_date}
					<input type="submit" value="Go!" />
				</form>
			</td>
		</tr>
	</table>
	<div style="max-width:960px;height:220px">
		<canvas id="canvas" height="200" width="900"></canvas>
	</div>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form" id="result">
		<tr>
			<td class="adminbg_h" colspan="5">Revenue</td>
		</tr>
		<tr>
			<td class="adminbg_c">Date</td>
			<td class="adminbg_c">Number of Sales</td>
			<td class="adminbg_c">Sales</td>
		</tr>
		<!-- BEGINBLOCK list -->
		<tr>
			<td><a href="rev_report.php?cmd=detail&amp;start_yy={$ye}&amp;start_mm={$mo}&amp;start_dd={$da}&amp;end_yy={$ye}&amp;end_mm={$mo}&amp;end_dd={$da}">{$order_date}</a></td>
			<td align="right">{$freq}</td>
			<td align="right">{$sales}</td>
		</tr>
		<!-- ENDBLOCK -->
		<tr>
			<td class="adminbg_r" align="right">Total</td>
			<td class="adminbg_r" style="text-align:right">{$tfreq}</td>
			<td class="adminbg_r" style="text-align:right">{$total}</td>
		</tr>
	</table>
	<!-- ENDIF -->


	<!-- BEGINIF $tpl_mode == 'detail_month' -->
		<tr>
			<td>
				<form method="get" action="rev_report.php">
					<input type="hidden" name="cmd" value="detail_month" />
					Display reports for year of {$start_date}
					<input type="submit" value="Go!" />
				</form>
			</td>
		</tr>
	</table>
	<div style="max-width:960px;height:220px">
		<canvas id="canvas" height="200" width="900"></canvas>
	</div>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form" id="result">
		<tr>
			<td class="adminbg_h" colspan="5">Revenue</td>
		</tr>
		<tr>
			<td class="adminbg_c">Month</td>
			<td class="adminbg_c">Number of Sales</td>
			<td class="adminbg_c">Sales</td>
		</tr>
		<!-- BEGINBLOCK list -->
		<tr>
			<td><a href="rev_report.php?cmd=detail_day&amp;start_mm={$mo}&amp;start_yy={$ye}">{$order_date}</a></td>
			<td align="right">{$freq}</td>
			<td align="right">{$sales}</td>
		</tr>
		<!-- ENDBLOCK -->
		<tr>
			<td class="adminbg_r" align="right">Total</td>
			<td class="adminbg_r" style="text-align:right">{$tfreq}</td>
			<td class="adminbg_r" style="text-align:right">{$total}</td>
		</tr>
	</table>
	<!-- ENDIF -->


	<!-- BEGINIF $tpl_mode == 'detail_year' -->
	</table>
	<div style="max-width:960px;height:220px">
		<canvas id="canvas" height="200" width="900"></canvas>
	</div>
	<table border="0" width="100%" cellpadding="3" cellspacing="1" class="table table-form" id="result">
		<tr>
			<td class="adminbg_c">Year</td>
			<td class="adminbg_c">Number of Sales</td>
			<td class="adminbg_c">Sales</td>
		</tr>
		<!-- BEGINBLOCK list -->
		<tr>
			<td><a href="rev_report.php?cmd=detail_month&amp;start_yy={$order_date}">{$order_date}</a></td>
			<td align="right">{$freq}</td>
			<td align="right">{$sales}</td>
		</tr>
		<!-- ENDBLOCK -->
		<tr>
			<td class="adminbg_r" align="right">Total</td>
			<td class="adminbg_r" style="text-align:right">{$tfreq}</td>
			<td class="adminbg_r" style="text-align:right">{$total}</td>
		</tr>
	</table>
	<!-- ENDIF -->
</div>
<p class="small">Revenues are based on <b>shipped</b>, <b>delivered</b> or <b>completed</b> orders.</p>

<script src="{$site_url}/misc/js/chart.min.js"></script>
<script>
var lineChartData = {
	labels : [{$chart_x}],
	datasets : [
		{
			label: "Sales",
			fillColor : "rgba(220,220,220,0.2)",
			strokeColor : "rgba(220,220,220,1)",
			pointColor : "rgba(220,220,220,1)",
			pointStrokeColor : "#fff",
			pointHighlightFill : "#fff",
			pointHighlightStroke : "rgba(220,220,220,1)",
			data : [{$chart_y1}]
		}
	]

}

window.onload = function(){
	var ctx = document.getElementById("canvas").getContext("2d");
	window.myLine = new Chart(ctx).Line(lineChartData, { responsive: true });
}
</script>