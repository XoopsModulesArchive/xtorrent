<?php
/* $Id: search.inc.php,v 1.7 2003/03/25 11:08:20 liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

function xtorrent_search($queryarray, $andor, $limit, $offset, $userid)
{
    global $xoopsDB, $xoopsUser;

    $sql = 'SELECT lid, cid, title, submitter, published, description FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' 
	WHERE status >0 AND published > 0';

    if (0 != $userid) {
        $sql .= ' AND submitter=' . $userid . ' ';
    }

    // because count() returns 1 even if a supplied variable

    // is not an array, we must check if $querryarray is really an array

    if (is_array($queryarray) && $count = count($queryarray)) {
        $sql .= " AND ((title LIKE '%$queryarray[0]%' OR description LIKE '%$queryarray[0]%')";

        for ($i = 1; $i < $count; $i++) {
            $sql .= " $andor ";

            $sql .= "(title LIKE '%$queryarray[$i]%' OR description LIKE '%$queryarray[$i]%')";
        }

        $sql .= ') ';
    }

    $sql .= 'ORDER BY date DESC';

    $result = $xoopsDB->query($sql, $limit, $offset);

    $ret = [];

    $i = 0;

    $groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

    $moduleHandler = xoops_getHandler('module');

    $xoopsxtorrentModule = $moduleHandler->getByDirname('xtorrent');

    $gpermHandler = xoops_getHandler('groupperm');

    while (false !== ($myrow = $xoopsDB->fetchArray($result))) {
        if (!$gpermHandler->checkRight('xtorrentownFilePerm', $myrow['cid'], $groups, $xoopsxtorrentModule->getVar('mid'))) {
            continue;
        }

        $ret[$i]['image'] = 'images/size2.gif';

        $ret[$i]['link'] = 'singlefile.php?cid=' . $myrow['cid'] . '&amp;lid=' . $myrow['lid'] . '';

        $ret[$i]['title'] = $myrow['title'];

        $ret[$i]['time'] = $myrow['published'];

        $ret[$i]['uid'] = $myrow['submitter'];

        $i++;
    }

    return $ret;
}
