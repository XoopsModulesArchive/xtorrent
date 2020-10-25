<?php
/**
 * $Id: visit.php v 1.02 06 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
require __DIR__ . '/header.php';
error_reporting(E_ALL);
global $xoopsUser, $xoopsModuleConfig, $myts;

$agreed = $_GET['agree'] ?? 0;

$lid = (int)$_GET['lid'];
$cid = (int)$_GET['cid'];

function reportBroken($lid)
{
    global $xoopsModule;

    echo '
		<h4>' . _MD_XTORRENT_BROKENFILE . "</h4>\n
		<div>" . _MD_XTORRENT_PLEASEREPORT . "\n
		<a href='" . XOOPS_URL . "/modules/xtorrent/brokenfile.php?lid=$lid'>" . _MD_XTORRENT_CLICKHERE . "</a>\n
		</div>\n";
}

if (0 == $agreed) {
    if ($xoopsModuleConfig['check_host']) {
        $goodhost = 0;

        $referer = parse_url(xoops_getenv('HTTP_REFERER'));

        $referer_host = $referer['host'];

        foreach ($xoopsModuleConfig['referers'] as $ref) {
            if (!empty($ref) && preg_match('/' . $ref . '/i', $referer_host)) {
                $goodhost = '1';

                break;
            }
        }

        if (!$goodhost) {
            redirect_header(XOOPS_URL . "/modules/xtorrent/singlefile.php?cid=$cid&amp;lid=$lid", 20, _MD_XTORRENT_NOPERMISETOLINK);

            exit();
        }
    }
}

if ($xoopsModuleConfig['showDowndisclaimer'] && 0 == $agreed) {
    require XOOPS_ROOT_PATH . '/header.php';

    echo "
		<div align='center'>" . xtorrent_imageheader() . "</div>\n
		<h4>" . _MD_XTORRENT_DISCLAIMERAGREEMENT . "</h4>\n
		<div>" . $myts->displayTarea($xoopsModuleConfig['downdisclaimer'], 0, 1, 1, 1, 1) . "</div><br>\n
		<form action='visit.php' method='post'>\n
		<div align='center'><b>" . _MD_XTORRENT_DOYOUAGREE . "</b><br><br>\n
		<input type='button' onclick='location=\"visit.php?agree=1&amp;lid=$lid&amp;cid=$cid\"' class='formButton' value='" . _MD_XTORRENT_AGREE . "' alt='" . _MD_XTORRENT_AGREE . "'>\n
		&nbsp;\n
		<input type='button' onclick='location=\"index.php\"' class='formButton' value='" . _CANCEL . "' alt='" . _CANCEL . "'>\n
		<input type='hidden' name='lid' value='1'>\n
		<input type='hidden' name='cid' value='1'>\n
		</div></form>\n";

    require XOOPS_ROOT_PATH . '/footer.php';

    exit();
}  
    $isadmin = (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;
    if (false === $isadmin) {
        $sql = sprintf('UPDATE ' . $xoopsDB->prefix('xtorrent_downloads') . " SET hits = hits+1 WHERE lid =$lid");

        $xoopsDB->queryF($sql);
    }
    $result = $xoopsDB->query('SELECT url FROM ' . $xoopsDB->prefix('xtorrent_downloads') . " WHERE lid=$lid");
    [$url] = $xoopsDB->fetchRow($result);

    // require XOOPS_ROOT_PATH . '/header.php';
    //   echo "<br><div align='center'>" . xtorrent_imageheader() . "</div>";
    //   $url = $myts -> htmlSpecialChars(preg_replace('/javascript:/si' , 'java script:', $url), ENT_QUOTES);

    if (!empty($url)) {
        if (!headers_sent()) {
            if (!empty($url)) {
                ini_set('allow_url_fopen', true);

                global $xoopsUser, $xoopsDB;

                if (1 == $xoopsModuleConfig['opentracker']) {
                    if (!empty($xoopsUser)) {
                        $sql = 'select id from ' . $xoopsDB->prefix('xtorrent_users') . " where username='" . $xoopsUser->getVar('uname') . "', uid='" . $xoopsUser->getVar('uid') . "'";

                        $rt = $xoopsDB->queryF($sql);

                        if (!$xoopsDB->getRowsNum($rt)) {
                            $sql = 'insert into '
                                   . $xoopsDB->prefix('xtorrent_users')
                                   . " (username, uid, old_password, secret, lid) VALUES ('"
                                   . $xoopsUser->getVar('uname')
                                   . "', "
                                   . $xoopsUser->getVar('uid')
                                   . ", '"
                                   . $xoopsUser->getVar('pass')
                                   . "', '"
                                   . $_SERVER['REMOTE_ADDR']
                                   . ':'
                                   . $_SERVER['REMOTE_PORT']
                                   . "','$lid')";

                            $rt = $xoopsDB->queryF($sql);
                        } else {
                            $sql = 'delete from ' . $xoopsDB->prefix('xtorrent_users') . ' where uid=' . $xoopsUser->getVar('uid') . " and lid = $lid and secret = '" . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "'";

                            $rt = $xoopsDB->queryF($sql);

                            $sql = 'insert into '
                                   . $xoopsDB->prefix('xtorrent_users')
                                   . " (username, uid, old_password, secret, lid) VALUES ('"
                                   . $xoopsUser->getVar('uname')
                                   . "', "
                                   . $xoopsUser->getVar('uid')
                                   . ", '"
                                   . $xoopsUser->getVar('pass')
                                   . "', '"
                                   . $_SERVER['REMOTE_ADDR']
                                   . ':'
                                   . $_SERVER['REMOTE_PORT']
                                   . "','$lid')";

                            $rt = $xoopsDB->queryF($sql);
                        }
                    } else {
                        $sql = 'select id from ' . $xoopsDB->prefix('xtorrent_users') . " where username='guest', uid=0, old_password = md5('guest'), secret = '" . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "'";

                        $rt = $xoopsDB->queryF($sql);

                        if (!$xoopsDB->getRowsNum($rt)) {
                            $sql = 'insert into ' . $xoopsDB->prefix('xtorrent_users') . " (username, uid, old_password, secret, lid) VALUES ('guest', 0, md5('guest'), '" . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "','$lid')";

                            $rt = $xoopsDB->queryF($sql);
                        } else {
                            $sql = 'delete from ' . $xoopsDB->prefix('xtorrent_users') . " where username='guest' and uid=0 and old_password = md5('guest') and lid = $lid and secret = '" . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "'";

                            $rt = $xoopsDB->queryF($sql);

                            $sql = 'insert into ' . $xoopsDB->prefix('xtorrent_users') . " (username, uid, old_password, secret, lid) VALUES ('guest', 0, md5('guest'), '" . $_SERVER['REMOTE_ADDR'] . ':' . $_SERVER['REMOTE_PORT'] . "','$lid')";

                            $rt = $xoopsDB->queryF($sql);
                        }
                    }

                    require_once 'include/bittorrent.php';

                    if ($rt) {
                        $kid = $xoopsDB->getInsertId();

                        $sql = 'update ' . $xoopsDB->prefix('xtorrent_users') . ' set passhash = md5(concat(secret, old_password, secret)) where id = ' . $kid;

                        $rt = $xoopsDB->queryF($sql);

                        $sql = 'select * from ' . $xoopsDB->prefix('xtorrent_users') . ' where id = ' . $kid;

                        $rt = $xoopsDB->queryF($sql);

                        $row = $xoopsDB->fetchArray($rt);

                        $passkey = md5($row['username'] . get_date_time() . $row['passhash']);

                        $sql = 'update ' . $xoopsDB->prefix('xtorrent_users') . " set passkey = '" . $passkey . "' where id = " . $kid;

                        $rt = $xoopsDB->queryF($sql);
                    }
                }

                // Begin Download

                $fn = str_replace(XOOPS_URL, XOOPS_ROOT_PATH, $url);

                require_once 'include/benc.php';

                $dict = bdec_file($fn, (1024 * 1024));

                $tracker = [];

                $buffer = [];

                $tracker['type'] = 'list';

                $buffer['type'] = 'string';

                if (1 == $xoopsModuleConfig['opentracker']) {
                    $buffer['value'] = $xoopsModuleConfig['announce_url'] . "?passkey=$passkey";

                    if (!empty($dict['value']['announce-list'])) {
                        $buffer['string'] = mb_strlen($buffer['value']) . ':' . $buffer['value'];

                        $buffer['strlen'] = mb_strlen($buffer['string']);

                        $tracker['value'] = [$buffer];

                        $tracker['string'] = 'l' . $buffer['string'] . 'e';

                        $tracker['strlen'] = mb_strlen($tracker['string']);

                        $dict['value']['announce-list']['value'][count($dict['value']['announce-list']['value'])] = $tracker;

                        $dict['value']['announce-list']['string'] = mb_substr($dict['value']['announce-list']['string'], 0, -2) . 'l' . $buffer['string'] . 'ee';

                        $dict['value']['announce-list']['strlen'] = mb_strlen($dict['value']['announce-list']['string']);
                    } else {
                        $dict['value']['announce-list']['type'] = 'list';

                        $buffer2 = [];

                        $buffer2['type'] = 'string';

                        $buffer2['string'] = mb_strlen($dict['value']['announce']['value']) . ':' . $dict['value']['announce']['value'];

                        $buffer2['value'] = $dict['value']['announce']['value'];

                        $buffer2['strlen'] = mb_strlen($buffer2['string']);

                        $tracker['value'] = [$buffer2];

                        $tracker['string'] = 'l' . $buffer2['string'] . 'e';

                        $tracker['strlen'] = mb_strlen($tracker['string']);

                        $dict['value']['announce-list']['value'][count($dict['value']['announce-list']['value'])] = $tracker;

                        $buffer['string'] = mb_strlen($buffer['value']) . ':' . $buffer['value'];

                        $buffer['strlen'] = mb_strlen($buffer['string']);

                        $tracker['value'] = [$buffer];

                        $tracker['string'] = 'l' . $buffer['string'] . 'e';

                        $tracker['strlen'] = mb_strlen($tracker['string']);

                        $dict['value']['announce-list']['value'][count($dict['value']['announce-list']['value'])] = $tracker;

                        $dict['value']['announce-list']['string'] = 'll' . $buffer2['string'] . '' . $buffer['string'] . 'ee';

                        $dict['value']['announce-list']['strlen'] = mb_strlen($dict['value']['announce-list']['string']);
                    }
                }

                header('Content-Disposition: attachment; filename="' . basename($url) . '"');

                header('Content-Type: application/x-bittorrent');

                //print_r($dict);

                print(benc($dict));

                exit();
            }  

            require XOOPS_ROOT_PATH . '/header.php';

            echo "<br><div align='center'>" . xtorrent_imageheader() . '</div>';

            reportBroken($lid);
        } else {
            die('Headers already sent');
        }
    } else {
        reportBroken($lid);

        require XOOPS_ROOT_PATH . '/footer.php';
    }

