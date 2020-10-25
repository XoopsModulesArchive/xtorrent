<?php
/**
 * $Id: admin.php v 1.22 02 july 2004 Liquid Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

// %%%%%%	Module NMDe 'MyDownloads' (Admin)	  %%%%%
// Buttons
define('_AM_XTORRENT_BMODIFY', 'Modify');
define('_AM_XTORRENT_BDELETE', 'Delete');
define('_AM_XTORRENT_BADD', 'Add');
define('_AM_XTORRENT_BAPPROVE', 'Approve');
define('_AM_XTORRENT_BIGNORE', 'Ignore');
define('_AM_XTORRENT_BCANCEL', 'Cancel');
define('_AM_XTORRENT_BSAVE', 'Save');
define('_AM_XTORRENT_BRESET', 'Reset');
define('_AM_XTORRENT_BMOVE', 'Move Files');
define('_AM_XTORRENT_BUPLOAD', 'Upload');
define('_AM_XTORRENT_BDELETEIMAGE', 'Delete Selected Image');
define('_AM_XTORRENT_BRETURN', 'Return to where you where!');
define('_AM_XTORRENT_DBERROR', 'Database Access Error: Please report this error to the WFSection Website');
//Banned Users
define('_AM_XTORRENT_NONBANNED', 'Not Banned');
define('_AM_XTORRENT_BANNED', 'Banned');
define('_AM_XTORRENT_EDITBANNED', 'Edit Banned Users');
// Other Options
define('_AM_XTORRENT_TEXTOPTIONS', 'Text Options:');
define('_AM_XTORRENT_DISABLEHTML', ' Disable HTML Tags');
define('_AM_XTORRENT_DISABLESMILEY', ' Disable Smilie Icons');
define('_AM_XTORRENT_DISABLEXCODE', ' Disable XOOPS Codes');
define('_AM_XTORRENT_DISABLEIMAGES', ' Disable Images');
define('_AM_XTORRENT_DISABLEBREAK', ' Use XOOPS linebreak conversion?');
define('_AM_XTORRENT_UPLOADFILE', 'File Uploaded Successfully');
define('_AM_XTORRENT_NOMENUITEMS', 'No menu items within the menu');

// Admin Bread crumb
define('_AM_XTORRENT_PREFS', 'Preferences');
define('_AM_XTORRENT_PERMISSIONS', 'Permissions');
define('_AM_XTORRENT_BINDEX', 'Main Index');
define('_AM_XTORRENT_BLOCKADMIN', 'Blocks');
define('_AM_XTORRENT_GOMODULE', 'Go to module');
define('_AM_XTORRENT_BHELP', 'Help');
define('_AM_XTORRENT_ABOUT', 'About');
// Admin Summary
define('_AM_XTORRENT_SCATEGORY', 'Category: ');
define('_AM_XTORRENT_SFILES', 'Files: ');
define('_AM_XTORRENT_SNEWFILESVAL', 'Submitted: ');
define('_AM_XTORRENT_SMODREQUEST', 'Modified: ');
define('_AM_XTORRENT_SREVIEWS', 'Reviews: ');
// Admin Main Menu
define('_AM_XTORRENT_MCATEGORY', 'Category Management');
define('_AM_XTORRENT_MDOWNLOADS', 'File Management');
define('_AM_XTORRENT_INDEXPAGE', 'Index Page Management');
define('_AM_XTORRENT_MUPLOADS', 'Image Upload');
define('_AM_XTORRENT_MMIMETYPES', 'Mimetypes Management');
define('_AM_XTORRENT_MCOMMENTS', 'Comments');
define('_AM_WFS_MVOTEDATA', 'Vote Data');
// waiting reviews
define('_AM_XTORRENT_AREVIEWS', 'Reviews Management');
define('_AM_XTORRENT_AREVIEWS_WAITING', 'Reviews Waiting Validation:');
define('_AM_XTORRENT_AREVIEWS_INFO', 'Reviews Management Information');
define('_AM_XTORRENT_AREVIEWS_APPROVE', '<b>Approve</b> new review without validation.');
define('_AM_XTORRENT_AREVIEWS_EDIT', '<b>Edit</b> new review and then approve.');
define('_AM_XTORRENT_AREVIEWS_DELETE', '<b>Delete</b> the new review information.');

// Catgeory defines
define('_AM_XTORRENT_CCATEGORY_CREATENEW', 'Create New Category');
define('_AM_XTORRENT_CCATEGORY_MODIFY', 'Modify Category');
define('_AM_XTORRENT_CCATEGORY_MOVE', 'Move Category Files');
define('_AM_XTORRENT_CCATEGORY_MODIFY_TITLE', 'Category Title:');
define('_AM_XTORRENT_CCATEGORY_MODIFY_FAILED', 'Failed Moving Files: Cannot move to this Category');
define('_AM_XTORRENT_CCATEGORY_MODIFY_FAILEDT', 'Failed Moving Files: Cannot find this Category');
define('_AM_XTORRENT_CCATEGORY_MODIFY_MOVED', 'Files Moved and Category Deleted');
define('_AM_XTORRENT_CCATEGORY_CREATED', 'New Category Created and Database Updated Successfully');
define('_AM_XTORRENT_CCATEGORY_MODIFIED', 'Selected Category Modified and Database Updated Successfully');
define('_AM_XTORRENT_CCATEGORY_DELETED', 'Selected Category Deleted and Database Updated Successfully');
define('_AM_XTORRENT_CCATEGORY_AREUSURE', 'WARNING: Are you sure you want to delete this Category and ALL its Files and Comments?');
define('_AM_XTORRENT_CCATEGORY_NOEXISTS', 'You must create a Category before you can add a new file');
define('_AM_XTORRENT_FCATEGORY_GROUPPROMPT', "Category Access Permissions:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Select user groups who will have access to this Category.</span></div>");
define('_AM_XTORRENT_FCATEGORY_TITLE', 'Category Title:');
define('_AM_XTORRENT_FCATEGORY_WEIGHT', 'Category Weight:');
define('_AM_XTORRENT_FCATEGORY_SUBCATEGORY', 'Set As Sub-Category:');
define('_AM_XTORRENT_FCATEGORY_CIMAGE', 'Select Category Image:');
define('_AM_XTORRENT_FCATEGORY_DESCRIPTION', 'Set Category Description:');
define('_AM_XTORRENT_FCATEGORY_SUMMARY', 'Set Category Summary:');
/*
* Index page Defines
*/
define('_AM_XTORRENT_IPAGE_UPDATED', 'Index Page Modified and Database Updated Successfully!');
define('_AM_XTORRENT_IPAGE_INFORMATION', 'Index Page Information');
define('_AM_XTORRENT_IPAGE_MODIFY', 'Modify Index Page');
define('_AM_XTORRENT_IPAGE_CIMAGE', 'Select Index Image:');
define('_AM_XTORRENT_IPAGE_CTITLE', 'Index Title:');
define('_AM_XTORRENT_IPAGE_CHEADING', 'Index Heading:');
define('_AM_XTORRENT_IPAGE_CHEADINGA', 'Index Heading Alignment:');
define('_AM_XTORRENT_IPAGE_CFOOTER', 'Index Footer:');
define('_AM_XTORRENT_IPAGE_CFOOTERA', 'Index Footer Alignment:');
define('_AM_XTORRENT_IPAGE_CLEFT', 'Align Left');
define('_AM_XTORRENT_IPAGE_CCENTER', 'Align Center');
define('_AM_XTORRENT_IPAGE_CRIGHT', 'Align Right');
/*
*  Permissions defines
*/
define('_AM_XTORRENT_PERM_MANAGEMENT', 'Permissions Management');
define(
    '_AM_XTORRENT_PERM_PERMSNOTE',
    '<div><b>NOTE:</b> Please be aware that even if you&#8217ve set correct viewing permissions here, a group might not see the articles or blocks if you don&#8217t also grant that group permissions to access the module. To do that, go to <b>System admin > Groups</b>, choose the appropriate group and click the checkboxes to grant its members the access.</div>'
);
define('_AM_XTORRENT_PERM_CPERMISSIONS', 'Category Permissions');
define('_AM_XTORRENT_PERM_CSELECTPERMISSIONS', 'Select categories that each group is allowed to view');
define('_AM_XTORRENT_PERM_CNOCATEGORY', "Cannot set permission's: No Categories's have been created yet!");
define('_AM_XTORRENT_PERM_FPERMISSIONS', 'File Permissions');
define('_AM_XTORRENT_PERM_FNOFILES', "Cannot set permission's: No files have been created yet!");
define('_AM_XTORRENT_PERM_FSELECTPERMISSIONS', 'Select the files that each group is allowed to view');
/*
* Upload defines
*/
define('_AM_XTORRENT_DOWN_IMAGEUPLOAD', 'Image successfully uploaded to server destination');
define('_AM_XTORRENT_DOWN_NOIMAGEEXIST', 'Error: No file was selected for uploading.  Please try again!');
define('_AM_XTORRENT_DOWN_IMAGEEXIST', 'Image already exists in upload area!');
define('_AM_XTORRENT_DOWN_FILEDELETED', 'File has been deleted.');
define('_AM_XTORRENT_DOWN_FILEERRORDELETE', 'Error deleting File: File not found on server.');
define('_AM_XTORRENT_DOWN_NOFILEERROR', 'Error deleting File: No File Selected For Deleting.');
define('_AM_XTORRENT_DOWN_DELETEFILE', 'WARNING: Are you sure you want to delete this Image file?');
define('_AM_XTORRENT_DOWN_IMAGEINFO', 'Server Status');
define('_AM_XTORRENT_DOWN_SPHPINI', '<b>Information taken from PHP ini File:</b>');
define('_AM_XTORRENT_DOWN_SAFEMODESTATUS', 'Safe Mode Status: ');
define('_AM_XTORRENT_DOWN_REGISTERGLOBALS', 'Register Globals: ');
define('_AM_XTORRENT_DOWN_SERVERUPLOADSTATUS', 'Server Uploads Status: ');
define('_AM_XTORRENT_DOWN_MAXUPLOADSIZE', 'Max Upload Size Permitted: ');
define('_AM_XTORRENT_DOWN_MAXPOSTSIZE', 'Max Post Size Permitted: ');
define('_AM_XTORRENT_DOWN_SAFEMODEPROBLEMS', ' (This May Cause Problems)');
define('_AM_XTORRENT_DOWN_GDLIBSTATUS', 'GD Library Support: ');
define('_AM_XTORRENT_DOWN_GDLIBVERSION', 'GD Library Version: ');
define('_AM_XTORRENT_DOWN_GDON', '<b>Enabled</b> (Thumbs Nails Available)');
define('_AM_XTORRENT_DOWN_GDOFF', '<b>Disabled</b> (No Thumb Nails Available)');
define('_AM_XTORRENT_DOWN_OFF', '<b>OFF</b>');
define('_AM_XTORRENT_DOWN_ON', '<b>ON</b>');
define('_AM_XTORRENT_DOWN_CATIMAGE', 'Category Images');
define('_AM_XTORRENT_DOWN_SCREENSHOTS', 'Screenshot Images');
define('_AM_XTORRENT_DOWN_MAINIMAGEDIR', 'Main images');
define('_AM_XTORRENT_DOWN_FCATIMAGE', 'Category Image Path');
define('_AM_XTORRENT_DOWN_FSCREENSHOTS', 'Screenshot Image Path');
define('_AM_XTORRENT_DOWN_FMAINIMAGEDIR', 'Main image Path');
define('_AM_XTORRENT_DOWN_FUPLOADIMAGETO', 'Upload Image: ');
define('_AM_XTORRENT_DOWN_FUPLOADPATH', 'Upload Path: ');
define('_AM_XTORRENT_DOWN_FUPLOADURL', 'Upload URL: ');
define('_AM_XTORRENT_DOWN_FOLDERSELECTION', 'Select Upload Destination:');
define('_AM_XTORRENT_DOWN_FSHOWSELECTEDIMAGE', 'Display Selected Image:');
define('_AM_XTORRENT_DOWN_FUPLOADIMAGE', 'Upload New Image to Selected Destination:');

