<!-- BEGIN search_player -->
<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}" class="nav">{L_HOME}</a>{NAV_SEP}<a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a>{NAV_SEP}<a href="{search_player.U_LINK}" class="nav">{search_player.L_LINK}</a>{NAV_SEP}<a href="#top" class="nav-current">{search_player.L_LINK_DESC}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{CURRENT_TIME}
		</div>
		&nbsp;
	</div>
</div>

{IMG_THL}{IMG_THC}<span class="forumlink">{search_player.L_LINK_DESC}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th>{L_USERNAME}</th>
	<th>{search_player.TOP_ONE}</th>
	<th>{search_player.TOP_TWO}</th>
	<th>{search_player.TOP_THREE}</th>
	<th>{search_player.TOP_FOUR}</th>
	<th>{L_Information}</th>
</tr>
<tr>
	<td width="16%" class="row1 row-center"><div class="post-text">{search_player.USERNAME}<br />{search_player.RANK_IMAGE}</div></td>
	<td width="17%" class="row1 row-center"><div class="post-text">{search_player.BOTTOM_ONE}</div></td>
	<td width="16%" class="row1 row-center"><div class="post-text">{search_player.BOTTOM_TWO}</div></td>
	<td width="16%" class="row1 row-center"><div class="post-text">{search_player.BOTTOM_THREE}</div></td>
	<td width="17%" class="row1 row-center"><div class="post-text">{search_player.BOTTOM_FOUR}</div></td>
	<td width="16%" class="row1 row-center"><span class="post-buttons">{search_player.BUTTONS}</span></td>
</tr>
<tr>
	<th colspan="3">{search_player.HEADER_ONE}</th>
	<th colspan="3">{search_player.HEADER_TWO}</th>
</tr>
<!-- END search_player -->
<!-- BEGIN search_player_games -->
<tr>
	<td class="row1 row-center" width="25%" colspan="3"><div class="post-text">{search_player_games.GAMES}</div></td>
	<td class="row1 row-center" width="25%" colspan="3"><div class="post-text">{L_scored}{search_player_games.SCORE_DATE}</div></td>
</tr>
<!-- END search_player_games -->
<tr><th width="100%" colspan="6">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}