<!-- BEGIN playing_games -->
<div id="online_games_h" style="display: none;">
{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MAXIMISE}" onclick="ShowHide('online_games','online_games_h','online_games');" alt="{L_SHOW}" /><span class="forumlink">{playing_games.ONLINE_TITLE}</span>{IMG_THR_ALT}<table class="forumlinenb">
<tr><td>&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<div id="online_games">
<script type="text/javascript">
<!--
tmp = 'online_games';
if(GetCookie(tmp) == '2')
{
	ShowHide('online_games', 'online_games_h', 'online_games');
}
//-->
</script>
{IMG_THL}{IMG_THC}<img class="max-min-right" style="padding-top: 3px;" src="{IMG_MINIMISE}" onclick="ShowHide('online_games','online_games_h','online_games');" alt="{L_HIDE}" /><span class="forumlink">{playing_games.ONLINE_TITLE}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1" colspan="2"><span class="gensmall">{playing_games.CURRENTLY_PLAYING1}{playing_games.TOTAL_M_PLAYING}{playing_games.CURRENTLY_PLAYING2}<br />{playing_games.CURRENTLY_PLAYING5}<br />{playing_games.CURRENTLY_PLAYING3}{playing_games.TOTAL_G_PLAYING}{playing_games.CURRENTLY_PLAYING4}<br />{playing_games.MAIN_COLOR1}{playing_games.MAIN_SEPARATOR}{playing_games.MAIN_COLOR2}<br /></span></td></tr>
<tr><td class="row1" colspan="2"><span class="gensmall">
<!-- END playing_games -->
<!-- BEGIN playing -->
{playing.USER_NUMBER}{playing.USERNAME}{playing.MAIN_SEPARATOR}
<!-- END playing -->
<!-- BEGIN playing_games -->
</span></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</div>
<!-- END playing_games -->
