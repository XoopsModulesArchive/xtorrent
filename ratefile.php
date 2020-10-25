<?php
/* $Id: ratefile.php,v 2.3 11 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

require __DIR__ . '/header.php';

global $myts;

if (!empty($_POST['submit'])) {
    if (empty($xoopsUser)) {
        $ratinguser = 0;
    } else {
        $ratinguser = $xoopsUser->getVar('uid');
    }

    // Make sure only 1 anonymous from an IP in a single day.

    $anonwaitdays = 1;

    $ip = getenv('REMOTE_ADDR');

    $lid = (int)$_POST['lid'];

    $cid = (int)$_POST['cid'];

    $rating = (int)$_POST['rating'];

    // Check if Rating is Null

    if ('--' == $rating) {
        redirect_header('ratefile.php?cid=' . $cid . '&amp;lid=' . $lid . '', 4, _MD_XTORRENT_NORATING);

        exit();
    }

    // Check if Download POSTER is voting (UNLESS Anonymous users allowed to post)

    if (0 != $ratinguser) {
        $result = $xoopsDB->query('SELECT submitter FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid=$lid");

        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('index.php', 4, _MD_XTORRENT_CANTVOTEOWN);

                exit();
            }
        }

        // Check if REG user is trying to vote twice.

        $result = $xoopsDB->query('SELECT ratinguser FROM ' . $xoopsDB->prefix('xtorrent_votedata') . " WHERE lid=$lid");

        while (list($ratinguserDB) = $xoopsDB->fetchRow($result)) {
            if ($ratinguserDB == $ratinguser) {
                redirect_header('index.php', 4, _MD_XTORRENT_VOTEONCE);

                exit();
            }
        }
    } else {
        // Check if ANONYMOUS user is trying to vote more than once per day.

        $yesterday = (time() - (86400 * $anonwaitdays));

        $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xtorrent_votedata') . " WHERE lid=$lid AND ratinguser=0 AND ratinghostname = '$ip'  AND ratingtimestamp > $yesterday");

        [$anonvotecount] = $xoopsDB->fetchRow($result);

        if ($anonvotecount >= 1) {
            redirect_header('index.php', 4, _MD_XTORRENT_VOTEONCE);

            exit();
        }
    }

    // All is well.  Add to Line Item Rate to DB.

    $newid = $xoopsDB->genId($xoopsDB->prefix('xtorrent_votedata') . '_ratingid_seq');

    $datetime = time();

    $sql = sprintf("INSERT INTO %s (ratingid, lid, ratinguser, rating, ratinghostname, ratingtimestamp) VALUES (%u, %u, %u, %u, '%s', %u)", $xoopsDB->prefix('xtorrent_votedata'), $newid, $lid, $ratinguser, $rating, $ip, $datetime);

    $xoopsDB->query($sql);

    // All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.

    xtorrent_updaterating($lid);

    $ratemessage = _MD_XTORRENT_VOTEAPPRE . '<br>' . sprintf(_MD_XTORRENT_THANKYOU, $xoopsConfig['sitename']);

    redirect_header('index.php', 4, $ratemessage);

    exit();
}  
    $GLOBALS['xoopsOption']['template_main'] = 'xtorrent_ratefile.html';
    require XOOPS_ROOT_PATH . '/header.php';
    $lid = (int)$_GET['lid'];
    $cid = (int)$_GET['cid'];
    $imageheader = xtorrent_imageheader();

    $result = $xoopsDB->query('SELECT title FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid=$lid");
    [$title] = $xoopsDB->fetchRow($result);
    $xoopsTpl->assign('file', ['id' => $lid, 'cid' => $cid, 'title' => htmlspecialchars($title, ENT_QUOTES | ENT_HTML5), 'imageheader' => $imageheader]);
    require XOOPS_ROOT_PATH . '/footer.php';

require __DIR__ . '/footer.php';
