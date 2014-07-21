<form action="{S_SEARCH_ACTION}" method="post" >
{IMG_THL}{IMG_THC}<span class="forumlink">{L_NAV2}</span>{IMG_THR}<table class="forumlinenb">
<tr><th colspan="2">{L_SEARCH_QUERY}</th></tr>
<tr>
	<td class="row1" width="50%" align="right"><span class="gen">{L_SEARCH_KEYWORDS}:</span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" name="search_keywords" size="30" maxlength="255" /></span></td>
</tr>
<tr>
	<td class="row1" width="50%" align="right"><span class="gen">{L_SEARCH_AUTHOR}:</span></td>
	<td class="row2"><span class="genmed"><input type="text" class="post" name="search_author" size="30" maxlength="32" /></span></td>
</tr>
<tr><th colspan="2">{L_SEARCH_OPTIONS}</th></tr>
<tr>
	<td class="row1 tdalignr"><span class="gen">{L_CATEGORY}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_CATEGORY_OPTIONS}</span></td>
</tr>
<tr>
	<td class="row1 tdalignr"><span class="gen">{L_SORT_BY}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_SORT_OPTIONS}</span></td>
</tr>
<tr>
	<td class="row1 tdalignr"><span class="gen">{L_SORT_DIR}:&nbsp;</span></td>
	<td class="row2"><span class="genmed">{S_SORT_ORDER}</span></td>
</tr>
<tr><td class="cat tdalignc" colspan="2">{S_HIDDEN_FIELDS}<input class="mainoption" type="submit" name="submit" value="{L_SEARCH}" /></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<table class="s2px p2px">
<tr><td align="right" valign="middle">{JUMPBOX}</td></tr>
</table>