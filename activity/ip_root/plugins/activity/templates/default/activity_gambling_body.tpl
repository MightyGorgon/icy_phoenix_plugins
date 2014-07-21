<form method="post" name="gambling" action="activity.php?page=gambling">
<!-- BEGIN stats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_gambling_link_3}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw5pct"> {stats.L_TITLE_1}</th>
	<th class="tw20pct">{stats.L_TITLE_2}</th>
	<th class="tw20pct">{stats.L_TITLE_3}</th>
	<th class="tw15pct">{stats.L_TITLE_4}</th>
	<th class="tw20pct">{stats.L_TITLE_5}</th>
	<th class="tw20pct">{stats.L_TITLE_6}</th>
</tr>
<!-- END stats -->
<!-- BEGIN stats_rows -->
<tr>
<tr>
	<td class="row1 row-center"><span class="post-text">{stats_rows.GAME_NUMBER}</span></td>
	<td class="row1 row-center"><span class="post-text">{stats_rows.WINNER_LINK}</span></td>
	<td class="row1 row-center"><span class="post-text">{stats_rows.LOSER_LINK}</span></td>
	<td class="row1 row-center"><span class="post-text">{stats_rows.GAME_IMAGE}</span></td>
	<td class="row1 row-center"><span class="post-text">{stats_rows.AMOUNT}</span></td>
	<td class="row1 row-center"><span class="post-text">{stats_rows.DATE}</span></td>
</tr>
<!-- END stats_rows -->
<!-- BEGIN stats -->
<tr><th colspan="6">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END stats -->

<!-- BEGIN user_selection -->
<table class="forumline">
<tr><td class="row-header" colspan="4"><span>{L_gambling_link_2}</span></td></tr>
<tr><th colspan="4">{user_selection.L_USER_SELECTION_TITLE}</th></tr>
<tr>
	<td class="row2 row-center" colspan="4" width="100%">
		<select name="user_option_one">
			<option selected value='0'>{user_selection.L_USER_SELECTION_DEFAULT}</option>
<!-- END user_selection -->
			<!-- BEGIN user_selection_array -->
			<option value="{user_selection_array.USER_ID}">{user_selection_array.USERNAME}</option>
			<!-- END user_selection_array -->
<!-- BEGIN user_selection -->
		</select>
	</td>
</tr>
<tr><td class="row2 row-center" colspan="4" width="100%"><input type="text" class="post" name="user_option_two" value="{user_selection.L_TEXT_BOX_DEFAULT}" /></td></tr>
<!-- END user_selection -->
<!-- BEGIN game_selection -->
<tr>
	<th class="tw5pct">#</th>
	<th class="tw10pct">{game_selection.L_GAME_RADIO}</th>
	<th class="tw35pct">{game_selection.L_GAME_IMAGE}</th>
	<th width="50%">{game_selection.L_GAME_DESC}</th>
</tr>
<!-- END game_selection -->
<!-- BEGIN game_selection_rows -->
<tr>
	<td class="row1 row-center"><span class="post-text">{game_selection_rows.GAME_NUMBERS}</span></td>
	<td class="row1 row-center"><span class="post-text"><input type="radio" name="game_selected" value="{game_selection_rows.GAME_ID}"></span></td>
	<td class="row1 row-center"><span class="post-text">{game_selection_rows.GAME_IMAGE}</span></td>
	<td class="row1"><span class="post-text">{game_selection_rows.GAME_DESC}</span></td>
</tr>
<!-- END game_selection_rows -->
<!-- BEGIN bet_selection -->
<br />
<tr><th width="80%" colspan="4">{bet_selection.L_BET_TITLE}</th></tr>
<tr>
	<td align="left" valign="middle" width="80%" class="row2" colspan="3"><span class="genmed">{bet_selection.L_BET_FOR_FUN}</span></td>
	<td class="row2 row-center tvalignm" width="20%"><span class="genmed"><input type="radio" name="bet_selection" value="1" /></span></td>
</tr>
<tr>
	<td align="left" valign="middle" width="80%" class="row1" colspan="3"><span class="genmed">{bet_selection.L_BET_FOR_FEE}</span></td>
	<td class="row1 row-center tvalignm" width="20%"><span class="genmed"><input type="radio" name="bet_selection" value="2" /></span></td>
</tr>
<tr>
	<td align="left" valign="middle" width="80%" class="row2" colspan="3">
		<span class="genmed">{bet_selection.L_BET_DESC}</span>
		<span class="gensmall"><i>({bet_selection.L_MAX_BET_DESC})</i>{bet_selection.L_POINTS_OFF}</span>
	</td>
	<td class="row2 row-center tvalignm" width="20%"><span class="genmed"><input type="text" name="bet_amount" value="" size="10" /></span></td>
</tr>

<tr><th colspan="4">{bet_selection.L_SUBMIT_TITLE}</th></tr>
<tr>
	<td class="cat tvalignm" colspan="4">
		<input class="mainoption" type="hidden" name="mode" value="submit_gamble" />
		<input class="mainoption" type="submit" value="{bet_selection.L_GAME_SUBMIT}" onchange="document.gambling.submit()" />
	</td>
</tr>
<tr><th colspan="4">&nbsp;</th></tr>
</table>
</form>
	<!-- END bet_selection -->