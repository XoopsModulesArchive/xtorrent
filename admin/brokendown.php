<?php
/**
 * $Id: brokendown.php v 1.03 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/admin_header.php';

$op = '';

$op = $_POST['op'] ?? $_GET['op'] ?? 'listBrokenDownloads';

$lid = (isset($_GET['lid'])) ? (int)$_GET['lid'] : 0;

switch ($op) {
    case 'updateNotice':
        global $xoopsDB;

        if (isset($_GET['ack'])) {
            $acknowledged = (isset($_GET['ack']) && 0 == $_GET['ack']) ? 1 : 0;

            $xoopsDB->queryF(
                'UPDATE ' . $xoopsDB->prefix('xtorrent_broken') . " SET acknowledged = '$acknowledged'
				WHERE lid='$lid'"
            );

            $update_mess = _AM_XTORRENT_BROKEN_NOWACK;
        }

        if (isset($_GET['con'])) {
            $confirmed = (isset($_GET['con']) && 0 == $_GET['con']) ? 1 : 0;

            $xoopsDB->queryF(
                'UPDATE ' . $xoopsDB->prefix('xtorrent_broken') . " SET confirmed = '$confirmed' 
			WHERE lid='$lid'"
            );

            $update_mess = _AM_XTORRENT_BROKEN_NOWCON;
        }
        redirect_header('brokendown.php?op=default', 1, $update_mess);
        break;
    case 'delBrokenDownloads':
        global $xoopsDB;

        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtorrent_broken') . " WHERE lid = '$lid'");
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid = '$lid'");
        redirect_header('brokendown.php?op=default', 1, _AM_XTORRENT_BROKENFILEDELETED);
        exit();
        break;
    case 'ignoreBrokenDownloads':
        global $xoopsDB;

        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtorrent_broken') . " WHERE lid = '$lid'");
        redirect_header('brokendown.php?op=default', 1, _AM_XTORRENT_BROKEN_FILEIGNORED);
        break;
    case 'listBrokenDownloads':
    case 'default':

        global $xoopsDB, $imagearray, $xoopsModule;
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix('xtorrent_broken') . ' ORDER BY reportid');
        $totalbrokendownloads = $xoopsDB->getRowsNum($result);

        xoops_cp_header();

        xtorrent_adminmenu(_AM_XTORRENT_BROKEN_FILE);

        echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_XTORRENT_BROKEN_REPORTINFO . "</legend>\n
		<div style='padding: 8px;'>" . _AM_XTORRENT_BROKEN_REPORTSNO . "&nbsp;<b>$totalbrokendownloads</b><div>\n
		<div style='padding: 8px;'>\n
		<ul><li>" . $imagearray['ignore'] . ' ' . _AM_XTORRENT_BROKEN_IGNOREDESC . "</li>\n
		<li>" . $imagearray['editimg'] . ' ' . _AM_XTORRENT_BROKEN_EDITDESC . '</li>
		<li>' . $imagearray['deleteimg'] . ' ' . _AM_XTORRENT_BROKEN_DELETEDESC . "</li>\n
		<li>" . $imagearray['ack_yes'] . ' ' . _AM_XTORRENT_BROKEN_ACKDESC . '</li>
		<li>' . $imagearray['con_yes'] . ' ' . _AM_XTORRENT_BROKEN_CONFIRMDESC . "</li>
		</ul></div>\n
		</fieldset><br>\n

		<table width='100%' border='0' cellspacing='1' cellpadding = '2' class='outer'>\n
		<tr align = 'center'>\n
		<th width = '3%' align = 'center'>" . _AM_XTORRENT_BROKEN_ID . "</th>\n
		<th width = '35%' align = 'left'>" . _AM_XTORRENT_BROKEN_TITLE . "</th>\n
		<th>" . _AM_XTORRENT_BROKEN_REPORTER . "</th>\n
		<th>" . _AM_XTORRENT_BROKEN_FILESUBMITTER . "</th>\n
		<th>" . _AM_XTORRENT_BROKEN_DATESUBMITTED . "</th>\n
		<th align='center'>" . _AM_XTORRENT_BROKEN_ACTION . "</th>\n
		</tr>\n
		";

        if (0 == $totalbrokendownloads) {
            echo "<tr align = 'center'><td align = 'center' class='head' colspan = '6'>" . _AM_XTORRENT_BROKEN_NOFILEMATCH . '</td></tr>';
        } else {
            while (list($reportid, $lid, $sender, $ip, $date, $confirmed, $acknowledged) = $xoopsDB->fetchRow($result)) {
                $result2 = $xoopsDB->query('SELECT cid, title, url, submitter FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid=$lid");

                [$cid, $fileshowname, $url, $submitter] = $xoopsDB->fetchRow($result2);

                if (0 != $sender) {
                    $result3 = $xoopsDB->query('SELECT uname, email FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $sender . '');

                    [$sendername, $email] = $xoopsDB->fetchRow($result3);
                }

                $result4 = $xoopsDB->query('SELECT uname, email FROM ' . $xoopsDB->prefix('users') . ' WHERE uid=' . $sender . '');

                [$ownername, $owneremail] = $xoopsDB->fetchRow($result4);

                echo "
		<tr align = 'center'>\n
		<td class = 'head'>$reportid</td>\n
		<td class = 'even' align = 'left'><a href='" . XOOPS_URL . '/modules/xtorrent/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid . "' target='_blank'>" . $fileshowname . "</a></td>\n
		";

                if ('' == $email) {
                    echo "<td class = 'even'>$sendername ($ip)";
                } else {
                    echo "<td class = 'even'><a href='mailto:$email'>$sendername</a> ($ip)";
                }

                if ('' == $owneremail) {
                    echo "<td class = 'even'>$ownername";
                } else {
                    echo "<td class = 'even'><a href='mailto:$owneremail'>$ownername</a>";
                }

                echo "
		</td>\n
		<td class='even' align='center'>" . formatTimestamp($date, $xoopsModuleConfig['dateformat']) . "</td>\n
		<td align='center' class = 'even' nowrap>\n
		<a href='brokendown.php?op=ignoreBrokenDownloads&amp;lid=$lid'>" . $imagearray['ignore'] . "</a>\n
		<a href='index.php?op=Download&amp;lid=" . $lid . "'> " . $imagearray['editimg'] . " </a>\n
		<a href='brokendown.php?op=delBrokenDownloads&amp;lid=$lid'>" . $imagearray['deleteimg'] . "</a>\n
		";

                $ack_image = ($acknowledged) ? $imagearray['ack_yes'] : $imagearray['ack_no'];

                echo "<a href='brokendown.php?op=updateNotice&amp;lid=$lid&ack=$acknowledged'>" . $ack_image . " </a>\n";

                $con_image = ($confirmed) ? $imagearray['con_yes'] : $imagearray['con_no'];

                echo "
		<a href='brokendown.php?op=updateNotice&amp;lid=$lid&amp;con=$confirmed'>" . $con_image . " </a>\n
		</td></tr>\n
		";
            }
        }
        echo '</table>';
}
xoops_cp_footer();
