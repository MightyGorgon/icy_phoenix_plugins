<head>
<script type="text/javascript">
function showmalescreate()
{
	document.images.male_imgs.src='./{P_ACTIVITY_MOD_PATH}amp_characters/male/' + document.creation_page.mchar.options[document.creation_page.mchar.selectedIndex].value
}

function showfemalescreate()
{
	document.images.female_imgs.src='./{P_ACTIVITY_MOD_PATH}amp_characters/female/' + document.creation_page.fchar.options[document.creation_page.fchar.selectedIndex].value
}

function showmalesedit()
{
	document.images.male_imgs.src='./{P_ACTIVITY_MOD_PATH}amp_characters/male/' + document.edit_page.mchar.options[document.edit_page.mchar.selectedIndex].value
}

function showfemalesedit()
{
	document.images.female_imgs.src='./{P_ACTIVITY_MOD_PATH}amp_characters/female/' + document.edit_page.fchar.options[document.edit_page.fchar.selectedIndex].value
}
</script>
</head>

<!-- BEGIN create_char -->
<form name="creation_page" method="post" action="activity_char.php">
{IMG_THL}{IMG_THC}<span class="forumlink">{CHAR_OPTIONS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="50%">{CHAR_OPTIONS_M}</th>
	<th width="50%">{CHAR_OPTIONS_F}</th>
</tr>
<tr>
	<td class="row2 row-center">
		<center>
		<select name="mchar" onchange="showmalescreate()">
			<!-- BEGIN males -->
			<option value="{create_char.males.VALUES}" class="post">{create_char.males.NAMES}</option>
			<!-- END males -->
			</select>
			<br /><br />
		<!-- BEGIN mdefault -->
		<img src="./{P_ACTIVITY_MOD_PATH}amp_characters/male/{create_char.mdefault.VALUE}" name="male_imgs" />
		<!-- END mdefault -->
		</center>
	</td>
	<td class="row2 row-center">
		<center>
		<select name="fchar" onchange="showfemalescreate()">
			<!-- BEGIN females -->
			<option value="{create_char.females.VALUES}" class="post">{create_char.females.NAMES}</option>
			<!-- END females -->
		</select>
			<br /><br />
		<!-- BEGIN fdefault -->
		<img src="./{P_ACTIVITY_MOD_PATH}amp_characters/female/{create_char.fdefault.VALUE}" name="female_imgs" />
		<!-- END fdefault -->
		</center>
	</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_NAME}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" class="post" value="" name="name" size="25" /></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_TITLE}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" class="post" value="" name="title" size="25" /></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_GENDER}</span></td>
	<td class="row2 row-center" width="50%"><span class="genmed"><input type="radio" value="1" name="gender">{CHAR_GENDER_M}<input type="radio" value="2" name="gender" />{CHAR_GENDER_F}</span></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_AGE}</span><br /><span class="gensmall">{CHAR_AGE_EXP}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" value="" size="10" name="age" class="post" /></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_INTRESTS}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" value="" size="40" name="intrests" class="post" /></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_SAYING}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" class="post" value="" name="saying" size="40" /></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_FROM}</span></td>
	<td class="row2 row-center" width="50%"><input type="text" value="" size="40" name="from" class="post" /></td>
</tr>
<tr>
	<td align="center" width="50%" class="cat" colspan="2">
		<input type="hidden" value="save_char" name="action" />
		<input type="hidden" value="create_char" name="mode" />
		<input type="submit" value="{CHAR_SUBMIT}" class="mainoption" onclick="document.creation_page.submit()" />
	</td>
</tr>
</table>
</form>
<!-- END create_char -->

<!-- BEGIN edit_char -->
<table align="center" width="100%"><tr><td align="center" width="100%">{USERS}</td></tr></table>
<!-- BEGIN view -->

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row3" width="100%"><span class="genmed">{edit_char.view.EDIT_EXP}</span></td></tr>
</table>

<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row3" width="100%"><span class="genmed">{edit_char.view.CHAR_PROFILE}</span></td></tr>
</table>

