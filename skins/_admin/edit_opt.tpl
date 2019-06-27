<form method="post" action="edit_opt.php">
<input type="hidden" name="fid" value="{$fid}" />
<input type="hidden" name="cmd" value="save" />

<table class="table table-condensed">
 <tr><th class="text-capitalize">{$title}</th><th class="text-center">Remove</th></tr>
<!-- BEGINBLOCK list -->
 <tr>
  <td><input type="text" name="value_{$idx}" value="{$config_value}" /></td>
  <td class="text-center"><a href="edit_opt.php?cmd=del&amp;idx={$idx}&amp;AXSRF_token={$axsrf}"><span class="glyphicon glyphicon-remove"></span></a></td>
 </tr>
<!-- ENDBLOCK -->
<!-- BEGINBLOCK new -->
 <tr>
  <td><input type="text" name="value_{$idx}" value="" size="40" /></td>
  <td align="center"></td>
 </tr>
<!-- ENDBLOCK -->
</table>
<p class="text-center"><button type="submit" class="btn btn-primary">Save</button>
<button type="reset" class="btn btn-danger">Reset</button></p>
</form>