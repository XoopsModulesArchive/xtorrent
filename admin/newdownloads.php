<?php
/**
 * $Id: newdownloads.php v 1.03 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/admin_header.php';

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        $$k = $v;
    }
}

if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        $$k = $v;
    }
}

$op = $_POST['op'] ?? $_GET['op'] ?? 'main';

switch ($op) {
    case 'approve':

        global $xoopsModule;

        $lid = (int)$_GET['lid'];
        $result = $xoopsDB->query('SELECT cid, title, notifypub FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE lid=' . $lid . '');
        [$cid, $title, $notifypub] = $xoopsDB->fetchRow($result);
        /**
         * Update the database
         */
        $time = time();
        $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('xtorrent_downloads') . " SET published = '$time.', status = '1' WHERE lid = " . $lid . '');

        $tags = [];
        $tags['FILE_NAME'] = $title;
        $tags['FILE_URL'] = XOOPS_URL . '/modules/xtorrent/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;

        $sql = 'SELECT title FROM ' . $xoopsDB->prefix('xtorrent_cat') . ' WHERE cid=' . $cid;
        $result = $xoopsDB->query($sql);

        $row = $xoopsDB->fetchArray($result);
        $tags['CATEGORY_NAME'] = $row['title'];
        $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/xtorrent/viewcat.php?cid=' . $cid;
        $notificationHandler = xoops_getHandler('notification');
        $notificationHandler->triggerEvent('global', 0, 'new_file', $tags);
        $notificationHandler->triggerEvent('category', $cid, 'new_file', $tags);

        if ($notifypub) {
            $notificationHandler->triggerEvent('file', $lid, 'approve', $tags);
        }
        redirect_header('newdownloads.php', 1, _AM_XTORRENT_SUB_NEWFILECREATED);
        break;
    // List downloads waiting for validation
    case 'main':
    default:

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        global $xoopsDB, $myts, $xoopsModuleConfig, $imagearray;

        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE published = 0 ORDER BY lid DESC';
        $new_array = $xoopsDB->query($sql, $xoopsModuleConfig['admin_perpage'], $start);
        $new_array_count = $xoopsDB->getRowsNum($xoopsDB->query($sql));

        xoops_cp_header();
        xtorrent_adminmenu(_AM_XTORRENT_SUB_SUBMITTEDFILES);

        echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_XTORRENT_SUB_FILESWAITINGINFO . "</legend>\n
		<div style='padding: 8px;'>" . _AM_XTORRENT_SUB_FILESWAITINGVALIDATION . "&nbsp;<b>$new_array_count</b><div>\n
		<div div style='padding: 8px;'>\n
		<li>" . $imagearray['approve'] . ' ' . _AM_XTORRENT_SUB_APPROVEWAITINGFILE . "\n
		<li>" . $imagearray['editimg'] . ' ' . _AM_XTORRENT_SUB_EDITWAITINGFILE . "\n
		<li>" . $imagearray['deleteimg'] . ' ' . _AM_XTORRENT_SUB_DELETEWAITINGFILE . "</div>\n
		</fieldset><br>\n

		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_XTORRENT_MINDEX_ID . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_XTORRENT_MINDEX_TITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_XTORRENT_MINDEX_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_XTORRENT_MINDEX_SUBMITTED . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_XTORRENT_MINDEX_ACTION . "</b></td>\n
		</tr>\n";
        if ($new_array_count > 0) {
            while (false !== ($new = $xoopsDB->fetchArray($new_array))) {
                $rating = number_format($new['rating'], 2);

                $title = htmlspecialchars($new['title'], ENT_QUOTES | ENT_HTML5);

                $url = htmlspecialchars($new['url'], ENT_QUOTES | ENT_HTML5);

                $url = urldecode($url);

                $homepage = htmlspecialchars($new['homepage'], ENT_QUOTES | ENT_HTML5);

                $version = htmlspecialchars($new['version'], ENT_QUOTES | ENT_HTML5);

                $size = htmlspecialchars($new['size'], ENT_QUOTES | ENT_HTML5);

                $platform = htmlspecialchars($new['platform'], ENT_QUOTES | ENT_HTML5);

                $logourl = htmlspecialchars($new['screenshot'], ENT_QUOTES | ENT_HTML5);

                $submitter = XoopsUserUtility::getUnameFromId($new['submitter']);

                $datetime = formatTimestamp($new['date'], $xoopsModuleConfig['dateformat']);

                $status = ($new['published']) ? $approved : "<a href='newdownloads.php?op=approve&amp;lid=" . $new['lid'] . "'>" . $imagearray['approve'] . '</a>';

                $modify = "<a href='index.php?op=Download&amp;lid=" . $new['lid'] . "'>" . $imagearray['editimg'] . '</a>';

                $delete = "<a href='index.php?op=delDownload&amp;lid=" . $new['lid'] . "'>" . $imagearray['deleteimg'] . '</a>';

                echo "
		<tr>\n
		<td class='head' align='center'>" . $new['lid'] . "</td>\n
		<td class='even' nowrap><a href='newdownloads.php?op=edit&lid=" . $new['lid'] . "'>" . $title . "</a></td>\n
		<td class='even' align='center' nowrap>$submitter</td>\n
		<td class='even' align='center'>" . $datetime . "</td>\n
		<td class='even' align='center' nowrap>$status $modify $delete</td>\n
		</tr>\n";
            }
        } else {
            echo "<tr ><td align='center' class='head' colspan='6'>" . _AM_XTORRENT_SUB_NOFILESWAITING . '</td></tr>';
        }
        echo "</table>\n";
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page = ($new_array_count > $xoopsModuleConfig['admin_perpage']) ? _AM_XTORRENT_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($new_array_count, $xoopsModuleConfig['admin_perpage'], $start, 'start');
        echo '<div align="right" style="padding: 8px;">' . $page . '' . $pagenav->renderNav() . '</div>';
        xoops_cp_footer();
        break;
}
