<!-- INCLUDE overall_header.tpl -->

<form name="shop_settings" method="post" action="{RETURN}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_OPTIONS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th align="center" width="100%" colspan="3">{L_OPTIONS_NAME}</th></tr>
<tr>
	<th align="center" width="20%" nowrap="nowrap"><span class="genmed">{L_EFFECT}</span></th>
	<th align="center" width="60%" nowrap="nowrap"><span class="genmed">{L_COLOR}</span></th>
	<th align="center" width="20%" nowrap="nowrap"><span class="genmed">{L_COST}</span></th>
</tr>
<tr>
	<td align="left" width="10%" class="row2" nowrap="nowrap"><span class="genmed"><input type="checkbox" name="color_name" {NAME_COLOR}> {L_EFFECTS_COLOR}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="color_color_name">
			<option selected value="{NAME_COLOR_V}" class="post">{NAME_COLOR_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{NAME_COST_ONE}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="shadow_name" {NAME_SHADOW} />{L_EFFECTS_SHADOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="shadow_color_name">
			<option selected value="{NAME_SHADOW_V}" class="post">{NAME_SHADOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{NAME_COST_TWO}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="glow_name" {NAME_GLOW} />{L_EFFECTS_GLOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="glow_color_name">
			<option selected value="{NAME_GLOW_V}" class="post">{NAME_GLOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{NAME_COST_THREE}</span></td>
</tr>
<tr>
	<td align="left" width="50%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="bold_name" {NAME_BOLD} />{L_EFFECTS_BOLD}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{NAME_COST_FOUR}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="italic_name" {NAME_ITALIC} />{L_EFFECTS_ITALIC}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{NAME_COST_FIVE}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="underline_name" {NAME_UNDERLINE} />{L_EFFECTS_UNDERLINE}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{NAME_COST_SIX}</span></td>
</tr>
<tr><th align="center" width="100%" colspan="3">{L_OPTIONS_SAYING}</th></tr>
<tr>
	<th width="10%"><span class="genmed">{L_EFFECT}</span></th>
	<th width="70%"><span class="genmed">{L_COLOR}</span></th>
	<th width="20%"><span class="genmed">{L_COST}</span></th>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="color_saying" {SAYING_COLOR} />{L_EFFECTS_COLOR}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="color_color_saying">
			<option selected value="{SAYING_COLOR_V}" class="post">{SAYING_COLOR_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{SAYING_COST_ONE}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="shadow_saying" {SAYING_SHADOW} />{L_EFFECTS_SHADOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="shadow_color_saying">
			<option selected value="{SAYING_SHADOW_V}" class="post">{SAYING_SHADOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{SAYING_COST_TWO}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="glow_saying" {SAYING_GLOW} />{L_EFFECTS_GLOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="glow_color_saying">
			<option selected value="{SAYING_GLOW_V}" class="post">{SAYING_GLOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{SAYING_COST_THREE}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="bold_saying" {SAYING_BOLD} />{L_EFFECTS_BOLD}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{SAYING_COST_FOUR}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="italic_saying" {SAYING_ITALIC} />{L_EFFECTS_ITALIC}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{SAYING_COST_FIVE}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="underline_saying" {SAYING_UNDERLINE} />{L_EFFECTS_UNDERLINE}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{SAYING_COST_SIX}</span></td>
</tr>
<tr><th align="center" width="100%" colspan="3">{L_OPTIONS_TITLE}</th></tr>
<tr>
	<th width="10%"><span class="genmed">{L_EFFECT}</span></th>
	<th width="70%"><span class="genmed">{L_COLOR}</span></th>
	<th width="20%"><span class="genmed">{L_COST}</span></th>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="color_title" {TITLE_COLOR} />{L_EFFECTS_COLOR}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="color_color_title">
			<option selected value="{TITLE_COLOR_V}" class="post">{TITLE_COLOR_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{TITLE_COST_ONE}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="shadow_title" {TITLE_SHADOW} />{L_EFFECTS_SHADOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="shadow_color_title">
			<option selected value="{TITLE_SHADOW_V}" class="post">{TITLE_SHADOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{TITLE_COST_TWO}</span></td>
</tr>
<tr>
	<td align="left" width="10%" class="row2"><span class="genmed"><input type="checkbox" name="glow_title" {TITLE_GLOW}>  {L_EFFECTS_GLOW}</span></td>
	<td align="center" width="50%" class="row2">
		<select name="glow_color_title">
			<option selected value="{TITLE_GLOW_V}" class="post">{TITLE_GLOW_S}</option>
			<option value="blue" class="post">{L_COLOR_BLUE}</option>
			<option value="green" class="post">{L_COLOR_GREEN}</option>
			<option value="black" class="post">{L_COLOR_BLACK}</option>
			<option value="white" class="post">{L_COLOR_WHITE}</option>
			<option value="yellow" class="post">{L_COLOR_YELLOW}</option>
			<option value="red" class="post">{L_COLOR_RED}</option>
			<option value="violet" class="post">{L_COLOR_VIOLET}</option>
			<option value="cyan" class="post">{L_COLOR_CYAN}</option>
		</select>
	</td>
	<td align="left" width="40%" class="row2"><span class="genmed">{TITLE_COST_THREE}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="bold_title" {TITLE_BOLD} />{L_EFFECTS_BOLD}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{TITLE_COST_FOUR}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="italic_title" {TITLE_ITALIC} />{L_EFFECTS_ITALIC}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{TITLE_COST_FIVE}</span></td>
</tr>
<tr>
	<td align="left" width="80%" class="row2" colspan="2"><span class="genmed"><input type="checkbox" name="underline_title" {TITLE_UNDERLINE} />{L_EFFECTS_UNDERLINE}</span></td>
	<td align="left" width="20%" class="row2"><span class="genmed">{TITLE_COST_SIX}</span></td>
</tr>
<tr>
	<td class="cat" colspan="3" align="center" width="100%">
		<input type="hidden" name="action" value="save_settings" />
		<input type="submit" value="{L_SUBMIT}" onclick="document.shop_settings.submit()" class="mainoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE overall_footer.tpl -->