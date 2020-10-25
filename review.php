<?php
/**
 * $Id: review.php v 1.03 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';

global $xoopsModuleConfig, $myts;

if (!is_object($xoopsUser) && !$xoopsModuleConfig['anonpost']) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_XTORRENT_MUSTREGFIRST);

    exit();
}

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

$cid = (int)$cid;
$lid = (int)$lid;

switch (isset($op) && !empty($op)) {
    case 'list':

        global $xoopsDB, $xoopsModuleConfig, $myts;
        $start = isset($_GET['start']) ? (int)$_GET['start'] : 0;

        require XOOPS_ROOT_PATH . '/header.php';
        $GLOBALS['xoopsOption']['template_main'] = 'xtorrent_reviews.html';

        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_indexpage') . ' ';
        $head_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
        $catarray['imageheader'] = xtorrent_imageheader();
        $catarray['letters'] = xtorrent_letters();
        $catarray['toolbar'] = xtorrent_toolbar();
        $xoopsTpl->assign('catarray', $catarray);

        $sql_review = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_reviews') . ' WHERE lid = ' . (int)$lid . ' AND submit = 1 ORDER by date';
        $result_review = $xoopsDB->query($sql_review, 5, $start);
        $result_count = $xoopsDB->query($sql_review);
        $review_amount = $xoopsDB->getRowsNum($result_count);

        $sql = 'SELECT title, lid, cid FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE lid = ' . $lid . '';
        $down_arr_text = $xoopsDB->fetchArray($xoopsDB->query($sql));
        $down_arr['title'] = htmlspecialchars($myts->stripSlashesGPC($down_arr_text['title']), ENT_QUOTES | ENT_HTML5);
        $down_arr['cid'] = (int)$down_arr_text['cid'];
        $down_arr['lid'] = (int)$down_arr_text['lid'];
        $down_arr['description'] = $myts->displayTarea($down_arr_text['description'], 1, 1, 1, 1, 0);
        $xoopsTpl->assign('down_arr', $down_arr);

        while (false !== ($arr_review = $xoopsDB->fetchArray($result_review))) {
            $down_review['review_id'] = (int)$arr_review['review_id'];

            $down_review['lid'] = (int)$arr_review['lid'];

            $down_review['title'] = $myts->censorString($arr_review['title']);

            $down_review['title'] = htmlspecialchars($myts->stripSlashesGPC($down_review['title']), ENT_QUOTES | ENT_HTML5);

            $down_review['review'] = $myts->censorString($arr_review['review']);

            $down_review['review'] = $myts->displayTarea($down_review['review'], 0, 0, 0, 0, 0);

            $down_review['date'] = formatTimestamp($arr_review['date'], $xoopsModuleConfig['dateformat']);

            $down_review['submitter'] = XoopsUserUtility::getUnameFromId((int)$arr_review['uid']);

            $review_rating = round(number_format($arr_review['rated'], 0) / 2);

            $rateimg = "rate$review_rating.gif";

            $down_review['rated_img'] = $rateimg;

            $xoopsTpl->append('down_review', $down_review);
        }
        $xoopsTpl->assign('lang_review_found', sprintf(_MD_XTORRENT_REVIEWTOTAL, $review_amount));

        require_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $pagenav = new XoopsPageNav($review_amount, 5, $start, 'start', 'op=list&amp;cid=' . $cid . '&amp;lid=' . $lid . '', 1);
        $navbar['navbar'] = $pagenav->renderNav();
        $xoopsTpl->assign('navbar', $navbar);

        require XOOPS_ROOT_PATH . '/footer.php';
        break;
    case 'default':
    default:
        if (!empty($_POST['submit'])) {
            $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

            $title = $myts->addSlashes(trim($_POST['title']));

            $review = $myts->addSlashes(trim($_POST['review']));

            $lid = (int)trim($_POST['lid']);

            $rated = (int)trim($_POST['rated']);

            $date = time();

            $submit = ($xoopsModuleConfig['autoapprove']) ? 1 : 0;

            $sql = 'INSERT INTO ' . $xoopsDB->prefix('xtorrent_reviews') . " (review_id, lid, title, review, submit, date, uid, rated) VALUES ('', $lid, '$title', '$review', '$submit', $date, $uid, $rated)";

            $result = $xoopsDB->query($sql);

            if (!$result) {
                $error = _MD_XTORRENT_ERROR_CREATCHANNEL . $sql;

                trigger_error($error, E_USER_ERROR);
            } else {
                $database_mess = ($xoopsModuleConfig['autoapprove']) ? _MD_XTORRENT_ISAPPROVED : _MD_XTORRENT_ISNOTAPPROVED;

                redirect_header('index.php', 2, $database_mess);
            }
        } else {
            require XOOPS_ROOT_PATH . '/header.php';

            require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

            echo "
				<div align='center'>" . xtorrent_imageheader() . "</div><br>\n
				<div>" . _MD_XTORRENT_REV_SNEWMNAMEDESC . "</div>\n";

            $sform = new XoopsThemeForm(_MD_XTORRENT_REV_SUBMITREV, 'reviewform', xoops_getenv('PHP_SELF'));

            $sform->addElement(new XoopsFormText(_MD_XTORRENT_REV_TITLE, 'title', 50, 255), true);

            $rating_select = new XoopsFormSelect(_MD_XTORRENT_REV_RATING, 'rated', '10');

            $rating_select->addOptionArray(['1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10]);

            $sform->addElement($rating_select);

            $sform->addElement(new XoopsFormDhtmlTextArea(_MD_XTORRENT_REV_DESCRIPTION, 'review', '', 15, 60), true);

            $sform->addElement(new XoopsFormHidden('lid', $_GET['lid']));

            $button_tray = new XoopsFormElementTray('', '');

            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));

            $sform->addElement($button_tray);

            $sform->display();

            require XOOPS_ROOT_PATH . '/footer.php';
        }
}
