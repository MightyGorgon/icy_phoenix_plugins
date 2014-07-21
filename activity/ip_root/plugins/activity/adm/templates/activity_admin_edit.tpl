<!-- BEGIN cat_choice -->
<table class="forumline talignc tw60pct">
<!-- BEGIN rows -->
<tr>
	<td align="left" valign="middle" width="20%" class="row2"><span class="genmed">{cat_choice.rows.ONE}</span></td>
	<td align="center" valign="middle" width="40%" class="row2"><span class="genmed">{cat_choice.rows.TWO}</span></td>
</tr>
<!-- END rows -->
</table>
<!-- END cat_choice -->

<!-- BEGIN editing -->
<form name="save" method="post" action="{RETURN}">
<table class="forumline talignc">
<tr>
	<th width="65%">&nbsp;</th>
	<th class="tw35pct">&nbsp;</th>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_ONE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_name" value="{V_ONE}" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_TWO}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_proper" value="{V_TWO}" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_CATEGORY}</span></td>
	<td class="row2 row-center tvalignm">{CAT}</td></tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_THREE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_path" value="{V_THREE}" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_TYPE}</span></td>
	<td class="row2 row-center tvalignm">{V_TYPE}</td>
</tr>
<tr>
	<td class="row2" valign="bottom" rowspan="2"><span class="genmed">{L_SIZE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed">{L_SIX}  <input type="text" name="game_width" value="{V_SIX}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2 row-center tvalignm"><span class="genmed">{L_SEVEN}  <input type="text" name="game_height" value="{V_SEVEN}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom" rowspan="2">
		<span class="genmed"><b>{L_FUNCTIONS}</b></span><br />
		<span class="gensmall">{L_FUNCTIONS_EXP}</span>
	</td>
	<td class="row2 row-center tvalignm"><span class="genmed">{L_MOUSE}&nbsp;&nbsp;<input type="checkbox" name="game_mouse" {MOUSE} /></span></td>
</tr>
<tr><td class="row2 row-center tvalignm"><span class="genmed">{L_KEYBOARD}&nbsp;&nbsp;<input type="checkbox" name="game_keyboard" {KEYBOARD}></span></td></tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_EIGHT}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_bonus" value="{V_EIGHT}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_NINE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_reward" value="{V_NINE}" size="10" class="post"></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_TEN}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_charge" value="{V_TEN}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom">
		<span class="genmed">{L_GE_COST}</span><br />
		<span class="gensmall">{L_GE_COST_EXP}</span>
	</td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_ge_cost" value="{V_GE_COST}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_ELEVEN}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="text" name="game_highscore" value="{V_ELEVEN}" size="10" class="post" /></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_LINKS}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><textarea rows="" cols="" class="post" name="game_links">{V_LINKS}</textarea></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_FOUR}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><textarea rows="" cols="" class="post" name="game_description">{V_FOUR}</textarea></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_FIVE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><textarea rows="" cols="" size="" class="post" name="game_instructions">{V_FIVE}</textarea></span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_REVERSE}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_reverse" value="1" {REVERSE_Y} />{L_YES}  <input type="radio" name="game_reverse" value="0" {REVERSE_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_SCORES}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_showscores" value="1" {SCORES_Y} />{L_YES}  <input type="radio" name="game_showscores" value="0" {SCORES_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_FLASH}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_flash" value="1" {FLASH_Y} />{L_YES}  <input type="radio" name="game_flash" value="0" {FLASH_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_GLIB}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_glib" value="1" {GLIB_Y} />{L_YES}  <input type="radio" name="game_glib" value="0" {GLIB_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_DISABLE}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_disable" value="1" {DIS_Y} />{L_YES}  <input type="radio" name="game_disable" value="0" {DIS_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_PARENT}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_parent" value="1" {PARENT_Y} />{L_YES}  <input type="radio" name="game_parent" value="0" {PARENT_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_POPUP}</span></td>
	<td class="row2 row-center tvalignm">
		<span class="genmed"><input type="radio" name="game_popup" value="1" {POPUP_Y} />{L_YES}  <input type="radio" name="game_popup" value="0" {POPUP_N} />{L_NO}</span>
	</td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_RESET_SCORES}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="checkbox" name="reset_scores" />  {L_YES}</span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_RESET_JACKPOT}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="checkbox" name="reset_jackpot" />  {L_YES}</span></td>
</tr>
<tr>
	<td class="row2" valign="bottom"><span class="genmed">{L_DELETE_GAME}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed"><input type="checkbox" name="delete_game" />  {L_YES}</span></td>
</tr>
<tr>
	<td colspan="2" class="cat" width="100%" align="center">
		<input type="hidden" name="game_id" value="{ID}">
		<input type="hidden" name="action" value="save">
		<input type="submit" class="mainoption" value=" {L_SUBMIT} " onclick="document.save.submit()">
	</td>
</tr>
</table>
</form>
<!-- END editing -->