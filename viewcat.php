<?php
/**
 * $Id: viewcat.php v 1.05 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

global $xoopsModuleConfig, $myts, $xoopsModules;

$start = isset($_GET['start']) ? (int)$_GET['start'] : 0;
$orderby = isset($_GET['orderby']) ? convertorderbyin($_GET['orderby']) : $xoopsModuleConfig['filexorder'];
$cid = (isset($_GET['cid']) && $_GET['cid'] > 0) ? (int)$_GET['cid'] : 0;

$GLOBALS['xoopsOption']['template_main'] = 'xtorrent_viewcat.html';
$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$gpermHandler = xoops_getHandler('groupperm');

/**
 * Begin Main page Heading etc
 */
require XOOPS_ROOT_PATH . '/header.php';

$catarray['imageheader'] = xtorrent_imageheader();
$catarray['letters'] = xtorrent_letters();
$catarray['toolbar'] = xtorrent_toolbar();
$xoopsTpl->assign('catarray', $catarray);

/**
 * Breadcrumb
 */
$mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');
$pathstring = "<a href='index.php'>" . _MD_XTORRENT_MAIN . '</a>&nbsp;:&nbsp;';
$pathstring .= $mytree->getNicePathFromId($cid, 'title', 'viewcat.php?op=');
$xoopsTpl->assign('category_path', $pathstring);
$xoopsTpl->assign('category_id', $cid);

$arr = $mytree->getFirstChild($cid, 'weight');

/**
 * Display Sub-categories for selected Category
 */
if (is_array($arr) > 0 && !isset($_GET['list']) && !isset($_GET['selectdate'])) {
    $scount = 1;

    foreach ($arr as $ele) {
        if (!$gpermHandler->checkRight('xtorrentownCatPerm', $ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
            continue;
        }

        $sub_arr = [];

        $sub_arr = $mytree->getFirstChild($ele['cid'], 'weight');

        $space = 0;

        $chcount = 0;

        $infercategories = '';

        foreach ($sub_arr as $sub_ele) {
            /**
             * Subitem file count
             */

            $hassubitems = xtorrent_getTotalItems($sub_ele['cid']);

            /**
             * Filter group permissions
             */

            if ($gpermHandler->checkRight('xtorrentownCatPerm', $sub_ele['cid'], $groups, $xoopsModule->getVar('mid'))) {
                /**
                 * If subcategory count > 5 then finish adding subcats to $infercategories and end
                 */

                if ($chcount > 5) {
                    $infercategories .= '...';

                    break;
                }

                if ($space > 0) {
                    $infercategories .= ', ';
                }

                $infercategories .= "<a href='" . XOOPS_URL . '/modules/xtorrent/viewcat.php?cid=' . $sub_ele['cid'] . "'>" . htmlspecialchars($sub_ele['title'], ENT_QUOTES | ENT_HTML5) . '</a> (' . $hassubitems['count'] . ')';

                $space++;

                $chcount++;
            }
        }

        $totallinks = xtorrent_getTotalItems($ele['cid']);

        $xoopsTpl->append(
            'subcategories',
            [
                'title' => htmlspecialchars($ele['title'], ENT_QUOTES | ENT_HTML5),
                'id' => $ele['cid'],
                'infercategories' => $infercategories,
                'totallinks' => $totallinks['count'],
                'count' => $scount,
            ]
        );

        $scount++;
    }
}

/**
 * Show Description for Category listing
 */
$sql = 'SELECT description, nohtml, nosmiley, noxcodes, noimages, nobreak FROM ' . $xoopsDB->prefix('xtorrent_cat') . " WHERE cid = $cid";
$head_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
$html = ($head_arr['nohtml']) ? 0 : 1;
$smiley = ($head_arr['nosmiley']) ? 0 : 1;
$xcodes = ($head_arr['noxcodes']) ? 0 : 1;
$images = ($head_arr['noimages']) ? 0 : 1;
$breaks = ($head_arr['nobreak']) ? 1 : 0;
$description = $myts->displayTarea($head_arr['description'], $html, $smiley, $xcodes, $images, $breaks);
$xoopsTpl->assign('description', $description);

/**
 * Extract Download information from database
 */
$xoopsTpl->assign('show_categort_title', true);
$sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' ';
if (isset($_GET['selectdate'])) {
    $sql .= 'WHERE TO_DAYS(FROM_UNIXTIME(published)) = TO_DAYS(FROM_UNIXTIME(' . $_GET['selectdate'] . ')) 
			AND published > 0 AND published <= ' . time() . ' AND (expired = 0 OR expired > ' . time() . ') 
			AND offline = 0 ORDER BY published DESC';

    $result = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $start);

    $total_numrows['count'] = $xoopsDB->getRowsNum($xoopsDB->query($sql));
} elseif (isset($_GET['list'])) {
    $sql .= "WHERE title LIKE '" . $_GET['list'] . "%' AND published > 0 AND 
			published <= " . time() . ' AND (expired = 0 OR expired > ' . time() . ') AND offline = 0 
			ORDER BY ' . $orderby;

    $result = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $start);

    $total_numrows = xtorrent_getTotalItems($cid);
} else {
    $sql .= 'WHERE cid=' . $cid . ' AND published > 0 AND published <= ' . time() . ' 
			AND (expired = 0 OR expired > ' . time() . ') AND offline = 0 
			ORDER BY ' . $orderby;

    $result = $xoopsDB->query($sql, $xoopsModuleConfig['perpage'], $start);

    $xoopsTpl->assign('show_categort_title', false);

    $total_numrows = xtorrent_getTotalItems($cid);
}
/**
 * Show Downloads by file
 */
