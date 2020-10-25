<?php

error_reporting(E_ALL ^ E_NOTICE);
require_once 'secrets.php';
require_once 'cleanup.php';

// PHP5 with register_long_arrays off?
if (!isset($_POST) && isset($_POST)) {
    $_POST = $_POST;

    $_GET = $_GET;

    $HTTP_SERVER_VARS = $_SERVER;

    $HTTP_COOKIE_VARS = $_COOKIE;

    $HTTP_ENV_VARS = $_ENV;

    $HTTP_POST_FILES = $_FILES;
}

function strip_magic_quotes($arr)
{
    foreach ($arr as $k => $v) {
        if (is_array($v)) {
            $arr[$k] = strip_magic_quotes($v);
        } else {
            $arr[$k] = stripslashes($v);
        }
    }

    return $arr;
}

if (get_magic_quotes_gpc()) {
    if (!empty($_GET)) {
        $_GET = strip_magic_quotes($_GET);
    }

    if (!empty($_POST)) {
        $_POST = strip_magic_quotes($_POST);
    }

    if (!empty($_COOKIE)) {
        $_COOKIE = strip_magic_quotes($_COOKIE);
    }
}

//
// addslashes to vars if magic_quotes_gpc is off
// this is a security precaution to prevent someone
// trying to break out of a SQL statement.
//
/*
if( !get_magic_quotes_gpc() )
{
    if( is_array($_GET) )
    {
        while( list($k, $v) = each($_GET) )
        {
            if( is_array($_GET[$k]) )
            {
                while( list($k2, $v2) = each($_GET[$k]) )
                {
                    $_GET[$k][$k2] = addslashes($v2);
                }
                @reset($_GET[$k]);
            }
            else
            {
                $_GET[$k] = addslashes($v);
            }
        }
        @reset($_GET);
    }

    if( is_array($_POST) )
    {
        while( list($k, $v) = each($_POST) )
        {
            if( is_array($_POST[$k]) )
            {
                while( list($k2, $v2) = each($_POST[$k]) )
                {
                    $_POST[$k][$k2] = addslashes($v2);
                }
                @reset($_POST[$k]);
            }
            else
            {
                $_POST[$k] = addslashes($v);
            }
        }
        @reset($_POST);
    }

    if( is_array($HTTP_COOKIE_VARS) )
    {
        while( list($k, $v) = each($HTTP_COOKIE_VARS) )
        {
            if( is_array($HTTP_COOKIE_VARS[$k]) )
            {
                while( list($k2, $v2) = each($HTTP_COOKIE_VARS[$k]) )
                {
                    $HTTP_COOKIE_VARS[$k][$k2] = addslashes($v2);
                }
                @reset($HTTP_COOKIE_VARS[$k]);
            }
            else
            {
                $HTTP_COOKIE_VARS[$k] = addslashes($v);
            }
        }
        @reset($HTTP_COOKIE_VARS);
    }
}
*/
function local_user()
{
    global $HTTP_SERVER_VARS;

    return $HTTP_SERVER_VARS['SERVER_ADDR'] == $HTTP_SERVER_VARS['REMOTE_ADDR'];
}

/*dbconn();
$sql = "SELECT *
    FROM config";
if( !($result = $GLOBALS['xoopsDB']->queryF($sql)) )
{
    die("Could not query config information");
}

while ( $row = $GLOBALS['xoopsDB']->fetchArray($result) )
{
    $config[$row['name']] = $row['value'];
}
*/
//$FUNDS = $config['funds'];

$SITE_ONLINE = $config['siteonline'];
//$SITE_ONLINE = local_user();
//$SITE_ONLINE = false;

$max_torrent_size = 1000000;
$announce_interval = 60 * 30;
$signup_timeout = 86400 * 3;
$minvotes = 1;
$max_dead_torrent_time = 6 * 3600;

// Max users on site
$maxusers = 75000;

# the first one will be displayed on the pages
$announce_urls = [];
$announce_urls[] = $xoopsModuleConfig['announce_url'];

if ('' == $HTTP_SERVER_VARS['HTTP_HOST']) {
    $HTTP_SERVER_VARS['HTTP_HOST'] = $HTTP_SERVER_VARS['SERVER_NAME'];
}
$BASEURL = 'http://' . $HTTP_SERVER_VARS['HTTP_HOST'];

//set this to true to make this a tracker that only registered users may use
$MEMBERSONLY = $xoopsModuleConfig['opentracker'];

//maximum number of peers (seeders+leechers) allowed before torrents starts to be deleted to make room...
//set this to something high if you don't require this feature
$PEERLIMIT = $xoopsModuleConfig['peerlimit'];

//
//
// Email for sender/return path.
$SITEEMAIL = $xoopsConfig['adminemail'];

$SITENAME = $xoopsConfig['sitename'];

// Set this to your site URL... No ending slash!
$DEFAULTBASEURL = XOOPS_URL;

$autoclean_interval = 900;
$pic_base_url = 'pic/';

/**** validip/getip courtesy of manolete <manolete@myway.com> ***
 * @param $ip
 * @return bool
 */

// IP Validation
function validip($ip)
{
    if (!empty($ip) && -1 != ip2long($ip)) {
        // reserved IANA IPv4 addresses

        // http://www.iana.org/assignments/ipv4-address-space

        $reserved_ips = [
            ['0.0.0.0', '2.255.255.255'],
            ['10.0.0.0', '10.255.255.255'],
            ['127.0.0.0', '127.255.255.255'],
            ['169.254.0.0', '169.254.255.255'],
            ['172.16.0.0', '172.31.255.255'],
            ['192.0.2.0', '192.0.2.255'],
            ['192.168.0.0', '192.168.255.255'],
            ['255.255.255.0', '255.255.255.255'],
        ];

        foreach ($reserved_ips as $r) {
            $min = ip2long($r[0]);

            $max = ip2long($r[1]);

            if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) {
                return false;
            }
        }

        return true;
    }
  

    return false;
}

// Patched function to detect REAL IP address if it's valid
function getip()
{
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } else {
            $ip = getenv('REMOTE_ADDR');
        }
    }

    return $ip;
}

/*
function dbconn($autoclean = false)
{
    global $mysql_host, $mysql_user, $mysql_pass, $mysql_db, $HTTP_SERVER_VARS;

    if (!@mysql_connect($mysql_host, $mysql_user, $mysql_pass))
    {
      switch ($GLOBALS['xoopsDB']->errno())
      {
        case 1040:
        case 2002:
            if ($HTTP_SERVER_VARS[REQUEST_METHOD] == "GET")
                die("<html><head><meta http-equiv=refresh content=\"5 $HTTP_SERVER_VARS[REQUEST_URI]\"></head><body><table border=0 width=100% height=100%><tr><td><h3 align=center>The server load is very high at the moment. Retrying, please wait...</h3></td></tr></table></body></html>");
            else
                die("Too many users. Please press the Refresh button in your browser to retry.");
        default:
            die("[" . $GLOBALS['xoopsDB']->errno() . "] dbconn: mysql_connect: " . $GLOBALS['xoopsDB']->error());
      }
    }
    mysqli_select_db($GLOBALS['xoopsDB']->conn, $mysql_db)
        || die('dbconn: mysql_select_db: ' + $GLOBALS['xoopsDB']->error());

    userlogin();

    if ($autoclean)
        register_shutdown_function("autoclean");
}
*/

function userlogin()
{
    global $HTTP_SERVER_VARS, $SITE_ONLINE;

    unset($GLOBALS['CURUSER']);

    $ip = getip();

    $nip = ip2long($ip);

    $res = $GLOBALS['xoopsDB']->queryF("SELECT * FROM bans WHERE $nip >= first AND $nip <= last") or sqlerr(__FILE__, __LINE__);

    if ($GLOBALS['xoopsDB']->getRowsNum($res) > 0) {
        header('HTTP/1.0 403 Forbidden');

        print("<html><body><h1>403 Forbidden</h1>Unauthorized IP address.</body></html>\n");

        die;
    }

    if (!$SITE_ONLINE || empty($_COOKIE['uid']) || empty($_COOKIE['pass'])) {
        return;
    }

    $id = 0 + $_COOKIE['uid'];

    if (!$id || 32 != mb_strlen($_COOKIE['pass'])) {
        return;
    }

    $res = $GLOBALS['xoopsDB']->queryF("SELECT * FROM users WHERE id = $id AND enabled='yes' AND status = 'confirmed'"); // || die($GLOBALS['xoopsDB']->error());

    $row = $GLOBALS['xoopsDB']->fetchBoth($res);

    if (!$row) {
        return;
    }

    $sec = hash_pad($row['secret']);

    if ($_COOKIE['pass'] !== $row['passhash']) {
        return;
    }

    $GLOBALS['xoopsDB']->queryF("UPDATE users SET last_access='" . get_date_time() . "', ip='$ip' WHERE id=" . $row['id']); // || die($GLOBALS['xoopsDB']->error());

    $row['ip'] = $ip;

    $GLOBALS['CURUSER'] = $row;
}

function autoclean()
{
    global $autoclean_interval;

    $now = time();

    $docleanup = 0;

    $res = $GLOBALS['xoopsDB']->queryF("SELECT value_u FROM avps WHERE arg = 'lastcleantime'");

    $row = $GLOBALS['xoopsDB']->fetchBoth($res);

    if (!$row) {
        $GLOBALS['xoopsDB']->queryF("INSERT INTO avps (arg, value_u) VALUES ('lastcleantime',$now)");

        return;
    }

    $ts = $row[0];

    if ($ts + $autoclean_interval > $now) {
        return;
    }

    $GLOBALS['xoopsDB']->queryF("UPDATE avps SET value_u=$now WHERE arg='lastcleantime' AND value_u = $ts");

    if (!$GLOBALS['xoopsDB']->getAffectedRows()) {
        return;
    }

    docleanup();
}

function unesc($x)
{
    if (function_exists('get_magic_quotes_gpc') && @get_magic_quotes_gpc()) {
        return stripslashes($x);
    }

    return $x;
}

