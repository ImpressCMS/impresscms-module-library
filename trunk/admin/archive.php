<?php
/**
 * Admin page to manage archives
 *
 * List, add, edit and delete archive objects. Only one archive object is permitted at a time. The
 * Archive object manages responses to incoming OAIPMH queries. It performs no other function and
 * if a site doesn't need OAIPMH functions there is no need to generate one. Strictly optional.
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2010
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		archive
 * @version		$Id$
 */

/**
 * Edit a Archive
 *
 * @param int $archive_id Archiveid to be edited
 */
function editarchive($archive_id = 0) {
	global $library_archive_handler, $icmsModule, $icmsAdminTpl;

	$archiveObj = $library_archive_handler->get($archive_id);

	if (!$archiveObj->isNew()) {
		$icmsModule->displayAdminMenu(3, _AM_LIBRARY_ARCHIVES . " > " . _CO_ICMS_EDITING);
		$sform = $archiveObj->getForm(_AM_LIBRARY_ARCHIVE_EDIT, 'addarchive');
		$sform->assign($icmsAdminTpl);

	} else {
		$icmsModule->displayAdminMenu(3, _AM_LIBRARY_ARCHIVES . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $archiveObj->getForm(_AM_LIBRARY_ARCHIVE_CREATE, 'addarchive');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:library_admin_archive.html');
}

include_once("admin_header.php");

$library_archive_handler = icms_getModuleHandler('archive', 
		basename(dirname(dirname(__FILE__))), 'library');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addarchive','del','view','');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_archive_id = isset($_GET['archive_id']) ? (int) $_GET['archive_id'] : 0 ;

/**
 * in_array() is a native PHP function that will determine if the value of the
 * first argument is found in the array listed in the second argument. Strings
 * are case sensitive and the 3rd argument determines whether type matching is
 * required
 */
if (in_array($clean_op,$valid_op,true)) {
	switch ($clean_op) {
		case "mod":
		case "changedField":
			icms_cp_header();
			editarchive($clean_archive_id);
			break;

		case "addarchive":
			include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
			$controller = new IcmsPersistableController($library_archive_handler);
			$controller->storeFromDefaultForm(_AM_LIBRARY_ARCHIVE_CREATED, _AM_LIBRARY_ARCHIVE_MODIFIED);

			break;

		case "del":
			include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
			$controller = new IcmsPersistableController($library_archive_handler);
			$controller->handleObjectDeletion();
			break;

		default:
			icms_cp_header();
			$icmsModule->displayAdminMenu(3, _AM_LIBRARY_ARCHIVES);

			// if no op is set, but there is a (valid) archive_id, display a single object
			if ($clean_archive_id) {
				$archiveObj = $library_archive_handler->get($clean_archive_id);
				if ($archiveObj->id()) {
					$archiveObj->displaySingleObject();
				}
			}

			include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";
			$objectTable = new IcmsPersistableTable($library_archive_handler, false, array('edit'));
			$objectTable->addColumn(new IcmsPersistableColumn('repository_name'));

			// only one archive object is needed or useful
			// so only show the 'add archive' button if there isn't one already
			$archive_count = $library_archive_handler->getCount();
			if ($archive_count == 0) {
				$icmsAdminTpl->assign('library_archive_no_archive', _AM_LIBRARY_ARCHIVE_NO_ARCHIVE);
				$objectTable->addIntroButton('addarchive', 'archive.php?op=mod',
					_AM_LIBRARY_ARCHIVE_CREATE);
			}
			// check if archive functionality is enabled and post status
			if ($libraryConfig['library_enable_archive'] == 0) {
				$archive_status = _AM_LIBRARY_ARCHIVE_OFFLINE;
			} else {
				$archive_status = _AM_LIBRARY_ARCHIVE_ONLINE;
			}
			$icmsAdminTpl->assign('library_archive_status', $archive_status);
			$icmsAdminTpl->assign('library_archive_table', $objectTable->fetch());
			$icmsAdminTpl->display('db:library_admin_archive.html');
			break;
	}
	icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */