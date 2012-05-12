<!-- INCLUDE simple_header.tpl -->

<!-- BEGIN chat -->
<!-- BEGIN history -->
{IMG_THL}{IMG_THC}<span class="forumlink">{chat.history.TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<td class="row1">
	<form method="post" name="refresh" action="activity_popup.php?mode=chat&amp;action=view">
		<input type="submit" class="liteoption" value="{chat.history.REFRESH}" onchange="document.refresh.submit()" />
	</form>
	</td>
	<td class="row1" width="100%">
	<form name="history_chat">
		<select name="history" onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
			<option value="" class="post">{DEFAULT}</option>
		<!-- BEGIN dates -->
			<option value="activity_popup.php?mode=chat&amp;action=history&amp;history={chat.history.dates.HISTORY}" class="post">{chat.history.dates.HISTORY}</option>
		<!-- END dates -->
		</select>
	<noscript><input type="submit" value="Go" /></noscript>
	</form>
	</td>
</tr>
{chat.history.CHAT}
<tr><th width="100%" colspan="2">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END history -->
<!-- BEGIN view -->
{IMG_THL}{IMG_THC}<span class="forumlink">Chat</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th width="100%" colspan="3">{chat.view.TITLE}</th></tr>
<tr>
	<td class="row1" width="60%">
	<form method="post" name="add_chat" action="activity_popup.php?mode=chat">
		<input type="hidden" value="add" name="action">
		<input type="text" value="" size="30" name="msg" class="post">
		<input type="submit" class="liteoption" value="{chat.view.SUBMIT}" onchange="document.add_chat.submit()">
	</form>
	</td>
	<td class="row1 row-center" width="15%">
	<form method="post" name="refresh" action="activity_popup.php?mode=chat&amp;action=view">
		<input type="submit" class="liteoption" value="{chat.view.REFRESH}" onchange="document.refresh.submit()" />
	</form>
	</td>
	<td class="row1 row-center" width="15%">
	<form name="history_chat">
		<select name="history" onchange="if(options[selectedIndex].value)window.location.href=(options[selectedIndex].value)">
			<option value="" class="post">{DEFAULT}</option>
		<!-- BEGIN history -->
			<option value="activity_popup.php?mode=chat&amp;action=history&history={chat.view.history.HISTORY}" class="post">{chat.view.history.HISTORY}</option>
		<!-- END history -->
		</select>
	<noscript><input type="submit" value="Go" /></noscript>
	</form>
	</td>
</tr>
{chat.view.CHAT}
<tr><th width="100%" colspan="3">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END view -->
<!-- END chat -->
<!-- BEGIN rate -->
<!-- BEGIN main -->
<form method="post" name="sub_rate" action="activity_popup.php?mode=rate">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_RATE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2" width="100%">{rate.main.TITLE}</th></tr>
<tr>
	<td class="row2" width="50%" nowrap="nowrap"><span class="post-text">{rate.main.CHOICES}</span></td>
	<td align="center" valign="middle" width="50%" class="row2">
		<select name="rating">
			<option selected value="">{rate.main.DEFAULT_RATE}</option>
			<option value="1">1</option>
			<option value="2">2</option>
			<option value="3">3</option>
			<option value="4">4</option>
			<option value="5">5</option>
			<option value="6">6</option>
			<option value="7">7</option>
			<option value="8">8</option>
			<option value="9">9</option>
			<option value="10">10</option>
		</select>
	</td>
</tr>
<tr>
	<th colspan="2">
		<input type="hidden" name="action" value="submit_rating" />
		<input type="hidden" name="game" value="{rate.main.GAME}" />
		<input class="mainoption" type="submit" value="{rate.main.SUBMIT}" onchange="document.sub_rate.submit()" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>
<!-- END main -->
<!-- END rate -->

<!-- BEGIN comments -->
<!-- BEGIN main -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="25%" align="center">{comments.main.MAIN_NAME}</th>
		<th width="15%" align="center">{comments.main.MAIN_LEFT}</th>
		<th width="20%" align="center">{comments.main.MAIN_CENTER1}</th>
		<th width="20%" align="center">{comments.main.MAIN_CENTER2}</th>
		<th width="20%" align="center">{comments.main.MAIN_RIGHT}</th>
</tr>
<tr>
	<td class="row2 row-center">{comments.main.MAIN_IMAGE}</td>
	<td class="row2 row-center"><span class="post-text">{comments.main.TROPHY_HOLDER}</span></td>
	<td class="row2"><span class="post-text">{comments.main.TROPHY_COMMENT}</span></td>
	<td class="row2 row-center"><span class="post-text">{comments.main.TROPHY_SCORE}</span></td>
	<td class="row2 row-center"><span class="post-text">{comments.main.TROPHY_DATE}</span></td>
</tr>
<tr><td class="spaceRow" colspan="5"><img src="{SPACER}" width="7%" height="3" alt="" /></td></tr>
<!-- END main -->
<!-- BEGIN comment -->
<tr>
	<td class="row2 row-center"><span class="post-text">{comments.comment.POS}</span></td>
	<td class="row2 row-center"><span class="post-text">{comments.comment.TROPHY_HOLDER}</span></td>
	<td class="row2"><span class="post-text">{comments.comment.TROPHY_COMMENT}</span></td>
	<td class="row2 row-center"><span class="post-text">{comments.comment.TROPHY_SCORE}</span></td>
	<td class="row2 row-center"><span class="post-text">{comments.comment.TROPHY_DATE}</span></td>
</tr>
<!-- END comment -->
<!-- BEGIN main -->
<tr><th width="100%" colspan="5">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END main -->


	<!-- BEGIN post_comment -->
<form method="post" action="{comments.post_comment.POST_LINK}">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_COMMENTS}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th width="100%">{comments.post_comment.POST_TITLE}</th></tr>
<tr><td align="center"><br />{comments.post_comment.POST_IMAGE}<br /><br /></td></tr>
<tr>
	<td class="row2 row-center">
		<span class="genmed">
		{comments.post_comment.POST_LENGTH}
		<br />
		<input type="text" name="comment" value="" size="38" />
		<input type="hidden" value="posting_comment" name="action" />
		<input type="hidden" value="{comments.post_comment.POST_GAME}" name="comment_game_name" />
		<input type="submit" value="{comments.post_comment.POST_SUBMIT}" class="liteoption" />
		</span>
	</td>
