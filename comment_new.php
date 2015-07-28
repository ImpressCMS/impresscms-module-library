<?php
/**
 * New comment form
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

include_once 'header.php';
$com_itemid = isset($_GET['com_itemid']) ? intval($_GET['com_itemid']) : 0;
$com_replytext = $com_replyTitle = '';
if ($com_itemid > 0) {
	$library_publication_handler = icms_getModuleHandler('publication',
		basename(dirname(__FILE__)), 'library');
	$publicationObj = $library_publication_handler->get($com_itemid);
	if ($publicationObj && !$publicationObj->isNew()) {
		$bodytext = $publicationObj->getVar('description');
		if ($bodytext != '') {
			$com_replytext .= $bodytext;
		}
		$com_replytitle = $publicationObj->getVar('title');
		include_once ICMS_ROOT_PATH .'/include/comment_new.php';
	}
}