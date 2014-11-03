<form action="{S_MANAGE_ACTION}" method="post" name="thumbnails">
<table class="forumline">
<tr><th colspan="3">{L_DL_THUMBNAILS}</th></tr>
<!-- BEGIN thumbnails -->
<tr>
	<td class="row1" width="5%" nowrap="nowrap">{thumbnails.CHECKBOX}</td>
	<td class="row1" width="70%" nowrap="nowrap">&nbsp;<a href="{thumbnails.U_REAL_FILE}" class="genmed" target="_blank">{thumbnails.REAL_FILE}</a>&nbsp;</td>
	<td class="row1 tw25pct" nowrap align="right"><span class="genmed">&nbsp;{thumbnails.FILE_SIZE}&nbsp;</span></td>
</tr>
<!-- END files_row -->
<tr><td class="cat tdalignc" colspan="3"><input type="submit" class="mainoption" name="del_real_thumbs" value="{L_DELETE}" /></td></tr>
</table>

<table class="talignc tw50pct">
<tr><td nowrap="nowrap" align="right"><span class="gensmall"><a href="#" onclick="setCheckboxes('thumbnails', 'thumb[]', true); return false;" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('thumbnails', 'thumb[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a><br class="mb5" /></span></td></tr>
</table>
</form>
