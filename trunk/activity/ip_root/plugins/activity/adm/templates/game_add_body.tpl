<form method="post" action="{S_GAME_ACTION}" name="add_game">
<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td class="row1">
		<span class="genmed">{L_NAME}</span><br />
		<span class="gensmall">{L_NAME_INFO}</span>
	</td>
	<td class="row2" width="20%">
		<select name="game_name">
			<option class="post" selected value="">{V_DEFAULT}</option>
			<!-- BEGIN drop -->
			<option class="post" value="{drop.D_SELECT}">{drop.D_SELECT}</option>
			<!-- END drop -->
		</select>
	</td>
</tr>
<tr>
	<td class="row1">
		<span class="genmed"><b>{L_PROPER_NAME}</b></span><br />
		<span class="gensmall">{L_PROPER_NAME_INFO}</span>
	</td>
	<td class="row2" width="20%"><input class="post" type="text" size="40" name="game_proper" value="" /></td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed"><b>{C_SHORT}</b></span><br />
		<span class="gensmall">{C_EXPLAIN}</span>
	</td>
	<td class="row2" width="20%">
		<select name="game_cat">
			<option class="post" selected value="">{C_DEFAULT}</option>
			<!-- BEGIN cat -->
			<option class="post" value="{cat.C_SELECT_1}">{cat.C_SELECT_2}</option>
			<!-- END cat -->
		</select>
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_PATH}</span><br />
		<span class="gensmall">{L_GAME_PATH_INFO}</span>
	</td>
	<td class="row2" width="20%"><input type="hidden" size="40" name="game_path" value="{V_GAME_PATH}" />{V_GAME_PATH}</td>
</tr>

<tr>
	<td class="row1"><span class="genmed">{L_TYPE}</span></td>
	<td class="row2" width="20%">{V_TYPE}</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_DESC}</span><br />
		<span class="gensmall">{L_GAME_DESC_INFO}</span>
	</td>
	<td class="row2" width="20%"><input class="post" type="text" size="40" name="game_description" value="{DESC}" /></td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_CHARGE}</span><br />
		<span class="gensmall">{L_GAME_CHARGE_INFO}</span>
	</td>
	<td class="row2" width="20%" align="left"> 
		<select name="game_charge">
			<option class="post" selected value="">{V_DEFAULT_2}</option>
			<option class="post" value="{V_INC_1}">{V_INC_1}</option>
			<!-- BEGIN charge -->
			<option class="post" value="{charge.D_SELECT}">{charge.D_SELECT}</option>
			<!-- END charge -->
		</select>
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GE_COST}</span><br />
		<span class="gensmall">{L_GE_COST_EXP}</span>
	</td>
	<td class="row2" width="20%"><input class="post" type="text" size="20" name="game_ge_cost" /></td>
</tr>

