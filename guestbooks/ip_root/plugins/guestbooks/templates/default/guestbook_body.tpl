<!-- INCLUDE overall_header.tpl -->

<br class="clear" />
<div style="margin: 0 auto; padding: 10px; text-align: left;">
<div class="topic-title-hide-flow-header" style="text-align: left;"><h2><a href="{U_GUESTBOOK_TITLE}" style="text-decoration: none;">{L_GUESTBOOK_TITLE}</a></h2></div>
<div class="post-text">{GUESTBOOK_DESCRIPTION}</div><br /><br /><br />
</div>

<br class="clear" /><br />
<div style="margin-top: 60px;">&nbsp;</div>
<div style="margin: 0 auto; padding-left: 60px; padding-right: 60px;"><hr /></div>
<br class="clear" /><br />

<!-- IF NO_GUESTBOOK_POSTS -->

<i>{L_NO_GUESTBOOK_POSTS}</i><br /><br />

<!-- ELSE -->

<div class="gen" style="text-align: left;"><b>{L_GUESTBOOKS_POST_POSTS}</b></div>
<br />
<!-- BEGIN posts -->
<div class="forumlinenb" style="margin: 0 auto; width: 720px;">
<div class="{posts.CLASS}h" style="padding: 10px; text-align: left;">
<div><!-- IF posts.S_MOD --><span style="float: right; text-align: right;">{posts.S_EDIT}&nbsp;{posts.S_DELETE}</span><!-- ENDIF --><div id="{GUESTBOOK_POST_ID_VAR}{posts.POST_ID}" class="post-details" style="margin: 0px;">{posts.POSTED_BY}</div></div><br /><br />
<div class="post-text">{posts.MESSAGE}</div><br /><br /><br />
<br class="clear" /><br /><br />
</div>
</div>
<br class="clear" />
<!-- END posts -->

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<!-- ENDIF -->

<!-- IF POST_POST_ALLOWED -->
<br class="clear" /><br /><br /><br /><br />
<div style="width: 680px; text-align: left;">
<span class="gen"><b>{L_GUESTBOOKS_POST_POST}</b></span><br /><br />
<form name="input_form" method="post" action="{S_MODE_ACTION}">
<table>
<!-- BEGIN field -->
<tr>
	<td width="30%" style="vertical-align: top; padding: 5px;"><span class="gen"><b>{field.L_NAME}</b></span><!-- IF field.L_EXPLAIN --><br /><div class="gensmall">{field.L_EXPLAIN}</div><!-- ENDIF --></td>
	<td class="tdnw" style="padding: 5px;"><!-- IF field.S_BBCB -->{BBCB_MG}<!-- ENDIF --><div class="gen">{field.INPUT}</div></td>
</tr>
<!-- END field -->
<!-- IF S_CAPTCHA -->
<tr><td class="row-center" colspan="2"><!-- INCLUDE captcha_include.tpl --></td></tr>
<!-- ENDIF -->
<tr><td class="row-center" colspan="2">{S_HIDDEN_FIELDS}<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" /></td></tr>
</table>
</form>
</div>
<!-- ENDIF -->

<br class="clear" /><br /><br />

<!-- INCLUDE overall_footer.tpl -->