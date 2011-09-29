<?php
/**
 * Publication index page - display, download or stream a single publication, collection, or a table of all publications
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

include_once 'header.php';

$xoopsOption['template_main'] = 'library_publication.html';

include_once ICMS_ROOT_PATH . '/header.php';

$clean_m3u_flag = '';
$library_publication_handler = icms_getModuleHandler('publication',
	basename(dirname(__FILE__)), 'library');

/** Use a naming convention that indicates the source of the content of the variable */
$clean_publication_id = isset($_GET['publication_id']) ? intval($_GET['publication_id']) : 0 ;

// view a descriptive list of collections or a table of collections, as per module preferences
// It is best to use descriptive list if there are only a few collections, if there are many use
// table view instead

global $libraryConfig;
$publication = $collections = $processed_collections = array();

$criteria = new CriteriaCompo();
$criteria->add(new Criteria('type', 'Collection'));
$criteria->add(new Criteria('status', true));
$criteria->setSort('submission_time');
$criteria->setOrder('DESC');

// view collections as a descriptive list
if ($libraryConfig['collection_view_mode'] == false) {
	$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
	$criteria->setStart($clean_start);
	$criteria->setLimit($libraryConfig['number_collections_per_page']);

	$collections = $library_publication_handler->getObjects($criteria);
	foreach($collections as $collection) {
		$collection = $collection->prepare_publication_for_display(false);
		$collection = library_implement_publication_display_preferences($collection);
		$processed_collections[] = $collection;
	}

	// need to set a template value
	$publication['subtemplate'] = 'db:library_collection.html';

	// assign to template
	$icmsTpl->assign('library_publication_view', 'multiple');
	$icmsTpl->assign('library_title', _MI_LIBRARY_COLLECTIONS);
	$icmsTpl->assign('library_publication', $publication);
	$icmsTpl->assign('library_collection_array', $processed_collections);

	// pagination
	include_once ICMS_ROOT_PATH . '/class/pagenav.php';
	$criteria = '';
	$criteria = icms_buildCriteria(array('type' => 'Collection', 'status' => '1'));
	$item_count = $library_publication_handler->getCount($criteria);
	$pagenav = new XoopsPageNav($item_count, $libraryConfig['number_collections_per_page'],
		$clean_start, 'start');
	$icmsTpl->assign('library_navbar', $pagenav->renderNav());

// view collections as a table
} else {
	include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";

	$icmsTpl->assign('library_title', _MI_LIBRARY_COLLECTIONS);

	$objectTable = new IcmsPersistableTable($library_publication_handler, $criteria, array());
	$objectTable->isForUserSide();
	$objectTable->addColumn(new IcmsPersistableColumn('title'));
	$objectTable->addColumn(new IcmsPersistableColumn('date'));
	$icmsTpl->assign('library_publication_table', $objectTable->fetch());
}

$icmsTpl->assign('library_module_home', library_getModuleName(true, true));

include_once 'footer.php';