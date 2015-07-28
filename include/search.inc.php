<?php
/**
 * Search function for the library module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function library_search($queryarray, $andor, $limit, $offset, $userid) {
	$library_publication_handler = icms_getModuleHandler('publication',
		basename(dirname(dirname(__FILE__))), 'library');
	$publicationArray = $library_publication_handler->getPublicationsForSearch($queryarray, $andor,
		$limit, $offset, $userid);

	$ret = array();

	foreach ($publicationArray as $publication) {
		$item['image'] = "images/stream.png";
		$item['link'] = str_replace(LIBRARY_URL, '', $publication['itemUrl']);
		$item['title'] = $publication['title'];
		$item['time'] = strtotime($publication['submission_time']);
		$item['uid'] = $publication['submitter'];
		$ret[] = $item;
		unset($item);
	}
	return $ret;
}