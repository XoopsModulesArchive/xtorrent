<?php
/**
 * $Id: downloadinfo.php v 1.01 02 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
$down['id'] = (int)$down_arr['lid'];
$down['cid'] = (int)$down_arr['cid'];

$path = $mytree->getPathFromId($down_arr['cid'], 'title');
$path = mb_substr($path, 1);
$path = basename($path);
$path = str_replace('/', '', $path);
$down['category'] = $path;

$rating = round(number_format($down_arr['rating'], 0) / 2);
$rateimg = "rate$rating.gif";
$down['rateimg'] = $rateimg;
$down['votes'] = (1 == $down_arr['votes']) ? _MD_XTORRENT_ONEVOTE : sprintf(_MD_XTORRENT_NUMVOTES, $down_arr['votes']);
$down['hits'] = (int)$down_arr['hits'];

$xoopsTpl->assign('lang_dltimes', sprintf(_MD_XTORRENT_DLTIMES, $down['hits']));

$down['title'] = $down_arr['title'];
$down['url'] = $down_arr['url'];

if (isset($down_arr['screenshot'])) {
    $down['screenshot_full'] = htmlspecialchars($down_arr['screenshot'], ENT_QUOTES | ENT_HTML5);

    if (!empty($down_arr['screenshot']) && file_exists(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['screenshots'] . '/' . xoops_trim($down_arr['screenshot']))) {
        if (isset($xoopsModuleConfig['usethumbs']) && 1 == $xoopsModuleConfig['usethumbs']) {
            $down['screenshot_thumb'] = down_createthumb(
                $down['screenshot_full'],
                $xoopsModuleConfig['screenshots'],
                'thumbs',
                $xoopsModuleConfig['shotwidth'],
                $xoopsModuleConfig['shotheight'],
                $xoopsModuleConfig['imagequality'],
                $xoopsModuleConfig['updatethumbs'],
                $xoopsModuleConfig['keepaspect']
            );
        } else {
            $down['screenshot_thumb'] = XOOPS_URL . '/' . $xoopsModuleConfig['screenshots'] . '/' . xoops_trim($down_arr['screenshot']);
        }
    }
}

$down['homepage'] = (!$down_arr['homepage'] || 'http://' == $down_arr['homepage']) ? '' : htmlspecialchars(trim($down_arr['homepage']), ENT_QUOTES | ENT_HTML5);
if ($down['homepage'] && !empty($down['homepage'])) {
    $down['homepagetitle'] = (empty($down_arr['homepagetitle'])) ? trim($down['homepage']) : htmlspecialchars(trim($down_arr['homepagetitle']), ENT_QUOTES | ENT_HTML5);

    $down['homepage'] = "<a href='" . $down['homepage'] . "' target='_blank'>" . $down['homepagetitle'] . '</a>';
} else {
    $down['homepage'] = _MD_XTORRENT_NOTSPECIFIED;
}

$down['mirror'] = ('http://' == $down_arr['mirror']) ? '' : htmlspecialchars(trim($down_arr['mirror']), ENT_QUOTES | ENT_HTML5);
$down['mirror'] = ($down['mirror']) ? "<a href='" . $down['mirror'] . "' target='_blank'>" . _MD_XTORRENT_MIRRORSITE . '</a>' : _MD_XTORRENT_NOTSPECIFIED;
$down['comments'] = $down_arr['comments'];
$down['version'] = $down_arr['version'];
$down['downtime'] = xtorrent_GetDownloadTime((int)$down_arr['size'], 1, 1, 1, 1, 0);
$down['downtime'] = str_replace('|', '<br>', $down['downtime']);
$down['size'] = xtorrent_PrettySize((int)$down_arr['size']);

$time = (0 != $down_arr['updated']) ? $down_arr['updated'] : $down_arr['published'];
$down['updated'] = formatTimestamp($time, $xoopsModuleConfig['dateformat']);
$is_updated = (0 != $down_arr['updated']) ? _MD_XTORRENT_UPDATEDON : _MD_XTORRENT_SUBMITDATE;
$xoopsTpl->assign('lang_subdate', $is_updated);

$down['description'] = $myts->displayTarea($down_arr['description'], 0); //no html
$down['price'] = (0 != $down_arr['price']) ? (int)$down_arr['price'] : _MD_XTORRENT_PRICEFREE;
$down['limitations'] = (empty($down_arr['limitations'])) ? _MD_XTORRENT_NOTSPECIFIED : htmlspecialchars(trim($xoopsModuleConfig['limitations'][$down_arr['limitations']]), ENT_QUOTES | ENT_HTML5);
$down['license'] = (empty($down_arr['license'])) ? _MD_XTORRENT_NOTSPECIFIED : htmlspecialchars(trim($xoopsModuleConfig['license'][$down_arr['license']]), ENT_QUOTES | ENT_HTML5);
$down['submitter'] = XoopsUserUtility::getUnameFromId((int)$down_arr['submitter']);
$down['publisher'] = (isset($down_arr['publisher']) && !empty($down_arr['publisher'])) ? htmlspecialchars($down_arr['publisher'], ENT_QUOTES | ENT_HTML5) : _MD_XTORRENT_NOTSPECIFIED;
$down['platform'] = htmlspecialchars($xoopsModuleConfig['platform'][$down_arr['platform']], ENT_QUOTES | ENT_HTML5);
$down['history'] = $myts->displayTarea($down_arr['dhistory'], 1);
$down['features'] = '';
if ($down_arr['features']) {
    $downfeatures = explode('|', trim($down_arr['features']));

    foreach ($downfeatures as $bi) {
        $down['features'][] = $bi;
    }
}

$down['requirements'] = '';
if ($down_arr['requirements']) {
    $downrequirements = explode('|', trim($down_arr['requirements']));

    foreach ($downrequirements as $bi) {
        $down['requirements'][] = $bi;
    }
}
$down['mail_subject'] = rawurlencode(sprintf(_MD_XTORRENT_INTFILEFOUND, $xoopsConfig['sitename']));
$down['mail_body'] = rawurlencode(sprintf(_MD_XTORRENT_INTFILEFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/xtorrent/singlefile.php?cid=' . $down_arr['cid'] . '&amp;lid=' . $down_arr['lid']);

$down['isadmin'] = (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;

$down['adminlink'] = '';
if (true === $down['isadmin']) {
    $down['adminlink'] = '[ <a href="' . XOOPS_URL . '/modules/xtorrent/admin/index.php?op=Download&amp;lid=' . $down_arr['lid'] . '">' . _MD_XTORRENT_EDIT . '</a> | ';

    $down['adminlink'] .= '<a href="' . XOOPS_URL . '/modules/xtorrent/admin/index.php?op=delDownload&amp;lid=' . $down_arr['lid'] . '">' . _MD_XTORRENT_DELETE . '</a> ]';
}
$votestring = (1 == $down_arr['votes']) ? _MD_XTORRENT_ONEVOTE : sprintf(_MD_XTORRENT_NUMVOTES, $down_arr['votes']);
$is_updated = ($down_arr['updated'] > 0) ? _MD_XTORRENT_UPDATEDON : _MD_XTORRENT_SUBMITDATE;
$xoopsTpl->assign('lang_subdate', $is_updated);
if (is_object($xoopsUser) && true !== $down['isadmin']) {
    $down['useradminlink'] = ($xoopsUser->getvar('uid') == $down_arr['submitter']) ? true : false;
}

$sql2 = 'SELECT rated FROM ' . $xoopsDB->prefix('xtorrent_reviews') . ' WHERE lid = ' . $down_arr['lid'] . ' AND submit = 1';
$results = $xoopsDB->query($sql2);
$numrows = $xoopsDB->getRowsNum($results);

$down['reviews_num'] = ($numrows) ?: 0;

$finalrating = 0;
$totalrating = 0;

while (false !== ($review_text = $xoopsDB->fetchArray($results))) {
    $totalrating += $review_text['rated'];
}

if ($down['reviews_num'] > 0) {
    $finalrating = $totalrating / $down['reviews_num'];

    $finalrating = round(number_format($finalrating, 0) / 2);
}
$down['review_rateimg'] = "rate$finalrating.gif";

$moduleHandler = xoops_getHandler('module');
$xoopsforumModule = $moduleHandler->getByDirname('newbb');
if (is_object($xoopsforumModule) && $xoopsforumModule->getVar('isactive')) {
    $down['forumid'] = ($down_arr['forumid'] > 0) ? $down_arr['forumid'] : 0;
}

$down['icons'] = xtorrent_displayicons($down_arr['published'], $down_arr['status'], $down_arr['hits']);

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

$xoopsTpl->append('file', $down);
