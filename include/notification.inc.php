<?php
/* $Id: notification.inc.php,v 1.3 july 2004 Catwolf Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 *//

function xtorrent_notify_iteminfo($category, $item_id)
{
	global $xoopsModule, $xoopsModuleConfig, $xoopsConfig;

	if (empty($xoopsModule) || $xoopsModule->getVar('dirname') != 'xtorrent') {	
		$moduleHandler = xoops_getHandler('module');
		$module = $moduleHandler->getByDirname('xtorrent');
		$configHandler = xoops_getHandler('config');
		$config = $configHandler->getConfigsByCat(0,$module->getVar('mid'));
	} else {
		$module =& $xoopsModule;
		$config = $xoopsModuleConfig;
	}

	if ($category=='global') {
		$item['name'] = '';
		$item['url'] = '';
		return $item;
	}

	global $xoopsDB;
	if ($category=='category') {
		// Assume we have a valid category id
		$sql = 'SELECT title FROM ' . $xoopsDB->prefix('xtorrent_cat') . ' WHERE cid = '.$item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];
		$item['url'] = XOOPS_URL . '/modules/xtorrent/viewcat.php?cid=' . $item_id;
		return $item;
	}

	if ($category=='file') {
		// Assume we have a valid file id
		$sql = 'SELECT cid,title FROM '.$xoopsDB->prefix('xtorrent_downloads') . ' WHERE lid = ' . $item_id;
		$result = $xoopsDB->query($sql); // TODO: error check
		$result_array = $xoopsDB->fetchArray($result);
		$item['name'] = $result_array['title'];
		$item['url'] = XOOPS_URL . '/modules/xtorrent/singlefile.php?cid=' . $result_array['cid'] . '&amp;lid=' . $item_id;
		return $item;
	}
}

