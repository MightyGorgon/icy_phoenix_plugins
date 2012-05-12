<!-- BEGIN admin -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_DELETE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1"><div class="post-text">{L_DELETE_SPECIFIC}</div></td>
	<td class="row1 row-center">{GAMES}</td>
	<td class="row1 row-center">
		<form method="post" name="del_top" action="{U_T_LINK}">
			<input class="mainoption" type="hidden" name="action" value="delete_specific_score" />
			<input class="mainoption" type="submit" value="{L_DELETE_SINGLE}" />
		</form>
	</td>
</tr>
<tr>
	<td colspan="2" class="row1"><span class="post-text">{L_DELETE_ALL}</span></td>
	<td class="row1 row-center">
		<form method="post" name="del_all" action="U_T_LINK">
			<input class="mainoption" type="hidden" name="action" value="delete_all_scores" />
			<input class="mainoption" type="submit" value="{L_DELETE_ALL_MSG}" />
		</form>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END admin -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_T_LINK}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="20%">{HEADER_ONE}</th>
	<th width="20%">{HEADER_TWO}</th>
	<th width="20%">{HEADER_THREE}</th>
	<th width="20%">{HEADER_FOUR}</th>
</tr>
<!-- BEGIN top_scores_rows -->
<tr>
	<td class="row1g"><div class="post-text"><b>{top_scores_rows.GAME_IMAGE}</b><br /><br /></div></td>
	<td class="row1g"><div class="post-text">{top_scores_rows.USER_SEARCH}</div></td>
	<td class="row1g"><div class="post-text">{top_scores_rows.SCORE_DATE}</div></td>
	<td class="row1g"><span class="post-buttons-single">{top_scores_rows.PM_PROFILE}</span></td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="7%" height="3" alt="" /></td></tr>
<!-- END top_scores_rows -->
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%" align="left"><span class="gen">{PAGE_NUMBER}</span></td>
	<td width="50%" align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>