<!-- INCLUDE overall_header.tpl -->

<form name="feedback_add" action="{S_FEEDBACK_ACTION}" method="post">
{IMG_THL}{IMG_THC}<span class="forumlink">{L_MG_FEEDBACK}</span>{IMG_THR}<table class="forumlinenb">
<tr><td class="row1" colspan="2"><span class="gen"><b>{L_FEEDBACK_RULES}</b></span></td></tr>
<!-- IF S_ADMIN_ALLOWED -->
<tr>
	<td class="row1"><span class="gen"><b>{L_DATE}</b></span></td>
	<td class="row1"><input type="text" name="feedback_time" tabindex="1" class="post" style="text-align:right;" value="{DATE}" size="15" maxlength="11" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_FEEDBACK_TOPIC_ID}</b></span></td>
	<td class="row1"><input type="text" name="feedback_topic_id" tabindex="3" class="post" style="text-align:right;" value="{TRANSACTION_ID}" size="15" maxlength="8" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_USERNAME_FROM}</b></span></td>
	<td class="row1"><input type="text" name="feedback_user_id_from" tabindex="3" class="post" style="text-align:right;" value="{USERID_FROM}" size="15" maxlength="8" /></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_USERNAME_TO}</b></span></td>
	<td class="row1"><input type="text" name="feedback_user_id_to" tabindex="3" class="post" style="text-align:right;" value="{USERID_TO}" size="15" maxlength="8" /></td>
</tr>
<!-- ENDIF -->
<tr>
	<td class="row1"><span class="gen"><b>{L_FEEDBACK_TRANSACTION}</b></span></td>
	<!-- <td class="row1"><input type="text" name="feedback_transaction" tabindex="2" class="post" style="text-align: left;" value="{TRANSACTION}" size="40" maxlength="255" /></td> -->
	<td class="row1">{TRANSACTION_SELECT}</td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_DESCRIPTION}</b></span></td>
	<td class="row1"><textarea name="feedback_description" rows="5" cols="35" style="width: 98%;" tabindex="7" class="post">{DESCRIPTION}</textarea></td>
</tr>
<tr>
	<td class="row1"><span class="gen"><b>{L_FEEDBACK_RATING}</b></span></td>
	<!-- <td class="row1"><input type="text" name="feedback_rating" tabindex="8" class="post" style="text-align:right;" value="{RATING}" size="4" maxlength="4" /></td> -->
	<td class="row1">{RATING_SELECT}</td>
</tr>
<tr><td class="spaceRow" colspan="2"><img src="{SPACER}" width="1" height="3" alt="" /></td></tr>
<tr>
	<td class="cat" colspan="2">
		{S_HIDDEN_FIELDS}
		<input type="submit" name="save" value="{L_SUBMIT}" class="mainoption" />
	</td>
</tr>
</table>{IMG_TFL}{IMG_TFC}{IMG_TFR}
</form>

<!-- INCLUDE overall_footer.tpl -->