<?php

ob_start('ob_gzhandler');
require_once '../../mainfile.php';
require_once 'include/bittorrent.php';
require_once 'include/benc.php';

global $xoopsDB, $xoopsConfig, $xoopsModuleConfig;
$filename = XOOPS_ROOT_PATH . '/test.txt';

// Let's make sure the file exists and is writable first.
function debugtxt($str, $filename)
{
    if (is_writable($filename)) {
        // In our example we're opening $filename in append mode.

        // The file pointer is at the bottom of the file hence

        // that's where $somecontent will go when we fwrite() it.

        if (!$handle = fopen($filename, 'ab')) {
        }

        if (false === fwrite($handle, $str . chr(13) . chr(10))) {
        }

        fclose($handle);
    }
}

//hit_start();

//$rt=debugtxt("Start", $filename);

function err($msg)
{
    benc_resp(['failure reason' => [type => 'string', value => $msg]]);

    hit_end();

    exit();
}

function benc_resp($d)
{
    benc_resp_raw(benc([type => 'dictionary', value => $d]));
}

function benc_resp_raw($x)
{
    header('Content-Type: text/plain');

    header('Pragma: no-cache');

    print($x);
}

foreach ($_GET as $x => $k) {
    if (isset($_GET[(string)$x])) {
        ${$x} = '' . $k;
    }

    //$rt=debugtxt("$x = '".$k, $filename);
    //echo ."'<br>";
}
$info_hash = md5($info_hash);
foreach (['port', 'downloaded', 'uploaded', 'left'] as $x) {
    ${$x} = 0 + $_GET[$x];
}

if (mb_strpos($passkey, '?')) {
    $tmp = mb_substr($passkey, mb_strpos($passkey, '?'));

    $passkey = mb_substr($passkey, 0, mb_strpos($passkey, '?'));

    $tmpname = mb_substr($tmp, 1, mb_strpos($tmp, '=') - 1);

    $tmpvalue = mb_substr($tmp, mb_strpos($tmp, '=') + 1);

    $GLOBALS[$tmpname] = $tmpvalue;
}

foreach (['passkey', 'info_hash', 'peer_id', 'port', 'downloaded', 'uploaded', 'left'] as $x) {
    if (!isset($x)) {
        err("Missing key: $x");
    }
}

//if (empty($ip) || !preg_match('/^(d{1,3}.){3}d{1,3}$/s', $ip))

$ip = getip();

$rsize = 50;
foreach (['num want', 'numwant', 'num_want'] as $k) {
    if (isset($_GET[$k])) {
        $rsize = 0 + $_GET[$k];

        break;
    }
}

$agent = $_SERVER['HTTP_USER_AGENT'];

// Deny access made with a browser...
if (preg_match('^Mozilla\\/', $agent) || preg_match('^Opera\\/', $agent) || preg_match('^Links ', $agent) || preg_match('^Lynx\\/', $agent)) {
    err('torrent not registered with this tracker');
}

if (!$port || $port > 0xffff) {
    err('invalid port');
}

if (!isset($event)) {
    $event = '';
}

$seeder = (0 == $left) ? 'yes' : 'no';

//$rt=debugtxt("pass check 1", $filename);

//dbconn(false);

//hit_count();

/*
SECURITY Feature not currently supported
*/
if (32 == mb_strlen($passkey)) {
    $valid = @$GLOBALS['xoopsDB']->fetchRow(@$xoopsDB->queryF('SELECT lid FROM ' . $xoopsDB->prefix('xtorrent_users') . ' WHERE passkey=' . sqlesc($passkey)));

    if (0 == $valid[0]) {
        err("Invalid passkey! Re-download the .torrent from $BASEURL");
    }

    $res = $xoopsDB->queryF('SELECT lid, seeds + leechers AS numpeers, added AS ts FROM ' . $xoopsDB->prefix('xtorrent_torrent') . ' WHERE lid = ' . $valid[0]);
} else {
    $res = $xoopsDB->queryF('SELECT lid, seeds + leechers AS numpeers, added AS ts FROM ' . $xoopsDB->prefix('xtorrent_torrent') . ' WHERE ' . hash_where('infoHash', addslashes($info_hash)));
}

//$rt=debugtxt("SELECT lid, seeds + leechers AS numpeers, added AS ts FROM ".$xoopsDB->prefix("xtorrent_torrent")." WHERE ".hash_where('infoHash',md5($info_hash)), $filename);

$torrent = $xoopsDB->fetchArray($res);
if (!$torrent) {
    err('torrent not registered with this tracker');
}

//$rt=debugtxt("pass check 2", $filename);

$torrentid = $torrent['lid'];

$fields = 'seeder, peer_id, ip, port, uploaded, downloaded, userid';