<!-- END view -->
<!-- BEGIN edit -->
<form name="edit_page" method="post" action="activity_char.php">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row3" width="100%"><span class="genmed">{edit_char.edit.CHR_EDIT_EXP}</span></td></tr>
</table>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><th align="center" width="100%" colspan="2">{CHAR_OPTIONS}</th></tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_OPTIONS_M}</span></td>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_OPTIONS_F}</span></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%" valign="top">
	<!-- BEGIN off -->
		<span class="genmed">{edit_char.edit.off.ERROR}</span>
	<!-- END off -->
	<!-- BEGIN on -->
		<select name="mchar" onchange="showmalesedit()">
	<!-- END on -->
			<!-- BEGIN males -->
			<option value="{edit_char.edit.males.VALUES}" class="post">{edit_char.edit.males.NAMES}</option>
			<!-- END males -->
	<!-- BEGIN on -->
		</select>
	<!-- END on -->
			<br /><br />
		<!-- BEGIN mdefault -->
		<img src="./{P_ACTIVITY_MOD_PATH}amp_characters/male/{edit_char.edit.mdefault.VALUE}" name="male_imgs" />
		<!-- END mdefault -->
	</td>
	<td class="row2 row-center" width="50%" valign="top">
	<!-- BEGIN off -->
		<span class="genmed">{edit_char.edit.off.ERROR}</span>
	<!-- END off -->
	<!-- BEGIN on -->
		<select name="fchar" onchange="showfemalesedit()">
	<!-- END on -->
			<!-- BEGIN females -->
			<option value="{edit_char.edit.females.VALUES}" class="post">{edit_char.edit.females.NAMES}</option>
			<!-- END females -->
	<!-- BEGIN on -->
		</select>
	<!-- END on -->
			<br /><br />
		<!-- BEGIN fdefault -->
		<img src="./{P_ACTIVITY_MOD_PATH}amp_characters/female/{edit_char.edit.fdefault.VALUE}" name="female_imgs" />
		<!-- END fdefault -->
	</td>
</tr>
<!-- BEGIN on -->
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_CHANGE_CHECK}</span></td>
	<td class="row2 row-center" width="50%">
		<span class="genmed"><input type="radio" name="change" value="1"> {CHAR_CHANGE_CHECK_Y}&nbsp;<input type="radio" name="change" value="2" />{CHAR_CHANGE_CHECK_N}</span>
	</td>
</tr>
<!-- END on -->
<!-- BEGIN values -->
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_NAME}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_NAME}</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_TITLE}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_TITLE}</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_GENDER}</span></td>
	<td class="row2 row-center" width="50%"><span class="genmed">{edit_char.edit.values.CHR_CHNG_GENDER}</span></td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_AGE}</span><br /><span class="gensmall">{CHAR_AGE_EXP}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_AGE}</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_INTRESTS}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_INTRESTS}</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_SAYING}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_SAYING}</td>
</tr>
<tr>
	<td class="row2 row-center" width="50%"><span class="genmed">{CHAR_FROM}</span></td>
	<td class="row2 row-center" width="50%">{edit_char.edit.values.CHR_CHNG_FROM}</td>
</tr>
<tr>
	<td class="cat" colspan="2">
		<input type="hidden" value="save_char" name="action" />
		<input type="hidden" value="edit_char" name="mode" />
		<input type="submit" value="{CHAR_SAVE}" class="mainoption" onclick="document.edit_page.submit()" />
		</form>
		<form name="delete_char" action="activity_char.php" method="get">
		<input type="hidden" name="mode" value="del_char" />
		<input type="submit" value="{CHAR_DELETE}" class="mainoption" onclick="document.delete_char.submit()" />
		</form>
	</td>
</tr>
</table>
<!-- END values -->
<!-- END edit -->
<!-- END edit_char -->

<!-- BEGIN profile_char -->
<!-- BEGIN data -->
<table width="100%" align="center" valign="top">
<tr><td align="left" width="100%"><span class="genmed"><a href="{U_PORTAL}" class="nav">{L_HOME}</a> :: <a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a></span></td></tr>
</table>
{profile_char.data.CHAR_PROFILE}
<!-- END data -->
<!-- END profile_char -->