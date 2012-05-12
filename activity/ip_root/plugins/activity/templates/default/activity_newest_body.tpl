<!-- BEGIN newest_only -->
<div id="newest_games_h" style="display: none;">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MAXIMISE}" onclick="ShowHide('newest_games','newest_games_h','newest_games');" alt="{L_SHOW}" /><span class="forumlink">{newest_only.NEWEST_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
	<tr><td class="row1g row-center">&nbsp;</td></tr>
	</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="newest_games">
	{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MINIMISE}" onclick="ShowHide('newest_games','newest_games_h','newest_games');" alt="{L_HIDE}" /><span class="forumlink">{newest_only.NEWEST_TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="20%" >{newest_only.L_GAMES}</th>
	<th width="20%">{newest_only.L_T_HOLDER}</th>
	<th width="20%">{newest_only.L_STATS}</th>
	<th width="45%">{newest_only.L_INFO}</th>
</tr>

<!-- END newest_only -->
<!-- BEGIN newest -->
<tr>
	<td class="row-post-author" valign="top" width="100%">
		<div class="post-text">
			<b>{newest.PROPER_NAME}</b><br />
			<div class="post-text">
				<div style="text-align: center;">{newest.NEW_I_LINK}{newest.IMAGE_LINK}</a></div>
				{newest.KEYBOARD}{newest.MOUSE}
			</div>
			<br />{newest.LINKS}{newest.DOWNLOAD_LINK}
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
			{newest.TROPHY_IMG} <span class="gen">{newest.TOP_PLAYER}</span><br />{newest.TOP_SCORE}<br /><br />
			{newest.RUNNER_IMG} <span class="genmed">{newest.BEST_PLAYER}</span><br /><span class="genmed">{newest.BEST_SCORE}</span>
			<br /><br />{newest.FAVORITE_GAME}
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
			&#8226; <a href="{newest.COMMENTS}" class="nav">{newest.L_COMMENTS}</a>
			{newest.CHALLENGE}
			{newest.LIST}
			<br />{newest.SEPARATOR}<a href="{newest.STATS}" class="nav">{newest.INFO}</a><br />
			{newest.GAMES_PLAYED} {newest.I_PLAYED}
			<!-- IF newest.POP_PIC --><br /><center>{newest.POP_PIC}</center><!-- ENDIF -->
		</div>
	</td>
	<td class="row-post">
		<div class="post-text">
		<b>{newest.DESC2}:</b><br />{newest.DESC}<br /><br />
		<b>{newest.RATING_TITLE}</b><br />{newest.SEPARATOR}{newest.RATING_SENT}  {newest.RATING_SUBMIT}  {newest.RATING_IMAGE}<br />
		</div>
	</td>
</tr>
<!-- END newest -->
<!-- BEGIN newest_only -->
<tr><td class="spaceRow" colspan="4"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><th width="100%" align="center" colspan="4">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<script type="text/javascript">
<!--
tmp = 'newest_games';
if(GetCookie(tmp) == '2')
{
	ShowHide('newest_games','newest_games_h','newest_games');
}
//-->
</script>
<!-- END newest_only -->