// Main Index defines
define('_AM_XTORRENT_MINDEX_DOWNSUMMARY', 'Module Admin Summary');
define('_AM_XTORRENT_MINDEX_PUBLISHEDDOWN', 'Published Files:');
define('_AM_XTORRENT_MINDEX_AUTOPUBLISHEDDOWN', 'Auto Published Files:');
define('_AM_XTORRENT_MINDEX_AUTOEXPIRE', 'Auto Expire Files:');
define('_AM_XTORRENT_MINDEX_OFFLINEDOWN', 'Offline Files:');
define('_AM_XTORRENT_MINDEX_ID', 'ID');
define('_AM_XTORRENT_MINDEX_TITLE', 'File Title');
define('_AM_XTORRENT_MINDEX_POSTER', 'Poster');
define('_AM_XTORRENT_MINDEX_SUBMITTED', 'Submission Date');
define('_AM_XTORRENT_MINDEX_ONLINESTATUS', 'Online Status');
define('_AM_XTORRENT_MINDEX_PUBLISHED', 'Published');
define('_AM_XTORRENT_MINDEX_ACTION', 'Action');
define('_AM_XTORRENT_MINDEX_NODOWNLOADSFOUND', 'NOTICE: There are no files that match this criteria');
define('_AM_XTORRENT_MINDEX_PAGE', '<b>Page:<b> ');
define('_AM_XTORRENT_MINDEX_PAGEINFOTXT', '<ul><li>WF-Downloads main page details.</li><li>You can easily change the image logo, heading, main index header and footer text to suit your own look</li></ul><br><br>Note: The Logo image choosen will be used throughout WF-Downloads.');

