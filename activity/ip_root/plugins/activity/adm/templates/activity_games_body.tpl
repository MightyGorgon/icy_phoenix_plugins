<form action="{S_CONFIG_ACTION}" method="post">
<br /><center><span class="gen"><b>.: {L_GAMES} :.</b></span></center>
<table class="forumline">
	<tr>
		<th class="tw5pct">{L_MONEY}</th>
		<th class="tw15pct">{L_BUTTON}</th>
		<th width="55%">{L_DESC}</th>
		<th class="tw5pct">{L_REWARD}</th>
		<th class="tw10pct">{L_BONUS}</th>
		<th class="tw5pct">{L_FLASH}</th>
		<th class="tw5pct">{L_SCORE}</th>
		<th class="tw5pct">{L_GAMELIB}</th>
		<th width="5%" colspan="2">{L_ACTION}</th>
	</tr>

	<!-- BEGIN game -->
	<tr>
		<td class="{game.ROW_CLASS} row-center">{game.CHARGE}</td>
		<td class="{game.ROW_CLASS}"><img src ="./../{game.PATH}{game.NAME}.gif" border="0" align="middle"></td>
		<td class="{game.ROW_CLASS}">{game.DESC}</td>
		<td class="{game.ROW_CLASS} row-center">{game.REWARD}</td>
		<td class="{game.ROW_CLASS} row-center">{game.BONUS}</td>
		<td class="{game.ROW_CLASS} row-center">{game.FLASH}</td>
		<td class="{game.ROW_CLASS} row-center">{game.SHOW_SCORE}</td>
		<td class="{game.ROW_CLASS} row-center">{game.GAMELIB}</td>
		<td class="{game.ROW_CLASS}"><a href="{game.U_GAME_EDIT}">{L_EDIT}</a></td>
		<td class="{game.ROW_CLASS}">[<a href="{game.U_GAME_DELETE}">{L_DEL}</a>]</td>
	</tr>
	<!-- END game -->

	<tr>
		<td colspan="11" class="cat" align="center">
			<input type="submit" name="add_game" value="{L_ADD}" class="mainoption" />
			<input type="submit" name="clear_scores" value="{L_RESET_SCORE}" class="mainoption" />
		</td>
	</tr>
</table>
</form>