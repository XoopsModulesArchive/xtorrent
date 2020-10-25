<?php

require_once 'bittorrent.php';

function docleanup()
{
    global $torrent_dir, $signup_timeout, $max_dead_torrent_time, $autoclean_interval;

    set_time_limit(0);

    ignore_user_abort(1);

    do {
        $res = $GLOBALS['xoopsDB']->queryF('SELECT id FROM torrents');

        $ar = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($res))) {
            $id = $row[0];

            $ar[$id] = 1;
        }

        if (!count($ar)) {
            break;
        }

        $dp = @opendir($torrent_dir);

        if (!$dp) {
            break;
        }

        $ar2 = [];

        while (false !== ($file = readdir($dp))) {
            if (!preg_match('/^(\d+)\.torrent$/', $file, $m)) {
                continue;
            }

            $id = $m[1];

            $ar2[$id] = 1;

            if (isset($ar[$id]) && $ar[$id]) {
                continue;
            }

            $ff = $torrent_dir . "/$file";

            unlink($ff);
        }

        closedir($dp);

        if (!count($ar2)) {
            break;
        }

        $delids = [];

        foreach (array_keys($ar) as $k) {
            if (isset($ar2[$k]) && $ar2[$k]) {
                continue;
            }

            $delids[] = $k;

            unset($ar[$k]);
        }

        if (count($delids)) {
            $GLOBALS['xoopsDB']->queryF('DELETE FROM torrents WHERE id IN (' . implode(',', $delids) . ')');
        }

        $res = $GLOBALS['xoopsDB']->queryF('SELECT torrent FROM peers GROUP BY torrent');

        $delids = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($res))) {
            $id = $row[0];

            if (isset($ar[$id]) && $ar[$id]) {
                continue;
            }

            $delids[] = $id;
        }

        if (count($delids)) {
            $GLOBALS['xoopsDB']->queryF('DELETE FROM peers WHERE torrent IN (' . implode(',', $delids) . ')');
        }

        $res = $GLOBALS['xoopsDB']->queryF('SELECT torrent FROM files GROUP BY torrent');

        $delids = [];

        while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($res))) {
            $id = $row[0];

            if ($ar[$id]) {
                continue;
            }

            $delids[] = $id;
        }

        if (count($delids)) {
            $GLOBALS['xoopsDB']->queryF('DELETE FROM files WHERE torrent IN (' . implode(',', $delids) . ')');
        }
    } while (0);

    $deadtime = deadtime();

    $GLOBALS['xoopsDB']->queryF("DELETE FROM peers WHERE last_action < FROM_UNIXTIME($deadtime)");

    $deadtime -= $max_dead_torrent_time;

    $GLOBALS['xoopsDB']->queryF("UPDATE torrents SET visible='no' WHERE visible='yes' AND last_action < FROM_UNIXTIME($deadtime)");

    $deadtime = time() - $signup_timeout;

    $GLOBALS['xoopsDB']->queryF("DELETE FROM users WHERE status = 'pending' AND added < FROM_UNIXTIME($deadtime) AND last_login < FROM_UNIXTIME($deadtime) AND last_access < FROM_UNIXTIME($deadtime)");

    $torrents = [];

    $res = $GLOBALS['xoopsDB']->queryF('SELECT torrent, seeder, COUNT(*) AS c FROM peers GROUP BY torrent, seeder');

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
        if ('yes' == $row['seeder']) {
            $key = 'seeders';
        } else {
            $key = 'leechers';
        }

        $torrents[$row['torrent']][$key] = $row['c'];
    }

    $res = $GLOBALS['xoopsDB']->queryF('SELECT torrent, COUNT(*) AS c FROM comments GROUP BY torrent');

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
        $torrents[$row['torrent']]['comments'] = $row['c'];
    }

    $fields = explode(':', 'comments:leechers:seeders');

    $res = $GLOBALS['xoopsDB']->queryF('SELECT id, seeders, leechers, comments FROM torrents');

    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
        $id = $row['id'];

        $torr = $torrents[$id];

        foreach ($fields as $field) {
            if (!isset($torr[$field])) {
                $torr[$field] = 0;
            }
        }

        $update = [];

        foreach ($fields as $field) {
            if ($torr[$field] != $row[$field]) {
                $update[] = "$field = " . $torr[$field];
            }
        }

        if (count($update)) {
            $GLOBALS['xoopsDB']->queryF('UPDATE torrents SET ' . implode(',', $update) . " WHERE id = $id");
        }
    }

    //delete inactive user accounts

    $secs = 42 * 86400;

    $dt = sqlesc(get_date_time(gmtime() - $secs));

    $maxclass = UC_POWER_USER;

    $GLOBALS['xoopsDB']->queryF("DELETE FROM users WHERE status='confirmed' AND class <= $maxclass AND last_access < $dt");

    // lock topics where last post was made more than x days ago

    $secs = 7 * 86400;

    $res = $GLOBALS['xoopsDB']->queryF("SELECT topics.id FROM topics JOIN posts ON topics.lastpost = posts.id AND topics.sticky = 'no' WHERE " . gmtime() . " - UNIX_TIMESTAMP(posts.added) > $secs") or sqlerr(__FILE__, __LINE__);

    while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($res))) {
        $GLOBALS['xoopsDB']->queryF("UPDATE topics SET locked='yes' WHERE id=$arr[id]") or sqlerr(__FILE__, __LINE__);
    }

    //remove expired warnings

    $res = $GLOBALS['xoopsDB']->queryF("SELECT id FROM users WHERE warned='yes' AND warneduntil < NOW() AND warneduntil <> '0000-00-00 00:00:00'") or sqlerr(__FILE__, __LINE__);

    if ($GLOBALS['xoopsDB']->getRowsNum($res) > 0) {
        $dt = sqlesc(get_date_time());

        $msg = sqlesc("Your warning has been removed. Please keep in your best behaviour from now on.\n");

        while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($res))) {
            $GLOBALS['xoopsDB']->queryF("UPDATE users SET warned = 'no', warneduntil = '0000-00-00 00:00:00' WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);

            $GLOBALS['xoopsDB']->queryF("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
        }
    }

    // promote power users

    $limit = 25 * 1024 * 1024 * 1024;

    $minratio = 1.05;

    $maxdt = sqlesc(get_date_time(gmtime() - 86400 * 28));

    $res = $GLOBALS['xoopsDB']->queryF("SELECT id FROM users WHERE class = 0 AND uploaded >= $limit AND uploaded / downloaded >= $minratio AND added < $maxdt") or sqlerr(__FILE__, __LINE__);

    if ($GLOBALS['xoopsDB']->getRowsNum($res) > 0) {
        $dt = sqlesc(get_date_time());

        $msg = sqlesc("Congratulations, you have been auto-promoted to [b]Power User[/b]. :)\nYou can now download dox over 1 meg and view torrent NFOs.\n");

        while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($res))) {
            $GLOBALS['xoopsDB']->queryF("UPDATE users SET class = 1 WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);

            $GLOBALS['xoopsDB']->queryF("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
        }
    }

    // demote power users

    $minratio = 0.95;

    $res = $GLOBALS['xoopsDB']->queryF("SELECT id FROM users WHERE class = 1 AND uploaded / downloaded < $minratio") or sqlerr(__FILE__, __LINE__);

    if ($GLOBALS['xoopsDB']->getRowsNum($res) > 0) {
        $dt = sqlesc(get_date_time());

        $msg = sqlesc("You have been auto-demoted from [b]Power User[/b] to [b]User[/b] because your share ratio has dropped below $minratio.\n");

        while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($res))) {
            $GLOBALS['xoopsDB']->queryF("UPDATE users SET class = 0 WHERE id = $arr[id]") or sqlerr(__FILE__, __LINE__);

            $GLOBALS['xoopsDB']->queryF("INSERT INTO messages (sender, receiver, added, msg, poster) VALUES(0, $arr[id], $dt, $msg, 0)") or sqlerr(__FILE__, __LINE__);
        }
    }

    // Update stats

    $seeders = get_row_count('peers', "WHERE seeder='yes'");

    $leechers = get_row_count('peers', "WHERE seeder='no'");

    $GLOBALS['xoopsDB']->queryF("UPDATE avps SET value_u=$seeders WHERE arg='seeders'") or sqlerr(__FILE__, __LINE__);

    $GLOBALS['xoopsDB']->queryF("UPDATE avps SET value_u=$leechers WHERE arg='leechers'") or sqlerr(__FILE__, __LINE__);

    // update forum post/topic count

    $forums = $GLOBALS['xoopsDB']->queryF('SELECT id FROM forums');

    while (false !== ($forum = $GLOBALS['xoopsDB']->fetchArray($forums))) {
        $postcount = 0;

        $topiccount = 0;

        $topics = $GLOBALS['xoopsDB']->queryF("select id from topics where forumid=$forum[id]");

        while (false !== ($topic = $GLOBALS['xoopsDB']->fetchArray($topics))) {
            $res = $GLOBALS['xoopsDB']->queryF("select count(*) from posts where topicid=$topic[id]");

            $arr = $GLOBALS['xoopsDB']->fetchRow($res);

            $postcount += $arr[0];

            ++$topiccount;
        }

        $GLOBALS['xoopsDB']->queryF("update forums set postcount=$postcount, topiccount=$topiccount where id=$forum[id]");
    }

    // delete old torrents

    $days = 28;

    $dt = sqlesc(get_date_time(gmtime() - ($days * 86400)));

    $res = $GLOBALS['xoopsDB']->queryF("SELECT id, name FROM torrents WHERE added < $dt");

    while (false !== ($arr = $GLOBALS['xoopsDB']->fetchArray($res))) {
        @unlink("$torrent_dir/$arr[id].torrent");

        $GLOBALS['xoopsDB']->queryF("DELETE FROM torrents WHERE id=$arr[id]");

        $GLOBALS['xoopsDB']->queryF("DELETE FROM peers WHERE torrent=$arr[id]");

        $GLOBALS['xoopsDB']->queryF("DELETE FROM comments WHERE torrent=$arr[id]");

        $GLOBALS['xoopsDB']->queryF("DELETE FROM files WHERE torrent=$arr[id]");

        write_log("Torrent $arr[id] ($arr[name]) was deleted by system (older than $days days)");
    }
}
