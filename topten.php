<?php
/**
 * $Id: topten.php v 1.0.3 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

global $xoopsDB, $xoopsUser;

$mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');
$GLOBALS['xoopsOption']['template_main'] = 'xtorrent_topten.html';

$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule->getVar('mid');
$gpermHandler = xoops_getHandler('groupperm');

require XOOPS_ROOT_PATH . '/header.php';

$action_array = ['hit' => 0, 'rate' => 1];
$list_array = ['hits', 'rating'];
$lang_array = [_MD_XTORRENT_HITS, _MD_XTORRENT_RATING];

$sort = (isset($_GET['list']) && in_array($_GET['list'], $action_array, true)) ? $_GET['list'] : 'rate';
$sortthis = $action_array[$sort];
$sortDB = $list_array[$sortthis];

$catarray['imageheader'] = xtorrent_imageheader();
$catarray['letters'] = xtorrent_letters();
$catarray['toolbar'] = xtorrent_toolbar();
$xoopsTpl->assign('catarray', $catarray);

$arr = [];
$result = $xoopsDB->query('SELECT cid, title FROM ' . $xoopsDB->prefix('xtorrent_cat') . ' WHERE pid=0');

$e = 0;
$rankings = [];
while (list($cid, $ctitle) = $xoopsDB->fetchRow($result)) {
    if ($gpermHandler->checkRight('xtorrentownCatPerm', $cid, $groups, $module_id)) {
        $query = 'SELECT lid, cid, title, hits, rating, votes, platform FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ") AND offline = 0 AND (cid=$cid";

        $arr = $mytree->getAllChildId($cid);

        for ($i = 0, $iMax = count($arr); $i < $iMax; $i++) {
            $query .= ' or cid=' . $arr[$i] . '';
        }

        $query .= ') order by ' . $sortDB . ' DESC';

        $result2 = $xoopsDB->query($query, 10, 0);

        $filecount = $xoopsDB->getRowsNum($result2);

        if ($filecount > 0) {
            $rankings[$e]['title'] = htmlspecialchars($ctitle, ENT_QUOTES | ENT_HTML5);

            $rank = 1;

            while (list($did, $dcid, $dtitle, $hits, $rating, $votes) = $xoopsDB->fetchRow($result2)) {
                if ($gpermHandler->checkRight('xtorrentownFilePerm', $did, $groups, $xoopsModule->getVar('mid'))) {
                    $catpath = $mytree->getPathFromId($dcid, 'title');

                    $catpath = basename($catpath);

                    $dtitle = htmlspecialchars($dtitle, ENT_QUOTES | ENT_HTML5);

                    //if ($catpath != $ctitle)

                    //{

                    //    $dtitle = $myts -> htmlSpecialChars($ctitle); //. $ctitle;

                    //}

                    $rankings[$e]['file'][] = ['id' => $did, 'cid' => $dcid, 'rank' => $rank, 'title' => $dtitle, 'category' => $catpath, 'hits' => $hits, 'rating' => number_format($rating, 2), 'votes' => $votes];

                    $rank++;
                }
            }

            $e++;
        }
    }
}

$xoopsTpl->assign('lang_sortby', $lang_array[$this]);

$xoopsTpl->assign('rankings', $rankings);
require XOOPS_ROOT_PATH . '/footer.php';

require __DIR__ . '/footer.php';
