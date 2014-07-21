<h1>{L_KB_TYPE_TITLE}</h1>
<p>{L_KB_TYPE_DESCRIPTION}</p>

<form action="{S_ACTION}" method="post">
<div style="text-align:right;padding:3px;">
<span class="genmed"><strong>{L_CREATE_TYPE}</strong>&nbsp;<input class="post" type="text" name="new_type_name" />&nbsp;&nbsp;<input type="submit" value="{L_CREATE}" class="liteoption" /></span>
</div>
</form>

<table class="forumline">
<tr>
	<th nowrap="nowrap">{L_TYPE}</th>
	<th nowrap="nowrap">{L_ACTION}</th>
</tr>
<!-- BEGIN typerow -->
<tr>
	<td class="{typerow.ROW_CLASS}"><span class="gen">{typerow.TYPE}</span></td>
	<td width="15%" nowrap="nowrap" class="{typerow.ROW_CLASS} row-center"><span class="post-buttons">{typerow.U_EDIT} | {typerow.U_DELETE}</span></td>
</tr>
<!-- END typerow -->
<tr><td class="cat" colspan="2">&nbsp;</td></tr>
</table>
<br clear="all" />