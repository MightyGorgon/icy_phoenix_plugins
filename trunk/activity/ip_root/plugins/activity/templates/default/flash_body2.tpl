<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="{S_CONTENT_DIRECTION}">
<head>

<meta http-equiv="content-type" content="text/html; charset={S_CONTENT_ENCODING}" />
<meta http-equiv="content-style-type" content="text/css" />
{META}
{META_TAG}
{NAV_LINKS}
<title>{BEST_USER_SCORE}</title>
<link rel="shortcut icon" href="{FULL_SITE_PATH}images/favicon.ico" />
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}style_{CSS_COLOR}.css" type="text/css" />
<!-- BEGIN css_style_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_TPL_PATH}{css_style_include.CSS_FILE}" type="text/css" />
<!-- END css_style_include -->
<!-- BEGIN css_include -->
<link rel="stylesheet" href="{FULL_SITE_PATH}{T_COMMON_TPL_PATH}{css_include.CSS_FILE}" type="text/css" />
<!-- END css_include -->

<!-- INCLUDE overall_inc_header_js.tpl -->

<script language="JavaScript" type="text/javascript">
<!--
window.status = "{BEST_USER_SCORE}";
// -->
</script>

</head>
<body oncontextmenu="return false;">

<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}" class="nav">{L_HOME}</a>{NAV_SEP}<a href="{U_ACTIVITY}" class="nav">{L_ACTIVITY}</a>{NAV_SEP}<a href="#top" class="nav-current">{L_GAME}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{CURRENT_TIME}
		</div>
		&nbsp;
	</div>
</div>
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td class="row-header"><span>{L_GAME}</span></td></tr>
<tr>
	<td>
		<table width="760" align="center" cellspacing="0" cellpadding="0" border="0">
		<tr>
			<td width="565" valign="top">
				<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0" id="inetangel" name="activitygame" width="{WIDTH}" height="{HEIGHT}">
				<param name="movie" value="{PATH}{SWFNAME}">
				<param name="quality" value="high">
				<param name="menu" value="false">
				<embed name="activitygame" src="{PATH}{SWFNAME}" width="{WIDTH}" height="{HEIGHT}" quality="high" menu="false" swliveconnect="true" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash"></embed>
				</object>
			</td>
			<td width="190" valign="top">
				<table class="forumline" width="180" align="center" cellspacing="0" cellpadding="0" border="0">
				<tr><th colspan="2"><img src="{T_IMAGE}" alt="" />&nbsp;&nbsp;{T_HOLDER}&nbsp;&nbsp;<img src="{T_IMAGE}" alt="" /></th></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="row1 row-center"><span class="gensmall"><b>{T_HOLDER_1}</b></span></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="row1"><span class="gensmall">{T_LINK}<br />{T_LINK_1}</span></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="row1 row-center"><span class="gensmall">{T_DATE_1}</span></td></tr>
				<tr><td class="row1 row-center"><span class="gensmall">{T_DATE}</span></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="row1 row-center"><span class="gensmall">{T_SCORE_1}</span></td></tr>
				<tr><td class="row1 row-center"><span class="gensmall"><b>{T_SCORE}</b></span></td></tr>
				<tr><th colspan="2" align="center" valign="middle"><img src="{T_IMAGE}" alt="" />&nbsp;&nbsp;{NAME}&nbsp;&nbsp;<img src="{T_IMAGE}" alt="" /></th></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="row1 row-center"><b>{U_PLAY_POPUP}</b></td></tr>
				</table>
				<br /><br />
				<table class="forumline" width="180" align="center" cellspacing="0" cellpadding="0" border="0">
				<tr><th colspan="2" align="center" valign="middle">{R_TITLE}</th></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<!-- BEGIN runner -->
				<tr>
					<td class="row1" width="90"><span class="gensmall">&nbsp;{runner.R_U_NAME}</span></td>
					<td class="row1 row-center" width="90"><span class="gensmall">{runner.R_U_SCORE}</span></td>
				</tr>
				<!-- END runner -->
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td class="cat" colspan="2">&nbsp;</td></tr>
				</table>
			</td>
		</tr>
		</table>
	</td>
</tr>
</table>

</body>
</html>