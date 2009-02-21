<form method="post" action="{S_MODE_ACTION}">
{IMG_TBL}<div class="forumline nav-div">
	<p class="nav-header">
		<a href="#" class="nav-current">{L_ACTIVITY}</a>
	</p>
	<div class="nav-links">
		<b>{ORDER_SELECT_TITLE}:&nbsp;&nbsp;&nbsp;</b>{L_SELECT_SORT_METHOD}:&nbsp;{S_MODE_SELECT}&nbsp;&nbsp;&nbsp;&nbsp;{L_ORDER}:&nbsp;{S_ORDER_SELECT}&nbsp;<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
	</div>
</div>{IMG_TBR}
</form>
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td id="var_width" width="220" valign="top" align="left" style="padding-right:7px;">
		<div id="quick_links_games2" style="padding-top:5px;display:none;margin-left:0px;text-align:left;position:relative;float:left;"><a href="javascript:ShowHide('quick_links_games','quick_links_games2','quick_links_games');setWidth(220);" title="{L_SHOW} {L_QUICK_LINKS_GAMES}"><img src="{IMG_NAV_MENU_APPLICATION}" alt="{L_SHOW} {L_QUICK_LINKS_GAMES}" /></a></div>
		<div id="quick_links_games">
<script type="text/javascript">
<!--
tmp = 'quick_links_games';
if(GetCookie(tmp) == '2')
{
	ShowHide('quick_links_games','quick_links_games2','quick_links_games');
	setWidth(16);
}
//-->
</script>

