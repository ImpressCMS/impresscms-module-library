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

// check pagination (collections only)
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$clean_m3u_flag = isset($_GET['m3u_flag']) ? intval($_GET['m3u_flag']) : 0;

$publicationObj = $library_publication_handler->get($clean_publication_id);

// check if the publication status, if set as offline torch it
if ($publicationObj->getVar('status') == false) {
	unset($publicationObj);
}

// display or stream a single publication or collection
if ($publicationObj && !$publicationObj->isNew()) {

	// if a stream flag is set, send an m3u playlist file to the browser to initiate streaming
	if ($clean_m3u_flag == 1) {
		$publicationObj->stream_audio();
		exit;

	} else { // display a single publication

		// increment hit counter
		$library_publication_handler->updateCounter($publicationObj);

		// prepare publication for display and convert to array
		$publication = $publicationObj->prepare_publication_for_display(true);

		// unset unwanted fields as per module preferences
		$publication = library_implement_publication_display_preferences($publication);

		// if the publication is a collection, append its member items & allow for pagination
		$collection_items = $collection_publications = array();

		if ($publication['type'] == 'Collection') {
			$criteria = new CriteriaCompo();
			$criteria->add(new Criteria('source', $clean_publication_id));
			$criteria->add(new Criteria('status', true));
			$criteria->setStart($clean_start);
			$criteria->setLimit($libraryConfig['number_publications_per_page']);
			$criteria->setSort('submission_time');
			$criteria->setOrder('DESC');

			$collection_items = $library_publication_handler->getObjects($criteria);
			
			// If there are more than 3 items in the collection, use buffers to minimise repetitive
			// queries, but if there are less it is more efficient not to
			$publication_count = count($collection_items);
			if ($publication_count > 3) {
				foreach($collection_items as $item) {
					$collection_publications[] = $item->prepare_publication_for_display(true);
				}
			} else {
				$collection_publications = $library_publication_handler->prepare_collection_for_display();
			}

			// pagination for viewing a collection-type publication internally
			include_once ICMS_ROOT_PATH . '/class/pagenav.php';
			$item_count = $publication['item_count'];
			$extra_arg = 'publication_id=' . $publication['publication_id'];
			$pagenav = new XoopsPageNav($item_count, $libraryConfig['number_publications_per_page'],
				$clean_start, 'start', $extra_arg);
			$icmsTpl->assign('library_navbar', $pagenav->renderNav());
			$icmsTpl->assign('library_publication_view', 'multiple');
		} else {
			$icmsTpl->assign('library_publication_view', 'single');
		}

		// assign to template
		$icmsTpl->assign('library_publication', $publication);
		$icmsTpl->assign('library_collection_publications', $collection_publications);

		// comments
		if ($libraryConfig['com_rule']) {
			$icmsTpl->assign('library_publication_comment', true);
			include_once ICMS_ROOT_PATH . '/include/comment_view.php';
		}

		// generating meta information for this page
		$icms_metagen = new IcmsMetagen($publicationObj->getVar('title'),
			$publicationObj->getVar('meta_keywords','n'),
			$publicationObj->getVar('meta_description', 'n'));
		$icms_metagen->createMetaTags();
	}
} else {
	// show the most recent publications, as summaries or as a table, as per preference settings
	$publications = $processed_publications = array();
	$new_rss_button = '<a href="rss.php" title="' . _CO_LIBRARY_PUBLICATION_ENCLOSURES . '">'
		. '<img src="' . 'images/rss.png" alt="RSS"' . ' /></a>';

	$icmsTpl->assign('library_title', _CO_LIBRARY_NEW);
	$icmsTpl->assign('library_new_rss_button', $new_rss_button);

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('status', true));
	$criteria->setSort('submission_time');
	$criteria->setOrder('DESC');

	// display recent publications in descriptive summary form
	if ($libraryConfig['new_publication_view_mode'] == 0) {
		$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;
		$criteria->setStart($clean_start);
		$criteria->setLimit($libraryConfig['number_publications_per_page']);

		$publications = $library_publication_handler->getObjects($criteria);
		foreach($publications as $publication) {
			$publication = $publication->prepare_publication_for_display(false);
			$publication = library_implement_publication_display_preferences($publication);
			if ($publication['type'] == 'Collection') {
				$publication['subtemplate'] = 'db:library_collection_description.html';
			}
			$processed_publications[] = $publication;
		}

		// assign to template
		$icmsTpl->assign('library_publication_view', 'multiple');
		$icmsTpl->assign('library_publication_array', $processed_publications);

		// pagination
		include_once ICMS_ROOT_PATH . '/class/pagenav.php';
		$criteria = '';
		$criteria = icms_buildCriteria(array('status' => '1'));
		$publication_count = $library_publication_handler->getCount($criteria);
		$pagenav = new XoopsPageNav($publication_count,
			$libraryConfig['number_publications_per_page'], $clean_start, 'start');

		$icmsTpl->assign('library_navbar', $pagenav->renderNav());
	} else {

		// show recent publications in table form
		include_once ICMS_ROOT_PATH."/kernel/icmspersistabletable.php";

		$objectTable = new IcmsPersistableTable($library_publication_handler, $criteria, array());
		$objectTable->isForUserSide();
		$objectTable->addColumn(new IcmsPersistableColumn('title'));
		$objectTable->addColumn(new IcmsPersistableColumn('source'));
		$objectTable->addColumn(new IcmsPersistableColumn('date'));
		$icmsTpl->assign('library_publication_table', $objectTable->fetch());
	}
}

$icmsTpl->assign('library_module_home', library_getModuleName(true, true));

include_once 'footer.php';
