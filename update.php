<?php
/**
 * $Id: update.php v 1.02 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
include 'header.php';

define('IS_UPDATE_FILE', true);

global $xoopsDB, $xoopsConfig, $xoopsUser, $xoopsModule;
if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
    exit('Access Denied');
}
require XOOPS_ROOT_PATH . '/header.php';

function install_header()
{
    ?>
    <!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <title>WF-Downloads Upgrade</title>
    <meta http-equiv="Content-Type" content="text/html; charset=">
    <meta name="AUTHOR" content="WFSECTIONS">
    <meta name="GENERATOR" content="WFSECTION---->http://wfsections.xoops2.com">
</head>
<body>
<br>
<div style="text-align:center"><img src="./images/logo-en.gif" alt=""><h4>WF-Downloads Update</h4>
    <?php
}

    function install_footer()
    {
        ?>
    <a href="http://wfsections.xoops2.com/" target="_blank"><img src="images/xtorrent_slogo.png" alt="XOOPS" border="0"></a></div>
</body>
</html>
<?php
    }
// echo "Welcome to the WF-Section update script";
foreach ($_POST as $k => $v) {
    ${$k} = $v;
}

foreach ($_GET as $k => $v) {
    ${$k} = $v;
}

if (!isset($action) || '' == $action) {
    $action = 'message';
}

if ('message' == $action) {
    install_header();

    $moduleHandler = xoops_getHandler('module');

    $mydownloads = $moduleHandler->getByDirname('mydownloads');

    if ($mydownloads) {
        $mydownload_version = round($mydownloads->getVar('version') / 100, 2);
    }

    $moduleHandler = xoops_getHandler('module');

    $xtorrent = $moduleHandler->getByDirname('xtorrent');

    if ($xtorrent) {
        $xtorrentownload_version = $xtorrent->getVar('version'); //getInfo('version');

        $xtorrentownload_version = round($xtorrent->getVar('version') / 100, 2);
    }

    /**
     * Set version number
     */

    echo $xtorrentownload_version;

    if (2.05 == $xtorrentownload_version && !$mydownload_version) {
        echo '<h4>Latest version of WF-Downloads installed. No Update Required</h4>';

        install_footer();

        require_once XOOPS_ROOT_PATH . '/footer.php';

        exit();
    }

    $down_num = $mydownload_version ?? 0;

    if (isset($xtorrentownload_version) && 2.05 != $xtorrentownload_version) {
        $down_num = $xtorrentownload_version;
    }

    echo '<div><b>Welcome to the WF-Downloads Update script</b></div><br>';

    echo '<div>This script will upgrade My-Downloads or WF-Downloads.</div><br><br>';

    if (0 != $down_num) {
        echo "<div><span style='color:#ff0000;font-weight:bold;'>WARNING: If upgrading from My Downloads. The My Download Module will **NOT** function after the upgrade and should be unistalled. </span></div><br>";

        echo '<div><b>Before upgrading Wf-Downloads, make sure that you have:</b></div><br>';

        echo "<div><span style='color:#ff0000; '>1. <b>Important:</b> First, create a back-up your database before proceeding further. </span></div>";

        echo '<div>2. Upload all the contents of the WF-Downloads package to your server.</div><br>';

        echo '<div>3. After the upgrade you must update WF-Download in System Admin -> Modules.</div><br>';

        echo '<div><b>Press the button below to ';

        switch ($down_num) {
            case '1.0.1':
            case '1.10':
                echo "update My Downloads $down_num</b></div>";
                break;
            case '2.0':
            case '2.1':
            case '2.2':
                echo "update My Downloads $down_num</b></div>";
                break;
            case '2.03':
            case '2.04':
                echo "update WF-Downloads $down_num</b></div>";
                break;
        }

        echo "<form action='" . $HTTP_SERVER_VARS['PHP_SELF'] . "' method='post'>
			<input type='submit' value='Start Upgrade'>
			<input type='hidden' value='upgrade' name='action'>
			<input type='hidden' name='down_num' value=$down_num>
			</form>";
    } else {
        echo '<h4>No module installed to update</h4>';
    }

    install_footer();

    require_once XOOPS_ROOT_PATH . '/footer.php';

    exit();
}
// THIS IS THE UPDATE DATABASE FROM HERE!!!!!!!!! DO NOT TOUCH THIS!!!!!!!!
if ('upgrade' == $action) {
    install_header();

    $num = $_POST['down_num'];

    switch ($num) {
        case '1.0.1':
        case '1.10':
            echo "Updating Mydownloads $num";
            include 'update/mydownloads_update.php';
            break;
        case '2.0':
        case '2.1':
        case '2.2':
            echo "Updating Mydownloads $num";
            include 'update/xtorrent_v2.0.2.php';
            break;
        case '2.03':
            echo "Updating xtorrent $num";
            include 'update/xtorrent_v2.0.3.php';
            break;
        case '2.04':
            echo "Updating xtorrent $num";
            include 'update/xtorrent_v2.0.4.php';
            break;
        case '0':
        default:
            echo "Version: $num not supported yet. Please contact the developers of this module";
            break;
    }

    echo 'To complete the upgrade, You must update WF-Downloads in Xoops System Admin -> Modules';

    echo 'Please enjoy using WF-Downloads, the WF-Sections Team.';

    require_once XOOPS_ROOT_PATH . '/footer.php';
}

?>
