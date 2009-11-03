<!-- BEGIN info_box -->
<div id="info_games_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MAXIMISE}" onclick="ShowHide('info_games','info_games_h','info_games');" alt="{L_SHOW}" /><span class="forumlink">{info_box.L_INFO_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="info_games">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MINIMISE}" onclick="ShowHide('info_games','info_games_h','info_games');" alt="{L_HIDE}" /><span class="forumlink">{info_box.L_INFO_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="33%">{info_box.L_INFO_TITLE1}</th>
	<th width="33%">{info_box.L_INFO_TITLE2}</th>
	<th width="33%">{info_box.L_INFO_TITLE3}</th>
</tr>
<tr>
	<td class="row-post" valign="top">
	<span class="post-text">
			 <b>{info_box.USERNAME}</b><br /><br />
			{info_box.FAVORITES_LINK}<br />
			{info_box.TOTAL_GAMES_LINK}<br />
			{info_box.TOTAL_CHALLENGES_SENT}<br />
			{info_box.TOTAL_CHALLENGES_RECIEVED}<br />
			{info_box.TOTAL_COMMENTS_LEFT}<br />
			{info_box.TOTAL_TROPHIES_HELD}<br />
			{info_box.TOTAL_ONHAND_POINTS}
	<!-- END info_box -->
	<!-- BEGIN personal_info_box -->
			{personal_info_box.LAST_GAME_PLAYED}<br />
	<!-- END personal_info_box -->
	<!-- BEGIN info_box -->
	{info_box.TOTAL_TIME_IN_GAMES}
	</span>
	</td>
	<td class="row-post row-center" valign="top">
		<div class="post-text">
		<b>&#8226; {info_box.L_NEWEST_TITLE}:</b><br />&nbsp;&nbsp;&nbsp;{info_box.LAST_GAME_PLAYED}<br /><br /><br />
		<b>&#8226; {info_box.TROPHY_GAME_1}:</b><br />&nbsp;&nbsp;&nbsp;{info_box.TROPHY_GAME}<br />&nbsp;&nbsp;&nbsp;{info_box.TROPHY_GAME_2}<br />
		&nbsp;&nbsp;&nbsp;{info_box.TROPHY_GAME_3}<br /><br /><br />
		<b>&#8226; {info_box.TROPHY_TOP_HOLDER}:</b>&nbsp;&nbsp;&nbsp;{info_box.TROPHY_TOP_HOLDER1}<br /><br />
		</div>
	</td>
	<td class="row-post" valign="top">
		<div class="post-text">
		<b>&#8226; {info_box.MOST_POPULAR_1}</b><br />{info_box.MOST_POPULAR_2}<br />{info_box.MOST_POPULAR_3}<br /><br />
		<b>&#8226; {info_box.LEAST_POPULAR_1}</b><br />{info_box.LEAST_POPULAR_2}<br />{info_box.LEAST_POPULAR_3}<br /><br />
		<b>&#8226; {L_STATISTICS}:</b><br />{info_box.TOTAL_GAMES_PLAYED}
		</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><th width="100%" align="center" colspan="3">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'info_games';
if(GetCookie(tmp) == '2')
{
	ShowHide('info_games','info_games_h','info_games');
}
//-->
</script>
<!-- END info_box -->