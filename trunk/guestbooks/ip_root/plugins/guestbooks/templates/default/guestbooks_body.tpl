<!-- INCLUDE overall_header.tpl -->

<!-- IF S_INPUT_ALLOWED -->
<div class="css-button-wrap">
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="images/cms/b_add.png" alt="{L_GUESTBOOK_ADD}" title="{L_GUESTBOOK_ADD}" />&nbsp;<b>{L_GUESTBOOK_ADD}</b>&nbsp;</div></div>
</div>
<!-- ENDIF -->

<!-- IF NO_GUESTBOOKS -->

<br clear="all" />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_GUESTBOOKS_PAGE}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr><th>{L_MG_GUESTBOOKS}</th></tr>
<tr><td class="row1 row-center">{L_NO_GUESTBOOKS}</td></tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- ELSE -->

<form method="post" action="{S_MODE_ACTION}">
<div class="genmed" style="text-align: right; margin-top: 5px; margin-bottom: 5px;">
	{L_SORT_ORDER}:&nbsp;{S_SORT_ORDER_SELECT}&nbsp;&nbsp;{L_SORT_DIR}:&nbsp;{S_SORT_DIR_SELECT}&nbsp;&nbsp;
	{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
</div>
</form>
<br clear="all" />

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb" width="100%" cellspacing="0" cellpadding="0">
<tr>
	<th width="30">#</th>
	<th>{L_GUESTBOOKS_TITLE}</th>
	<th>{L_GUESTBOOKS_STATUS}</th>
	<th>{L_GUESTBOOKS_TIME_UPDATE}</th>
	<th>{L_MANAGEMENT}</th>
</tr>
<!-- BEGIN guestbooks -->
<tr class="{guestbooks.CLASS} {guestbooks.CLASS}h" style="background-image: none;">
	<td class="{guestbooks.CLASS} row-center" style="background: none;" nowrap="nowrap"><span class="genmed"><b>{guestbooks.ROW_NUMBER}</b></span></td>
	<td class="{guestbooks.CLASS}" style="background: none; line-height: 160%;"><span class="genmed"><b><a href="{guestbooks.U_VIEW}">{guestbooks.TITLE}</a></b></span><br /><div class="gensmall">{guestbooks.POSTER}&nbsp;&bull;&nbsp;{guestbooks.DATE}</div></td>
	<td class="{guestbooks.CLASS} row-center" style="background: none;" nowrap="nowrap"><span class="genmed">{guestbooks.STATUS}</span></td>
	<td class="{guestbooks.CLASS} row-center" style="background: none;" nowrap="nowrap"><div class="gensmall">{guestbooks.DATE_UPDATE}<br />{guestbooks.LAST_POSTER}</div></td>
	<td class="{guestbooks.CLASS} row-center" style="background: none;">{guestbooks.S_VIEW}<!-- IF S_EDIT_ALLOWED -->&nbsp;{guestbooks.S_EDIT}<!-- ENDIF --><!-- IF S_ADMIN_ALLOWED -->&nbsp;{guestbooks.S_DELETE}<!-- ENDIF --></td>
</tr>
<!-- END guestbooks -->
<tr><td class="spaceRow" colspan="5"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="5">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<!-- IF S_INPUT_ALLOWED -->
<div class="css-button-wrap">
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="images/cms/b_add.png" alt="{L_GUESTBOOK_ADD}" title="{L_GUESTBOOK_ADD}" />&nbsp;<b>{L_GUESTBOOK_ADD}</b>&nbsp;</div></div>
</div>
<!-- ENDIF -->

<br clear="all" />
<br /><br />

<!-- ENDIF -->

<!-- INCLUDE overall_footer.tpl -->