// Submitted Files
define('_AM_XTORRENT_SUB_SUBMITTEDFILES', 'Submitted Files');
define('_AM_XTORRENT_SUB_FILESWAITINGINFO', 'Waiting Files Information');
define('_AM_XTORRENT_SUB_FILESWAITINGVALIDATION', 'Files Waiting Validation: ');
define('_AM_XTORRENT_SUB_APPROVEWAITINGFILE', '<b>Approve</b> new file information without validation.');
define('_AM_XTORRENT_SUB_EDITWAITINGFILE', '<b>Edit</b> new file information and then approve.');
define('_AM_XTORRENT_SUB_DELETEWAITINGFILE', '<b>Delete</b> the new file information.');
define('_AM_XTORRENT_SUB_NOFILESWAITING', 'There are no files that match this critera');
define('_AM_XTORRENT_SUB_NEWFILECREATED', 'New File Data Created and Database Updated Successfully');
// Mimetypes
define('_AM_XTORRENT_MIME_ID', 'ID');
define('_AM_XTORRENT_MIME_EXT', 'EXT');
define('_AM_XTORRENT_MIME_NAME', 'Application Type');
define('_AM_XTORRENT_MIME_ADMIN', 'Admin');
define('_AM_XTORRENT_MIME_USER', 'User');
// Mimetype Form
define('_AM_XTORRENT_MIME_CREATEF', 'Create Mimetype');
define('_AM_XTORRENT_MIME_MODIFYF', 'Modify Mimetype');
define('_AM_XTORRENT_MIME_EXTF', 'File Extension:');
define('_AM_XTORRENT_MIME_NAMEF', "Application Type/Name:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter application associated with this extension.</span></div>");
define('_AM_XTORRENT_MIME_TYPEF', "Mimetypes:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter each mimetype associated with the file extension. Each mimetype must be seperated with a space.</span></div>");
define('_AM_XTORRENT_MIME_ADMINF', 'Allowed Admin Mimetype');
define('_AM_XTORRENT_MIME_ADMINFINFO', '<b>Mimetypes that are available for Admin uploads:</b>');
define('_AM_XTORRENT_MIME_USERF', 'Allowed User Mimetype');
define('_AM_XTORRENT_MIME_USERFINFO', '<b>Mimetypes that are available for User uploads:</b>');
define('_AM_XTORRENT_MIME_NOMIMEINFO', 'No mimetypes selected.');
define('_AM_XTORRENT_MIME_FINDMIMETYPE', 'Find New Mimetype:');
define('_AM_XTORRENT_MIME_EXTFIND', "Search File Extension:<div style='padding-top: 8px;'><span style='font-weight: normal;'>Enter file extension you wish to search.</span></div>");
define(
    '_AM_XTORRENT_MIME_INFOTEXT',
    '<ul><li>New mimetypes can be created, edit or deleted easily via this form.</li> 
	<li>Search for a new mimetypes via an external website.</li> 
	<li>View displayed mimetypes for Admin and User uploads.</li> 
	<li>Change mimetype upload status.</li></ul> 
	'
);

// Mimetype Buttons
define('_AM_XTORRENT_MIME_CREATE', 'Create');
define('_AM_XTORRENT_MIME_CLEAR', 'Reset');
define('_AM_XTORRENT_MIME_CANCEL', 'Cancel');
define('_AM_XTORRENT_MIME_MODIFY', 'Modify');
define('_AM_XTORRENT_MIME_DELETE', 'Delete');
define('_AM_XTORRENT_MIME_FINDIT', 'Get Extension!');
// Mimetype Database
define('_AM_XTORRENT_MIME_DELETETHIS', 'Delete Selected Mimetype?');
define('_AM_XTORRENT_MIME_MIMEDELETED', 'Mimetype %s has been deleted');
define('_AM_XTORRENT_MIME_CREATED', 'Mimetype Information Created');
define('_AM_XTORRENT_MIME_MODIFIED', 'Mimetype Information Modified');
// Vote Information
define('_AM_XTORRENT_VOTE_RATINGINFOMATION', 'Voting Information');
define('_AM_XTORRENT_VOTE_TOTALVOTES', 'Total votes: ');
define('_AM_XTORRENT_VOTE_REGUSERVOTES', 'Registered User Votes: %s');
define('_AM_XTORRENT_VOTE_ANONUSERVOTES', 'Anonymous User Votes: %s');
define('_AM_XTORRENT_VOTE_USER', 'User');
define('_AM_XTORRENT_VOTE_IP', 'IP Address');
define('_AM_XTORRENT_VOTE_USERAVG', 'Average User Rating');
define('_AM_XTORRENT_VOTE_TOTALRATE', 'Total Ratings');
define('_AM_XTORRENT_VOTE_DATE', 'Submitted');
define('_AM_XTORRENT_VOTE_RATING', 'Rating');
define('_AM_XTORRENT_VOTE_NOREGVOTES', 'No Registered User Votes');
define('_AM_XTORRENT_VOTE_NOUNREGVOTES', 'No Unregistered User Votes');
define('_AM_XTORRENT_VOTE_VOTEDELETED', 'Vote data deleted.');
define('_AM_XTORRENT_VOTE_ID', 'ID');
define('_AM_XTORRENT_VOTE_FILETITLE', 'File Title');
define('_AM_XTORRENT_VOTE_DISPLAYVOTES', 'Voting Data Information');
define('_AM_XTORRENT_VOTE_NOVOTES', 'No User Votes to display');
define('_AM_XTORRENT_VOTE_DELETE', 'No User Votes to display');
define('_AM_XTORRENT_VOTE_DELETEDSC', '<b>Deletes</b> the chosen vote information from the database.');