</tr>
<tr><td class="cat" colspan="4"></td></tr>
</table>
</form>
	<!-- END post_comment -->
<!-- END comments -->

<!-- BEGIN challenge -->
{IMG_THL}{IMG_THC}<span class="forumlink">{challenge.TITLE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><td class="row2 row-center" valign="top" width="100%"><div class="post-text">{challenge.MSG}</div></td></tr>
<tr><th width="100%">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END challenge -->

<!-- BEGIN info -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_INFO}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th colspan="2" align="center">{info.L_TITLE}</th></tr>
<tr>
	<td class="row1 row-center" width="50%" ><img src ="./{info.PATH}/{info.NAME}.gif" align="middle"></td>
	<td class="row1"><span class="gen">{info.DESC}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_CATEGORY}</td>
	<td class="row1"><span class="gen">{info.CATEGORY}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_PLAYED}</span></td>
	<td class="row1"><span class="gen">{info.PLAYED}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_COST}</span></td>
	<td class="row1"><span class="gen">{info.COST}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_BORROWED}</span></td>
	<td class="row1"><span class="gen">{info.BORROWED}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_BONUS}</span></td>
	<td class="row1"><span class="gen">{info.BONUS}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_PLAYER}</span></td>
	<td class="row1"><span class="gen">{info.BEST_PLAYER}</span></td>
</tr>
<tr>
	<td class="row2" align="left"><span class="gen">{info.L_SCORE}</span></td>
	<td class="row1"><span class="gen">{info.BEST_SCORE}</span></td>
</tr>
<tr><th colspan="2">{info.L_TITLE_2}</th></tr>
<tr><td class="row2" colspan="2"><span class="gen">{info.INSTRUCTIONS}</span></td></tr>
<tr><th width="100%" colspan="2">&nbsp;</th></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<!-- END info -->

<!-- INCLUDE simple_footer.tpl -->