function mksize($bytes)
{
    if ($bytes < 1000 * 1024) {
        return number_format($bytes / 1024, 2) . ' kB';
    } elseif ($bytes < 1000 * 1048576) {
        return number_format($bytes / 1048576, 2) . ' MB';
    } elseif ($bytes < 1000 * 1073741824) {
        return number_format($bytes / 1073741824, 2) . ' GB';
    }
  

    return number_format($bytes / 1099511627776, 2) . ' TB';
}

function mksizeint($bytes)
{
    $bytes = max(0, $bytes);

    if ($bytes < 1000) {
        return floor($bytes) . ' B';
    } elseif ($bytes < 1000 * 1024) {
        return floor($bytes / 1024) . ' kB';
    } elseif ($bytes < 1000 * 1048576) {
        return floor($bytes / 1048576) . ' MB';
    } elseif ($bytes < 1000 * 1073741824) {
        return floor($bytes / 1073741824) . ' GB';
    }
  

    return floor($bytes / 1099511627776) . ' TB';
}

function deadtime()
{
    global $announce_interval;

    return time() - floor($announce_interval * 1.3);
}

function mkprettytime($s)
{
    if ($s < 0) {
        $s = 0;
    }

    $t = [];

    foreach (['60:sec', '60:min', '24:hour', '0:day'] as $x) {
        $y = explode(':', $x);

        if ($y[0] > 1) {
            $v = $s % $y[0];

            $s = floor($s / $y[0]);
        } else {
            $v = $s;
        }

        $t[$y[1]] = $v;
    }

    if ($t['day']) {
        return $t['day'] . 'd ' . sprintf('%02d:%02d:%02d', $t['hour'], $t['min'], $t['sec']);
    }

    if ($t['hour']) {
        return sprintf('%d:%02d:%02d', $t['hour'], $t['min'], $t['sec']);
    }

    //    if ($t["min"])

    return sprintf('%d:%02d', $t['min'], $t['sec']);
    //    return $t["sec"] . " secs";
}

function mkglobal($vars)
{
    if (!is_array($vars)) {
        $vars = explode(':', $vars);
    }

    foreach ($vars as $v) {
        if (isset($_GET[$v])) {
            $GLOBALS[$v] = unesc($_GET[$v]);
        } elseif (isset($_POST[$v])) {
            $GLOBALS[$v] = unesc($_POST[$v]);
        } else {
            return 0;
        }
    }

    return 1;
}

function tr($x, $y, $noesc = 0)
{
    if ($noesc) {
        $a = $y;
    } else {
        $a = htmlspecialchars($y, ENT_QUOTES | ENT_HTML5);

        $a = str_replace("\n", "<br>\n", $a);
    }

    print("<tr><td class=\"heading\" valign=\"top\" align=\"right\">$x</td><td valign=\"top\" align=left>$a</td></tr>\n");
}

function validfilename($name)
{
    return preg_match('/^[^\0-\x1f:\\\\\/?*\xff#<>|]+$/si', $name);
}

function validemail($email)
{
    return preg_match('/^[\w.-]+@([\w.-]+\.)+[a-z]{2,6}$/is', $email);
}

function sqlesc($x)
{
    return "'" . $GLOBALS['xoopsDB']->escape($x) . "'";
}

function sqlwildcardesc($x)
{
    return str_replace(['%', '_'], ['\\%', '\\_'], $GLOBALS['xoopsDB']->escape($x));
}

function urlparse($m)
{
    $t = $m[0];

    if (preg_match(',^\w+://,', $t)) {
        return "<a href=\"$t\">$t</a>";
    }

    return "<a href=\"http://$t\">$t</a>";
}

function parsedescr($d, $html)
{
    if (!$html) {
        $d = htmlspecialchars($d, ENT_QUOTES | ENT_HTML5);

        $d = str_replace("\n", "\n<br>", $d);
    }

    return $d;
}