<tr>
	<td align="left" valign="bottom" class="row1"><span class="genmed">{L_LINKS}</span></td>
	<td align="center" valign="middle" class="row2"><span class="genmed"><textarea rows="" cols="" class="post" name="game_links">{V_LINKS}</textarea></span></td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_PER}</span><br />
		<span class="gensmall">{L_GAME_PER_INFO}</span>
	</td>
	<td class="row2" width="20%" align="left"><input class="post" type="text" size="20" name="game_reward" value="{REWARD}" /></td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_BONUS}</span><br />
		<span class="gensmall">{L_GAME_BONUS_INFO}</span>
	</td>
	<td class="row2" width="20%" align="left">
		<select name="game_bonus">
			<option class="post" selected value="">{V_DEFAULT_3}</option>
			<option class="post" value="{V_INC_2}">{V_INC_2}</option>
			<!-- BEGIN bonus -->
			<option class="post" value="{bonus.D_SELECT}">{bonus.D_SELECT}</option>
			<!-- END bonus -->
		</select>
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_DISABLE_DES}</span><br />
		<span class="gensmall">{L_DISABLE_DS}</span>
	</td>
	<td class="row2" align="center">
		<input type="radio" name="game_disable" value="2" /> {L_YES}
		<input type="radio" name="game_disable" checked="checked" value="1" /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_GAMELIB}</span><br />
		<span class="gensmall">{L_GAME_GAMELIB_INFO}</span>
	</td>
	<td class="row2" align="center">
		<input type="radio" name="game_glib" value="1" {S_USE_GL_YES} /> {L_YES}
		<input type="radio" name="game_glib" checked="checked" value="0" {S_USE_GL_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_FLASH}</span><br />
		<span class="gensmall">{L_GAME_FLASH_INFO}</span>
	</td>
	<td class="row2" align="center">
		<input type="radio" name="game_flash" checked="checked" value="1" {S_USE_FLASH_YES} /> {L_YES}
		<input type="radio" name="game_flash" value="0" {S_USE_FLASH_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_SHOW_SCORE}</span><br />
		<span class="gensmall">{L_GAME_SHOW_INFO}</span>
	</td>
	<td class="row2" align="center">
		<input type="radio" name="game_highscore" checked="checked" value="1" {S_SHOW_SCORE_YES} /> {L_YES}
		<input type="radio" name="game_highscore" value="0" {S_SHOW_SCORE_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_REVERSE}</span><br />
		<span class="gensmall">{L_GAME_REVERSE_INFO}</span>
	</td>
	<td class="row2" align="center">
		<input type="radio" name="game_reverse" value="1" {S_REVERSE_LIST_YES} /> {L_YES}
		<input type="radio" name="game_reverse" checked="checked" value="0" {S_REVERSE_LIST_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_HIGHSCORE_LIMIT}</span><br />
		<span class="gensmall">{L_HIGHSCORE_INFO}</span>
	</td>
	<td class="row2" width="20%" align="center">{L_LIMIT}{DASH} <input class="post" type="text" size="5" name="game_highscore" value="{HIGHSCORE_LIMIT}" /></td>
</tr>

<tr>
	<td class="row1">
		<span class="genmed">{L_GAME_SIZE}</span><br />
		<span class="gensmall">{L_GAME_SIZE_INFO}</span>
	</td>
	<td class="row2" width="20%" align="center">
		{L_WIDTH}{DASH} <input class="post" type="text" size="5" name="game_width" value="{V_GAME_WIDTH}" />
		{L_HEIGHT}{DASH} <input class="post" type="text" size="5" name="game_height" value="{V_GAME_HEIGHT}" />
	</td>
</tr>

<tr>
	<td align="left" valign="bottom" class="row2" rowspan="2">
		<span class="genmed"><b>{L_FUNCTIONS}</b></span><br />
		<span class="gensmall">{L_FUNCTIONS_EXP}</span>
	</td>
	<td align="center" valign="middle" class="row2"><span class="genmed">{L_MOUSE}&nbsp;&nbsp;<input type="checkbox" name="game_mouse" /></span></td>
</tr>
<tr><td align="center" valign="middle" class="row2"><span class="genmed">{L_KEYBOARD}&nbsp;&nbsp;<input type="checkbox" name="game_keyboard" /></span></td></tr>

<tr><th colspan="2">{L_INSTRUCTIONS}</th></tr>

<tr>
	<td class="row2 row-center" colspan="2">
		<span class="gentblsmall">{L_INSTRUCTIONS_INFO}</span><br />
		<textarea rows="10" cols="100%" name="game_instructions" class="post">{GAME_INSTRUCTIONS}</textarea>
	</td>
</tr>

<tr>
	<td class="cat" colspan="2" align="center">
		{S_HIDDEN_FIELDS}
		<input class="mainoption" type="submit" value="{L_SUBMIT}" onclick="document.add_game.submit()" />
		<input class="liteoption" type="reset" value="{L_RESET}" />
	</td>
</tr>
</table>
</form>