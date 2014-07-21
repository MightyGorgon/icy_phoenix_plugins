<!-- INCLUDE overall_header.tpl -->

<!-- <h2 style="text-align: left;">{DONATE_TO_SITENAME}</h2> -->

<form action="{S_DONATE_ACTION}" method="post" id="donate">

{IMG_THL}{IMG_THC}<span class="forumlink">{L_MG_DONATIONS}</span>{IMG_THR}<table class="forumlinenb">
<tr><th>&nbsp;</th></tr>
<tr>
	<td class="row1">

		<p>{DONATE_TO_SITENAME_EXPLAIN}</p><br />

		<span class="genmed"><b>{L_DONATE_AMOUNT}</b>:</span>
		<!-- <span class="gensmall">{L_DONATE_AMOUNT_EXPLAIN}</span> -->
		<input type="text" tabindex="1" name="amount" id="amount" size="25" maxlength="6" value="" class="post" title="{L_DONATE_AMOUNT}" /><select name="currency_code" id="currency_code" class="post">{S_CURRENCY_OPTIONS}</select>

		<br /><br /><br />

		<span class="genmed"><b>{L_COUNTRY}</b>:</span>
		<!-- <span class="gensmall">{L_COUNTRY_EXPLAIN}</span> -->
		<select name="lc" id="lc">{S_COUNTRY_OPTIONS}</select>

		<br /><br /><br />

	<img src="https://www.paypal.com/en_US/i/scr/pixel.gif" alt="" width="1" height="1" />
	</td>
</tr>
<tr><td class="spaceRow"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat">
		{S_HIDDEN_FIELDS}
		<input type="reset" name="reset" value="{L_RESET}" class="liteoption" />&nbsp;
		<input type="submit" name="submit" value="{L_SUBMIT}" class="mainoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}

</form>

<!-- INCLUDE overall_footer.tpl -->