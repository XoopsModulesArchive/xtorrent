<?php
/**
 * $Id: singlefile.php v 1.04 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$lid = (int)$_GET['lid'];
$cid = (int)$_GET['cid'];
$GLOBALS['xoopsOption']['template_main'] = 'xtorrent_singlefile.html';

$sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid = $lid";
$result = $xoopsDB->query($sql);
$down_arr = $xoopsDB->fetchArray($result);

if (!$down_arr) {
    redirect_header('index.php', 1, _MD_XTORRENT_NODOWNLOAD);

    exit();
}

require XOOPS_ROOT_PATH . '/header.php';

/**
 * Begin Main page Heading etc
 */
$down['imageheader'] = xtorrent_imageheader();
$down['id'] = (int)$down_arr['lid'];
$down['cid'] = (int)$down_arr['cid'];
/**
 * Breadcrumb
 */
$mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');
$pathstring = "<a href='index.php'>" . _MD_XTORRENT_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$down['path'] = $pathstring;

require_once XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->dirname() . '/include/downloadinfo.php';

$xoopsTpl->assign('show_screenshot', false);
if (isset($xoopsModuleConfig['screenshot']) && 1 == $xoopsModuleConfig['screenshot']) {
    $xoopsTpl->assign('shots_dir', $xoopsModuleConfig['screenshots']);

    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);

    $xoopsTpl->assign('shotheight', $xoopsModuleConfig['shotheight']);

    $xoopsTpl->assign('show_screenshot', true);
}
/**
 * Show other author downloads
 */
$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler = xoops_getHandler('groupperm');

$sql = 'SELECT lid, cid, title, published FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' 
	WHERE submitter = ' . $down_arr['submitter'] . ' 
	AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ') 
	AND offline = 0 ORDER by published DESC';
$result = $xoopsDB->query($sql, 20, 0);

while (false !== ($arr = $xoopsDB->fetchArray($result))) {
    if (!$gpermHandler->checkRight('xtorrentownFilePerm', $arr['lid'], $groups, $xoopsModule->getVar('mid')) || $arr['lid'] == $lid) {
        continue;
    }

    $downuid['title'] = $arr['title'];

    $downuid['lid'] = $arr['lid'];

    $downuid['cid'] = $arr['cid'];

    $downuid['published'] = formatTimestamp($arr['published'], $xoopsModuleConfig['dateformat']);

    $xoopsTpl->append('down_uid', $downuid);
}
/**
 * User reviews
 */
$sql_review = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_reviews') . ' 
	WHERE lid = ' . $down_arr['lid'] . ' AND submit = 1';
$result_review = $xoopsDB->query($sql_review);
$review_amount = $xoopsDB->getRowsNum($result_review);
if ($review_amount > 0) {
    $user_reviews = 'op=list&amp;cid=' . $down_arr['cid'] . '&amp;lid=' . $down_arr['lid'] . '">' . _MD_XTORRENT_USERREVIEWS;
} else {
    $user_reviews = 'cid=' . $down_arr['cid'] . '&amp;lid=' . $down_arr['lid'] . '">' . _MD_XTORRENT_NOUSERREVIEWS;
}
$xoopsTpl->assign('lang_user_reviews', $xoopsConfig['sitename'] . ' ' . _MD_XTORRENT_USERREVIEWSTITLE);
$xoopsTpl->assign('lang_UserReviews', sprintf($user_reviews, $down_arr['title']));

if (isset($xoopsModuleConfig['copyright']) && 1 == $xoopsModuleConfig['copyright']) {
    $xoopsTpl->assign('lang_copyright', '' . $down['title'] . ' © ' . _MD_XTORRENT_COPYRIGHT . ' ' . date('Y') . ' ' . XOOPS_URL);
}

// GETS TORRENT DATA FROM DATABASE
$sql = [];
$sql[0] = 'SELECT torrent, tracker FROM ' . $xoopsDB->prefix('xtorrent_poll') . ' WHERE lid = ' . $down['id'];
$sql[1] = 'SELECT seeds, leechers, tracker FROM ' . $xoopsDB->prefix('xtorrent_tracker') . ' WHERE lid = ' . $down['id'];
$sql[2] = 'SELECT seeds, leechers, totalsize, modifiedby, tname FROM ' . $xoopsDB->prefix('xtorrent_torrent') . ' WHERE lid = ' . $down['id'];
$sql[3] = 'SELECT file FROM ' . $xoopsDB->prefix('xtorrent_files') . ' WHERE lid = ' . $down['id'];
//print_r($sql);
$ret = [];
$ret[0] = $xoopsDB->query($sql[0]);
$ret[1] = $xoopsDB->query($sql[1]);
$ret[2] = $xoopsDB->query($sql[2]);
$ret[3] = $xoopsDB->query($sql[3]);

$poll = $xoopsDB->fetchArray($ret[0]);
$torrent = $xoopsDB->fetchArray($ret[2]);

$trkcr = [];
while (false !== ($row = $xoopsDB->fetchArray($ret[1]))) {
    $trkcr[] = [
        'seeds' => $row['seeds'],
        'leeches' => $row['leechers'],
        'tracker' => $row['tracker'],
    ];

    $down['total_seeds'] += $row['seeds'];

    $down['total_leeches'] += $row['leechers'];
}

$files = [];
while (false !== ($row = $xoopsDB->fetchArray($ret[3]))) {
    $files[] = ['file' => $row['file']];
}

$down['torrent_last_polled'] = date('H:i:s', $poll['torrent']);
$down['tracker_last_polled'] = date('H:i:s', $poll['tracker']);
$down['torrent'] = $torrent;
$down['total_seeds'] += $torrent['seeds'];
$down['total_leeches'] += $torrent['leechers'];
$down['tracker'] = $trkcr;
$down['files'] = $files;
//print_r($down);
$xoopsTpl->assign('down', $down);

require XOOPS_ROOT_PATH . '/include/comment_view.php';
require XOOPS_ROOT_PATH . '/footer.php';

// START TO CHECK FOR POLLING OF TORRENT

include 'include/pollall.php';

if (time() > $poll['torrent'] + ($xoopsModuleConfig['poll_torrent_time'] * 60) && 1 == $xoopsModuleConfig['poll_torrent']) {
    $rt = poll_torrent($down['id']);
}

if (time() > $poll['tracker'] + ($xoopsModuleConfig['poll_tracker_time'] * 60) && 1 == $xoopsModuleConfig['poll_tracker']) {
    $rt = poll_tracker($rt, $down['id'], $xoopsModuleConfig['poll_tracker_timeout']);
}
