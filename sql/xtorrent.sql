# phpMyAdmin SQL Dump
# version 2.5.5-pl1
# http://www.phpmyadmin.net
#
# Host: localhost
# Generation Time: Jul 25, 2004 at 11:45 PM
# Server version: 3.23.56
# PHP Version: 4.3.4
# 
# Database : `205test`
# 

#
# Table structure for table `xtorrent_users`
#

CREATE TABLE xtorrent_users (
    `id`           INT(10) UNSIGNED                                    NOT NULL AUTO_INCREMENT,
    `uid`          INT(20)                                                      DEFAULT NULL,
    `lid`          INT(20)                                                      DEFAULT NULL,
    `username`     VARCHAR(40)                                         NOT NULL DEFAULT '',
    `old_password` VARCHAR(40)                                         NOT NULL DEFAULT '',
    `passhash`     VARCHAR(32)                                         NOT NULL DEFAULT '',
    `secret`       VARCHAR(20) CHARACTER SET latin1 COLLATE latin1_bin NOT NULL DEFAULT '',
    `uploaded`     INT(20)                                                      DEFAULT NULL,
    `downloaded`   INT(20)                                                      DEFAULT NULL,
    `enabled`      ENUM ('yes','no')                                            DEFAULT 'yes',
    `last_access`  DATETIME                                                     DEFAULT NULL,
    `passkey`      VARCHAR(128)                                                 DEFAULT NULL,
    PRIMARY KEY (`id`)
)
    ENGINE = ISAM;

#
# Table structure for table `xtorrent_peers`
#


CREATE TABLE xtorrent_peers (
    id             INT(10) UNSIGNED     NOT NULL AUTO_INCREMENT,
    torrent        INT(10) UNSIGNED     NOT NULL DEFAULT '0',
    peer_id        VARCHAR(20) BINARY   NOT NULL DEFAULT '',
    ip             VARCHAR(64)          NOT NULL DEFAULT '',
    port           SMALLINT(5) UNSIGNED NOT NULL DEFAULT '0',
    uploaded       BIGINT(20) UNSIGNED  NOT NULL DEFAULT '0',
    downloaded     BIGINT(20) UNSIGNED  NOT NULL DEFAULT '0',
    to_go          BIGINT(20) UNSIGNED  NOT NULL DEFAULT '0',
    seeder         ENUM ('yes','no')    NOT NULL DEFAULT 'no',
    started        DATETIME             NOT NULL DEFAULT '0000-00-00 00:00:00',
    last_action    DATETIME             NOT NULL DEFAULT '0000-00-00 00:00:00',
    connectable    ENUM ('yes','no')    NOT NULL DEFAULT 'yes',
    userid         INT(10) UNSIGNED     NOT NULL DEFAULT '0',
    agent          VARCHAR(60)          NOT NULL DEFAULT '',
    finishedat     INT(10) UNSIGNED     NOT NULL DEFAULT '0',
    downloadoffset BIGINT(20) UNSIGNED  NOT NULL DEFAULT '0',
    uploadoffset   BIGINT(20) UNSIGNED  NOT NULL DEFAULT '0',
    passkey        VARCHAR(32)          NOT NULL,
    PRIMARY KEY (id),
    UNIQUE KEY torrent_peer_id (torrent, peer_id),
    KEY torrent (torrent),
    KEY torrent_seeder (torrent, seeder),
    KEY last_action (last_action),
    KEY connectable (connectable),
    KEY userid (userid)
)
    ENGINE = ISAM;

#
# Table structure for table `xtorrent_broken`
#

