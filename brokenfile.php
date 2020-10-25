<?php
/**
 * $Id: broken.php v 1.0.2 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';

if (!empty($_POST['submit'])) {
    global $xoopsModule, $xoopsModuleConfig, $xoopsUser;

    $sender = (is_object($xoopsUser)) ? $xoopsUser->getVar('uid') : 0;

    $ip = getenv('REMOTE_ADDR');

    $lid = (int)$_POST['lid'];

    $time = time();

    /*
    *  Check if REG user is trying to report twice.
    */

    $result = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xtorrent_broken') . " WHERE lid=$lid");

    [$count] = $xoopsDB->fetchRow($result);

    if ($count > 0) {
        redirect_header('index.php', 2, _MD_XTORRENT_ALREADYREPORTED);

        exit();
    }  

    $sql = sprintf('INSERT INTO ' . $xoopsDB->prefix('xtorrent_broken') . " (reportid, lid, sender, ip, date, confirmed, acknowledged ) VALUES ( '', '$lid', '$sender', '$ip', '$time', '0', '0')");

    $result = $xoopsDB->query($sql);

    $newid = $xoopsDB->getInsertId();

    $tags = [];

    $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/xtorrent/admin/index.php?op=listBrokenDownloads';

    $notificationHandler = xoops_getHandler('notification');

    $notificationHandler->triggerEvent('global', 0, 'file_broken', $tags);

    /**
     * Send email to the owner of the download stating that it is broken
     */

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid = $lid AND published > 0 AND published <= " . time() . ' AND (expired = 0 OR expired > ' . time() . ')';

    $down_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));

    unset($sql);

    $user = new XoopsUser((int)$down_arr['submitter']);

    $subdate = formatTimestamp($down_arr['date'], $xoopsModuleConfig['dateformat']);

    $cid = $down_arr['cid'];

    $title = $down_arr['title'];

    $subject = _MD_XTORRENT_BROKENREPORTED;

    $xoopsMailer = getMailer();

    $xoopsMailer->useMail();

    $template_dir = XOOPS_ROOT_PATH . '/modules/xtorrent/language/' . $xoopsConfig['language'] . '/mail_template';

    $xoopsMailer->setTemplateDir($template_dir);

    $xoopsMailer->setTemplate('filebroken_notify.tpl');

    $xoopsMailer->setToEmails($user->email());

    $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);

    $xoopsMailer->setFromName($xoopsConfig['sitename']);

    $xoopsMailer->assign('X_UNAME', $user->uname());

    $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);

    $xoopsMailer->assign('X_ADMINMAIL', $xoopsConfig['adminmail']);

    $xoopsMailer->assign('X_SITEURL', XOOPS_URL . '/');

    $xoopsMailer->assign('X_TITLE', $title);

    $xoopsMailer->assign('X_SUB_DATE', $subdate);

    $xoopsMailer->assign('X_DOWNLOAD', XOOPS_URL . '/modules/xtorrent/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid);

    $xoopsMailer->setSubject($subject);

    $xoopsMailer->send();

    redirect_header('index.php', 2, _MD_XTORRENT_BROKENREPORTED);

    exit();
}  
    $GLOBALS['xoopsOption']['template_main'] = 'xtorrent_brokenfile.html';
    require XOOPS_ROOT_PATH . '/header.php';
    /**
     * Begin Main page Heading etc
     */
    $catarray['imageheader'] = xtorrent_imageheader();
    $xoopsTpl->assign('catarray', $catarray);

    $lid = (isset($_GET['lid']) && $_GET['lid'] > 0) ? (int)$_GET['lid'] : 0;
    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid = $lid AND published > 0 AND published <= " . time() . ' AND (expired = 0 OR expired > ' . time() . ')';
    $down_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
    unset($sql);

    $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_broken') . " WHERE lid = $lid";
    $broke_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));

    if (is_array($broke_arr)) {
        global $xoopsModuleConfig;

        $broken['title'] = trim($down_arr['title']);

        $broken['id'] = $broke_arr['reportid'];

        $broken['reporter'] = XoopsUserUtility::getUnameFromId((int)$broke_arr['sender']);

        $broken['date'] = formatTimestamp($broke_arr['date'], $xoopsModuleConfig['dateformat']);

        $broken['acknowledged'] = (1 == $broke_arr['acknowledged']) ? _YES : _NO;

        $broken['confirmed'] = (1 == $broke_arr['confirmed']) ? _YES : _NO;

        $xoopsTpl->assign('broken', $broken);

        $xoopsTpl->assign('brokenreport', true);
    } else {
        $amount = $xoopsDB->getRowsNum($sql);

        if (!is_array($down_arr)) {
            redirect_header('index.php', 0, _MD_XTORRENT_THISFILEDOESNOTEXIST);

            exit();
        }

        /**
         * file info
         */

        $down['title'] = trim($down_arr['title']);

        $down['homepage'] = $myts->makeClickable(formatURL(trim($down_arr['homepage'])));

        $time = (0 != $down_arr['updated']) ? $down_arr['updated'] : $down_arr['published'];

        $down['updated'] = formatTimestamp($time, $xoopsModuleConfig['dateformat']);

        $is_updated = (0 != $down_arr['updated']) ? _MD_XTORRENT_UPDATEDON : _MD_XTORRENT_SUBMITDATE;

        $down['publisher'] = XoopsUserUtility::getUnameFromId((int)$down_arr['submitter']);

        $xoopsTpl->assign('file_id', $lid);

        $xoopsTpl->assign('lang_subdate', $is_updated);

        $xoopsTpl->assign('down', $down);
    }
    require_once XOOPS_ROOT_PATH . '/footer.php';