if ($total_numrows['count'] > 0) {
    while (false !== ($down_arr = $xoopsDB->fetchArray($result))) {
        if ($gpermHandler->checkRight('xtorrentownFilePerm', $down_arr['lid'], $groups, $xoopsModule->getVar('mid'))) {
            require XOOPS_ROOT_PATH . '/modules/xtorrent/include/downloadinfo.php';
        }
    }

    /**
     * Show order box
     */

    $xoopsTpl->assign('show_links', false);

    if ($total_numrows['count'] > 1 && 0 != $cid) {
        $xoopsTpl->assign('show_links', true);

        $orderbyTrans = convertorderbytrans($orderby);

        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_XTORRENT_CURSORTBY, convertorderbytrans($orderby)));

        $orderby = convertorderbyout($orderby);
    }

    /**
     * Screenshots display
     */

    $xoopsTpl->assign('show_screenshot', false);

    if (isset($xoopsModuleConfig['screenshot']) && 1 == $xoopsModuleConfig['screenshot']) {
        $xoopsTpl->assign('shots_dir', $xoopsModuleConfig['screenshots']);

        $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);

        $xoopsTpl->assign('shotheight', $xoopsModuleConfig['shotheight']);

        $xoopsTpl->assign('show_screenshot', true);
    }

    /**
     * Nav page render
     */

    require_once XOOPS_ROOT_PATH . '/class/pagenav.php';

    if (isset($_GET['selectdate'])) {
        $pagenav = new XoopsPageNav($total_numrows['count'], $xoopsModuleConfig['perpage'], $start, 'start', 'list=' . $_GET['selectdate']);
    } else {
        $pagenav = new XoopsPageNav($total_numrows['count'], $xoopsModuleConfig['perpage'], $start, 'start', 'cid=' . $cid);
    }

    $page_nav = $pagenav->renderNav();

    $istrue = (isset($page_nav) && !empty($page_nav)) ? true : false;

    $xoopsTpl->assign('page_nav', $istrue);

    $xoopsTpl->assign('pagenav', $page_nav);
}
require XOOPS_ROOT_PATH . '/footer.php';