$numpeers = $torrent['numpeers'];
$limit = '';
if ($numpeers > $rsize) {
    $limit = "ORDER BY RAND() LIMIT $rsize";
}
$res = $xoopsDB->queryF("SELECT $fields FROM " . $xoopsDB->prefix('xtorrent_peers') . " WHERE torrent = $torrentid AND connectable = 'yes' $limit");

$resp = 'd' . benc_str('interval') . 'i' . $xoopsModuleConfig['announce_interval'] . 'e' . benc_str('peers') . 'l';
unset($self);
while (false !== ($row = $xoopsDB->fetchArray($res))) {
    $row['peer_id'] = hash_pad($row['peer_id']);

    if ($row['peer_id'] === $peer_id) {
        $userid = $row['userid'];

        $self = $row;

        continue;
    }

    if ($seeder = 'yes') {
        $seedss .= 'd' . benc_str('ip') . benc_str($row['ip']) . benc_str('peer id') . benc_str($row['peer_id']) . benc_str('port') . 'i' . $row['port'] . 'e' . 'e';

        $resp .= 'd' . benc_str('ip') . benc_str($row['ip']) . benc_str('peer id') . benc_str($row['peer_id']) . benc_str('port') . 'i' . $row['port'] . 'e' . 'e';
    } else {
        $resp .= 'd' . benc_str('ip') . benc_str($row['ip']) . benc_str('peer id') . benc_str($row['peer_id']) . benc_str('port') . 'i' . $row['port'] . 'e' . 'e';
    }
}

$resp .= 'ee';

$resp .= 'e' . benc_str('seeds') . 'l' . $seedss . 'ee';

$selfwhere = "torrent = $torrentid AND " . hash_where('peer_id', $peer_id);

if (!isset($self)) {
    //$rt=debugtxt("start self", $filename);

    $sql = "SELECT $fields FROM " . $xoopsDB->prefix('xtorrent_peers') . " WHERE $selfwhere";

    $res = $xoopsDB->queryF($sql) or err('fout');

    $row = $xoopsDB->fetchArray($res);

    if ($row) {
        $userid = $row['userid'];

        $self = $row;
    }
}

//// Up/down stats ////////////////////////////////////////////////////////////
if (!isset($self)) {
    //$rt=debugtxt("start self 2", $filename);

    $valid = @$xoopsDB->fetchRow(@$xoopsDB->queryF('SELECT COUNT(*) FROM ' . $xoopsDB->prefix('xtorrent_peers') . " WHERE torrent=$torrentid AND passkey=" . sqlesc($passkey))) or err('mistake');

    if (0 != $xoopsModuleConfig['numleechers'] && $valid[0] >= $xoopsModuleConfig['numleechers'] && 'no' == $seeder) {
        err('Connection limit exceeded! You may only leech from one location at a time.');
    }

    if (0 != $xoopsModuleConfig['numseeds'] && $valid[0] >= $xoopsModuleConfig['numseeds'] && 'yes' == $seeder) {
        err('Connection limit exceeded!');
    }

    $rz = $xoopsDB->queryF('SELECT id, uploaded, downloaded FROM ' . $xoopsDB->prefix('xtorrent_users') . ' WHERE passkey=' . sqlesc($passkey) . " AND enabled = 'yes' ORDER BY last_access DESC LIMIT 1") or err('Tracker error 2');

    $az = $xoopsDB->fetchArray($rz);

    $userid = ($az['id']);

    if (1 == $xoopsModuleConfig['opentracker']) {
        if (0 == $xoopsDB->getRowsNum($rz)) {
            err("Unknown passkey. Please redownload the torrent from $BASEURL.");
        }
    }

    //	if ($left > 0 && $az["class"] < UC_VIP)

    //	if ($az["class"] < UC_VIP)

    if (1 == $xoopsModuleConfig['throttle']) {
        $gigs = $az['uploaded'] / (1024 * 1024 * 1024);

        $elapsed = floor((gmtime() - $torrent['ts']) / 3600);

        $ratio = (($az['downloaded'] > 0) ? ($az['uploaded'] / $az['downloaded']) : 1);

        if ($ratio < 0.5 || $gigs < 5) {
            $wait = 48;
        } elseif ($ratio < 0.65 || $gigs < 6.5) {
            $wait = 24;
        } elseif ($ratio < 0.8 || $gigs < 8) {
            $wait = 12;
        } elseif ($ratio < 0.95 || $gigs < 9.5) {
            $wait = 6;
        } else {
            $wait = 0;
        }

        if ($elapsed < $wait) {
            err('Not authorized (' . ($wait - $elapsed) . 'h) - READ THE FAQ!');
        }
    }
} else {
    $rz = $xoopsDB->queryF('SELECT id, uploaded, downloaded FROM ' . $xoopsDB->prefix('xtorrent_users') . ' WHERE passkey=' . sqlesc($passkey) . " AND enabled = 'yes' ORDER BY last_access DESC LIMIT 1") or err('Tracker error 2');

    $az = $xoopsDB->fetchArray($rz);

    $userid = ($az['id']);

    $upthis = max(0, $uploaded - $self['uploaded']);

    $downthis = max(0, $downloaded - $self['downloaded']);

    if (($upthis > 0 || $downthis > 0) && 0 != $userid) {
        $rt = $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('xtorrent_users') . " SET uploaded = uploaded + $upthis, downloaded = downloaded + $downthis WHERE id=$userid") or err('Tracker error 3');
    }
}