// Modifications
/*
define("_AM_XTORRENT_MOD_TOTMODREQUESTS", "Total Modification Requests: ");
define("_AM_XTORRENT_MOD_MODREQUESTS", "Modified Files");
define("_AM_XTORRENT_MOD_MODREQUESTSINFO", "Modified Files Information");
define("_AM_XTORRENT_MOD_MODID", "ID");
define("_AM_XTORRENT_MOD_MODTITLE", "Title");
define("_AM_XTORRENT_MOD_MODPOSTER", "Original Poster: ");
define("_AM_XTORRENT_MOD_DATE", "Submitted");
define("_AM_XTORRENT_MOD_NOMODREQUEST", "There are no requests that match this critera");
define("_AM_XTORRENT_MOD_MODIFYSUBMIT", "Submitter");
define("_AM_XTORRENT_MOD_ORIGINAL", "Orginal Download Details");
define("_AM_XTORRENT_MOD_REQDELETED", "Modification request removed from the database");
define("_AM_XTORRENT_MOD_REQUPDATED", "Selected Download Modified and Database Updated Successfully");

*/
define('_AM_XTORRENT_MOD_TOTMODREQUESTS', 'Total Modification Requests: ');
define('_AM_XTORRENT_MOD_MODREQUESTS', 'Modified Files');
define('_AM_XTORRENT_MOD_MODREQUESTSINFO', 'Modified Files Information');
define('_AM_XTORRENT_MOD_MODID', 'ID');
define('_AM_XTORRENT_MOD_MODTITLE', 'Title');
define('_AM_XTORRENT_MOD_MODPOSTER', 'Original Poster: ');
define('_AM_XTORRENT_MOD_DATE', 'Submitted');
define('_AM_XTORRENT_MOD_NOMODREQUEST', 'There are no requests that match this critera');
define('_AM_XTORRENT_MOD_TITLE', 'Download Title: ');
define('_AM_XTORRENT_MOD_LID', 'Download ID: ');
define('_AM_XTORRENT_MOD_CID', 'Category: ');
define('_AM_XTORRENT_MOD_URL', 'Download Url: ');
define('_AM_XTORRENT_MOD_MIRROR', 'Download Mirror: ');
define('_AM_XTORRENT_MOD_SIZE', 'Download Size: ');
define('_AM_XTORRENT_MOD_PUBLISHER', 'Publisher: ');
define('_AM_XTORRENT_MOD_LICENSE', 'Software Licence: ');
define('_AM_XTORRENT_MOD_FEATURES', 'Key Features: ');
define('_AM_XTORRENT_MOD_FORUMID', 'Forum: ');
define('_AM_XTORRENT_MOD_LIMITATIONS', 'Software limitations: ');
define('_AM_XTORRENT_MOD_DHISTORY', 'Download History: ');
define('_AM_XTORRENT_MOD_SCREENSHOT', 'Screenshot Image: ');
define('_AM_XTORRENT_MOD_HOMEPAGE', 'Home Page: ');
define('_AM_XTORRENT_MOD_HOMEPAGETITLE', 'Home Page Title: ');
define('_AM_XTORRENT_MOD_VERSION', 'Version: ');
define('_AM_XTORRENT_MOD_SHOTIMAGE', 'Screenshot Image: ');
define('_AM_XTORRENT_MOD_FILESIZE', 'File Size: ');
define('_AM_XTORRENT_MOD_PLATFORM', 'Software Platform: ');
define('_AM_XTORRENT_MOD_PRICE', 'Price: ');
define('_AM_XTORRENT_MOD_LICENCE', 'Software Licence: ');
define('_AM_XTORRENT_MOD_DESCRIPTION', 'Software limitations: ');
define('_AM_XTORRENT_MOD_REQUIREMENTS', 'Requirements: ');
define('_AM_XTORRENT_MOD_MODIFYSUBMITTER', 'Submitter: ');
define('_AM_XTORRENT_MOD_MODIFYSUBMIT', 'Submitter');
define('_AM_XTORRENT_MOD_PROPOSED', 'Proposed Download Details');
define('_AM_XTORRENT_MOD_ORIGINAL', 'Orginal Download Details');
define('_AM_XTORRENT_MOD_REQDELETED', 'Modification request removed from the database');
define('_AM_XTORRENT_MOD_REQUPDATED', 'Selected Download Modified and Database Updated Successfully');
define('_AM_XTORRENT_MOD_VIEW', 'View');

