<!-- INCLUDE overall_header.tpl -->

<form method="post" action="{S_MODE_ACTION}">
<div class="genmed" style="text-align: right; margin-top: 5px; margin-bottom: 5px;">
	{L_SELECT_SORT_METHOD}:&nbsp;{S_SORT_SELECT}&nbsp;&nbsp;{L_ORDER}&nbsp;{S_ORDER_SELECT}&nbsp;&nbsp;
	{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
</div>
</form>

{IMG_THL}{IMG_THC}<span class="forumlink">{L_MG_FEEDBACK}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw30px">#</th>
	<th>{L_DATE}</th>
	<th>{L_USERNAME_FROM}</th>
	<th>{L_USERNAME_TO}</th>
	<th>{L_FEEDBACK_RATING}</th>
	<th>{L_FEEDBACK_TRANSACTION}</th>
	<!-- IF S_ADMIN_ALLOWED -->
	<th>{L_EDIT}</th>
	<th>{L_DELETE}</th>
	<!-- ENDIF -->
</tr>
<!-- BEGIN feedback -->
<tr class="{feedback.CLASS} {feedback.CLASS}h" style="background-image: none;">
	<td class="{feedback.CLASS} row-center" style="background: none;" nowrap="nowrap">{feedback.ROW_NUMBER}</td>
	<td class="{feedback.CLASS} row-center" style="background: none;" nowrap="nowrap">{feedback.DATE}</td>
	<td class="{feedback.CLASS} row-center" style="background: none;">{feedback.USERNAME_FROM}</td>
	<td class="{feedback.CLASS} row-center" style="background: none;">{feedback.USERNAME_TO}</td>
	<td class="{feedback.CLASS} row-center" style="background: none;"><img src="{feedback.RATING_IMG}" alt="{feedback.RATING}" title="{feedback.RATING}" /></td>
	<td class="{feedback.CLASS} row-center-small" style="background: none;"><span class="topiclink"><a href="{feedback.U_TRANSACTION}">{L_FEEDBACK_TRANSACTION}</a></span></td>
	<!-- IF S_ADMIN_ALLOWED -->
	<!-- IF IS_PROSILVER -->
	<td class="{feedback.CLASS} row-center-small" style="background: none;"><ul class="profile-icons" style="padding-left: 10px;"><li class="edit-icon"><a href="{feedback.U_EDIT}"><span>{L_EDIT}</span></a></li></ul></td>
	<td class="{feedback.CLASS} row-center-small" style="background: none;"><ul class="profile-icons" style="padding-left: 10px;"><li class="delete-icon"><a href="{feedback.U_DELETE}"><span>{L_DELETE}</span></a></li></ul></td>
	<!-- ELSE -->
	<td class="{feedback.CLASS} row-center-small" style="background: none;"><span class="post-buttons-single">{feedback.EDIT}</span></td>
	<td class="{feedback.CLASS} row-center-small" style="background: none;"><span class="post-buttons-single">{feedback.DELETE}</span></td>
	<!-- ENDIF -->
	<!-- ENDIF -->
</tr>
<!-- END feedback -->
<!-- BEGIN no_feedback -->
<tr><td class="row1 row-center" colspan="8" nowrap="nowrap">{L_NO_FEEDBACK}</td></tr>
<!-- END no_feedback -->
<tr><td class="spaceRow" colspan="8"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="8">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<br /><br /><br />

<!-- IF S_ADMIN_ALLOWED -->
<!-- <center><span class="gensmall"><b><a href="{U_FEEDBACK_ADD}">{L_FEEDBACK_ADD}</a></b></span></center> -->
<!-- ENDIF -->

<!-- INCLUDE overall_footer.tpl -->