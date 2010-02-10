<!-- BEGIN daily_game -->
<div id="daily_games_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MAXIMISE}" onclick="ShowHide('daily_games','daily_games_h','daily_games');" alt="{L_SHOW}" /><span class="forumlink">{daily_game.TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="daily_games">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MINIMISE}" onclick="ShowHide('daily_games','daily_games_h','daily_games');" alt="{L_HIDE}" /><span class="forumlink">{daily_game.TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="20%" >{L_GAMES}</th>
	<th width="20%">{L_T_HOLDER}</th>
	<th width="20%">{L_STATS}</th>
	<th width="45%">{L_INFO}</th>
</tr>
<tr>
	<td class="row-post-author" valign="top" width="100%">
		<div class="post-text">
			<b>{daily_game.PROPER_NAME}</b><br />
			<div class="post-text">
				<div style="text-align: center;">{daily_game.NEW_I_LINK}{daily_game.IMAGE_LINK}</a></div>
				{daily_game.KEYBOARD}<br />{daily_game.MOUSE}
			</div>
			<br />{daily_game.LINKS}{daily_game.DOWNLOAD_LINK}
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
			{daily_game.TROPHY_IMG} <span class="gen">{daily_game.TOP_PLAYER}</span><br />{daily_game.TOP_SCORE}<br /><br />
			{daily_game.RUNNER_IMG} <span class="genmed">{daily_game.BEST_PLAYER}</span><br />{daily_game.BEST_SCORE}<br /><br />{daily_game.FAVORITE_GAME}
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
			&#8226; <a href="{daily_game.COMMENTS}" class="nav">{daily_game.L_COMMENTS}</a>
			{daily_game.CHALLENGE}{daily_game.LIST}
			<br />{daily_game.SEPERATOR}<a href="{daily_game.STATS}" class="nav">{daily_game.INFO}</a><br />
			{daily_game.GAMES_PLAYED} {daily_game.I_PLAYED}
			<!-- IF daily_game.POP_PIC --><br /><center>{daily_game.POP_PIC}</center><!-- ENDIF -->
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
		<b>{daily_game.DESC2}:</b><br />{daily_game.DESC}<br /><br /><b>{daily_game.RATING_TITLE}</b><br />
		{daily_game.SEPERATOR}{daily_game.RATING_SENT} {daily_game.RATING_SUBMIT}  {daily_game.RATING_IMAGE}<br />
		</div>
	</td>
</tr>
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><th width="100%" align="center" colspan="4">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'daily_games';
if(GetCookie(tmp) == '2')
{
	ShowHide('daily_games','daily_games_h','daily_games');
}
//-->
</script>
<!-- END daily_game -->