<?PHP
/**
*
* @package Icy Phoenix
* @version $Id$
* @copyright (c) 2008 Icy Phoenix
* @license http://opensource.org/licenses/gpl-license.php GNU Public License
*
*/

/**
*
* @Extra credits for this file
* aUsTiN-Inc 2003/5 (austin@phpbb-amod.com) - (http://phpbb-amod.com)
*
*/

if (!defined('IN_ICYPHOENIX'))
{
	die('Hacking Attempt');
}

function copyright()
{
	$img_link = "http://phpbb-amod.com/amod_files/arrow.gif";
	$img2 = "http://phpbb-amod.com/images/phpbbhacks_awards/hack.gif";

	echo "<html>\n"
	. "<body bgcolor=\"#F6F6EB\" link=\"#363636\" alink=\"#363636\" vlink=\"#363636\">\n"
	. "<title>Activity Mod Plus: Copyright Information</title>\n"
	. "<font size=\"2\" color=\"#363636\" face=\"Verdana, Helvetica\">\n"
	. "<center><b>Mod Copyright &copy; Information</b><br /></center>"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>Hack Title:</b> Activity Mod Plus<br />\n"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>Hack Version:</b> 1.1.0<br />\n"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>License:</b> Personal License<br />\n"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>Author\"s Name:</b> aUsTiN<br />\n"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>Author\"s Email:</b> <a href=\"mailto:austin_inc@hotmail.com\">Primary</a> | <a href=\"mailto:austin@phpbb-amod.com\">Secondary</a> | <a href=\"mailto:austin.inc@gmail.com\">Last Resort</a><br /><br />\n"
	. "<img src=\"$img_link\" border=\"0\" />&nbsp;<b>Hack Description:</b> Adds a very configurable game play
	mod to your board. Allows you to add games/edit them from the ACP, comes with many features such
	as:<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Forum wide trophy system.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Wager system, users can bet users.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Comment system.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Top five page.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Games online list.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Challenge system.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; Games ban system.<br />
	&nbsp;<img src=\"$img_link\" border=\"0\" />&nbsp; &amp; much much more.
	<br />\n"
	. "<br /><center>[ <a href=\"http://phpbb-amod.com/activity.php\" target=\"new\">Official Demo Board</a> | <a href=\"http://www.phpbb.com/phpBB/viewtopic.php?t=203074&postdays=0&postorder=asc&start=0\" target=\"new\">phpBB Topic</a> | <a href=\"javascript:void(0)\" onclick=javascript:self.close()>Close</a> ]<br /><br /><b>Games Links</b><br />[ <a href=\"http://phpbb-amod.com/downloads.php\" target=\"new\">phpBB Amod Games</a> ]</center>"
	. "<br /><center><b>Awards</b><br /><img src=\"$img2\" border=\"0\">"
	. "</font>\n"
	. "</body>\n"
	. "</html>";
}

copyright();
?>