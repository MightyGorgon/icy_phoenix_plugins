<!-- INCLUDE overall_header.tpl -->

<!-- IF NO_BLOG_ARTICLES -->

<br class="clear" />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_BLOGS_PAGE}</span>{IMG_THR}<table class="forumlinenb">
<tr><th>{L_MG_BLOGS}</th></tr>
<tr><td class="row1 row-center">{L_NO_BLOG_ARTICLES}</td></tr>
<tr><td class="cat">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- ELSE -->

<!-- IF L_ITEM_TITLE --><br class="clear" /><div class="topic-title-hide-flow-header" style="text-align: left;"><h2><a href="{U_ITEM_TITLE}" style="text-decoration: none;">{L_ITEM_TITLE}</a></h2></div><br /><!-- ENDIF -->

<br class="clear" />

<!-- BEGIN articles -->
<div class="forumlinenb" style="margin: 0 auto;">
<div class="{articles.CLASS}h" style="padding: 10px; text-align: left;">
<div><!-- IF S_EDIT_ALLOWED --><span style="float: right; text-align: right;">{articles.S_EDIT}&nbsp;{articles.S_DELETE}</span><!-- ENDIF --><h3 style="line-height: 120%;"><a id="blt{articles.TOPIC_ID}" href="{articles.U_VIEW}" style="text-decoration: none;">{articles.TITLE}</a></h3></div>
<div class="post-details" style="margin: 0px;">{articles.POSTED_BY}&nbsp;&bull;&nbsp;{L_BLOGS_POST_COMMENTS}&nbsp;{articles.COMMENTS}</div><br /><br />
<div class="post-text">{articles.ARTICLE}</div>
<br class="clear" /><br /><br />
</div>
</div>
<!-- <div style="margin: 0 auto; padding-left: 60px; padding-right: 60px;"><hr /></div> -->

<br class="clear" />
<!-- END articles -->

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<!-- ENDIF -->

<!-- IF S_INPUT_ALLOWED -->
<div class="css-button-wrap">
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="{IMG_CMS_ICON_ADD}" alt="{L_BLOG_LINK_POST_ARTICLE}" title="{L_BLOG_LINK_POST_ARTICLE}" />&nbsp;<b>{L_BLOG_LINK_POST_ARTICLE}</b>&nbsp;</div></div>
</div>
<!-- ENDIF -->

<br class="clear" />
<br /><br />

<!-- INCLUDE overall_footer.tpl -->