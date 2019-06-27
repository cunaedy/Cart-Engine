<h1>{$l_my_files}</h1>
<!-- BEGINIF $priv_avail -->
<table border="0" width="100%" class="table">
 <tr>
  <th width="50%">{$l_title}</td>
  <th width="50%">{$l_description}</td>
 </tr>
<!-- BEGINBLOCK priv_list -->
 <tr>
  <td valign="top"><a href="user_file.php?cmd=download&amp;item_id={$item_id}">{$file_thumb}</a><br /><a href="user_file.php?cmd=download&amp;item_id={$item_id}">{$file_title}</a></td>
  <td valign="top">{$file_details}</td>
 </tr>
<!-- ENDBLOCK -->
</table>
<!-- ELSE -->
<div style="text-align:center">{$l_my_files_none}</div>
<!-- ENDIF -->