//Reviews defines
define('_AM_XTORRENT_REV_SNEWMNAMEDESC', 'Approve Review: ');
define('_AM_XTORRENT_REV_ID', 'ID');
define('_AM_XTORRENT_REV_TITLE', 'Title');
define('_AM_XTORRENT_REV_POSTER', 'Submitter');
define('_AM_XTORRENT_REV_SUBMITDATE', 'Submitted');
define('_AM_XTORRENT_REV_FTITLE', 'Review Title: ');
define('_AM_XTORRENT_REV_FRATING', 'Review Rating: ');
define('_AM_XTORRENT_REV_FDESCRIPTION', 'Review Description: ');
define('_AM_XTORRENT_REV_FAPPROVE', 'Review Approve: ');
define('_AM_XTORRENT_REV_ACTION', 'Action');
define('_AM_XTORRENT_REV_NOWAITINGREVIEWS', 'No Waiting Reviews Found');
define('_AM_XTORRENT_REV_REVIEW_UPDATED', 'Selected Review Modified and Database Updated Successfully');

//File management
define('_AM_XTORRENT_FILE_ID', 'File ID: ');
define('_AM_XTORRENT_FILE_IP', 'Uploaders IP Address: ');
define('_AM_XTORRENT_FILE_ALLOWEDAMIME', "<div style='padding-top: 4px; padding-bottom: 4px;'><b>Allowed Admin File Extensions</b>:</div>");
define('_AM_XTORRENT_FILE_MODIFYFILE', 'Modify File Information');
define('_AM_XTORRENT_FILE_CREATENEWFILE', 'Create New File');
define('_AM_XTORRENT_FILE_TITLE', 'File Title: ');
define('_AM_XTORRENT_FILE_DLURL', 'File URL: ');
define('_AM_XTORRENT_FILE_MIRRORURL', 'File Mirror: ');
define('_AM_XTORRENT_FILE_DESCRIPTION', 'File Description: ');
define('_AM_XTORRENT_FILE_DUPLOAD', ' Upload File:');
define('_AM_XTORRENT_FILE_CATEGORY', 'Select Category: ');
define('_AM_XTORRENT_FILE_HOMEPAGETITLE', 'Home Page Title: ');
define('_AM_XTORRENT_FILE_HOMEPAGE', 'Home Page: ');
define('_AM_XTORRENT_FILE_SIZE', 'File Size: ');
define('_AM_XTORRENT_FILE_VERSION', 'File Version: ');
define('_AM_XTORRENT_FILE_PUBLISHER', 'File Publisher: ');
define('_AM_XTORRENT_FILE_PLATFORM', 'Software Platform: ');
define('_AM_XTORRENT_FILE_LICENCE', 'Software Licence: ');
define('_AM_XTORRENT_FILE_LIMITATIONS', 'Software limitations: ');
define('_AM_XTORRENT_FILE_PRICE', 'Price: ');
define('_AM_XTORRENT_FILE_KEYFEATURES', "Key Features:<br><br><span style='font-weight: normal;'>Seperate each Key Feature with a |</span>");
define('_AM_XTORRENT_FILE_REQUIREMENTS', "System Requirements:<br><br><span style='font-weight: normal;'>Seperate each Requirement with |</span>");
define('_AM_XTORRENT_FILE_HISTORY', "Download History Edit:<br><br><span style='font-weight: normal;'>Add New Download History and only use this field to if you need to edit the previous history.</span>");
define('_AM_XTORRENT_FILE_HISTORYD', "Add New Download History:<br><br><span style='font-weight: normal;'>The version Number and date will be added automatically</span>");
define('_AM_XTORRENT_FILE_HISTORYVERS', '<b>Version: </b>');
define('_AM_XTORRENT_FILE_HISTORDATE', ' <b>Updated:</b> ');
define('_AM_XTORRENT_FILE_FILESSTATUS', " Set Download offline?<br><br><span style='font-weight: normal;'>Download will not be viewable to all users.</span>");
define('_AM_XTORRENT_FILE_SETASUPDATED', " Set Download Status as Updated?<br><br><span style='font-weight: normal;'>Download will Display updated icon.</span>");
define('_AM_XTORRENT_FILE_SHOTIMAGE', 'Select Screenshot Image: ');
define('_AM_XTORRENT_FILE_DISCUSSINFORUM', 'Add Discuss in this Forum?');
define('_AM_XTORRENT_FILE_PUBLISHDATE', 'File Publish Date:');
define('_AM_XTORRENT_FILE_EXPIREDATE', 'File Expire Date:');
define('_AM_XTORRENT_FILE_CLEARPUBLISHDATE', '<br><br>Remove Publish date:');
define('_AM_XTORRENT_FILE_CLEAREXPIREDATE', '<br><br>Remove Expire date:');
define('_AM_XTORRENT_FILE_PUBLISHDATESET', ' Publish date set: ');
define('_AM_XTORRENT_FILE_SETDATETIMEPUBLISH', ' Set the date/time of publish');
define('_AM_XTORRENT_FILE_SETDATETIMEEXPIRE', ' Set the date/time of expire');
define('_AM_XTORRENT_FILE_SETPUBLISHDATE', '<b>Set Publish Date: </b>');
define('_AM_XTORRENT_FILE_SETNEWPUBLISHDATE', '<b>Set New Publish Date: </b><br>Published:');
define('_AM_XTORRENT_FILE_SETPUBDATESETS', '<b>Publish Date Set: </b><br>Publishes On Date:');
define('_AM_XTORRENT_FILE_EXPIREDATESET', ' Expire date set: ');
define('_AM_XTORRENT_FILE_SETEXPIREDATE', '<b>Set Expire Date: </b>');
define('_AM_XTORRENT_FILE_MUSTBEVALID', 'Screenshot image must be a valid image file under %s directory (ex. shot.gif). Leave it blank if there is no image file.');
define('_AM_XTORRENT_FILE_EDITAPPROVE', 'Approve download:');
define('_AM_XTORRENT_FILE_NEWFILEUPLOAD', 'New File Created and Database Updated Successfully');
define('_AM_XTORRENT_FILE_FILEMODIFIEDUPDATE', 'Selected File Modified and Database Updated Successfully');
define('_AM_XTORRENT_FILE_REALLYDELETEDTHIS', 'Really delete the selected file?');
define('_AM_XTORRENT_FILE_FILEWASDELETED', 'File %s successfully removed from the database!');
define('_AM_XTORRENT_FILE_USE_UPLOAD_TITLE', ' Use upload filename for file title.');
define('_AM_XTORRENT_FILE_FILEAPPROVED', 'File Approved and Database Updated Successfully');
define('_AM_XTORRENT_FILE_CREATENEWSSTORY', '<b>Create News Story From Download</b>');
define('_AM_XTORRENT_FILE_SUBMITNEWS', 'Submit New file as News item?');
define('_AM_XTORRENT_FILE_NEWSCATEGORY', 'Select News Category to submit News:');
define('_AM_XTORRENT_FILE_NEWSTITLE', "News Title:<div style='padding-top: 4px; padding-bottom: 4px;'><span style='font-weight: normal;'>Leave Blank to use File Title</span></div>");

