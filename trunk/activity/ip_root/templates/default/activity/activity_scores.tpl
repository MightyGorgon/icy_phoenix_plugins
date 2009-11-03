<div class="forumline nav-div">
	<p class="nav-header">
		<a href="{U_PORTAL}" class="nav">{L_HOME}</a>{NAV_SEP}<a href="{U_ACTIVITY}" class="nav-current">{L_ACTIVITY}</a>
	</p>
	<div class="nav-links">
		<div class="nav-links-left">
			{CURRENT_TIME}
		</div>
		&nbsp;
	</div>
</div>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_HIGHSCORE} {DASH} {TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="40">#</th>
	<th align="center" width="{WIDTH1}">{L_HIGHSCORE} {DASH} {TITLE}</th>
	<th align="center" width="{WIDTH2}">{L_SCORE}</th>
	<th align="center" width="{WIDTH3}">{L_PLAYED}</th>
</tr>

<!-- BEGIN scores -->
<tr>
	<td class="{scores.ROW_CLASS} row-center"><span class="gen">{scores.POS}</span></td>
	<td class="{scores.ROW_CLASS}" align="left">  <span class="gen">{scores.NAME}</span></td>
	<td class="{scores.ROW_CLASS}" align="{scores.ALIGN}"><span class="gen">{scores.SCORE}</span></td>
	<td class="{scores.ROW_CLASS} row-center"><span class="gen">{scores.DATE}</span></td>
</tr>
<!-- END scores -->

<!-- BEGIN scores_stats -->
<tr><td class="{scores.ROW_CLASS}" align="left" colspan="4"><span class="gen">{scores.scores_stats.STATS}</span></td></tr>
<!-- END scores_stats -->
<tr><th width="100%" colspan="4">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}