{IMG_THL}{IMG_THC}
<!-- &nbsp;[<a href="javascript:ShowHide('quick_links_games','quick_links_games2','quick_links_games');setWidth(16);" title="{L_HIDE} {L_QUICK_LINKS_GAMES}">{L_HIDE}</a>] -->
<img style="padding-top:3px;float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('quick_links_games','quick_links_games2','quick_links_games');setWidth(16);" alt="{L_SHOW}" />
<span class="forumlink">{L_QUICK_LINKS_GAMES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">

<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('main_links_games','main_links_games2','main_links_games');">
		<img src="{IMG_NAV_MENU_GAMES}" alt="{L_STATISTICS}" title="{L_STATISTICS}" />&nbsp;
		<a href="javascript:void(0);" title="{L_STATISTICS}" style="vertical-align:top;text-decoration:none;"><b>{L_STATISTICS}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="main_links_games2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<!-- BEGIN links_check -->
				{links_check.LINKS}
				<!-- END links_check -->
				<tr>
					<td align="left" width="8">{IMG_NAV_MENU_SEP}</td>
					<td class="genmed" align="left"><a href="{RANDOM_GAME}">{RANDOM_LINK}</a></td>
				</tr>
			</table>
		</div>
		<div id="main_links_games" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'main_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('main_links_games','main_links_games2','main_links_games');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
<tr>
	<th style="cursor:pointer;" align="left" onclick="ShowHide('games_links','games_links2','games_links');">
		<img src="{IMG_NAV_MENU_GAMES_ALT}" alt="{L_GAMES}" title="{L_GAMES}" />&nbsp;
		<a href="javascript:void(0);" title="{L_GAMES}" style="vertical-align:top;text-decoration:none;"><b>{L_GAMES}</b></a>
	</th>
</tr>
<tr>
	<td class="row5">
		<div id="games_links2" style="display:none;position:relative;padding-top:0px;padding-bottom:0px;">
			<table class="forumline-no2" width="100%" cellspacing="0" cellpadding="2" border="0">
				<tr>
					<td width="8" align="left" valign="middle">
					<form>
							<span class="genmed">
							<select onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
								<option selected value="">{D_DEFAULT}</option>
							<!-- BEGIN drop -->
								<option value="{drop.D_SELECT_1}">{drop.D_SELECT_2}</option>
							<!-- END drop -->
							</select>
							<noscript><input type="submit" value="Go"></noscript>
						</span>
					</form>
					</td>
				</tr>
				<tr>
					<td width="8" align="left" valign="middle">
					<form>
					<span class="genmed">
						<select onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
							<option selected value="">{C_DEFAULT}</option>
							<option value="{C_DEFAULT_ALL}">{C_DEFAULT_ALL_L}</option>
							<option value="{C_CAT_PAGE}">{L_CAT_PAGE}</option>
						<!-- BEGIN cat -->
							<option value="{cat.C_SELECT_2}">{cat.C_SELECT_1}</option>
						<!-- END cat -->
						</select>
						<noscript><input type="submit" value="Go"></noscript>
					</span>
					</form>
					</td>
				</tr>
			</table>
		</div>
		<div id="games_links" style="display:'';position:relative;">
			<script type="text/javascript">
			<!--
			tmp = 'games_links';
			if(GetCookie(tmp) == '2')
			{
				ShowHide('games_links','games_links2','games_links');
			}
			//-->
			</script>
		</div>
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
		</div>
	</td>
	<td valign="top">

{ACTIVITY_INFO_SECTION}

{ACTIVITY_DAILY_SECTION}

{ACTIVITY_NEWEST_SECTION}

<!-- BEGIN games_on -->
<div id="games_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img style="padding-top:3px;float:right;cursor:pointer;" src="{IMG_MAXIMISE}" onclick="javascript:ShowHide('games','games_h','games');" alt="{L_SHOW}" /><span class="forumlink">{L_GAMES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="games">
	{IMG_THL}{IMG_THC}<img style="padding-top:3px;float:right;cursor:pointer;" src="{IMG_MINIMISE}" onclick="javascript:ShowHide('games','games_h','games');" alt="{L_HIDE}" /><span class="forumlink">{L_GAMES}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th class="th" width="20%">{L_GAMES}</th>
	<th class="th" width="15%">{L_T_HOLDER}</th>
	<th class="th" width="20%">{L_STATS}</th>
	<th class="th" width="45%">{L_INFO}</th>
</tr>
<!-- BEGIN game -->
<tr>
	<td class="row-post-author" valign="top" width="100%">
		<div class="post-text">
			<b>{games_on.game.PROPER_NAME}</b><br />
			<div class="post-text">
				<div class="activity-links-left">{games_on.game.NEW_I_LINK}{games_on.game.IMAGE_LINK}</a></div>
				{games_on.game.KEYBOARD}{games_on.game.MOUSE}
			</div>
			<br />{games_on.game.LINKS}{games_on.game.DOWNLOAD_LINK}
		</div>
	</td>
	<td class="row-post row-center">
		<div class="post-text">
			{games_on.game.TROPHY_IMG} <span class="gen">{games_on.game.TOP_PLAYER}</span><br />{games_on.game.TOP_SCORE}<br /><br />
			{games_on.game.RUNNER_IMG} <span class="genmed">{games_on.game.BEST_PLAYER}</span><br />{games_on.game.BEST_SCORE}
			<br /><br />{games_on.game.FAVORITE_GAME}
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
			&#8226; <a href="{games_on.game.COMMENTS}" class="nav">{games_on.game.L_COMMENTS}</a>
			{games_on.game.CHALLENGE}
			{games_on.game.LIST}
			<br />{games_on.game.SEPERATOR}<a href="{games_on.game.STATS}" class="nav">{games_on.game.INFO}</a><br />
			{games_on.game.GAMES_PLAYED} {games_on.game.I_PLAYED}
			<br /><center>{games_on.game.POP_PIC}</center>
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
		<b>{games_on.game.DESC2}:</b>	<br />
		{games_on.game.DESC}<br /><br />
		<b>{games_on.game.RATING_TITLE}</b><br />
		{games_on.game.SEPERATOR}{games_on.game.RATING_SENT} {games_on.game.RATING_SUBMIT}  {games_on.game.RATING_IMAGE}<br />
		</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<!-- END game -->
<tr><th width="100%" align="center" colspan="4">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'games';
if(GetCookie(tmp) == '2')
{
	ShowHide('games','games_h','games');
}
//-->
</script>
<!-- END games_on -->

{ACTIVITY_ONLINE_SECTION}
</td>
</tr>
</table>
<!-- BEGIN games_on -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td align="left"><span class="gen">{PAGE_NUMBER}</span></td>
	<td align="right"><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- END games_on -->

{GAMELIB_LINK}