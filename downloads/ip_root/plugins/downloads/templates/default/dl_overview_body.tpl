<form action="{U_DOWNLOADS_ADV}" method="post" name="dl_mod">
{IMG_THL}{IMG_THC}<span class="forumlink">{PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">{L_STATUS}</th>
	<th class="tdnw tw100pct" colspan="2">{L_NAME}</th>
	<th class="tdnw">{L_SIZE}</th>
	<th class="tdnw">{L_KLICKS}<br />{L_OVERALL_KLICKS}</th>
	<th class="tdnw">{L_RATING}</th>
</tr>
<!-- BEGIN download -->
<tr>
	<td class="{download.ROW_CLASS} row-center">{download.STATUS}</td>
	<td class="{download.ROW_CLASS}"><a href="{download.U_DL_LINK}" class="nav">{download.DESCRIPTION}</a>&nbsp;<span class="genmed">{download.HACK_VERSION}</span></td>
	<td class="{download.ROW_CLASS} row-center"><a href="{download.U_CAT_VIEW}" class="gensmall">{download.CAT_NAME}</a></td>
	<td class="{download.ROW_CLASS} tdalignr"><span class="genmed">{download.FILE_SIZE}</span></td>
	<td class="{download.ROW_CLASS} row-center"><span class="genmed">{download.FILE_KLICKS} / {download.FILE_OVERALL_KLICKS}</span></td>
	<td class="{download.ROW_CLASS} tdnw">
	<table cellpadding="0" cellspacing="0" border="0">
	<tr>
		<td class="tdnw">{download.RATING_IMG}</td>
		<td class="tdalignr tdnw"><span class="postdetails">{download.RATINGS}</span></td>
	</tr>
	<tr><td class="tdalignc tdnw" colspan="2"><span class="postdetails">&nbsp;{download.U_RATING}&nbsp;</span></td></tr>
	</table>
	</td>
</tr>
<!-- END download -->
<tr>
	<td class="cat" colspan="6">&nbsp;
		<!-- BEGIN sort_options -->
		<span class="genmed">{L_SORT_BY}&nbsp;{S_SORT_BY}&nbsp;&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER}</span>
		<input type="submit" class="liteoption" value="{L_GO}" />
		<!-- END sort_options -->
	&nbsp;</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br />

<table>
<tr><td class="tdalignr"><span class="pagination">{PAGINATION}</span></td></tr>
</table>
<br />