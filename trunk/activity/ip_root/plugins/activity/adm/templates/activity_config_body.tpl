<form action="{S_CONFIG_ACTION}" method="post">

<table class="forumline" width="100%" cellpadding="0" cellspacing="0" border="0">
<tr><th colspan="2" align="center">{L_TOGGLES}</td></tr>
<tr>
	<td class="row1">{L_USE_ADAR_SHOP}<span class="gensmall">{L_USE_ADAR_INFO}</span></td>
	<td class="row1 row-center">
		<input type="radio" name="use_gk_shop" value="1" {S_USE_GKS_YES} /> {L_YES}
		<input type="radio" name="use_gk_shop" value="0" {S_USE_GKS_NO} /> {L_NO}
	</td>
</tr>
<tr>
	<td class="row2">{L_USE_GAMELIB}<span class="gensmall">{L_USE_GL_INFO}</span></td>
	<td class="row2 row-center">
		<input type="radio" name="use_gamelib" value="1" {S_USE_GL_YES} /> {L_YES}
		<input type="radio" name="use_gamelib" value="0" {S_USE_GL_NO} /> {L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_USE_REWARDS}<span class="gensmall">{L_USE_REWARDS_INFO}</span></td>
	<td class="row1 row-center">
		<input type="radio" name="use_rewards_mod" value="1" {S_USE_REWARDS_YES} /> {L_YES}
		<input type="radio" name="use_rewards_mod" value="0" {S_USE_REWARDS_NO} /> {L_NO}
	</td>
</tr>

<!-- BEGIN rewards_menu_on -->
<tr><th colspan="2" align="center">{L_REWARDS}</td></tr>

<tr>
	<td class="row1">{L_USE_POINTS}<span class="gensmall">{L_USE_POINTS_INFO}</span></td>
	<td class="row1 row-center">
		<input type="radio" name="use_point_system" value="1" {S_USE_PSM_YES} /> {L_YES}
		<input type="radio" name="use_point_system" value="0" {S_USE_PSM_NO} /> {L_NO}
	</td>
</tr>

<tr>
	<td class="row2">{L_USE_ALLOWANCE}<span class="gensmall">{L_USE_ALLOWANCE_INFO}</span></td>
	<td class="row2 row-center">
		<input type="radio" name="use_allowance_system" value="1" {S_USE_ASM_YES} /> {L_YES}
		<input type="radio" name="use_allowance_system" value="0" {S_USE_ASM_NO} /> {L_NO}
	</td>
</tr>
<tr>
	<td class="row1">{L_USE_CASH}<span class="gensmall">{L_USE_CASH_INFO}</span></td>
	<td class="row1 row-center">
		<input type="radio" name="use_cash_system" value="1" {S_USE_CASH_YES} /> {L_YES}
		<input type="radio" name="use_cash_system" value="0" {S_USE_CASH_NO} /> {L_NO}
	</td>
</tr>
<!-- END rewards_menu_on -->

<!-- BEGIN cash_default_menu -->
<tr>
	<td class="row1"><span class="gensmall">{L_CASH_DEFAULT_INFO}</span></td>
	<td class="row1" align="right">{L_CASH} {DASH} <input class="post" type="text" size="15" name="default_cash" value="{DEFAULT_CASH}" /></td>
</tr>
<!-- END cash_default_menu -->
<tr><th colspan="2" align="center">{L_ACTIVITY_CONFIG}</td></tr>

<!-- BEGIN display_gamelib_menu -->
<tr>
	<td class="row1">{L_GL_GAME_PATH}<span class="gensmall">{L_GL_PATH_INFO}</span></td>
	<td class="row1" align="right">{L_PATH} {DASH} <input class="post" type="text" size="15" name="games_path" value="{S_GAMES_PATH}" /></td>
</tr>
<tr>
	<td class="row2">{L_GL_LIB_PATH}<span class="gensmall">{L_GL_LIB_INFO}</span></td>
	<td class="row2" align="right">{L_PATH} {DASH} <input class="post" type="text" size="15" name="gamelib_path" value="{S_GAMELIB_PATH}" /></td>
</tr>
<!-- END display_gamelib_menu -->

<tr>
	<td class="row1">{L_GAMES_PER_PAGE}<span class="gensmall">{L_GAMES_PER_INFO}</span></td>
	<td class="row1" align="right">{L_PAGE} {DASH} <input class="post" type="text" size="15" name="games_per_page" value="{S_GAMES_PER_PAGE}" /></td>
</tr>

<!-- BEGIN display_shop_menu -->
<tr><th colspan="2" align="center">{L_ADAR_SHOP_CONFIG}</td></tr>
<tr><td class="row1" colspan="2">{L_ADAR_SHOP}<span class="gensmall">{L_ADAR_INFO}</span></td></tr>
<!-- END display_shop_menu -->

<tr>
	<td class="cat" colspan="2" align="center">{S_HIDDEN_FIELDS}
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" /> 
		<input type="reset" value="{L_RESET}" class="mainoption" />
		<input type="submit" name="edit_games" value="Edit Games" class="mainoption" />
	</td>
</tr>

</table>
</form>