{IMG_THL}{IMG_THC}<span class="forumlink">{L_SEARCH_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">&nbsp;{L_STATUS}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_CATEGORY}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_DESCRIPTION}&nbsp;</th>
	<th class="tdnw">&nbsp;{L_FILENAME}&nbsp;</th>
</tr>
<tr>
	<td class="row3">&nbsp;</td>
	<td class="row3" colspan="3">&nbsp;<b>{L_LONG_DESC}</b>&nbsp;</td>
</tr>
<!-- BEGIN searchresults -->
<tr>
	<td class="{searchresults.ROW_CLASS} row-center tvalignm">{searchresults.STATUS}</td>
	<td class="{searchresults.ROW_CLASS}"><span class="forumlink"><a href="{searchresults.U_CAT_LINK}" class="forumlink">{searchresults.CAT_NAME}</a></span></td>
	<td class="{searchresults.ROW_CLASS}"><span class="topiclink">{searchresults.MINI_ICON}<a href="{searchresults.U_FILE_LINK}" class="topictitle">{searchresults.DESCRIPTION}</span></td>
	<td class="{searchresults.ROW_CLASS}"><span class="name">{searchresults.FILE_NAME}</span></td>
</tr>
<tr>
	<td class="{searchresults.ROW_CLASS}">&nbsp;</td>
	<td class="{searchresults.ROW_CLASS}" colspan="3"><span class="postdetails">{searchresults.LONG_DESC}</span></td>
</tr>
<!-- END searchresults -->
<!-- BEGIN no_searchresults -->
<tr><td class="row1 row-center tvalignm" colspan="4">{no_searchresults.L_NO_RESULTS}</td></tr>
<!-- END no_searchresults -->
<tr><td class="cat tdalignc tvalignm" colspan="4">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<table class="s2px p2px">
<tr><td class="tdalignr tdnw"><span class="pagination">{PAGINATION}</span><br /><span class="gensmall">{S_TIMEZONE}</span></td></tr>
</table>

<table class="s2px p2px">
<tr><td class="tdalignr">{JUMPBOX}</td></tr>
</table>