function stdhead($title = '', $msgalert = true)
{
    global $CURUSER, $HTTP_SERVER_VARS, $PHP_SELF, $SITE_ONLINE, $FUNDS, $SITENAME;

    if (!$SITE_ONLINE) {
        die('Site is down for maintenance, please check back again later... thanks<br>');
    }

    header('Content-Type: text/html; charset=iso-8859-1');

    //header("Pragma: No-cache");

    if ('' == $title) {
        $title = $SITENAME;
    } else {
        $title = "$SITENAME :: " . htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
    }

    if ($CURUSER) {
        $ss_a = @$GLOBALS['xoopsDB']->fetchBoth(@$GLOBALS['xoopsDB']->queryF('select uri from stylesheets where id=' . $CURUSER['stylesheet']));

        if ($ss_a) {
            $ss_uri = $ss_a['uri'];
        }
    }

    if (!$ss_uri) {
        ($r = $GLOBALS['xoopsDB']->queryF('SELECT uri FROM stylesheets WHERE id=1')) || die($GLOBALS['xoopsDB']->error());

        ($a = $GLOBALS['xoopsDB']->fetchBoth($r)) || die($GLOBALS['xoopsDB']->error());

        $ss_uri = $a['uri'];
    }

    if ($msgalert && $CURUSER) {
        $res = $GLOBALS['xoopsDB']->queryF('SELECT COUNT(*) FROM messages WHERE receiver=' . $CURUSER['id'] . " && unread='yes'") || die('OopppsY!');

        $arr = $GLOBALS['xoopsDB']->fetchRow($res);

        $unread = $arr[0];
    } ?>
<html>
<head>
    <title><?= $title ?></title>
    <link rel="stylesheet" href="styles/<?= $ss_uri ?>" type="text/css">
</head>
<body>

<table width=100% cellspacing=0 cellpadding=0 style='background: transparent'>
    <tr>
        <td class=clear width=49%>
            <!--
<table border=0 cellspacing=0 cellpadding=0 style='background: transparent'>
<tr>

<td class=clear>
<img src=/pic/star20.gif style='margin-right: 10px'>
</td>
<td class=clear>
<font color=white><b>Current funds: <?= $FUNDS ?></b></font>
</td>
</tr>
</table>
-->

        </td>
        <td class=clear>
            <div align=center>
                <img src="pic/logo.gif" align=center>
            </div>
        </td>
        <td class=clear width=49% align=right>
            <a href=donate.php><img src="https://www.paypal.com/en_US/i/btn/x-click-but04.gif" border="0" alt="Make a donation" style='margin-top: 5px'></a>
        </td>
    </tr>
</table>
<?php

$w = 'width=100%';

    //if ($HTTP_SERVER_VARS["REMOTE_ADDR"] == $HTTP_SERVER_VARS["SERVER_ADDR"]) $w = "width=984"; ?>
<table class=mainouter <?= $w; ?> border="1" cellspacing="0" cellpadding="10">

    <!------------- MENU ------------------------------------------------------------------------>

    <?php $fn = mb_substr($PHP_SELF, mb_strrpos($PHP_SELF, '/') + 1); ?>
    <tr>
        <td class=outer align=center>
            <table class=main width=700 cellspacing="0" cellpadding="5" border="0">
                <tr>

                    <td align="center" class="navigation"><a href='index.php'>Home</a></td>
                    <td align="center" class="navigation"><a href='browse.php'>Browse</a></td>
                    <td align="center" class="navigation"><a href='upload.php'>Upload</a></td>
                    <?php if (!$CURUSER) { ?>
                        <td align="center" class="navigation">
                            <a href='login.php'>Login</a> / <a href='signup.php'>Signup</a>
                        </td>
                    <?php } else { ?>
                        <td align="center" class="navigation"><a href='my.php'>Profile</a></td>
                    <?php } ?>
                    <td align="center" class="navigation">Chat</td>
                    <td align="center" class="navigation"><a href='forums.php'>Forums</a></td>
                    <td align="center" class="navigation">DOX</td>
                    <td align="center" class="navigation"><a href='topten.php'>Top 10</a></td>
                    <td align="center" class="navigation"><a href='log.php'>Log</a></td>
                    <td align="center" class="navigation"><a href='rules.php'>Rules</a></td>
                    <td align="center" class="navigation"><a href='faq.php'>FAQ</a></td>
                    <td align="center" class="navigation"><a href='links.php'>Links</a></td>
                    <td align="center" class="navigation"><a href='staff.php'>Staff</a></td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td align=center class=outer style="padding-top: 20px; padding-bottom: 20px">
            <?php

            if ($unread) {
                print("<p><table border=0 cellspacing=0 cellpadding=10 bgcolor=red><tr><td style='padding: 10px; background: red'>\n");

                print("<b><a href=$BASEURL/inbox.php><font color=white>You have $unread new message" . ($unread > 1 ? 's' : '') . '!</font></a></b>');

                print("</td></tr></table></p>\n");
            }
} // stdhead

            function stdfoot()
            {
                print("</td></tr></table>\n");

                print("<table class=bottom width=100% border=0 cellspacing=0 cellpadding=0><tr valign=top>\n");

                print("<td class=bottom align=left width=49%><img src=pic/bottom_left.gif></td><td width=49% align=right class=bottom><img src=pic/bottom_right.gif></td>\n");

                print("</tr></table>\n");

                print("</body></html>\n");
            }

            function genbark($x, $y)
            {
                stdhead($y);

                print('<h2>' . htmlspecialchars($y, ENT_QUOTES | ENT_HTML5) . "</h2>\n");

                print('<p>' . htmlspecialchars($x, ENT_QUOTES | ENT_HTML5) . "</p>\n");

                stdfoot();

                exit();
            }

            function mksecret($len = 20)
            {
                $ret = '';

                for ($i = 0; $i < $len; $i++) {
                    $ret .= chr(mt_rand(0, 255));
                }

                return $ret;
            }

            function httperr($code = 404)
            {
                header('HTTP/1.0 404 Not found');

                print("<h1>Not Found</h1>\n");

                print("<p>Sorry pal :(</p>\n");

                exit();
            }

            function gmtime()
            {
                return strtotime(get_date_time());
            }

            /*
            function logincookie($id, $password, $secret, $updatedb = 1, $expires = 0x7fffffff) {
                $md5 = md5($secret . $password . $secret);
                setcookie("uid", $id, $expires, "/");
                setcookie("pass", $md5, $expires, "/");

                if ($updatedb)
                    $GLOBALS['xoopsDB']->queryF("UPDATE users SET last_login = NOW() WHERE id = $id");
            }
            */

            function logincookie($id, $passhash, $updatedb = 1, $expires = 0x7fffffff)
            {
                setcookie('uid', $id, $expires, '/');

                setcookie('pass', $passhash, $expires, '/');

                if ($updatedb) {
                    $GLOBALS['xoopsDB']->queryF("UPDATE users SET last_login = NOW() WHERE id = $id");
                }
            }

            function logoutcookie()
            {
                setcookie('uid', '', 0x7fffffff, '/');

                setcookie('pass', '', 0x7fffffff, '/');
            }

            function loggedinorreturn()
            {
                global $CURUSER;

                if (!$CURUSER) {
                    header('Location: login.php?returnto=' . urlencode($_SERVER['REQUEST_URI']));

                    exit();
                }
            }

            function deletetorrent($id)
            {
                global $torrent_dir;

                $GLOBALS['xoopsDB']->queryF("DELETE FROM torrents WHERE id = $id");

                foreach (explode('.', 'peers.files.comments.ratings') as $x) {
                    $GLOBALS['xoopsDB']->queryF("DELETE FROM $x WHERE torrent = $id");
                }

                unlink("$torrent_dir/$id.torrent");
            }

            function pager($rpp, $count, $href, $opts = [])
            {
                $pages = ceil($count / $rpp);

                if (!$opts['lastpagedefault']) {
                    $pagedefault = 0;
                } else {
                    $pagedefault = floor(($count - 1) / $rpp);

                    if ($pagedefault < 0) {
                        $pagedefault = 0;
                    }
                }

                if (isset($_GET['page'])) {
                    $page = 0 + $_GET['page'];

                    if ($page < 0) {
                        $page = $pagedefault;
                    }
                } else {
                    $page = $pagedefault;
                }

                $pager = '';

                $mp = $pages - 1;

                $as = '<b>&lt;&lt;&nbsp;Prev</b>';

                if ($page >= 1) {
                    $pager .= "<a href=\"{$href}page=" . ($page - 1) . '">';

                    $pager .= $as;

                    $pager .= '</a>';
                } else {
                    $pager .= $as;
                }

                $pager .= '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';

                $as = '<b>Next&nbsp;&gt;&gt;</b>';

                if ($page < $mp && $mp >= 0) {
                    $pager .= "<a href=\"{$href}page=" . ($page + 1) . '">';

                    $pager .= $as;

                    $pager .= '</a>';
                } else {
                    $pager .= $as;
                }

                if ($count) {
                    $pagerarr = [];

                    $dotted = 0;

                    $dotspace = 3;

                    $dotend = $pages - $dotspace;

                    $curdotend = $page - $dotspace;

                    $curdotstart = $page + $dotspace;

                    for ($i = 0; $i < $pages; $i++) {
                        if (($i >= $dotspace && $i <= $curdotend) || ($i >= $curdotstart && $i < $dotend)) {
                            if (!$dotted) {
                                $pagerarr[] = '...';
                            }

                            $dotted = 1;

                            continue;
                        }

                        $dotted = 0;

                        $start = $i * $rpp + 1;

                        $end = $start + $rpp - 1;

                        if ($end > $count) {
                            $end = $count;
                        }

                        $text = "$start&nbsp;-&nbsp;$end";

                        if ($i != $page) {
                            $pagerarr[] = "<a href=\"{$href}page=$i\"><b>$text</b></a>";
                        } else {
                            $pagerarr[] = "<b>$text</b>";
                        }
                    }

                    $pagerstr = implode(' | ', $pagerarr);

                    $pagertop = "<p align=\"center\">$pager<br>$pagerstr</p>\n";

                    $pagerbottom = "<p align=\"center\">$pagerstr<br>$pager</p>\n";
                } else {
                    $pagertop = "<p align=\"center\">$pager</p>\n";

                    $pagerbottom = $pagertop;
                }

                $start = $page * $rpp;

                return [$pagertop, $pagerbottom, "LIMIT $start,$rpp"];
            }

            function downloaderdata($res)
            {
                $rows = [];

                $ids = [];

                $peerdata = [];

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
                    $rows[] = $row;

                    $id = $row['id'];

                    $ids[] = $id;

                    $peerdata[$id] = [downloaders => 0, seeders => 0, comments => 0];
                }

                if (count($ids)) {
                    $allids = implode(',', $ids);

                    $res = $GLOBALS['xoopsDB']->queryF("SELECT COUNT(*) AS c, torrent, seeder FROM peers WHERE torrent IN ($allids) GROUP BY torrent, seeder");

                    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
                        if ('yes' == $row['seeder']) {
                            $key = 'seeders';
                        } else {
                            $key = 'downloaders';
                        }

                        $peerdata[$row['torrent']][$key] = $row['c'];
                    }

                    $res = $GLOBALS['xoopsDB']->queryF("SELECT COUNT(*) AS c, torrent FROM comments WHERE torrent IN ($allids) GROUP BY torrent");

                    while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
                        $peerdata[$row['torrent']]['comments'] = $row['c'];
                    }
                }

                return [$rows, $peerdata];
            }

            function commenttable($rows)
            {
                global $CURUSER, $HTTP_SERVER_VARS;

                begin_main_frame();

                begin_frame();

                $count = 0;

                foreach ($rows as $row) {
                    print('<p class=sub>#' . $row['id'] . ' by ');

                    if (isset($row['username'])) {
                        $title = $row['title'];

                        if ('' == $title) {
                            $title = get_user_class_name($row['class']);
                        } else {
                            $title = htmlspecialchars($title, ENT_QUOTES | ENT_HTML5);
                        }

                        print('<a name=comm'
                              . $row['id']
                              . ' href=userdetails.php?id='
                              . $row['user']
                              . '><b>'
                              . htmlspecialchars($row['username'], ENT_QUOTES | ENT_HTML5)
                              . '</b></a>'
                              . ('yes' == $row['donor'] ? "<img src=pic/star.gif alt='Donor'>" : '')
                              . ('yes' == $row['warned'] ? '<img src='
                                                           . 'pic/warned.gif alt="Warned">' : '')
                              . " ($title)\n");
                    } else {
                        print('<a name="comm' . $row['id'] . "\"><i>(orphaned)</i></a>\n");
                    }

                    print(' at '
                          . $row['added']
                          . ' GMT'
                          . ($row['user'] == $CURUSER['id'] || get_user_class() >= UC_MODERATOR ? "- [<a href=comment.php?action=edit&amp;cid=$row[id]>Edit</a>]" : '')
                          . (get_user_class()
                             >= UC_MODERATOR ? "- [<a href=comment.php?action=delete&amp;cid=$row[id]>Delete</a>]" : '')
                          . ($row['editedby'] && get_user_class() >= UC_MODERATOR ? "- [<a href=comment.php?action=vieworiginal&amp;cid=$row[id]>View original</a>]" : '')
                          . "</p>\n");

                    $avatar = ('yes' == $CURUSER['avatars'] ? htmlspecialchars($row['avatar'], ENT_QUOTES | ENT_HTML5) : '');

                    if (!$avatar) {
                        $avatar = 'pic/default_avatar.gif';
                    }

                    $text = format_comment($row['text']);

                    if ($row['editedby']) {
                        $text .= "<p><font size=1 class=small>Last edited by <a href=userdetails.php?id=$row[editedby]><b>$row[username]</b></a> at $row[editedat] GMT</font></p>\n";
                    }

                    begin_table(true);

                    print("<tr valign=top>\n");

                    print("<td align=center width=150 style='padding: 0px'><img width=150 src=$avatar></td>\n");

                    print("<td class=text>$text</td>\n");

                    print("</tr>\n");

                    end_table();
                }

                end_frame();

                end_main_frame();
            }

            function searchfield($s)
            {
                return preg_replace(['/[^a-z0-9]/si', '/^\s*/s', '/\s*$/s', '/\s+/s'], [' ', '', '', ' '], $s);
            }

            function genrelist()
            {
                $ret = [];

                $res = $GLOBALS['xoopsDB']->queryF('SELECT id, name FROM categories ORDER BY name');

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchBoth($res))) {
                    $ret[] = $row;
                }

                return $ret;
            }

            function linkcolor($num)
            {
                if (!$num) {
                    return 'red';
                }

                //    if ($num == 1)

                //        return "yellow";

                return 'green';
            }

            function ratingpic($num)
            {
                global $pic_base_url;

                $r = round($num * 2) / 2;

                if ($r < 1 || $r > 5) {
                    return;
                }

                return "<img src=\"$pic_base_url$r.gif\" border=\"0\" alt=\"rating: $num / 5\">";
            }

            function torrenttable($res, $variant = 'index')
            {
                global $pic_base_url, $CURUSER;

                if ($CURUSER['class'] < UC_VIP) {
                    $gigs = $CURUSER['uploaded'] / (1024 * 1024 * 1024);

                    $ratio = (($CURUSER['downloaded'] > 0) ? ($CURUSER['uploaded'] / $CURUSER['downloaded']) : 0);

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
                } ?>
            <table border="1" cellspacing=0 cellpadding=5>
                <tr>

                    <td class="colhead" align="center">Type</td>
                    <td class="colhead" align=left>Name</td>
                    <!--<td class="heading" align=left>DL</td>-->
                    <?php
                    if ($wait) {
                        print("<td class=\"colhead\" align=\"center\">Wait</td>\n");
                    }

                if ('mytorrents' == $variant) {
                    print("<td class=\"colhead\" align=\"center\">Edit</td>\n");

                    print("<td class=\"colhead\" align=\"center\">Visible</td>\n");
                } ?>
                    <td class="colhead" align=right>Files</td>
                    <td class="colhead" align=right>Comm.</td>
                    <!--<td class="colhead" align="center">Rating</td>-->
                    <td class="colhead" align="center">Added</td>
                    <td class="colhead" align="center">TTL</td>
                    <td class="colhead" align="center">Size</td>
                    <!--
                    <td class="colhead" align=right>Views</td>
                    <td class="colhead" align=right>Hits</td>
                    -->
                    <td class="colhead" align="center">Snatched</td>
                    <td class="colhead" align=right>Seeders</td>
                    <td class="colhead" align=right>Leechers</td>
                    <?php

                    if ('index' == $variant) {
                        print("<td class=\"colhead\" align=center>Upped&nbsp;by</td>\n");
                    }

                print("</tr>\n");

                while (false !== ($row = $GLOBALS['xoopsDB']->fetchArray($res))) {
                    $id = $row['id'];

                    print("<tr>\n");

                    print("<td align=center style='padding: 0px'>");

                    if (isset($row['cat_name'])) {
                        print('<a href="browse.php?cat=' . $row['category'] . '">');

                        if (isset($row['cat_pic']) && '' != $row['cat_pic']) {
                            print("<img border=\"0\" src=\"$pic_base_url" . $row['cat_pic'] . '" alt="' . $row['cat_name'] . '">');
                        } else {
                            print($row['cat_name']);
                        }

                        print('</a>');
                    } else {
                        print('-');
                    }

                    print("</td>\n");

                    $dispname = htmlspecialchars($row['name'], ENT_QUOTES | ENT_HTML5);

                    print('<td align=left><a href="details.php?');

                    if ('mytorrents' == $variant) {
                        print('returnto=' . urlencode($_SERVER['REQUEST_URI']) . '&amp;');
                    }

                    print("id=$id");

                    if ('index' == $variant) {
                        print('&amp;hit=1');
                    }

                    print("\"><b>$dispname</b></a>\n");

                    if ($wait) {
                        $elapsed = floor((gmtime() - strtotime($row['added'])) / 3600);

                        if ($elapsed < $wait) {
                            $color = dechex(floor(127 * ($wait - $elapsed) / 48 + 128) * 65536);

                            print("<td align=center><nobr><a href=\"/faq.php#dl8\"><font color=\"$color\">" . number_format($wait - $elapsed) . " h</font></a></nobr></td>\n");
                        } else {
                            print("<td align=center><nobr>None</nobr></td>\n");
                        }
                    }

                    /*
                            if ($row["nfoav"] && get_user_class() >= UC_POWER_USER)
                              print("<a href=viewnfo.php?id=$row[id]><img src=pic/viewnfo.gif border=0 alt='View NFO'></a>\n");
                            if ($variant == "index")
                                print("<a href=\"download.php/$id/" . rawurlencode($row["filename"]) . "\"><img src=pic/download.gif border=0 alt=Download></a>\n");

                            else */

                    if ('mytorrents' == $variant) {
                        print('<td align="center"><a href="edit.php?returnto=' . urlencode($_SERVER['REQUEST_URI']) . '&amp;id=' . $row['id'] . "\">edit</a>\n");
                    }

                    print("</td>\n");

                    if ('mytorrents' == $variant) {
                        print('<td align="right">');

                        if ('no' == $row['visible']) {
                            print('<b>no</b>');
                        } else {
                            print('yes');
                        }

                        print("</td>\n");
                    }

                    if ('single' == $row['type']) {
                        print('<td align="right">' . $row['numfiles'] . "</td>\n");
                    } else {
                        if ('index' == $variant) {
                            print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;filelist=1\">" . $row['numfiles'] . "</a></b></td>\n");
                        } else {
                            print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;filelist=1#filelist\">" . $row['numfiles'] . "</a></b></td>\n");
                        }
                    }

                    if (!$row['comments']) {
                        print('<td align="right">' . $row['comments'] . "</td>\n");
                    } else {
                        if ('index' == $variant) {
                            print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;hit=1&amp;tocomm=1\">" . $row['comments'] . "</a></b></td>\n");
                        } else {
                            print("<td align=\"right\"><b><a href=\"details.php?id=$id&amp;page=0#startcomments\">" . $row['comments'] . "</a></b></td>\n");
                        }
                    }

                    /*
                            print("<td align=\"center\">");
                            if (!isset($row["rating"]))
                                print("---");
                            else {
                                $rating = round($row["rating"] * 2) / 2;
                                $rating = ratingpic($row["rating"]);
                                if (!isset($rating))
                                    print("---");
                                else
                                    print($rating);
                            }
                            print("</td>\n");
                    */

                    print('<td align=center><nobr>' . str_replace(' ', '<br>', $row['added']) . "</nobr></td>\n");

                    $ttl = (28 * 24) - floor((gmtime() - sql_timestamp_to_unix_timestamp($row['added'])) / 3600);

                    if (1 == $ttl) {
                        $ttl .= '<br>hour';
                    } else {
                        $ttl .= '<br>hours';
                    }

                    print("<td align=center>$ttl</td>\n");

                    print('<td align=center>' . str_replace(' ', '<br>', mksize($row['size'])) . "</td>\n");

                    //        print("<td align=\"right\">" . $row["views"] . "</td>\n");

                    //        print("<td align=\"right\">" . $row["hits"] . "</td>\n");

                    $_s = '';

                    if (1 != $row['times_completed']) {
                        $_s = 's';
                    }

                    print('<td align=center>' . number_format($row['times_completed']) . "<br>time$_s</td>\n");

                    if ($row['seeders']) {
                        if ('index' == $variant) {
                            if ($row['leechers']) {
                                $ratio = $row['seeders'] / $row['leechers'];
                            } else {
                                $ratio = 1;
                            }

                            print("<td align=right><b><a href=details.php?id=$id&amp;hit=1&amp;toseeders=1><font color=" . get_slr_color($ratio) . '>' . $row['seeders'] . "</font></a></b></td>\n");
                        } else {
                            print('<td align="right"><b><a class="' . linkcolor($row['seeders']) . "\" href=\"details.php?id=$id&amp;dllist=1#seeders\">" . $row['seeders'] . "</a></b></td>\n");
                        }
                    } else {
                        print('<td align="right"><span class="' . linkcolor($row['seeders']) . '">' . $row['seeders'] . "</span></td>\n");
                    }

                    if ($row['leechers']) {
                        if ('index' == $variant) {
                            print("<td align=right><b><a href=details.php?id=$id&amp;hit=1&amp;todlers=1>" . number_format($row['leechers']) . (isset($peerlink) ? '</a>' : '') . "</b></td>\n");
                        } else {
                            print('<td align="right"><b><a class="' . linkcolor($row['leechers']) . "\" href=\"details.php?id=$id&amp;dllist=1#leechers\">" . $row['leechers'] . "</a></b></td>\n");
                        }
                    } else {
                        print("<td align=\"right\">0</td>\n");
                    }

                    if ('index' == $variant) {
                        print('<td align=center>' . (isset($row['username']) ? ('<a href=userdetails.php?id=' . $row['owner'] . '><b>' . htmlspecialchars($row['username'], ENT_QUOTES | ENT_HTML5) . '</b></a>') : '<i>(unknown)</i>') . "</td>\n");
                    }

                    print("</tr>\n");
                }

                print("</table>\n");

                return $rows;
            }

                    function hit_start()
                    {
                        return;
                        global $RUNTIME_START, $RUNTIME_TIMES;

                        $RUNTIME_TIMES = posix_times();

                        $RUNTIME_START = gettimeofday();
                    }

                    function hit_count()
                    {
                        return;
                        global $RUNTIME_CLAUSE;

                        if (preg_match(',([^/]+)$,', $_SERVER['SCRIPT_NAME'], $matches)) {
                            $path = $matches[1];
                        } else {
                            $path = '(unknown)';
                        }

                        $period = date('Y-m-d H') . ':00:00';

                        $RUNTIME_CLAUSE = 'page = ' . sqlesc($path) . " AND period = '$period'";

                        $update = "UPDATE hits SET count = count + 1 WHERE $RUNTIME_CLAUSE";

                        $GLOBALS['xoopsDB']->queryF($update);

                        if ($GLOBALS['xoopsDB']->getAffectedRows()) {
                            return;
                        }

                        $ret = $GLOBALS['xoopsDB']->queryF('INSERT INTO hits (page, period, count) VALUES (' . sqlesc($path) . ", '$period', 1)");

                        if (!$ret) {
                            $GLOBALS['xoopsDB']->queryF($update);
                        }
                    }

                    function hit_end()
                    {
                        return;
                        global $RUNTIME_START, $RUNTIME_CLAUSE, $RUNTIME_TIMES;

                        if (empty($RUNTIME_CLAUSE)) {
                            return;
                        }

                        $now = gettimeofday();

                        $runtime = ($now['sec'] - $RUNTIME_START['sec']) + ($now['usec'] - $RUNTIME_START['usec']) / 1000000;

                        $ts = posix_times();

                        $sys = ($ts['stime'] - $RUNTIME_TIMES['stime']) / 100;

                        $user = ($ts['utime'] - $RUNTIME_TIMES['utime']) / 100;

                        $GLOBALS['xoopsDB']->queryF("UPDATE hits SET runs = runs + 1, runtime = runtime + $runtime, user_cpu = user_cpu + $user, sys_cpu = sys_cpu + $sys WHERE $RUNTIME_CLAUSE");
                    }

                    function hash_pad($hash)
                    {
                        return str_pad($hash, 20);
                    }

                    function hash_where($name, $hash)
                    {
                        $shhash = rtrim($hash, ' ');

                        return "($name = " . sqlesc($hash) . " OR $name = " . sqlesc($shhash) . ')';
                    }

                    function get_user_icons($arr, $big = false)
                    {
                        if ($big) {
                            $donorpic = 'starbig.gif';

                            $warnedpic = 'warnedbig.gif';

                            $disabledpic = 'disabledbig.gif';

                            $style = "style='margin-left: 4pt'";
                        } else {
                            $donorpic = 'star.gif';

                            $warnedpic = 'warned.gif';

                            $disabledpic = 'disabled.gif';

                            $style = 'style="margin-left: 2pt"';
                        }

                        $pics = 'yes' == $arr['donor'] ? "<img src=pic/$donorpic alt='Donor' border=0 $style>" : '';

                        if ('yes' == $arr['enabled']) {
                            $pics .= 'yes' == $arr['warned'] ? "<img src=pic/$warnedpic alt=\"Warned\" border=0 $style>" : '';
                        } else {
                            $pics .= "<img src=pic/$disabledpic alt=\"Disabled\" border=0 $style>\n";
                        }

                        return $pics;
                    }

                    require 'global.php';

                    ?>
