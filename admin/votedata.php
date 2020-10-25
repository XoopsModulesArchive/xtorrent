<?php
/**
 * $Id: votedata.php v 1.0.1 02 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/admin_header.php';

$op = '';
if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

if (isset($_GET)) {
    foreach ($_GET as $k => $v) {
        ${$k} = $v;
    }
}

if (isset($_GET['op'])) {
    $op = $_GET['op'];
}
if (isset($_POST['op'])) {
    $op = $_POST['op'];
}

switch ($op) {
    case 'delVote':
        global $xoopsDB, $_GET;
        $rid = (int)$_GET['rid'];
        $lid = (int)$_GET['lid'];
        $sql = $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtorrent_votedata') . " WHERE ratingid = $rid");
        $xoopsDB->query($sql);
        xtorrent_updaterating($lid);
        redirect_header('votedata.php', 1, _AM_XTORRENT_VOTEDELETED);
        break;
    case 'main':
    default:

        global $xoopsDB, $imagearray;

        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
        $useravgrating = '0';
        $uservotes = '0';

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_votedata') . ' ORDER BY ratingtimestamp DESC';
        $results = $xoopsDB->query($sql, 20, $start);
        $votes = $xoopsDB->getRowsNum($results);

        $sql = 'SELECT rating FROM ' . $xoopsDB->prefix('xtorrent_votedata') . '';
        $result2 = $xoopsDB->query($sql, 20, $start);
        $uservotes = $xoopsDB->getRowsNum($result2);
        $useravgrating = 0;

        while (list($rating2) = $xoopsDB->fetchRow($result2)) {
            $useravgrating += $rating2;
        }
        if ($useravgrating > 0) {
            $useravgrating /= $uservotes;

            $useravgrating = number_format($useravgrating, 2);
        }

        xoops_cp_header();
        xtorrent_adminmenu(_AM_XTORRENT_VOTE_RATINGINFOMATION);

        echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_XTORRENT_VOTE_DISPLAYVOTES . "</legend>\n
		<div style='padding: 8px;'>\n	
		<div><b>" . _AM_XTORRENT_VOTE_USERAVG . ": </b>$useravgrating</div>\n
		<div><b>" . _AM_XTORRENT_VOTE_TOTALRATE . ": </b>$uservotes</div>\n
		<div style='padding: 8px;'>\n	
		<li>" . $imagearray['deleteimg'] . ' ' . _AM_XTORRENT_VOTE_DELETEDSC . "</li>
		<div>\n
		</fieldset>\n
		<br>\n

		<table width='100%' cellspacing='1' cellpadding='2' class='outer'>\n
		<tr>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_ID . "</th>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_USER . "</th>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_IP . "</th>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_FILETITLE . "</th>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_RATING . "</th>\n
		<th align='center'>" . _AM_XTORRENT_VOTE_DATE . "</th>\n
		<th align='center'>" . _AM_XTORRENT_MINDEX_ACTION . "</th></tr>\n";

        if (0 == $votes) {
            echo "<tr><td align='center' colspan='7' class='head'>" . _AM_XTORRENT_VOTE_NOVOTES . '</td></tr>';
        }
        while (list($ratingid, $lid, $ratinguser, $rating, $ratinghostname, $ratingtimestamp) = $xoopsDB->fetchRow($results)) {
            $sql = 'SELECT title FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE lid=' . $lid . '';

            $down_array = $xoopsDB->fetchArray($xoopsDB->query($sql));

            $formatted_date = formatTimestamp($ratingtimestamp, $xoopsModuleConfig['dateformat']);

            $ratinguname = XoopsUser::getUnameFromId($ratinguser);

            echo "
		<tr>\n
		<td class='head' align='center'>$ratingid</td>\n
		<td class='even' align='center'>$ratinguname</td>\n
		<td class='even' align='center' >$ratinghostname</td>\n
		<td class='even' align='center'>" . $down_array['title'] . "</td>\n
		<td class='even' align='center'>$rating</td>\n
		<td class='even' align='center'>$formatted_date</td>\n
		<td class='even' align='center'><b><a href='votedata.php?op=delVote&amp;lid=$lid&amp;rid=$ratingid'>" . $imagearray['deleteimg'] . "</a></b></td>\n
		</tr>\n";
        }
        echo '</table>';
        //Include page navigation
        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page = ($votes > 20) ? _AM_XTORRENT_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($page, 20, $start, 'start');
        echo '<div align="right" style="padding: 8px;">' . $page . '' . $pagenav->renderNav() . '</div>';
        break;
}
xoops_cp_footer();
