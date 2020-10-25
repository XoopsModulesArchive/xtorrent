<?php
/**
 * $Id: indexpage.php v 1.0.3 05 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/admin_header.php';

if (isset($_POST)) {
    foreach ($_POST as $k => $v) {
        ${$k} = $v;
    }
}

$op = $_POST['op'] ?? '';

switch ($op) {
    case 'save':

        global $xoopsDB;

        $indexheading = $myts->addSlashes($_POST['indexheading']);
        $indexheader = $myts->addSlashes($_POST['indexheader']);
        $indexfooter = $myts->addSlashes($_POST['indexfooter']);
        $indeximage = $myts->addSlashes($_POST['indeximage']);
        $nohtml = isset($_POST['nohtml']);
        $nosmiley = isset($_POST['nosmiley']);
        $noxcodes = isset($_POST['noxcodes']);
        $noimages = isset($_POST['noimages']);
        $nobreak = isset($_POST['nobreak']);
        $indexheaderalign = $_POST['indexheaderalign'];
        $indexfooteralign = $_POST['indexfooteralign'];

        $xoopsDB->query(
            'update '
            . $xoopsDB->prefix('xtorrent_indexpage')
            . " set indexheading='$indexheading', indexheader='$indexheader', indexfooter='$indexfooter', indeximage='$indeximage', indexheaderalign='$indexheaderalign ', indexfooteralign='$indexfooteralign', nohtml='$nohtml', nosmiley='$nosmiley', noxcodes='$noxcodes', noimages='$noimages', nobreak='$nobreak' "
        );
        redirect_header('indexpage.php', 1, _AM_XTORRENT_IPAGE_UPDATED);
        exit();

        break;
    default:

        require_once dirname(__DIR__) . '/class/xtorrent_lists.php';
        require XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        global $xoopsModuleConfig, $xoopsDB;

        $result = $xoopsDB->query('SELECT indeximage, indexheading, indexheader, indexfooter, nohtml, nosmiley, noxcodes, noimages, nobreak, indexheaderalign, indexfooteralign FROM ' . $xoopsDB->prefix('xtorrent_indexpage') . ' ');
        [$indeximage, $indexheading, $indexheader, $indexfooter, $nohtml, $nosmiley, $noxcodes, $noimages, $nobreak, $indexheaderalign, $indexfooteralign] = $xoopsDB->fetchRow($result);

        xoops_cp_header();
        xtorrent_adminmenu(_AM_XTORRENT_INDEXPAGE);

        echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_XTORRENT_IPAGE_INFORMATION . "</legend>\n
		<div style='padding: 8px;'>" . _AM_XTORRENT_MINDEX_PAGEINFOTXT . "</div>\n
		</fieldset>\n
		";

        $sform = new XoopsThemeForm(_AM_XTORRENT_IPAGE_MODIFY, 'op', xoops_getenv('PHP_SELF'));
        $sform->addElement(new XoopsFormText(_AM_XTORRENT_IPAGE_CTITLE, 'indexheading', 60, 60, $indexheading), false);
        $graph_array = &WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . '/' . $xoopsModuleConfig['mainimagedir'], $type = 'images');
        $indeximage_select = new XoopsFormSelect('', 'indeximage', $indeximage);
        $indeximage_select->addOptionArray($graph_array);
        $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"indeximage\", \"" . $xoopsModuleConfig['mainimagedir'] . '", "", "' . XOOPS_URL . "\")'");
        $indeximage_tray = new XoopsFormElementTray(_AM_XTORRENT_IPAGE_CIMAGE, '&nbsp;');
        $indeximage_tray->addElement($indeximage_select);
        if (!empty($indeximage)) {
            $indeximage_tray->addElement(new XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . '/' . $xoopsModuleConfig['mainimagedir'] . '/' . $indeximage . "' name='image' id='image' alt=''>"));
        } else {
            $indeximage_tray->addElement(new XoopsFormLabel('', "<br><br><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt=''>"));
        }
        $sform->addElement($indeximage_tray);

        $sform->addElement(new XoopsFormDhtmlTextArea(_AM_XTORRENT_IPAGE_CHEADING, 'indexheader', $indexheader, 15, 60));
        $headeralign_select = new XoopsFormSelect(_AM_XTORRENT_IPAGE_CHEADINGA, 'indexheaderalign', $indexheaderalign);
        $headeralign_select->addOptionArray(['left' => _AM_XTORRENT_IPAGE_CLEFT, 'right' => _AM_XTORRENT_IPAGE_CRIGHT, 'center' => _AM_XTORRENT_IPAGE_CCENTER]);
        $sform->addElement($headeralign_select);
        $sform->addElement(new XoopsFormTextArea(_AM_XTORRENT_IPAGE_CFOOTER, 'indexfooter', $indexfooter, 10, 60));
        $footeralign_select = new XoopsFormSelect(_AM_XTORRENT_IPAGE_CFOOTERA, 'indexfooteralign', $indexfooteralign);
        $footeralign_select->addOptionArray(['left' => _AM_XTORRENT_IPAGE_CLEFT, 'right' => _AM_XTORRENT_IPAGE_CRIGHT, 'center' => _AM_XTORRENT_IPAGE_CCENTER]);
        $sform->addElement($footeralign_select);

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

        $button_tray = new XoopsFormElementTray('', '');
        $hidden = new XoopsFormHidden('op', 'save');
        $button_tray->addElement($hidden);
        $button_tray->addElement(new XoopsFormButton('', 'post', _AM_XTORRENT_BSAVE, 'submit'));
        $sform->addElement($button_tray);
        $sform->display();
        break;
}
xoops_cp_footer();
