<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}" class="nav">{L_HOME}</a>{NAV_SEP}<a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a>{NAV_SEP}<a href="#" class="nav-current">{L_games_settings_link}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
		{CURRENT_TIME}
		</div>
		&nbsp;
	</div>
</div>
<form name="save_this" method="post" action="activity.php?page=settings">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_games_settings_link}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_GAMES}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed">{V_GAMES}</span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_GAMES_COUNT}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed"><input type="text" class="post" value="{V_GAMES_COUNT}" name="game_count" /></span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_NEW}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed">{V_NEW}</span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_NEW_COUNT}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed"><input type="text" class="post" value="{V_NEW_COUNT}" name="new_count" /></span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_INFO}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed">{V_INFO}</span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_DAILY}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed">{V_DAILY}</span></td>
</tr>
<tr>
	<td align="left" valign="bottom" width="50%" class="row1"><span class="genmed">{L_ONLINE}</span></td>
	<td align="left" valign="bottom" width="50%" class="row2"><span class="genmed">{V_ONLINE}</span></td>
</tr>
<tr>
	<td colspan="2" width="100%" class="cat">
		<input type="hidden" value="save" name="mode" />
		<input type="submit" class="mainoption" value="{SUBMIT}" onlick="document.save_this.submit()" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<br clear="all">