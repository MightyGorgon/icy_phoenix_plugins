<!-- BEGIN main -->
<table width="100%" align="center" cellspacing="0" cellpadding="0" border="0">
<tr>
	<th width="25%" align="center">{main.MAIN_NAME}</th>
	<th width="15%" align="center">{main.MAIN_LEFT}</th>
	<th width="20%" align="center">{main.MAIN_CENTER1}</th>
	<th width="20%" align="center">{main.MAIN_CENTER2}</th>
	<th width="20%" align="center">{main.MAIN_RIGHT}</th>
</tr>
<tr>
	<td class="row2 row-center">{main.MAIN_IMAGE}</td>
	<td class="row2 row-center"><span class="genmed">{main.TROPHY_HOLDER}</span></td>
	<td class="row2"><span class="genmed">{main.TROPHY_COMMENT}</span></td>
	<td class="row2"><span class="genmed">{main.TROPHY_SCORE}</span></td>
	<td class="row2"><span class="genmed">{main.TROPHY_DATE}</span></td>
</tr>
<tr><th colspan="5">&nbsp;</th></tr>
<!-- END main -->
<!-- BEGIN comments -->
<tr>
	<td class="{comments.ROW} row-center"><span class="genmed">{comments.POS}</span></td>
	<td class="{comments.ROW} row-center"><span class="genmed">{comments.TROPHY_HOLDER}</span></td>
	<td class="{comments.ROW}"><span class="genmed">{comments.TROPHY_COMMENT}</span></td>
	<td class="{comments.ROW}"><span class="genmed">{comments.TROPHY_SCORE}</span></td>
	<td class="{comments.ROW}"><span class="genmed">{comments.TROPHY_DATE}</span></td>
</tr>
<!-- END comments -->
<!-- BEGIN main -->
</table>
<!-- END main -->

<!-- BEGIN post_comment -->
<form method="post" action="{post_comment.POST_LINK}">
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0"><tr><th width="100%">{post_comment.POST_TITLE}</th></tr></table>
<br />
<table width="100%" align="center" border="0"><tr><td align="center">{post_comment.POST_IMAGE}</td></tr></table>
<br />
<table class="forumline" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td class="row2 row-center">
		<span class="genmed">{post_comment.POST_LENGTH}</span><br />
		<span class="genmed">
			<input type="text" name="comment" value="" />
			<input type="hidden" value="posting_comment" name="mode" />
			<input type="hidden" value="{post_comment.POST_GAME}" name="comment_game_name" />
		</span>
	</td>
</tr>
<tr><td class="row2 row-center"><input type="submit" value="{post_comment.POST_SUBMIT}" class="liteoption" /></td></tr>
<tr><td class="cat" colspan="4">&nbsp;</td></tr>
</table>
</form>
<!-- END post_comment -->