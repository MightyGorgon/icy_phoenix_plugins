<!-- INCLUDE overall_header.tpl -->

<!-- IF S_INPUT_ALLOWED -->
<div class="css-button-wrap">
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="{IMG_CMS_ICON_ADD}" alt="{L_BLOG_ADD}" title="{L_BLOG_ADD}" />&nbsp;<b>{L_BLOG_ADD}</b>&nbsp;</div></div>
</div>
<!-- ENDIF -->

<!-- IF NO_BLOGS -->

<br class="clear" />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BLOGS_PAGE}</span>{IMG_THR}<table class="forumlinenb">
<tr><th>{L_MG_BLOGS}</th></tr>
<tr><td class="row1 row-center">{L_NO_BLOGS}</td></tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- ELSE -->

<form method="post" action="{S_MODE_ACTION}">
<div class="genmed" style="text-align: right; margin-top: 5px; margin-bottom: 5px;">
	{L_SORT_ORDER}:&nbsp;{S_SORT_ORDER_SELECT}&nbsp;&nbsp;{L_SORT_DIR}:&nbsp;{S_SORT_DIR_SELECT}&nbsp;&nbsp;
	{S_HIDDEN_FIELDS}<input type="submit" name="submit" value="{L_SUBMIT}" class="liteoption" />
</div>
</form>
<br class="clear" />

{IMG_THL}{IMG_THC}<span class="forumlink">{L_PAGE_NAME}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw30px">#</th>
	<th>{L_BLOGS_TITLE}</th>
	<th>{L_BLOGS_STATUS}</th>
	<th>{L_BLOGS_TIME_UPDATE}</th>
	<th>{L_MANAGEMENT}</th>
</tr>
<!-- BEGIN blogs -->
<tr class="{blogs.CLASS} {blogs.CLASS}h" style="background-image: none;">
	<td class="{blogs.CLASS} row-center tdnw" style="background: none;"><span class="genmed"><b>{blogs.ROW_NUMBER}</b></span></td>
	<td class="{blogs.CLASS}" style="background: none; line-height: 160%;"><span class="genmed"><b><a href="{blogs.U_VIEW}">{blogs.TITLE}</a></b></span><br /><div class="gensmall">{blogs.POSTER}&nbsp;&bull;&nbsp;{blogs.DATE}</div></td>
	<td class="{blogs.CLASS} row-center tdnw" style="background: none;"><span class="genmed">{blogs.STATUS}</span></td>
	<td class="{blogs.CLASS} row-center tdnw" style="background: none;"><div class="gensmall">{blogs.DATE_UPDATE}<br />{blogs.LAST_POSTER}</div></td>
	<td class="{blogs.CLASS} row-center" style="background: none;">{blogs.S_VIEW}<!-- IF S_EDIT_ALLOWED -->&nbsp;{blogs.S_EDIT}<!-- ENDIF --><!-- IF S_ADMIN_ALLOWED -->&nbsp;{blogs.S_DELETE}<!-- ENDIF --></td>
</tr>
<!-- END blogs -->
<tr><td class="spaceRow" colspan="5"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="5">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<!-- IF S_INPUT_ALLOWED or S_ADMIN_ALLOWED -->
<div class="css-button-wrap">

<!-- IF S_INPUT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="{IMG_CMS_ICON_ADD}" alt="{L_BLOG_ADD}" title="{L_BLOG_ADD}" />&nbsp;<b>{L_BLOG_ADD}</b>&nbsp;</div></div>
<!-- ENDIF -->

<!-- IF S_ADMIN_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_BLOGS_RESYNC}'"><img src="{IMG_CMS_ICON_REFRESH}" alt="{L_BLOGS_RESYNC}" title="{L_BLOGS_RESYNC}" />&nbsp;<b>{L_BLOGS_RESYNC}</b>&nbsp;</div></div>
<!-- ENDIF -->

</div>
<!-- ENDIF -->

<br class="clear" />
<br /><br />

<!-- ENDIF -->

<!-- INCLUDE overall_footer.tpl -->