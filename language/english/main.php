<?php
/**
 * $Id: main.php v 1.22 02 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

//Todo - Still to remove redundat defines from this area.
define('_MD_XTORRENT_NODOWNLOAD', 'This download does not exist!');

define('_MD_XTORRENT_SEEDS', 'Seeds');
define('_MD_XTORRENT_LEECHES', 'Leeches');
define('_MD_XTORRENT_TTLSEEDS', 'Total Seeds');
define('_MD_XTORRENT_TTLLEECHES', 'Total Leeches');
define('_MD_XTORRENT_TOTALSIZE', 'Torrent Size');
define('_MD_XTORRENT_TNAME', 'Name');
define('_MD_XTORRENT_POLLED', 'Torrent Last Polled');
define('_MD_XTORRENT_TRACKERPOLLED', 'Tracker Last Polled');

define('_MD_XTORRENT_SUBCATLISTING', 'Category Listing');
define('_MD_XTORRENT_ISADMINNOTICE', 'Webmaster: There is a problem with this image.');
define('_MD_XTORRENT_THANKSFORINFO', 'Thank-you for your submission. You will be notified once your request has be approved by the webmaster.');
define('_MD_XTORRENT_ISAPPROVED', 'Thank-you for your submission. Your request has been approved and will now appear in our listing.');
define('_MD_XTORRENT_THANKSFORHELP', "Thank-you for helping to maintain this directory's integrity.");
define('_MD_XTORRENT_FORSECURITY', 'For security reasons your user name and IP address will also be temporarily recorded.');
define(
    '_MD_XTORRENT_NOPERMISETOLINK',
    "This file doesn't belong to the site you came from <br><br>Please e-mail the webmaster of the site you came from and tell him:   <br><b>NOT TO LEECH OTHER SITES LINKS!!</b> <br><br><b>Definition of a Leecher:</b> One who is to lazy to link from his own server or steals other peoples hard work and makes it look like his own <br><br>  Your IP address <b>has been logged</b>."
);
define('_MD_XTORRENT_DESCRIPTION', 'Description');
define('_MD_XTORRENT_SUBMITCATHEAD', 'Submit torrent Form');
define('_MD_XTORRENT_MAIN', 'HOME');
define('_MD_XTORRENT_POPULAR', 'Popular');
define('_MD_XTORRENT_NEWTHISWEEK', 'New this week');
define('_MD_XTORRENT_UPTHISWEEK', 'Updated this week');
define('_MD_XTORRENT_POPULARITYLTOM', 'Popularity (Least to Most Hits)');
define('_MD_XTORRENT_POPULARITYMTOL', 'Popularity (Most to Least Hits)');
define('_MD_XTORRENT_TITLEATOZ', 'Title (A to Z)');
define('_MD_XTORRENT_TITLEZTOA', 'Title (Z to A)');
define('_MD_XTORRENT_DATEOLD', 'Date (Old Files Listed First)');
define('_MD_XTORRENT_DATENEW', 'Date (New Files Listed First)');
define('_MD_XTORRENT_RATINGLTOH', 'Rating (Lowest Score to Highest Score)');
define('_MD_XTORRENT_RATINGHTOL', 'Rating (Highest Score to Lowest Score)');
define('_MD_XTORRENT_DESCRIPTIONC', 'Description: ');
define('_MD_XTORRENT_CATEGORYC', 'Category: ');
define('_MD_XTORRENT_VERSION', 'Version');
define('_MD_XTORRENT_SUBMITDATE', 'Released');
define('_MD_XTORRENT_DLTIMES', 'Downloaded %s times');
define('_MD_XTORRENT_FILESIZE', 'File Size');
define('_MD_XTORRENT_SUPPORTEDPLAT', 'Platform');
define('_MD_XTORRENT_HOMEPAGE', 'Home Page');
define('_MD_XTORRENT_PUBLISHERC', 'Publisher: ');
define('_MD_XTORRENT_RATINGC', 'Rating: ');
define('_MD_XTORRENT_ONEVOTE', '1 Vote');
define('_MD_XTORRENT_NUMVOTES', '%s Votes');
define('_MD_XTORRENT_RATETHISFILE', 'Rate Resource');
define('_MD_XTORRENT_REVIEWTHISFILE', 'Review Resource');
define('_MD_XTORRENT_REVIEWS', 'Reviews:');
define('_MD_XTORRENT_DOWNLOADHITS', 'Downloads');
define('_MD_XTORRENT_MODIFY', 'Modify');
define('_MD_XTORRENT_REPORTBROKEN', 'Report Broken');
define('_MD_XTORRENT_BROKENREPORT', 'Report Broken Resource');
define('_MD_XTORRENT_SUBMITBROKEN', 'Submit');
define('_MD_XTORRENT_BEFORESUBMIT', 'Before submitting a broken resource request, please check that the actual source of the file you intend reporting broken, is no longer there and that the website is not temporally down.');
define('_MD_XTORRENT_TELLAFRIEND', 'Recommend');
define('_MD_XTORRENT_EDIT', 'Edit');
define('_MD_XTORRENT_THEREARE', 'There are <b>%s</b> <i>Categories</i> and <b>%s</b> <i>Downloads</i> listed');
define('_MD_XTORRENT_THEREIS', 'There is <b>%s</b> <i>Category</i> and <b>%s</b> <i>Downloads</i> listed');
define('_MD_XTORRENT_LATESTLIST', 'Latest Listings');
define('_MD_XTORRENT_FILETITLE', 'Download Title: ');
define('_MD_XTORRENT_DLURL', 'Download URL: ');
define('_MD_XTORRENT_HOMEPAGEC', 'Home Page: ');
define('_MD_XTORRENT_UPLOAD_FILEC', 'Upload File: ');
define('_MD_XTORRENT_VERSIONC', 'Version: ');
define('_MD_XTORRENT_FILESIZEC', 'File Size: ');
define('_MD_XTORRENT_NUMBYTES', '%s bytes');
define('_MD_XTORRENT_PLATFORMC', 'Platform: ');
define('_MD_XTORRENT_PRICE', 'Price');
define('_MD_XTORRENT_LIMITS', 'Limitations');
define('_MD_XTORRENT_DOWNLICENSE', 'License');
define('_MD_XTORRENT_NOTSPECIFIED', 'Not Specified');
define('_MD_XTORRENT_MIRRORSITE', 'Download Mirror');
define('_MD_XTORRENT_MIRROR', 'Mirror Website');
define('_MD_XTORRENT_PUBLISHER', 'Publisher');
define('_MD_XTORRENT_UPDATEDON', 'Updated On');
define('_MD_XTORRENT_PRICEFREE', 'Free');
define('_MD_XTORRENT_VIEWDETAILS', 'View Full Details');
define('_MD_XTORRENT_OPTIONS', 'Options: ');
define('_MD_XTORRENT_NOTIFYAPPROVE', 'Notify me when this file is approved');
define('_MD_XTORRENT_VOTEAPPRE', 'Your vote is appreciated.');
define('_MD_XTORRENT_THANKYOU', 'Thank you for taking the time to vote here at %s'); // %s is your site name
define('_MD_XTORRENT_VOTEONCE', 'Please do not vote for the same resource more than once.');
define('_MD_XTORRENT_RATINGSCALE', 'The scale is 1 - 10, with 1 being poor and 10 being excellent.');
define('_MD_XTORRENT_BEOBJECTIVE', "Please be objective, if everyone receives a 1 or a 10, the ratings aren't very useful.");
define('_MD_XTORRENT_DONOTVOTE', 'Do not vote for your own resource.');
define('_MD_XTORRENT_RATEIT', 'Rate It!');
define('_MD_XTORRENT_INTFILEFOUND', 'Here is a good file to download at %s'); // %s is your site name
define('_MD_XTORRENT_RANK', 'Rank');
define('_MD_XTORRENT_CATEGORY', 'Category');
define('_MD_XTORRENT_HITS', 'Hits');
define('_MD_XTORRENT_RATING', 'Rating');
define('_MD_XTORRENT_VOTE', 'Vote');
define('_MD_XTORRENT_SORTBY', 'Sort by:');
define('_MD_XTORRENT_TITLE', 'Title');
define('_MD_XTORRENT_DATE', 'Date');
define('_MD_XTORRENT_POPULARITY', 'Popularity');
define('_MD_XTORRENT_TOPRATED', 'Rating');
define('_MD_XTORRENT_CURSORTBY', 'Files currently sorted by: %s');
define('_MD_XTORRENT_CANCEL', 'Cancel');
define('_MD_XTORRENT_ALREADYREPORTED', 'You have already submitted a broken report for this resource.');
define('_MD_XTORRENT_MUSTREGFIRST', "Sorry, you don't have the permission to perform this action.<br>Please register or login first!");
define('_MD_XTORRENT_NORATING', 'No rating selected.');
define('_MD_XTORRENT_CANTVOTEOWN', 'You cannot vote on the resource you submitted.<br>All votes are logged and reviewed.');
define('_MD_XTORRENT_SUBMITDOWNLOAD', 'Submit Torrent');
define('_MD_XTORRENT_SUB_SNEWMNAMEDESC', "<ul><li>All new Downloads's are subject to validation and may take up to 24 hours before they appear in our listing.</li><li>We reserve the rights to refuse any submitted download or change the content without approval.</li></ul>");
define('_MD_XTORRENT_MAINLISTING', 'Main Category Listings');
define('_MD_XTORRENT_LASTWEEK', 'Last Week');
define('_MD_XTORRENT_LAST30DAYS', 'Last 30 Days');
define('_MD_XTORRENT_1WEEK', '1 Week');
define('_MD_XTORRENT_2WEEKS', '2 Weeks');
define('_MD_XTORRENT_30DAYS', '30 Days');
define('_MD_XTORRENT_SHOW', 'Show');
define('_MD_XTORRENT_DAYS', 'days');
define('_MD_XTORRENT_NEWDOWNLOADS', 'New Downloads');
define('_MD_XTORRENT_TOTALNEWDOWNLOADS', 'Total New Downloads');
define('_MD_XTORRENT_DTOTALFORLAST', 'Total new downloads for last');
define('_MD_XTORRENT_AGREE', 'I Agree');
define('_MD_XTORRENT_DOYOUAGREE', 'Do you agree to the above terms?');
define('_MD_XTORRENT_DISCLAIMERAGREEMENT', 'Disclaimer');
define('_MD_XTORRENT_DUPLOADSCRSHOT', 'Upload Screenshot Image:');
define('_MD_XTORRENT_RESOURCEID', 'Resource id#: ');
define('_MD_XTORRENT_REPORTER', 'Original Reporter: ');
define('_MD_XTORRENT_DATEREPORTED', 'Date Reported: ');
define('_MD_XTORRENT_RESOURCEREPORTED', 'Resource Reported Broken');
define('_MD_XTORRENT_BROWSETOTOPIC', '<b>Browse Downloads by alphabetical listing</b>');
define('_MD_XTORRENT_WEBMASTERACKNOW', 'Broken Report Acknowledged: ');
define('_MD_XTORRENT_WEBMASTERCONFIRM', 'Broken Report Confirmed: ');
define('_MD_XTORRENT_DELETE', 'Delete');
define('_MD_XTORRENT_DISPLAYING', 'Displayed by: ');
define('_MD_XTORRENT_LEGENDTEXTNEW', 'New Today');
define('_MD_XTORRENT_LEGENDTEXTNEWTHREE', 'New 3 Days');
define('_MD_XTORRENT_LEGENDTEXTTHISWEEK', 'New This Week');
define('_MD_XTORRENT_LEGENDTEXTNEWLAST', 'Over 1 Week');
define('_MD_XTORRENT_THISFILEDOESNOTEXIST', 'Error: This file does not exist!');
define('_MD_XTORRENT_BROKENREPORTED', 'Broken File Reported');
// visit
define('_MD_XTORRENT_DOWNINPROGRESS', 'Download in Progress');
define('_MD_XTORRENT_DOWNSTARTINSEC', 'Your download should start in 3 seconds...<b>please wait</b>.');
define('_MD_XTORRENT_DOWNNOTSTART', 'If your download does not start, ');
define('_MD_XTORRENT_CLICKHERE', 'Click here!');
define('_MD_XTORRENT_BROKENFILE', 'Broken File');
define('_MD_XTORRENT_PLEASEREPORT', 'Please report this broken file to the webmaster, ');
// Reviews
define('_MD_XTORRENT_REV_TITLE', 'Review Title:');
define('_MD_XTORRENT_REV_RATING', 'Review Rating:');
define('_MD_XTORRENT_REV_DESCRIPTION', 'Review:');
define('_MD_XTORRENT_REV_SUBMITREV', 'Submit Review');
define(
    '_MD_XTORRENT_REV_SNEWMNAMEDESC',
    " 
Please completely fill out the form below, and we'll add your review as soon as possible.<br><br>
Thank you for taking the time to submit your opinion. We want to give our users a possibility to find quality software faster.<br><br>All reviews will be reviewed by one of our webmasters before they are put up on the web site. 
"
);
define('_MD_XTORRENT_ISNOTAPPROVED', 'Your submission has to be approved by a moderator first.');
define('_MD_XTORRENT_LICENCEC', 'Software Licence: ');
define('_MD_XTORRENT_LIMITATIONS', 'Software limitations: ');
define('_MD_XTORRENT_KEYFEATURESC', "Key Features:<br><br><span style='font-weight: normal;'>Seperate each Key Feature with a |</span>");
define('_MD_XTORRENT_REQUIREMENTSC', "System Requirements:<br><br><span style='font-weight: normal;'>Seperate each Requirement with |</span>");
define('_MD_XTORRENT_HISTORYC', 'Download History:');
define('_MD_XTORRENT_HISTORYD', "Add New Download History:<br><br><span style='font-weight: normal;'>The Submit date will automatically be added to this.</span>");
define('_MD_XTORRENT_HOMEPAGETITLEC', 'Home Page Title: ');
define('_MD_XTORRENT_REQUIREMENTS', 'System Requirements:');
define('_MD_XTORRENT_FEATURES', 'Features:');
define('_MD_XTORRENT_HISTORY', 'Download History:');
define('_MD_XTORRENT_PRICEC', 'Price:');
define('_MD_XTORRENT_SCREENSHOT', 'Screenshot:');
define('_MD_XTORRENT_SCREENSHOTCLICK', 'Display full image');
define('_MD_XTORRENT_OTHERBYUID', 'Other files by: ');
define('_MD_XTORRENT_DOWNTIMES', 'Download Times: ');
define('_MD_XTORRENT_MAINTOTAL', 'Total Files: ');
define('_MD_XTORRENT_DOWNLOADNOW', 'Download Now');
define('_MD_XTORRENT_PAGES', '<b>Pages</b>');
define('_MD_XTORRENT_REVIEWER', 'Reviewer');
define('_MD_XTORRENT_RATEDRESOURCE', 'Rated Resource');
define('_MD_XTORRENT_SUBMITTER', 'Submitter');
define('_MD_XTORRENT_REVIEWTITLE', 'User Reviews');
define('_MD_XTORRENT_REVIEWTOTAL', '<b>Reviews total:</b> %s');
define('_MD_XTORRENT_USERREVIEWSTITLE', 'User Reviews');
define('_MD_XTORRENT_USERREVIEWS', 'Read User Reviews on %s');
define('_MD_XTORRENT_NOUSERREVIEWS', 'Be the first person to review %s.');
define('_MD_XTORRENT_ERROR', 'Error Updating Database: Information not saved');
define('_MD_XTORRENT_COPYRIGHT', 'copyright');
define('_MD_XTORRENT_INFORUM', 'Discuss In Forum');

//added frankblack

//submit.php
define('_MD_XTORRENT_NOTALLOWESTOSUBMIT', 'You are not allowed to submit files');
define('_MD_XTORRENT_INFONOSAVEDB', 'Information not saved to database: <br><br>');

//review.php
define('_MD_XTORRENT_ERROR_CREATCHANNEL', 'Create Channel first');

define('_MD_XTORRENT_NEWLAST', 'New Submitted Before Last Week');
define('_MD_XTORRENT_NEWTHIS', 'New Submitted Within This week');
define('_MD_XTORRENT_THREE', 'New Submitted Within Last Three days');
define('_MD_XTORRENT_TODAY', 'New Submitted Today');
define('_MD_XTORRENT_NO_FILES', 'No Files Yet');
