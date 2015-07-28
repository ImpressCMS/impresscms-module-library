<?php
/**
 * Admin page to manage collections
 *
 * List, add, edit and delete publication objects of type Collection
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

/**
 * Edit a publication (collections are publications of type 'Collection')
 *
 * @param int $publication_id Publicationid to be edited
 */
function editpublication($publicationObj) {
	global $library_publication_handler, $icmsModule, $icmsUser, $icmsAdminTpl;

	if (!$publicationObj->isNew()) {
		$icmsModule->displayAdminMenu(1, _AM_LIBRARY_PUBLICATIONS . " > " . _CO_ICMS_EDITING);
		$sform = $publicationObj->getForm(_AM_LIBRARY_PUBLICATION_EDIT, 'addpublication');
		$sform->assign($icmsAdminTpl);

	} else {
		$publicationObj->setVar('submitter', $icmsUser->getVar('uid'));
		$icmsModule->displayAdminMenu(1, _AM_LIBRARY_PUBLICATIONS . " > " . _CO_ICMS_CREATINGNEW);
		$sform = $publicationObj->getForm(_AM_LIBRARY_PUBLICATION_CREATE, 'addpublication');
		$sform->assign($icmsAdminTpl);

	}
	$icmsAdminTpl->display('db:library_admin_publication.html');
}

function contextualise_form_fields($publicationObj) {
	$type = $publicationObj->getVar('type');

	switch ($type) {
		default:
			$publicationObj->setControl('type', array(
			'name' => 'select',
			'itemHandler' => 'publication',
			'module' => 'library',
			'options' => array('Collection' => _CO_LIBRARY_COLLECTION)));
			$publicationObj->doHideFieldFromForm('source');
			$publicationObj->doShowFieldOnForm('compact_view');
			$publicationObj->setVar('source', 0);
	}
}

include_once("admin_header.php");

global $icmsUser;

$library_publication_handler = icms_getModuleHandler('publication',
		basename(dirname(dirname(__FILE__))), 'library');
/** Use a naming convention that indicates the source of the content of the variable */
$clean_op = '';
/** Create a whitelist of valid values, be sure to use appropriate types for each value
 * Be sure to include a value for no parameter, if you have a default condition
 */
$valid_op = array ('mod','changedField','addpublication','del','view','changeStatus',
	'changeFederated', '');

if (isset($_GET['op'])) $clean_op = htmlentities($_GET['op']);
if (isset($_POST['op'])) $clean_op = htmlentities($_POST['op']);

/** Again, use a naming convention that indicates the source of the content of the variable */
$clean_publication_id = isset($_GET['publication_id']) ? (int) $_GET['publication_id'] : 0 ;

if (in_array($clean_op,$valid_op,true)) {
	switch ($clean_op) {
		case "mod":
			case "mod":
			icms_cp_header();
			$publicationObj = $library_publication_handler->get($clean_publication_id);
			contextualise_form_fields($publicationObj);
			editpublication($publicationObj);

			break;

		case "changedField":
			icms_cp_header();
			$publicationObj = $library_publication_handler->get($clean_publication_id);
			
			if (isset($_POST['op'])) {
				$controller = new IcmsPersistableController($library_publication_handler);
				$controller->postDataToObject($publicationObj);
				if ($_POST['op'] == 'changedField') {
					switch ($_POST['changedField']) {
						case 'type':
							contextualise_form_fields($publicationObj);
					}

				}
			}
			editpublication($publicationObj);
			break;

		case "addpublication":
			include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
			$controller = new IcmsPersistableController($library_publication_handler);
			$controller->storeFromDefaultForm(_AM_LIBRARY_PUBLICATION_CREATED,
				_AM_LIBRARY_PUBLICATION_MODIFIED);

			break;

		case "del":
			include_once ICMS_ROOT_PATH."/kernel/icmspersistablecontroller.php";
			$controller = new IcmsPersistableController($library_publication_handler);
			$controller->handleObjectDeletion();

			break;

		case "changeStatus":
			$status = $library_publication_handler->change_status($clean_publication_id, 'status');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/collection.php';
			if ($status == 0) {
				redirect_header(ICMS_URL . $ret, 2, _AM_LIBRARY_COLLECTION_OFFLINE);
			} else {
				redirect_header(ICMS_URL . $ret, 2, _AM_LIBRARY_COLLECTION_ONLINE);
			}
			break;

		case "changeFederated":
			$federated = $library_publication_handler->change_status($clean_publication_id, 'federated');
			$ret = '/modules/' . basename(dirname(dirname(__FILE__))) . '/admin/collection.php';
			if ($federated == 0) {
				redirect_header(ICMS_URL . $ret, 2, _AM_LIBRARY_COLLECTION_NOT_FEDERATED);
			} else {
				redirect_header(ICMS_URL . $ret, 2, _AM_LIBRARY_COLLECTION_FEDERATED);
			}
			break;

		default:
			icms_cp_header();

			$icmsModule->displayAdminMenu(1, _AM_LIBRARY_PUBLICATIONS);

			include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";

			// check to see if /uploads/library is writeable, if not, complain
			$warnings = '';
			$warnings = library_check_module_configuration();
			if (!empty($warnings)) {
				$icmsAdminTpl->assign('library_warnings', $warnings);
			}

			// if no op is set, but there is a (valid) publication_id, display a single object
			if ($clean_publication_id) {
				$publicationObj = $library_publication_handler->get($clean_publication_id);
				if ($publicationObj->id()) {
					$publicationObj->displaySingleObject();
				}
			}

			// Collections are actually just publication objects with type = Collection
			$criteria = icms_buildCriteria(array('type' => 'Collection'));

			$objectTable = new IcmsPersistableTable($library_publication_handler, $criteria);
			$objectTable->addColumn(new IcmsPersistableColumn('status', 'center', true));
			$objectTable->addColumn(new IcmsPersistableColumn('title'));
			$objectTable->addColumn(new IcmsPersistableColumn('format'));
			$objectTable->addColumn(new IcmsPersistableColumn('submission_time'));
			$objectTable->addColumn(new IcmsPersistableColumn('date'));
			$objectTable->addColumn(new IcmsPersistableColumn('federated', 'center', true));
			$objectTable->addFilter('federated', 'federated_filter');
			$objectTable->addFilter('format', 'format_filter');
			$objectTable->addFilter('rights', 'rights_filter');
			$objectTable->addFilter('status', 'status_filter');
			$objectTable->addQuickSearch('title');
			$objectTable->addIntroButton('addpublication', 'collection.php?op=mod',
				_AM_LIBRARY_COLLECTION_CREATE);
			$icmsAdminTpl->assign('library_publication_table', $objectTable->fetch());
			$icmsAdminTpl->display('db:library_admin_publication.html');
			break;
	}
	icms_cp_footer();
}
/**
 * If you want to have a specific action taken because the user input was invalid,
 * place it at this point. Otherwise, a blank page will be displayed
 */