/*
* Broken downloads defines
*/
define('_AM_XTORRENT_SBROKENSUBMIT', 'Broken: ');
define('_AM_XTORRENT_BROKEN_FILE', 'Broken Reports');
define('_AM_XTORRENT_BROKEN_FILEIGNORED', 'Broken report ignored and successfully removed from the database!');
define('_AM_XTORRENT_BROKEN_NOWACK', 'Acknowledged status changed and database updated!');
define('_AM_XTORRENT_BROKEN_NOWCON', 'Confirmed status changed and database updated!');
define('_AM_XTORRENT_BROKEN_REPORTINFO', 'Broken Report Information');
define('_AM_XTORRENT_BROKEN_REPORTSNO', 'Broken Reports Waiting:');
define('_AM_XTORRENT_BROKEN_IGNOREDESC', '<b>Ignores</b> the report and only deletes the broken file report.');
define('_AM_XTORRENT_BROKEN_DELETEDESC', '<b>Deletes</b> the reported download data and broken file reports for the file.');
define('_AM_XTORRENT_BROKEN_EDITDESC', '<b>Edit</b> download to correct the problem.');
define('_AM_XTORRENT_BROKEN_ACKDESC', '<b>Acknowledged</b> Set Acknowledged state of broken file report.');
define('_AM_XTORRENT_BROKEN_CONFIRMDESC', '<b>Confirmed</b> Set confirmed state of broken file report.');

