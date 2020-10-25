<?php

require __DIR__ . '/admin_header.php';
/**
 * @version   $Id$
 * @copyright 2003
 */
global $_GET, $_POST;

$g_id = 1;

xoops_cp_header();

$memberHandler = xoops_getHandler('member');
$thisgroup = $memberHandler->getGroup($g_id);
$name_value = $thisgroup->getVar('name', 'E');
$desc_value = $thisgroup->getVar('description', 'E');
$modulepermHandler = xoops_getHandler('groupperm');

xtorrent_adminmenu(_AM_XTORRENT_EDITBANNED);

$usercount = $memberHandler->getUserCount(new Criteria('level', 0, '>'));
$memberHandler = xoops_getHandler('member');
$membercount = $memberHandler->getUserCountByGroup($g_id);

$members = $memberHandler->getUsersByGroup($g_id, true);
$mlist = [];
$mcount = count($members);
for ($i = 0; $i < $mcount; $i++) {
    $mlist[$members[$i]->getVar('uid')] = $members[$i]->getVar('uname');
}
$criteria = new Criteria('level', 0, '>');
$criteria->setSort('uname');
$userslist = $memberHandler->getUserList($criteria);
$users     = array_diff($userslist, $mlist);

echo '<table class="outer">
		<tr><th align="center">' . _AM_XTORRENT_NONBANNED . '<br>';

echo '</th><th></th><th align="center">' . _AM_XTORRENT_BANNED . '<br>';
echo '</th></tr>
		<tr><td class="even">
		<form action="admin.php" method="post">
		<select name="uids[]" size="10" multiple="multiple">' . "\n";
foreach ($mlist as $m_id => $m_name) {
    echo '<option value="' . $m_id . '">' . $m_name . '</option>' . "\n";
}

echo '</select>';
echo "</td><td align='center' class='odd'>
		<input type='hidden' name='op' value='addUser'>
		<input type='hidden' name='fct' value='groups'>
		<input type='hidden' name='groupid' value='" . $thisgroup->getVar('groupid') . "'>
		<input type='submit' name='submit' value='" . _AM_XTORRENT_BADD . "'>
		</form><br>
		<form action='admin.php' method='post'>
		<input type='hidden' name='op' value='delUser'>
		<input type='hidden' name='fct' value='groups'>
		<input type='hidden' name='groupid' value='" . $thisgroup->getVar('groupid') . "'>
		<input type='submit' name='submit' value='" . _AM_XTORRENT_BDELETE . "'>
		</td>
		<td class='even'>";
echo "<select name='uids[]' size='10' multiple='multiple'>";
foreach ($users as $u_id => $u_name) {
    echo '<option value="' . $u_id . '">' . $u_name . '</option>' . "\n";
}
echo '</select>';
echo '</td></tr>
		</form>
		</table>';
xoops_cp_footer();
