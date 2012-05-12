<!-- INCLUDE overall_header.tpl -->

{IMG_THL}{IMG_THC}<span class="forumlink">{L_favorites_page_title}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="20%">{L_GAMES}</th>
	<th width="15%">{L_T_HOLDER}</th>
	<th width="20%">{L_STATS}</th>
	<th width="45%">{L_INFO}</th>
</tr>
<!-- BEGIN game -->
<tr>
	<td class="row1g" width="20%"><span class="post-text"><b>{game.PROPER_NAME}</b>{game.NEW_I_LINK}{game.IMAGE_LINK}</a><br /><br />{game.LINK}{game.DOWNLOAD_LINK}</span></td>
	<td class="row1g" width="15%"><span class="post-text"><img src="{game.TROPHY_LINK}" height="16" width="13">  {game.TOP_PLAYER}<br />{game.TOP_SCORE}</span></td>
	<td class="row1g-left" width="20%">
		<span class="post-text">
			{game.SEPARATOR}{game.LIST}<br />
			{game.SEPARATOR}<a href="{game.STATS}" class="nav">{game.INFO}</a><br />
			{game.SEPARATOR}{game.GAMES_PLAYED} {game.I_PLAYED}<br />
			{game.POP_PIC}
		</span>
	</td>
	<td class="row-post" width="45%"><b><span class="post-text">{game.DESC2}:<br /></b>{game.DESC}</span></td>
</tr>
<!-- END game -->
<tr><td class="cat" colspan="4"></td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
{GAMELIB_LINK}

<br /><br />
<a href="#" class="gensmall"style="text-decoration :none;">&copy; Activity Mod Plus</a>

<!-- INCLUDE overall_footer.tpl -->