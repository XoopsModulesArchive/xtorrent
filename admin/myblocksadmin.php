<?php
// ------------------------------------------------------------------------- //
// myblocksadmin.php                              //
// - XOOPS block admin for each modules -                     //
// GIJOE <http://www.peak.ne.jp>                   //
// ------------------------------------------------------------------------- //
require_once dirname(__DIR__, 3) . '/include/cp_header.php';
require_once __DIR__ . '/mygrouppermform.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsblock.php';
require_once XOOPS_ROOT_PATH . '/modules/xtorrent/include/functions.php';

$xoops_system_url = XOOPS_URL . '/modules/system';
$xoops_system_path = XOOPS_ROOT_PATH . '/modules/system';
// language files
$language = $xoopsConfig['language'];
if (!file_exists("$xoops_system_path/language/$language/admin/blocksadmin.php")) {
    $language = 'english';
}

require_once "$xoops_system_path/language/$language/admin.php";
require_once "$xoops_system_path/language/$language/admin/blocksadmin.php";
$group_defs = file("$xoops_system_path/language/$language/admin/groups.php");
foreach ($group_defs as $def) {
    if (mb_strstr($def, '_AM_XTORRENT_ACCESSRIGHTS') || mb_strstr($def, '_AM_XTORRENT_ACTIVERIGHTS')) {
        eval($def);
    }
}
// check $xoopsModule
if (!is_object($xoopsModule)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}
// get blocks owned by the module
$block_arr = XoopsBlock::getByModule($xoopsModule->mid());

function list_blocks()
{
    global $xoopsUser, $xoopsConfig, $xoopsDB;

    global $block_arr, $xoops_system_url;

    $side_descs = [0 => _AM_XTORRENT_SBLEFT, 1 => _AM_XTORRENT_SBRIGHT, 3 => _AM_XTORRENT_CBLEFT, 4 => _AM_XTORRENT_CBRIGHT, 5 => _AM_XTORRENT_CBCENTER];

    // displaying TH

    echo "
	<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
	<tr valign='middle'><th width='20%'>"
         . _AM_XTORRENT_BLKDESC
         . '</th><th>'
         . _AM_XTORRENT_TITLE
         . "</th><th align='center' nowrap='nowrap'>"
         . _AM_XTORRENT_SIDE
         . "</th><th align='center'>"
         . _AM_XTORRENT_WEIGHT
         . "</th><th align='center'>"
         . _AM_XTORRENT_VISIBLE
         . "</th><th align='center'>"
         . _AM_XTORRENT_ACTION
         . '</th></tr>
	';

    // blocks displaying loop

    $class = 'even';

    foreach (array_keys($block_arr) as $i) {
        $visible = (1 == $block_arr[$i]->getVar('visible')) ? _YES : _NO;

        $weight = $block_arr[$i]->getVar('weight');

        $side_desc = $side_descs[$block_arr[$i]->getVar('side')];

        $title = $block_arr[$i]->getVar('title');

        if ('' == $title) {
            $title = '&nbsp;';
        }

        $name = $block_arr[$i]->getVar('name');

        $bid = $block_arr[$i]->getVar('bid');

        echo "<tr valign='top'><td class='$class'>$name</td><td class='$class'>$title</td><td class='$class' align='center'>$side_desc</td><td class='$class' align='center'>$weight</td><td class='$class' align='center' nowrap>$visible</td><td class='$class' align='center'><a href='$xoops_system_url/admin.php?fct=blocksadmin&amp;op=edit&amp;bid=$bid' target='_blank'>"
             . _EDIT
             . "</a></td></tr>\n";

        $class = ('even' == $class) ? 'odd' : 'even';
    }

    echo "<tr><td class='foot' align='center' colspan='7'>
	</td></tr></table>\n";
}

function list_groups()
{
    global $xoopsUser, $xoopsConfig, $xoopsDB;

    global $xoopsModule, $block_arr, $xoops_system_url;

    foreach (array_keys($block_arr) as $i) {
        $item_list[$block_arr[$i]->getVar('bid')] = $block_arr[$i]->getVar('title');
    }

    $form = new MyXoopsGroupPermForm('', 1, 'block_read', _MD_AM_ADGS);

    $form->addAppendix('module_admin', $xoopsModule->mid(), $xoopsModule->name() . ' ' . _AM_XTORRENT_ACTIVERIGHTS);

    $form->addAppendix('module_read', $xoopsModule->mid(), $xoopsModule->name() . ' ' . _AM_XTORRENT_ACCESSRIGHTS);

    foreach ($item_list as $item_id => $item_name) {
        $form->addItem($item_id, $item_name);
    }

    echo $form->render();
}

if (!empty($_POST['submit'])) {
    include 'mygroupperm.php';

    redirect_header(XOOPS_URL . '/modules/xtorrent/admin/myblocksadmin.php', 1, _MD_AM_DBUPDATED);
}

xoops_cp_header();
xtorrent_adminmenu(_AM_XTORRENT_BADMIN);

list_blocks();
list_groups();
xoops_cp_footer();
?>
