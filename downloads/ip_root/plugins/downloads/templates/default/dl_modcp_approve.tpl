<form method="post" name="dl_modcp" action="{S_DL_MODCP_ACTION}" >
{IMG_THL}{IMG_THC}<span class="forumlink">{L_NAV2}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">&nbsp;{L_DL_CAT_NAME}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_DOWNLOAD}&nbsp;</th>
	<th colspan="2">&nbsp;{L_SET}&nbsp;</th>
</tr>
<!-- BEGIN approve_row -->
<tr>
	<td class="{approve_row.ROW_CLASS}"><a href="{approve_row.U_CAT_VIEW}" class="forumlink">{approve_row.CAT_NAME}</a></td>
	<td class="{approve_row.ROW_CLASS}">{approve_row.MINI_ICON}&nbsp;<a href="{approve_row.U_DOWNLOAD}" class="topiclink">{approve_row.DESCRIPTION}</a></td>
	<td class="{approve_row.ROW_CLASS} row-center" width="10%"><a href="{approve_row.U_EDIT}">{approve_row.EDIT_IMG}</a></td>
	<td class="{approve_row.ROW_CLASS} row-center" width="5%"><input type="checkbox" name="dlo_id[]" value="{approve_row.FILE_ID}" /></td>
</tr>
<!-- END approve_row -->
<tr>
	<td colspan="4" align="right" class="cat" valign="top" nowrap="nowrap">
		<input type="submit" name="delete" value="{L_DELETE}" class="liteoption" />
		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_APPROVE}" class="mainoption"/>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table>
<tr>
	<td class="tdalignr tdnw">
		<span class="gensmall"><a href="#" onclick="setCheckboxes('dl_modcp', 'dlo_id[]', true); return false;" class="gensmall">{L_MARK_ALL}</a>&nbsp;&bull;&nbsp;<a href="#" onclick="setCheckboxes('dl_modcp', 'dlo_id[]', false); return false;" class="gensmall">{L_UNMARK_ALL}</a></span><br class="mb5" />
		<span class="pagination">{PAGINATION}</span>
	</td>
</tr>
</table>
</form>