define('_AM_XTORRENT_BROKEN_ID', 'ID');
define('_AM_XTORRENT_BROKEN_TITLE', 'Title');
define('_AM_XTORRENT_BROKEN_REPORTER', 'Reporter');
define('_AM_XTORRENT_BROKEN_FILESUBMITTER', 'Submitter');
define('_AM_XTORRENT_BROKEN_DATESUBMITTED', 'Submit Date');
define('_AM_XTORRENT_BROKEN_ACTION', 'Action');
define('_AM_XTORRENT_BROKEN_NOFILEMATCH', 'There are no Broken reports that match this critera');
define('_AM_XTORRENT_BROKENFILEDELETED', 'Download description removed from database and broken report removed');

/*
* About defines
*/
define('_AM_XTORRENT_BY', 'by');

//block defines
define('_AM_XTORRENT_BADMIN', 'Block Administration');
define('_AM_XTORRENT_BLKDESC', 'Description');
define('_AM_XTORRENT_TITLE', 'Title');
define('_AM_XTORRENT_SIDE', 'Alignment');
define('_AM_XTORRENT_WEIGHT', 'Weight');
define('_AM_XTORRENT_VISIBLE', 'Visible');
define('_AM_XTORRENT_ACTION', 'Action');
define('_AM_XTORRENT_SBLEFT', 'Left');
define('_AM_XTORRENT_SBRIGHT', 'Right');
define('_AM_XTORRENT_CBLEFT', 'Center Left');
define('_AM_XTORRENT_CBRIGHT', 'Center Right');
define('_AM_XTORRENT_CBCENTER', 'Center Middle');
define('_AM_XTORRENT_ACTIVERIGHTS', 'Active Rights');
define('_AM_XTORRENT_ACCESSRIGHTS', 'Access Rights');

//image admin icon
define('_AM_XTORRENT_ICO_EDIT', 'Edit This Item');
define('_AM_XTORRENT_ICO_DELETE', 'Delete This Item');
define('_AM_XTORRENT_ICO_ONLINE', 'Online');
define('_AM_XTORRENT_ICO_OFFLINE', 'Offline');
define('_AM_XTORRENT_ICO_APPROVED', 'Approved');
define('_AM_XTORRENT_ICO_NOTAPPROVED', 'Not Approved');

define('_AM_XTORRENT_ICO_LINK', 'Related Link');
define('_AM_XTORRENT_ICO_URL', 'Add Related URL');
define('_AM_XTORRENT_ICO_ADD', 'Add');
define('_AM_XTORRENT_ICO_APPROVE', 'Approve');
define('_AM_XTORRENT_ICO_STATS', 'Stats');

define('_AM_XTORRENT_ICO_IGNORE', 'Ignore');
define('_AM_XTORRENT_ICO_ACK', 'Broken Report Acknowledged');
define('_AM_XTORRENT_ICO_REPORT', 'Acknowledge Broken Report?');
define('_AM_XTORRENT_ICO_CONFIRM', 'Broken Report Confirmed');
define('_AM_XTORRENT_ICO_CONBROKEN', 'Confirm Broken Report?');
