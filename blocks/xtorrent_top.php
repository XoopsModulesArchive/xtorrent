<?php
/**
 * $Id: xtorrent.php v 1.03 05 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

/**
 * Function: b_mydownloads_top_show
 * Input   : $options[0] = date for the most recent downloads
 *                     hits for the most popular downloads
 *            $block['content'] = The optional above content
 *            $options[1]   = How many downloads are displayes
 * Output  : Returns the most recent or most popular downloads
 */
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

function b_XTORRENT_top_show($options)
{
    global $xoopsDB, $xoopsModule, $xoopsUser;

    $block = [];

    $myts = MyTextSanitizer::getInstance();

    $moduleHandler = xoops_getHandler('module');

    $xoopsModule = $moduleHandler->getByDirname('xtorrent');

    $configHandler = xoops_getHandler('config');

    $xoopsModuleConfig = &$configHandler->getConfigsByCat(0, $xoopsModule->getVar('mid'));

    $groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

    $gpermHandler = xoops_getHandler('groupperm');

    $result = $xoopsDB->query('SELECT lid, cid, title, date, hits FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE status > 0 AND offline = 0 ORDER BY ' . $options[0] . ' DESC', $options[1], 0);

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        if ($gpermHandler->checkRight('xtorrentownFilePerm', $myrow['lid'], $groups, $xoopsModule->getVar('mid'))) {
            $download = [];

            $title = htmlspecialchars($myrow['title'], ENT_QUOTES | ENT_HTML5);

            if (!XOOPS_USE_MULTIBYTES) {
                if (mb_strlen($myrow['title']) >= $options[2]) {
                    $title = htmlspecialchars(mb_substr($myrow['title'], 0, ($options[2] - 1)), ENT_QUOTES | ENT_HTML5) . '...';
                }
            }

            $download['id'] = $myrow['lid'];

            $download['cid'] = $myrow['cid'];

            $download['title'] = $title;

            if ('date' == $options[0]) {
                $download['date'] = formatTimestamp($myrow['date'], $xoopsModuleConfig['dateformat']);
            } elseif ('hits' == $options[0]) {
                $download['hits'] = $myrow['hits'];
            }

            $download['dirname'] = $xoopsModule->dirname();

            $block['downloads'][] = $download;
        }
    }

    return $block;
}

function b_XTORRENT_top_edit($options)
{
    $form = '' . _MB_XTORRENT_DISP . '&nbsp;';

    $form .= "<input type='hidden' name='options[]' value='";

    if ('date' == $options[0]) {
        $form .= "date'";
    } else {
        $form .= "hits'";
    }

    $form .= '>';

    $form .= "<input type='text' name='options[]' value='" . $options[1] . "'>&nbsp;" . _MB_XTORRENT_FILES . '';

    $form .= '&nbsp;<br>' . _MB_XTORRENT_CHARS . "&nbsp;<input type='text' name='options[]' value='" . $options[2] . "'>&nbsp;" . _MB_XTORRENT_LENGTH . '';

    return $form;
}
