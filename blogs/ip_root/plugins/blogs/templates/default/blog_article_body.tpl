<!-- INCLUDE overall_header.tpl -->

<br class="clear" />
<div class="topic-title-hide-flow-header" style="text-align: left;"><h2><a href="{U_BLOG_TITLE}" style="text-decoration: none;">{L_BLOG_TITLE}</a></h2></div>

<!-- IF NEXT_ARTICLE or PREV_ARTICLE -->
<div style="min-height: 40px;">
<!-- IF NEXT_ARTICLE -->
<span style="float: right; text-align: right;"><b>{NEXT_ARTICLE}&nbsp;&raquo;</b></span>
<!-- ENDIF -->
<!-- IF PREV_ARTICLE -->
<span style="float: left; text-align: right;"><b>&laquo;&nbsp;{PREV_ARTICLE}</b></span>
<!-- ENDIF -->
</div>
<!-- ENDIF -->

<br class="clear" /><br />
<div style="margin: 0 auto; padding: 10px; text-align: left;">
<div><!-- IF S_EDIT_ALLOWED --><span style="float: right; text-align: right;">{S_EDIT}&nbsp;{S_DELETE}</span><!-- ENDIF --><h2 style="line-height: 120%;"><a id="{BLOG_TOPIC_ID_VAR}{TOPIC_ID}" href="{U_VIEW}" style="text-decoration: none;">{BLOG_TITLE}</a></h2></div>
<div class="post-details" style="margin: 0px;">{POSTED_BY}&nbsp;&bull;&nbsp;{L_BLOGS_POST_COMMENTS}&nbsp;{COMMENTS}</div><br /><br />
<div class="post-text">{ARTICLE}</div><br /><br /><br />
</div>

<br class="clear" /><br />
<div style="margin-top: 60px;">&nbsp;</div>
<div style="margin: 0 auto; padding-left: 60px; padding-right: 60px;"><hr /></div>
<br class="clear" /><br />

<!-- IF NO_BLOG_COMMENTS -->

<i>{L_NO_BLOG_COMMENTS}</i><br /><br />

<!-- ELSE -->

<div class="gen" style="text-align: left;"><b>{L_BLOGS_POST_COMMENTS}</b></div>
<br />
<!-- BEGIN comments -->
<div class="forumlinenb" style="margin: 0 auto; width: 720px;">
<div class="{comments.CLASS}h" style="padding: 10px; text-align: left;">
<div><!-- IF comments.S_MOD --><span style="float: right; text-align: right;">{comments.S_EDIT}&nbsp;{comments.S_DELETE}</span><!-- ENDIF --><div id="{BLOG_POST_ID_VAR}{comments.POST_ID}" class="post-details" style="margin: 0px;">{comments.POSTED_BY}</div></div><br /><br />
<div class="post-text">{comments.COMMENT}</div><br /><br /><br />
<br class="clear" /><br /><br />
</div>
</div>
<br class="clear" />
<!-- END comments -->

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<!-- ENDIF -->

<!-- IF COMMENT_POST_ALLOWED -->
<br class="clear" /><br /><br /><br /><br />
<div style="width: 680px; text-align: left;">
<span class="gen"><b>{L_BLOGS_POST_COMMENT}</b></span><br /><br />
<form name="input_form" method="post" action="{S_MODE_ACTION}">
<table>
<!-- BEGIN field -->
<tr>
	<td style="width: 30%; vertical-align: top; padding: 5px;"><span class="gen"><b>{field.L_NAME}</b></span><!-- IF field.L_EXPLAIN --><br /><div class="gensmall">{field.L_EXPLAIN}</div><!-- ENDIF --></td>
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