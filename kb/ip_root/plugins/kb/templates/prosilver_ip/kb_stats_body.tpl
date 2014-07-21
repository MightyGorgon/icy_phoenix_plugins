<!-- BEGIN switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{L_CATEGORY}</span>{IMG_THR}<table class="forumlinenb">
<!-- BEGIN catrow -->
<tr>
	<td class="row1" height="50">
		<span class="forumlink">{switch_sub_cats.catrow.CATEGORY}</span><br />
		<span class="genmed">{switch_sub_cats.catrow.CAT_DESCRIPTION}</span>
	</td>
	<td class="row2 row-center tvalignm"><span class="genmed">{switch_sub_cats.catrow.CAT_ARTICLES}</span></td>
</tr>
<!-- END catrow -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
<br />
<!-- END switch_sub_cats -->
{IMG_THL}{IMG_THC}<span class="forumlink">{PATH}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tdnw">{L_ARTICLE}</th>
	<th class="tdnw">{L_CAT}</th>
	<th class="tdnw">{L_ARTICLE_TYPE}</th>
	<th class="tdnw">{L_ARTICLE_AUTHOR}</th>
	<th class="tdnw">{L_ARTICLE_DATE}</th>
	<th class="tdnw">{L_VIEWS}</th>
</tr>
<!-- BEGIN articlerow -->
<tr>
	<td class="row2 tvalignm"><span class="forumlink">{articlerow.ARTICLE}</span></td>
	<td class="row2 row-center tvalignm"><span class="gen">{articlerow.CATEGORY}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed">{articlerow.ARTICLE_TYPE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed">{articlerow.ARTICLE_AUTHOR}</span></td>
	<td class="row2 row-center tvalignm" nowrap="nowrap"><span class="gensmall">{articlerow.ARTICLE_DATE}</span></td>
	<td class="row2 row-center tvalignm"><span class="genmed">{articlerow.ART_VIEWS}</span></td>
</tr>
<tr>
	<td class="row1" colspan="4"><span class="genmed">{articlerow.ARTICLE_DESCRIPTION}</span></td>
	<td class="row1 row-center" colspan="2"><span class="post-buttons">{articlerow.U_APPROVE}&nbsp;&bull;&nbsp;{articlerow.U_DELETE}</span></td>
</tr>
<!-- END articlerow -->
<!-- BEGIN no_articles -->
<tr><td class="row1 row-center th50px" colspan="6"><span class="genmed">{no_articles.COMMENT}</span></td></tr>
<!-- END no_articles -->
<tr><td class="cat" colspan="6">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- BEGIN pagination -->
<table>
<tr>
	<td><span class="gensmall">{PAGE_NUMBER}</span></td>
	<td><span class="gensmall">{S_TIMEZONE}</span><br /><span class="pagination">{PAGINATION}</span></td>
</tr>
</table>
<!-- END pagination -->