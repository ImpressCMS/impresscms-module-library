<?php
/**
 * Displays details about the Open Archive functionality of the module and how to access the
 * OAIPMH target, including the base URL
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2010
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		archive
 * @version		$Id$
 */

include_once 'header.php';

$xoopsOption['template_main'] = 'library_archive.html';
include_once ICMS_ROOT_PATH . '/header.php';

$archive_objects = $archiveObj = $archive = '';

$library_archive_handler = icms_getModuleHandler('archive',
	basename(dirname(__FILE__)), 'library');

// there should only be one archive object

$archive_objects = $library_archive_handler->getObjects();
$archiveObj = array_shift($archive_objects);

if ($archiveObj && $libraryConfig['library_enable_archive'] == 1) {

	// make the email address human readable only, because man we hate spambots
	$archive = $archiveObj->toArray();
	$archive['admin_email'] = str_replace('@', ' "at" ', $archive['admin_email']);

	// display this archive
	$icmsTpl->assign('library_archive', $archive);

	// generate metadata for this page
	$icms_metagen = new IcmsMetagen($archiveObj->getVar('repository_name'),
		$archiveObj->getVar('meta_keywords','n'), $archiveObj->getVar('meta_description', 'n'));
	$icms_metagen->createMetaTags();
}

$icmsTpl->assign('library_module_home', library_getModuleName(true, true));

include_once 'footer.php';