CREATE TABLE xtorrent_broken (
    reportid     INT(5)         NOT NULL AUTO_INCREMENT,
    lid          INT(11)        NOT NULL DEFAULT '0',
    sender       INT(11)        NOT NULL DEFAULT '0',
    ip           VARCHAR(20)    NOT NULL DEFAULT '',
    date         VARCHAR(11)    NOT NULL DEFAULT '0',
    confirmed    ENUM ('0','1') NOT NULL DEFAULT '0',
    acknowledged ENUM ('0','1') NOT NULL DEFAULT '0',
    PRIMARY KEY (reportid),
    KEY lid (lid),
    KEY sender (sender),
    KEY ip (ip)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_broken`
#


# --------------------------------------------------------

#
# Table structure for table `xtorrent_cat`
#

CREATE TABLE xtorrent_cat (
    cid          INT(5) UNSIGNED NOT NULL AUTO_INCREMENT,
    pid          INT(5) UNSIGNED NOT NULL DEFAULT '0',
    title        VARCHAR(50)     NOT NULL DEFAULT '',
    imgurl       VARCHAR(150)    NOT NULL DEFAULT '',
    description  VARCHAR(255)    NOT NULL DEFAULT '',
    total        INT(11)         NOT NULL DEFAULT '0',
    summary      TEXT            NOT NULL,
    spotlighttop INT(11)         NOT NULL DEFAULT '0',
    spotlighthis INT(11)         NOT NULL DEFAULT '0',
    nohtml       INT(1)          NOT NULL DEFAULT '0',
    nosmiley     INT(1)          NOT NULL DEFAULT '0',
    noxcodes     INT(1)          NOT NULL DEFAULT '0',
    noimages     INT(1)          NOT NULL DEFAULT '0',
    nobreak      INT(1)          NOT NULL DEFAULT '1',
    weight       INT(11)         NOT NULL DEFAULT '0',
    PRIMARY KEY (cid),
    KEY pid (pid)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_cat`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_downloads`
#

CREATE TABLE xtorrent_downloads (
    lid           INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    cid           INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    title         VARCHAR(100)     NOT NULL DEFAULT '',
    url           VARCHAR(255)     NOT NULL DEFAULT '',
    homepage      VARCHAR(100)     NOT NULL DEFAULT '',
    version       VARCHAR(20)      NOT NULL DEFAULT '',
    size          INT(8)           NOT NULL DEFAULT '0',
    platform      VARCHAR(50)      NOT NULL DEFAULT '',
    screenshot    VARCHAR(255)     NOT NULL DEFAULT '',
    submitter     INT(11)          NOT NULL DEFAULT '0',
    publisher     VARCHAR(255)     NOT NULL DEFAULT '',
    status        TINYINT(2)       NOT NULL DEFAULT '0',
    date          INT(10)          NOT NULL DEFAULT '0',
    hits          INT(11) UNSIGNED NOT NULL DEFAULT '0',
    rating        DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    votes         INT(11) UNSIGNED NOT NULL DEFAULT '0',
    comments      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    license       VARCHAR(255)     NOT NULL DEFAULT '',
    mirror        VARCHAR(255)     NOT NULL DEFAULT '',
    price         VARCHAR(10)      NOT NULL DEFAULT 'Free',
    paypalemail   VARCHAR(255)     NOT NULL DEFAULT '',
    features      TEXT             NOT NULL,
    requirements  TEXT             NOT NULL,
    homepagetitle VARCHAR(255)     NOT NULL DEFAULT '',
    forumid       INT(11)          NOT NULL DEFAULT '0',
    limitations   VARCHAR(255)     NOT NULL DEFAULT '30 day trial',
    dhistory      TEXT             NOT NULL,
    published     INT(11)          NOT NULL DEFAULT '1089662528',
    expired       INT(10)          NOT NULL DEFAULT '0',
    updated       INT(11)          NOT NULL DEFAULT '0',
    offline       TINYINT(1)       NOT NULL DEFAULT '0',
    description   TEXT             NOT NULL,
    ipaddress     VARCHAR(120)     NOT NULL DEFAULT '0',
    notifypub     INT(1)           NOT NULL DEFAULT '0',
    PRIMARY KEY (lid),
    KEY cid (cid),
    KEY status (status),
    KEY title (title(40))
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_downloads`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_files`
#

CREATE TABLE xtorrent_files (
    lid  INT(11) UNSIGNED NOT NULL DEFAULT '0',
    file VARCHAR(255)     NOT NULL DEFAULT ''
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_files`
#

#
# Table structure for table `xtorrent_torrent`
#

CREATE TABLE `xtorrent_torrent` (
    `lid`        INT(11) UNSIGNED     NOT NULL DEFAULT '0',
    `seeds`      INT(5) UNSIGNED      NOT NULL DEFAULT '0',
    `leechers`   INT(5) UNSIGNED      NOT NULL DEFAULT '0',
    `totalsize`  FLOAT(5, 2) UNSIGNED NOT NULL DEFAULT '0.00',
    `modifiedby` VARCHAR(250)         NOT NULL DEFAULT '',
    `tname`      VARCHAR(255)         NOT NULL DEFAULT '',
    `infoHash`   VARCHAR(128)                  DEFAULT NULL,
    `announce`   VARCHAR(255)                  DEFAULT NULL,
    `md5sum`     VARCHAR(32)                   DEFAULT NULL,
    `added`      INT(12)                       DEFAULT NULL
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_torrent`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_tracker`
#

CREATE TABLE xtorrent_tracker (
    lid      INT(11) UNSIGNED NOT NULL DEFAULT '0',
    seeds    INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    leechers INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    tracker  VARCHAR(250)     NOT NULL DEFAULT ''
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_tracker`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_poll`
#

CREATE TABLE xtorrent_poll (
    lid     INT(11) UNSIGNED NOT NULL DEFAULT '0',
    torrent INT(11) UNSIGNED NOT NULL DEFAULT '0',
    tracker INT(11) UNSIGNED NOT NULL DEFAULT '0'
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_poll`
#

# --------------------------------------------------------


#
# Table structure for table `xtorrent_indexpage`
#

CREATE TABLE xtorrent_indexpage (
    indeximage       VARCHAR(255) NOT NULL DEFAULT 'blank.png',
    indexheading     VARCHAR(255) NOT NULL DEFAULT 'WF-Sections',
    indexheader      TEXT         NOT NULL,
    indexfooter      TEXT         NOT NULL,
    nohtml           TINYINT(8)   NOT NULL DEFAULT '1',
    nosmiley         TINYINT(8)   NOT NULL DEFAULT '1',
    noxcodes         TINYINT(8)   NOT NULL DEFAULT '1',
    noimages         TINYINT(8)   NOT NULL DEFAULT '1',
    nobreak          TINYINT(4)   NOT NULL DEFAULT '0',
    indexheaderalign VARCHAR(25)  NOT NULL DEFAULT 'left',
    indexfooteralign VARCHAR(25)  NOT NULL DEFAULT 'center',
    FULLTEXT KEY indexheading (indexheading),
    FULLTEXT KEY indexheader (indexheader),
    FULLTEXT KEY indexfooter (indexfooter)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_indexpage`
#

INSERT INTO xtorrent_indexpage
VALUES ('logo-en.gif', 'WF-Downloads', '<div><b>Welcome to the x-Torrents Section.</b></div>', 'WF-Downloads', 0, 0, 0, 0, 1, 'left', 'Center');

# --------------------------------------------------------

#
# Table structure for table `xtorrent_mimetypes`
#

CREATE TABLE xtorrent_mimetypes (
    mime_id    INT(11)      NOT NULL AUTO_INCREMENT,
    mime_ext   VARCHAR(60)  NOT NULL DEFAULT '',
    mime_types TEXT         NOT NULL,
    mime_name  VARCHAR(255) NOT NULL DEFAULT '',
    mime_admin INT(1)       NOT NULL DEFAULT '1',
    mime_user  INT(1)       NOT NULL DEFAULT '0',
    KEY mime_id (mime_id)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_mimetypes`
#

INSERT INTO xtorrent_mimetypes
VALUES (1, 'torrent', 'application/x-bittorrent application/octet-stream', 'Binary Torrent File', 1, 1);

# --------------------------------------------------------

#
# Table structure for table `xtorrent_mod`
#

CREATE TABLE xtorrent_mod (
    requestid       INT(11)          NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED NOT NULL DEFAULT '0',
    cid             INT(5) UNSIGNED  NOT NULL DEFAULT '0',
    title           VARCHAR(255)     NOT NULL DEFAULT '',
    url             VARCHAR(255)     NOT NULL DEFAULT '',
    homepage        VARCHAR(255)     NOT NULL DEFAULT '',
    version         VARCHAR(20)      NOT NULL DEFAULT '',
    size            INT(8)           NOT NULL DEFAULT '0',
    platform        VARCHAR(50)      NOT NULL DEFAULT '',
    screenshot      VARCHAR(255)     NOT NULL DEFAULT '',
    submitter       INT(11)          NOT NULL DEFAULT '0',
    publisher       TEXT             NOT NULL,
    status          TINYINT(2)       NOT NULL DEFAULT '0',
    date            INT(10)          NOT NULL DEFAULT '0',
    hits            INT(11) UNSIGNED NOT NULL DEFAULT '0',
    rating          DOUBLE(6, 4)     NOT NULL DEFAULT '0.0000',
    votes           INT(11) UNSIGNED NOT NULL DEFAULT '0',
    comments        INT(11) UNSIGNED NOT NULL DEFAULT '0',
    license         VARCHAR(255)     NOT NULL DEFAULT '',
    mirror          VARCHAR(255)     NOT NULL DEFAULT '',
    price           VARCHAR(10)      NOT NULL DEFAULT 'Free',
    paypalemail     VARCHAR(255)     NOT NULL DEFAULT '',
    features        TEXT             NOT NULL,
    requirements    TEXT             NOT NULL,
    homepagetitle   VARCHAR(255)     NOT NULL DEFAULT '',
    forumid         INT(11)          NOT NULL DEFAULT '0',
    limitations     VARCHAR(255)     NOT NULL DEFAULT '30 day trial',
    dhistory        TEXT             NOT NULL,
    published       INT(10)          NOT NULL DEFAULT '0',
    expired         INT(10)          NOT NULL DEFAULT '0',
    updated         INT(11)          NOT NULL DEFAULT '0',
    offline         TINYINT(1)       NOT NULL DEFAULT '0',
    description     TEXT             NOT NULL,
    modifysubmitter INT(11)          NOT NULL DEFAULT '0',
    requestdate     INT(11)          NOT NULL DEFAULT '0',
    PRIMARY KEY (requestid)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_mod`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_reviews`
#

CREATE TABLE xtorrent_reviews (
    review_id INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    lid       INT(11)          NOT NULL DEFAULT '0',
    title     VARCHAR(255)              DEFAULT NULL,
    review    TEXT,
    submit    INT(11)          NOT NULL DEFAULT '0',
    date      INT(11)          NOT NULL DEFAULT '0',
    uid       INT(10)          NOT NULL DEFAULT '0',
    rated     INT(11)          NOT NULL DEFAULT '0',
    PRIMARY KEY (review_id),
    KEY categoryid (lid)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_reviews`
#

# --------------------------------------------------------

#
# Table structure for table `xtorrent_votedata`
#

CREATE TABLE xtorrent_votedata (
    ratingid        INT(11) UNSIGNED    NOT NULL AUTO_INCREMENT,
    lid             INT(11) UNSIGNED    NOT NULL DEFAULT '0',
    ratinguser      INT(11)             NOT NULL DEFAULT '0',
    rating          TINYINT(3) UNSIGNED NOT NULL DEFAULT '0',
    ratinghostname  VARCHAR(60)         NOT NULL DEFAULT '',
    ratingtimestamp INT(10)             NOT NULL DEFAULT '0',
    PRIMARY KEY (ratingid),
    KEY ratinguser (ratinguser),
    KEY ratinghostname (ratinghostname),
    KEY lid (lid)
)
    ENGINE = ISAM;

#
# Dumping data for table `xtorrent_votedata`
#
