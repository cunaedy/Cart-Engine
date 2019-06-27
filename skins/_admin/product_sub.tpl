<div id="sp_form_div">
	<form method="get" id="sp_form" onsubmit="return sp_submit_form()" name="sp_form">
		<input type="hidden" name="cmd" value="save" />
		<input type="hidden" name="item_id" value="{$item_id}" />
		<table border="0" width="100%" class="table table-form">
			<!-- BEGINBLOCK list -->
			<tr>
				<td nowrap="nowrap" width="20%"><b>Group Name</b></td><td><input type="text" size="50" name="group_{$idx}" value="{$group_name}" style="width:394px;border-color:#999"> <a href="javascript:sp_del({$idx})"><span class="glyphicon glyphicon-remove"></span></a></td>
			</tr>
			<tr>
				<td style="vertical-align:top">Members</td><td nowrap="nowrap" style="padding-bottom:20px"><input type="text" id="members_{$idx}" name="members_{$idx}" value="{$group_members}" /></td>
			</tr>
			<!-- ENDBLOCK -->
			<tr>
				<td nowrap="nowrap" width="20%"><b>New Group</b></td><td><input type="text" size="50" name="new_group"></td>
			</tr>
			<tr>
				<td>&nbsp;</td><td><button type="submit" class="btn btn-default btn-sm">Save Changes</button></td>
			</tr>
		</table>
	</form>
</div>

<script>
$(function(){
<!-- BEGINBLOCK list_js -->
$("#members_{$idx}").tokenInput("admin_ajax.php?cmd=product", { queryParam:"query", preventDuplicates:true, prePopulate:{$preset}});
<!-- ENDBLOCK -->
})
function sp_del(idx)
{
	$('#sp_form_div').load('product_sub.php?cmd=del&item_id={$item_id}&idx='+idx);
	return;
}
function sp_submit_form (isform)
{
	var g = $('#sp_form').serialize();
	$('#sp_form_div').load('product_sub.php?'+g);
	return false;
}
$('#sp_saved').fadeOut(1000);
</script>

<!-- BEGINSECTION sp_saved -->
<div class="warning_small" id="sp_saved">Saved</div>
<!-- ENDSECTION -->