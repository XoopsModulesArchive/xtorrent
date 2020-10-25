<?php

/**
 * $Id: brokendown.php v 1.03 06 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/admin_header.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

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

function createcat($cid = 0)
{
    require_once dirname(__DIR__) . '/class/xtorrent_lists.php';

    require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    global $xoopsDB, $myts, $xoopsModuleConfig, $totalcats, $xoopsModule;

    $lid = 0;

    $title = '';

    $imgurl = '';

    $description = '';

    $summary = '';

    $pid = '';

    $weight = 0;

    $nohtml = 0;

    $nosmiley = 0;

    $noxcodes = 0;

    $noimages = 0;

    $nobreak = 1;

    $spotlighttop = 0;

    $spotlighthis = 0;

    $heading = _AM_XTORRENT_CCATEGORY_CREATENEW;

    $totalcats = xtorrent_totalcategory();

    if ($cid) {
        $sql = 'SELECT * FROM ' . $xoopsDB->prefix('xtorrent_cat') . " WHERE cid=$cid";

        $cat_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));

        $title = htmlspecialchars($cat_arr['title'], ENT_QUOTES | ENT_HTML5);

        $imgurl = htmlspecialchars($cat_arr['imgurl'], ENT_QUOTES | ENT_HTML5);

        $description = htmlspecialchars($cat_arr['description'], ENT_QUOTES | ENT_HTML5);

        $summary = htmlspecialchars($cat_arr['summary'], ENT_QUOTES | ENT_HTML5);

        $nohtml = (int)$cat_arr['nohtml'];

        $nosmiley = (int)$cat_arr['nosmiley'];

        $noxcodes = (int)$cat_arr['noxcodes'];

        $noimages = (int)$cat_arr['noimages'];

        $nobreak = (int)$cat_arr['nobreak'];

        $spotlighthis = (int)$cat_arr['spotlighthis'];

        $spotlighttop = (int)$cat_arr['spotlighttop'];

        $weight = $cat_arr['weight'];

        $heading = _AM_XTORRENT_CCATEGORY_MODIFY;

        $memberHandler = xoops_getHandler('member');

        $group_list = $memberHandler->getGroupList();

        $gpermHandler = xoops_getHandler('groupperm');

        $groups = $gpermHandler->getGroupIds('xtorrentownCatPerm', $cid, $xoopsModule->getVar('mid'));

        $groups = $groups;
    } else {
        $groups = true;
    }

    $sform = new XoopsThemeForm($heading, 'op', xoops_getenv('PHP_SELF'));

    $sform->setExtra('enctype="multipart/form-data"');

    $sform->addElement(new XoopsFormSelectGroup(_AM_XTORRENT_FCATEGORY_GROUPPROMPT, 'groups', true, $groups, 5, true));

    if ($totalcats > 0) {
        $mytreechose = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');

        ob_start();

        $mytreechose->makeMySelBox('title', 'title', 0, 1, 'pid');

        $sform->addElement(new XoopsFormLabel(_AM_XTORRENT_FCATEGORY_SUBCATEGORY, ob_get_contents()));

        ob_end_clean();
    }

    $sform->addElement(new XoopsFormText(_AM_XTORRENT_FCATEGORY_TITLE, 'title', 50, 80, $title), true);

    $sform->addElement(new XoopsFormText(_AM_XTORRENT_FCATEGORY_WEIGHT, 'weight', 10, 80, $weight), false);

    $graph_array = &WfsLists:: getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['catimage'], $type = 'images');

    $indeximage_select = new XoopsFormSelect('', 'imgurl', $imgurl);

    $indeximage_select->addOptionArray($graph_array);

    $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $xoopsModuleConfig['catimage'] . '", "", "' . XOOPS_URL . "\")'");

    $indeximage_tray = new XoopsFormElementTray(_AM_XTORRENT_FCATEGORY_CIMAGE, '&nbsp;');

    $indeximage_tray->addElement($indeximage_select);

    if (!empty($imgurl)) {
        $indeximage_tray->addElement(new XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . '/' . $xoopsModuleConfig['catimage'] . '/' . $imgurl . "' name='image' id='image' alt=''>"));
    } else {
        $indeximage_tray->addElement(new XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt=''>"));
    }

    $sform->addElement($indeximage_tray);

    $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XTORRENT_FCATEGORY_DESCRIPTION, 'description', $description, 15, 60), true);

    $sform->addElement(new XoopsFormTextArea(_AM_XTORRENT_FCATEGORY_SUMMARY, 'summary', $summary, 10, 60));

    $options_tray = new XoopsFormElementTray(_AM_XTORRENT_TEXTOPTIONS, '<br>');

    $html_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);

    $html_checkbox->addOption(1, _AM_XTORRENT_DISABLEHTML);

    $options_tray->addElement($html_checkbox);

    $smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);

    $smiley_checkbox->addOption(1, _AM_XTORRENT_DISABLESMILEY);

    $options_tray->addElement($smiley_checkbox);

    $xcodes_checkbox = new XoopsFormCheckBox('', 'noxcodes', $noxcodes);

    $xcodes_checkbox->addOption(1, _AM_XTORRENT_DISABLEXCODE);

    $options_tray->addElement($xcodes_checkbox);

    $noimages_checkbox = new XoopsFormCheckBox('', 'noimages', $noimages);

    $noimages_checkbox->addOption(1, _AM_XTORRENT_DISABLEIMAGES);

    $options_tray->addElement($noimages_checkbox);

    $breaks_checkbox = new XoopsFormCheckBox('', 'nobreak', $nobreak);

    $breaks_checkbox->addOption(1, _AM_XTORRENT_DISABLEBREAK);

    $options_tray->addElement($breaks_checkbox);

    $sform->addElement($options_tray);

    $sform->addElement(new XoopsFormHidden('cid', $cid));

    $sform->addElement(new XoopsFormHidden('spotlighttop', $cid));

    $button_tray = new XoopsFormElementTray('', '');

    $hidden = new XoopsFormHidden('op', 'save');

    $button_tray->addElement($hidden);

    if (!$cid) {
        $butt_create = new XoopsFormButton('', '', _AM_XTORRENT_BSAVE, 'submit');

        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addCat\'"');

        $button_tray->addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_XTORRENT_BRESET, 'reset');

        $button_tray->addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_XTORRENT_BCANCEL, 'button');

        $butt_cancel->setExtra('onclick="history.go(-1)"');

        $button_tray->addElement($butt_cancel);
    } else {
        $butt_create = new XoopsFormButton('', '', _AM_XTORRENT_BMODIFY, 'submit');

        $butt_create->setExtra('onclick="this.form.elements.op.value=\'addCat\'"');

        $button_tray->addElement($butt_create);

        $butt_delete = new XoopsFormButton('', '', _AM_XTORRENT_BDELETE, 'submit');

        $butt_delete->setExtra('onclick="this.form.elements.op.value=\'delCat\'"');

        $button_tray->addElement($butt_delete);

        $butt_cancel = new XoopsFormButton('', '', _AM_XTORRENT_BCANCEL, 'button');

        $butt_cancel->setExtra('onclick="history.go(-1)"');

        $button_tray->addElement($butt_cancel);
    }

    $sform->addElement($button_tray);

    $sform->display();

    $result2 = $xoopsDB->query('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xtorrent_cat') . '');

    [$numrows] = $xoopsDB->fetchRow($result2);
}

if (!isset($_POST['op'])) {
    $op = $_GET['op'] ?? 'main';
} else {
    $op = $_POST['op'] ?? 'main';
}

switch ($op) {
    case 'move':
        if (!isset($_POST['ok'])) {
            $cid = (isset($_POST['cid'])) ? (int)$_POST['cid'] : (int)$_GET['cid'];

            xoops_cp_header();

            xtorrent_adminmenu(_AM_XTORRENT_MCATEGORY);

            require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

            $mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');

            $sform = new XoopsThemeForm(_AM_XTORRENT_CCATEGORY_MOVE, 'move', xoops_getenv('PHP_SELF'));

            ob_start();

            $mytree->makeMySelBox('title', 'title', 0, 0, 'target');

            $sform->addElement(new XoopsFormLabel(_AM_XTORRENT_BMODIFY, ob_get_contents()));

            ob_end_clean();

            $create_tray = new XoopsFormElementTray('', '');

            $create_tray->addElement(new XoopsFormHidden('source', $cid));

            $create_tray->addElement(new XoopsFormHidden('ok', 1));

            $create_tray->addElement(new XoopsFormHidden('op', 'move'));

            $butt_save = new XoopsFormButton('', '', _AM_XTORRENT_BMOVE, 'submit');

            $butt_save->setExtra('onclick="this.form.elements.op.value=\'move\'"');

            $create_tray->addElement($butt_save);

            $butt_cancel = new XoopsFormButton('', '', _AM_XTORRENT_BCANCEL, 'submit');

            $butt_cancel->setExtra('onclick="this.form.elements.op.value=\'cancel\'"');

            $create_tray->addElement($butt_cancel);

            $sform->addElement($create_tray);

            $sform->display();

            xoops_cp_footer();
        } else {
            global $xoopsDB;

            $source = $_POST['source'];

            $target = $_POST['target'];

            if ($target == $source) {
                redirect_header("category.php?op=move&amp;ok=0&amp;cid=$source", 5, _AM_XTORRENT_CCATEGORY_MODIFY_FAILED);
            }

            if (!$target) {
                redirect_header("category.php?op=move&amp;ok=0&amp;cid=$source", 5, _AM_XTORRENT_CCATEGORY_MODIFY_FAILEDT);
            }

            $sql = 'UPDATE ' . $xoopsDB->prefix('xtorrent_downloads') . ' set cid = ' . $target . ' WHERE cid =' . $source;

            $result = $xoopsDB->queryF($sql);

            $error = _AM_XTORRENT_DBERROR . ': <br><br>' . $sql;

            if (!$result) {
                trigger_error($error, E_USER_ERROR);
            }

            redirect_header('category.php?op=default', 1, _AM_XTORRENT_CCATEGORY_MODIFY_MOVED);

            exit();
        }
        break;
    case 'addCat':

        global $xoopsDB, $myts, $_FILES, $xoopsModuleConfig;

        $groups = $_POST['groups'] ?? [];
        $cid = $_POST['cid'] ?? 0;
        $pid = $_POST['pid'] ?? 0;
        $weight = (isset($_POST['weight']) && $_POST['weight'] > 0) ? $_POST['weight'] : 0;
        $spotlighthis = $_POST['lid'] ?? 0;
        $spotlighttop = (1 == $_POST['spotlighttop']) ? 1 : 0;
        $title = $myts->addSlashes($_POST['title']);
        $description = $myts->addSlashes($_POST['description']);
        $summary = $myts->addSlashes($_POST['summary']);
        $imgurl = ($_POST['imgurl'] && 'blank.png' != $_POST['imgurl']) ? $myts->addSlashes($_POST['imgurl']) : '';

        $nohtml = isset($_POST['nohtml']);
        $nosmiley = isset($_POST['nosmiley']);
        $noxcodes = isset($_POST['noxcodes']);
        $noimages = isset($_POST['noimages']);
        $nobreak = isset($_POST['nobreak']);

        if (!$cid) {
            $sql = 'INSERT INTO ' . $xoopsDB->prefix('xtorrent_cat') . " 
				(cid, pid, title, imgurl, description, summary, nohtml, nosmiley, 
				noxcodes, noimages, nobreak, weight, spotlighttop, spotlighthis) VALUES 
				('', $pid, '$title', '$imgurl', '$description', '$summary', '$nohtml', '$nosmiley', 
				'$noxcodes', '$noimages', '$nobreak', '$weight',  $spotlighttop, $spotlighthis)";

            $result = $xoopsDB->query($sql);

            $error = _AM_XTORRENT_DBERROR . ': <br><br>' . $sql;

            if (0 == $cid) {
                $newid = $xoopsDB->getInsertId();
            }

            xtorrent_save_Permissions($groups, $newid, 'xtorrentownCatPerm');

            /**
             * Notify of new category
             */

            global $xoopsModule;

            $tags = [];

            $tags['CATEGORY_NAME'] = $title;

            $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/xtorrent/viewcat.php?cid=' . $newid;

            $notificationHandler = xoops_getHandler('notification');

            $notificationHandler->triggerEvent('global', 0, 'new_category', $tags);

            $database_mess = _AM_XTORRENT_CCATEGORY_CREATED;
        } else {
            $sql = 'UPDATE ' . $xoopsDB->prefix('xtorrent_cat') . " SET 
				title ='$title', imgurl = '$imgurl', pid =$pid, description = '$description', summary = '$summary', 
				spotlighthis = '$spotlighthis' , spotlighttop = '$spotlighttop', nohtml='$nohtml', nosmiley='$nosmiley', 
				noxcodes='$noxcodes', noimages='$noimages', nobreak='$nobreak', weight='$weight' WHERE cid = '$cid'";

            $result = $xoopsDB->query($sql);

            $error = _AM_XTORRENT_DBERROR . ': <br><br>' . $sql;

            $database_mess = _AM_XTORRENT_CCATEGORY_MODIFIED;

            xtorrent_save_Permissions($groups, $cid, 'xtorrentownCatPerm');
        }
        if (!$result) {
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header('category.php', 1, $database_mess);
        break;
    case 'del':

        global $xoopsDB, $xoopsModule;

        $cid = (isset($_POST['cid']) && is_numeric($_POST['cid'])) ? (int)$_POST['cid'] : (int)$_GET['cid'];
        $ok = (isset($_POST['ok']) && 1 == $_POST['ok']) ? (int)$_POST['ok'] : 0;
        $mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');

        if (1 == $ok) {
            // get all subcategories under the specified category

            $arr = $mytree->getAllChildId($cid);

            $lcount = count($arr);

            for ($i = 0; $i < $lcount; $i++) {
                // get all downloads in each subcategory

                $result = $xoopsDB->query('SELECT lid FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE cid=' . $arr[$i] . '');

                // now for each download, delete the text data and vote ata associated with the download

                while (list($lid) = $xoopsDB->fetchRow($result)) {
                    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('xtorrent_votedata'), $lid);

                    $xoopsDB->query($sql);

                    $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('xtorrent_downloads'), $lid);

                    $xoopsDB->query($sql);

                    // delete comments

                    xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'xtorrentownFilePerm', $lid);

                    xoops_comment_delete($xoopsModule->getVar('mid'), $lid);
                }

                // all downloads for each subcategory is deleted, now delete the subcategory data

                $sql = sprintf('DELETE FROM %s WHERE cid = %u', $xoopsDB->prefix('xtorrent_cat'), $arr[$i]);

                $xoopsDB->query($sql);
            }

            // all subcategory and associated data are deleted, now delete category data and its associated data

            $result = $xoopsDB->query('SELECT lid FROM ' . $xoopsDB->prefix('xtorrent_downloads') . ' WHERE cid=' . $cid . '');

            while (list($lid) = $xoopsDB->fetchRow($result)) {
                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('xtorrent_downloads'), $lid);

                $xoopsDB->query($sql);

                // delete comments

                xoops_comment_delete($xoopsModule->getVar('mid'), $lid);

                $sql = sprintf('DELETE FROM %s WHERE lid = %u', $xoopsDB->prefix('xtorrent_votedata'), $lid);

                $xoopsDB->query($sql);
            }

            $sql = sprintf('DELETE FROM %s WHERE cid = %u', $xoopsDB->prefix('xtorrent_cat'), $cid);

            $error = _AM_XTORRENT_DBERROR . ': <br><br>' . $sql;

            xoops_groupperm_deletebymoditem($xoopsModule->getVar('mid'), 'xtorrentownCatPerm', $cid);

            if (!$result = $xoopsDB->query($sql)) {
                trigger_error($error, E_USER_ERROR);
            }

            redirect_header('category.php', 1, _AM_XTORRENT_CCATEGORY_DELETED);

            exit();
        }  
            xoops_cp_header();
            xoops_confirm(['op' => 'del', 'cid' => $cid, 'ok' => 1], 'category.php', _AM_XTORRENT_CCATEGORY_AREUSURE);
            xoops_cp_footer();

        break;
    case 'modCat':
        $cid = $_POST['cid'] ?? 0;
        xoops_cp_header();
        xtorrent_adminmenu(_AM_XTORRENT_MCATEGORY);
        createcat($cid);
        xoops_cp_footer();
        break;
    case 'main':
    default:

        xoops_cp_header();
        xtorrent_adminmenu(_AM_XTORRENT_MCATEGORY);

        require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $mytree = new XoopsTree($xoopsDB->prefix('xtorrent_cat'), 'cid', 'pid');
        $sform = new XoopsThemeForm(_AM_XTORRENT_CCATEGORY_MODIFY, 'category', xoops_getenv('PHP_SELF'));
        $totalcats = xtorrent_totalcategory();

        if ($totalcats > 0) {
            ob_start();

            $sform->addElement(new XoopsFormHidden('cid', ''));

            $mytree->makeMySelBox('title', 'title');

            $sform->addElement(new XoopsFormLabel(_AM_XTORRENT_CCATEGORY_MODIFY_TITLE, ob_get_contents()));

            ob_end_clean();

            $dup_tray = new XoopsFormElementTray('', '');

            $dup_tray->addElement(new XoopsFormHidden('op', 'modCat'));

            $butt_dup = new XoopsFormButton('', '', _AM_XTORRENT_BMODIFY, 'submit');

            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'modCat\'"');

            $dup_tray->addElement($butt_dup);

            $butt_move = new XoopsFormButton('', '', _AM_XTORRENT_BMOVE, 'submit');

            $butt_move->setExtra('onclick="this.form.elements.op.value=\'move\'"');

            $dup_tray->addElement($butt_move);

            $butt_dupct = new XoopsFormButton('', '', _AM_XTORRENT_BDELETE, 'submit');

            $butt_dupct->setExtra('onclick="this.form.elements.op.value=\'del\'"');

            $dup_tray->addElement($butt_dupct);

            $sform->addElement($dup_tray);

            $sform->display();
        }
        createcat(0);
        xoops_cp_footer();
        break;
}