///////////////////////////////////////////////////////////////////////////////

function portblacklisted($port)
{
    // direct connect

    if ($port >= 411 && $port <= 413) {
        return true;
    }

    // bittorrent

    if ($port >= 6881 && $port <= 6889) {
        return true;
    }

    // kazaa

    if (1214 == $port) {
        return true;
    }

    // gnutella

    if ($port >= 6346 && $port <= 6347) {
        return true;
    }

    // emule

    if (4662 == $port) {
        return true;
    }

    // winmx

    if (6699 == $port) {
        return true;
    }

    return false;
}

$updateset = [];

if ('stopped' == $event) {
    //$rt=debugtxt("start stopped 1", $filename);

    if (isset($self)) {
        $xoopsDB->queryF('DELETE FROM ' . $xoopsDB->prefix('xtorrent_peers') . " WHERE $selfwhere");

        if ($xoopsDB->getAffectedRows()) {
            if ('yes' == $self['seeder']) {
                $updateset[] = 'seeds = seeds - 1';
            } else {
                $updateset[] = 'leechers = leechers - 1';
            }
        }
    }
} else {
    //$rt=debugtxt("start ".$event."", $filename);

    if ('completed' == $event) {
        $updateset[] = 'times_completed = times_completed + 1';
    }

    if (isset($self)) {
        $xoopsDB->queryF(
            'UPDATE ' . $xoopsDB->prefix('xtorrent_peers') . " SET uploaded = $uploaded, downloaded = $downloaded, to_go = $left, last_action = NOW(), seeder = '$seeder'" . ('yes' == $seeder && $self['seeder'] != $seeder ? ', finishedat = ' . time() : '') . " WHERE $selfwhere"
        );

        if ($xoopsDB->getAffectedRows() && $self['seeder'] != $seeder) {
            if ('yes' == $seeder) {
                $updateset[] = 'seeds = seeds + 1';

                $updateset[] = 'leechers = leechers - 1';
            } else {
                $updateset[] = 'seeds = seeds - 1';

                $updateset[] = 'leechers = leechers + 1';
            }
        }
    } else {
        if (portblacklisted($port)) {
            err("Port $port is blacklisted.");
        } else {
            $sockres = @fsockopen($ip, $port, $errno, $errstr, 5);

            if (!$sockres) {
                $connectable = 'no';
            } else {
                $connectable = 'yes';

                @fclose($sockres);
            }
        }

        //$rt=debugtxt("INSERT INTO ".$xoopsDB->prefix('xtorrent_peers')." (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, uploadoffset, downloadoffset, passkey) VALUES ('$connectable', '$torrentid', " . sqlesc($peer_id) . ", " . sqlesc($ip) . ", '$port', '$uploaded', '$downloaded', '$left', NOW(), NOW(), '$seeder', '$userid', " . sqlesc(addslashes($agent)) . ", '$uploaded', '$downloaded', " . sqlesc($passkey) . ")",$filename);

        $ret = $xoopsDB->queryF(
            'INSERT INTO ' . $xoopsDB->prefix('xtorrent_peers') . " (connectable, torrent, peer_id, ip, port, uploaded, downloaded, to_go, started, last_action, seeder, userid, agent, uploadoffset, downloadoffset, passkey) VALUES ('$connectable', '$torrentid', " . sqlesc($peer_id) . ', ' . sqlesc(
                $ip
            ) . ", '$port', '$uploaded', '$downloaded', '$left', NOW(), NOW(), '$seeder', '$userid', " . sqlesc(addslashes($agent)) . ", '$uploaded', '$downloaded', " . sqlesc($passkey) . ')'
        ) or err('tracker error');

        if ($ret) {
            if ('yes' == $seeder) {
                $updateset[] = 'seeds = seeds + 1';
            } else {
                $updateset[] = 'leechers = leechers + 1';
            }
        }
    }
}

if ('yes' == $seeder) {
    /*if ($torrent["banned"] != "yes")
        $updateset[] = "visible = 'yes'";*/

    $updateset[] = 'last_action = NOW()';
}

if (count($updateset)) {
    $xoopsDB->queryF('UPDATE ' . $xoopsDB->prefix('xtorrent_torrent') . ' SET ' . implode(',', $updateset) . " WHERE lid = $torrentid");
}

//$rt=debugtxt("start resp:".$resp."", $filename);

benc_resp_raw($resp);

//hit_end();
