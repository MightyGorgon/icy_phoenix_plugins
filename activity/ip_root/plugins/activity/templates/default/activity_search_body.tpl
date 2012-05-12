<!-- BEGIN search_switch -->
<form name="game_search" action="activity.php?page=search" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{SEARCH_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="50%">{L_TOP_LEFT}</th>
	<th width="50%">{L_TOP_RIGHT}</th>
</tr>
<tr>
	<td class="row1 row-center">
		<select name="top_left">
			<option selected value="flash">{L_TL_OPTION_1}</option>
			<option value="glib">{L_TL_OPTION_2}</option>
		</select>
	</td>
	<td class="row1 row-center">
		<select name="top_right">
			<option value="desc">{L_TR_OPTION_1}</option>
			<option selected value="name">{L_TR_OPTION_2}</option>
			<option value="reverse">{L_TR_OPTION_3}</option>
		</select>
	</td>
</tr>
<tr>
	<td class="row1 row-center" colspan="2">
		<span class="post-text">
			{L_QUERY}<br /><br />
			<input type="post" name="query" size="30" value="" class="post" /><br /><br />
			{L_WILDCARD}&nbsp;<input type="checkbox" name="wildcard" />
		</span>
		</td>
</tr>
<tr>
	<td class="cat" colspan="2">
		<input type="hidden" name="mode" value="search" />
		<input type="submit" class="mainoption" value="{L_SUBMIT}" onchange="game_search.submit()" />
	</td>
</tr>
<tr><th width="100%" colspan="2">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END search_switch -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_admin_cat_24}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<!-- BEGIN search_results -->
<tr>
	<th width="20%">{L_IMAGE}</th>
	<th width="30%">{L_NAME}</th>
	<th width="50%">{L_DESC}</th>
</tr>
<tr>
	<td class="row1 row-center"><span class="post-text">{search_results.IMAGE}</span></td>
	<td class="row1"><span class="post-text">{search_results.NAME}</span></td>
	<td class="row1"><span class="post-text">{search_results.DESC}</span></td>
</tr>
<!-- END search_results -->
<tr><th width="100%" colspan="3">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}