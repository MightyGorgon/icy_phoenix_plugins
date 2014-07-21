<form method="post" name="sub_rate" action="activity_rating.php{CAT_RATE}">
<table><tr><th class="tw100pct">{TITLE}</th></tr></table>
<table class="forumline">
	<tr>
		<td class="tw50pct" class="row2"><span class="genmed">{CHOICES}</span></td>
		<td align="center" valign="middle" width="50%" class="row2">
			<select name="rating">
				<option selected value="">{DEFAULT_RATE}</option>
				<option value="1">1</option>
				<option value="2">2</option>
				<option value="3">3</option>
				<option value="4">4</option>
				<option value="5">5</option>
				<option value="6">6</option>
				<option value="7">7</option>
				<option value="8">8</option>
				<option value="9">9</option>
				<option value="10">10</option>
			</select>
			<input type="hidden" name="mode" value="submit_rating" />
			<input type="hidden" name="game" value="{GAME}" />
			<input class="mainoption" type="submit" value="{SUBMIT}" onchange="document.sub_rate.submit()" />
		</td>
	</tr>
</table>
</form>