<h2>Print</h2>
<div class="admin_div">
	<p style="margin-top:0">Please wait until the contents have been fully loaded.</p>
	<button type="button" class="btn btn-primary" onclick="printIt()" id="print">Print Now!</button>
	<button type="button" class="btn btn-default" onclick="{$redir}" id="done">Close</button>
</div>

<div class="admin_div" style="margin-top:10px">
	<iframe frameborder="1" style="width:100%; height:380px; border:inset 1px" src="{$src}" id="preview" name="preview"></iframe>
</div>

<script>
function printIt()
{
	window.frames["preview"].focus();
	window.frames["preview"].print();
}

$('#preview').focus();
</script>