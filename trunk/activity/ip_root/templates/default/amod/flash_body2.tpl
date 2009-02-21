<html>
<head>
<script language="JavaScript" type="text/javascript">
<!--
window.status="{BEST_USER_SCORE}"
// -->
</script>
</head>
<title>{BEST_USER_SCORE}</title>
<body oncontextmenu="javascript:return false">
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
		<table width="760" align="center" valign="top" cellspacing="0" cellpadding="0" border="0">
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
				<table class="forumline" width="180" align="center" valign="top" cellspacing="0" cellpadding="0" border="0">
				<tr><th colspan="2" align="center" valign="middle"><img src="{T_IMAGE}" alt="" />&nbsp;&nbsp;{T_HOLDER}&nbsp;&nbsp;<img src="{T_IMAGE}" alt="" /></th></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td align="center"><div class="post-text"><b>{T_HOLDER_1}</b></div></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td align="left"><div class="post-text">{T_LINK}<br />{T_LINK_1}</div></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td align="center"><div class="post-text">{T_DATE_1}</div></td></tr>
				<tr><td align="center"><div class="post-text">{T_DATE}</div></td></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<tr><td align="center"><div class="post-text">{T_SCORE_1}</span></td></tr>
				<tr><td class="row1 row-center"><div class="post-text"><b>{T_SCORE}</b></div></td></tr>
				<tr><th colspan="2" align="center" valign="middle"><img src="{T_IMAGE}" alt="" />&nbsp;&nbsp;{NAME}&nbsp;&nbsp;<img src="{T_IMAGE}" alt="" /></th></tr>
				</table>
				<br /><br />
				<table class="forumline" width="180" align="center" valign="bottom" cellspacing="0" cellpadding="0" border="0">
				<tr><th colspan="2" align="center" valign="middle">{R_TITLE}</th></tr>
				<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" height="3" alt="" /></td></tr>
				<!-- BEGIN runner -->
				<tr>
					<td class="row1" width="90"><div class="post-text">&nbsp;{runner.R_U_NAME}</div></td>
					<td class="row1 row-center" width="90"><div class="post-text">{runner.R_U_SCORE}</span></td>
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