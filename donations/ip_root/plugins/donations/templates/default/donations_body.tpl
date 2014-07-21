<!-- INCLUDE overall_header.tpl -->

<div class="css-button-wrap">

<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_DONATE}'"><img src="{IMG_CMS_ICON_DOLLAR}" alt="{L_MG_DONATE}" title="{L_MG_DONATE}" />&nbsp;<b>{L_MG_DONATE}</b>&nbsp;</div></div>
<!-- IF S_INPUT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="{IMG_CMS_ICON_ADD}" alt="{L_DONATION_ADD}" title="{L_DONATION_ADD}" />&nbsp;<b>{L_DONATION_ADD}</b>&nbsp;</div></div>
<!-- ENDIF -->

</div>

<!-- IF NO_DONATIONS -->

<br class="clear" />
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MG_DONATIONS}</span>{IMG_THR}<table class="forumlinenb">
<tr><th>{L_MG_DONATIONS}</th></tr>
<tr><td class="row1 row-center">{L_NO_DONATIONS}</td></tr>
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

{IMG_THL}{IMG_THC}<span class="forumlink">{L_MG_DONATIONS}</span>{IMG_THR}<table class="forumlinenb">
<tr>
	<th class="tw30px">#</th>
	<th>{L_DATE}</th>
	<th>{L_USERNAME}</th>
	<th>{L_WEBSITE}</th>
	<!-- IF S_ADMIN_ALLOWED -->
	<th>{L_EMAIL}</th>
	<th>{L_AMOUNT}</th>
	<th>{L_MANAGEMENT}</th>
	<!-- ENDIF -->
</tr>
<!-- BEGIN donations -->
<tr class="{donations.CLASS} {donations.CLASS}h" style="background-image: none;">
	<td class="{donations.CLASS} row-center tdnw" style="background: none; line-height: 150%;">{donations.ROW_NUMBER}</td>
	<td class="{donations.CLASS} row-center tdnw" style="background: none;">{donations.DATE}</td>
	<td class="{donations.CLASS} row-center tdnw" style="background: none;">{donations.USERNAME}</td>
	<!-- IF IS_PROSILVER -->
	<td class="{donations.CLASS} row-center" style="background: none;"><!-- IF donations.TEXT_LINK -->{donations.WEBSITE}<!-- ELSE --><!-- IF donations.U_WEBSITE --><ul class="profile-icons" style="padding-left: 10px;"><li class="web-icon"><a href="{donations.U_WEBSITE}" rel="nofollow" target="_blank"><span>{L_WEBSITE}</span></a></li></ul><!-- ELSE -->&nbsp;<!-- ENDIF --><!-- ENDIF --></td>
	<!-- ELSE -->
	<td class="{donations.CLASS} row-center tdnw" style="background: none;"><span class="post-buttons-single"><!-- IF donations.WEBSITE -->{donations.WEBSITE}<!-- ELSE -->&nbsp;<!-- ENDIF --></span></td>
	<!-- ENDIF -->
	<!-- IF S_ADMIN_ALLOWED -->
	<!-- IF IS_PROSILVER -->
	<td class="{donations.CLASS} row-center tdnw" style="background: none;"><!-- IF donations.U_EMAIL --><ul class="profile-icons" style="padding-left: 10px;"><li class="email-icon"><a href="{donations.U_EMAIL}" rel="nofollow"><span>{L_EMAIL}</span></a></li></ul><!-- ELSE -->&nbsp;<!-- ENDIF --></td>
	<!-- ELSE -->
	<td class="{donations.CLASS} row-center tdnw" style="background: none;"><span class="post-buttons-single">{donations.EMAIL}</span></td>
	<!-- ENDIF -->
	<td class="{donations.CLASS} row-center tdnw" style="background: none;">{donations.AMOUNT}</td>
	<td class="{donations.CLASS} row-center" style="background: none;">{donations.S_VIEW}&nbsp;{donations.S_EDIT}&nbsp;{donations.S_DELETE}</td>
	<!-- ENDIF -->
</tr>
<!-- END donations -->
<tr><td class="spaceRow" colspan="8"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr><td class="cat" colspan="8">&nbsp;</td></tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

<!-- IF PAGINATION -->
<div style="float: right; text-align: right;">
	<span class="pagination">{PAGINATION}</span><br />
	<span class="gensmall">{PAGE_NUMBER}</span>
</div>
<!-- ENDIF -->

<div class="css-button-wrap">

<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_DONATE}'"><img src="{IMG_CMS_ICON_DOLLAR}" alt="{L_MG_DONATE}" title="{L_MG_DONATE}" />&nbsp;<b>{L_MG_DONATE}</b>&nbsp;</div></div>
<!-- IF S_INPUT_ALLOWED -->
<div class="forumline css-button-left"><div class="row1h css-button-body" onclick="window.location.href='{U_ITEM_ADD}'"><img src="{IMG_CMS_ICON_ADD}" alt="{L_DONATION_ADD}" title="{L_DONATION_ADD}" />&nbsp;<b>{L_DONATION_ADD}</b>&nbsp;</div></div>
<!-- ENDIF -->

</div>

<br class="clear" />
<br /><br />

<!-- ENDIF -->

<!-- IF S_INPUT_ALLOWED -->
<!-- <div style="text-align: center; margin: 0 auto; clear: both;"><span class="gensmall"><b><a href="{U_ITEM_ADD}">{L_DONATION_ADD}</a></b></span></div> -->
<!-- ENDIF -->

<!-- INCLUDE overall_footer.tpl -->