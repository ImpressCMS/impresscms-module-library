<?php
/**
 * Category index page - display category list or single view, dependent on ImTagging being installed
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

include_once 'header.php';

$xoopsOption['template_main'] = 'library_category.html';

include_once ICMS_ROOT_PATH . '/header.php';

/** Use a naming convention that indicates the source of the content of the variable */
$clean_category_id = isset($_GET['category_id']) ? intval($_GET['category_id']) : 0 ;
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : 0;

$imtagging_category_handler = icms_getModuleHandler('category', 'imtagging');
$base_link = ICMS_URL . '/modules/' . basename(dirname(__FILE__))
	. '/category.php?category_id=';


// display a list of top level categories - those with no parent id (category_pid)
if (empty($clean_category_id)) {
	global $libraryConfig;
	$criteria = '';
	$top_level_categories = $category_list = array();

	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('category_pid', 0));
	$criteria->setSort('category_title');
	$criteria->setOrder('ASC');
	$top_level_categories = $imtagging_category_handler->getObjects($criteria, false, false);
	foreach($top_level_categories as $category) {
		$category_list[] = '<a href="' . $base_link . $category['category_id'] . '">'
			. $category['category_title'] . '</a>';
	}
	// assign to template
	$icmsTpl->assign('library_categories', $category_list);
} else {
	// display the contents of one category including first-level subcategories and parent

	// build a category tree - although perhaps it is overkill?
	include_once(ICMS_ROOT_PATH . "/modules/imtagging/class/icmspersistabletree.php");

	$categories = $childObjArray = $child_link_array = $category_link_array = 
		$publication_object_array = $processed_publications = array();
	$categoryObj = $parentObj = $moduleObj = $category_link = $parent_link = $mid = '';
	$imtagging_category_handler = icms_getModuleHandler('category', 'imtagging');
	$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
	$library_publication_handler = icms_getModuleHandler('publication',
	basename(dirname(__FILE__)), 'library');

	$categories = $imtagging_category_handler->getObjects();
	$category_tree = new IcmsPersistableTree($categories, 'category_id', 'category_pid');

	// lookup the category
	$categoryObj = $category_tree->getByKey($clean_category_id);
	$category_link = '<a href="' . $base_link . $clean_category_id . '">'
		. $categoryObj->getVar('category_title') . '</a>';
	$icmsTpl->assign('library_category_link', $category_link);

	// lookup the parent category
	$parent_id = $categoryObj->getVar('category_pid', 'e');
	if(!empty($parent_id)) {
		$parentObj = $category_tree->getByKey($parent_id);
		$parent_link = '<a href="' . $base_link . $parentObj->getVar('category_id') . '">'
		. $parentObj->getVar('category_title') . '</a>';
		$icmsTpl->assign('library_parent_link', $parent_link);
	}

	// lookup the child categories (one level only)
	$childObjArray = $category_tree->getFirstChild($categoryObj->getVar('category_id'));
	foreach($childObjArray as $child) {
		$child_link_array[] = '<a href="' . $base_link . $child->getVar('category_id') . '">'
			. $child->getVar('category_title') . '</a>';
		$icmsTpl->assign('library_child_link', $child_link_array);
	}

	// make category navigation breadcrumb
	// getAllParent($key, $ret = array(), $uplevel = 1)

	// lookup the publications in this category
	$publication_ids = $imtagging_category_link_handler->getItemidsForCategory( $clean_category_id,
		$library_publication_handler );

	$criteria = '';
	$criteria = new CriteriaCompo();
	$criteria->add(new Criteria('publication_id', '(' . implode(',', $publication_ids) . ')', 'IN'));
	$criteria->add(new Criteria('status', '1'));

	// jump in to get item count before proceeding :)
	$item_count = $library_publication_handler->getCount($criteria);

	$criteria->setStart($clean_start);
	$criteria->setLimit($libraryConfig['number_publications_per_page']);
	$criteria->setSort('submission_time');
	$criteria->setOrder('DESC');

	// prepare publications for display
	$publication_object_array = $library_publication_handler->getObjects($criteria);
	foreach ($publication_object_array as $publicationObj) {
		$publication = $publicationObj->prepare_publication_for_display(false);
		$publication = library_implement_publication_display_preferences($publication);
		if ($publication['type'] == 'Collection') {
				$publication['subtemplate'] = 'db:library_collection_description.html';
		}
		$processed_publications[] = $publication;
	}
	$icmsTpl->assign('library_publication_array', $processed_publications);
	$icmsTpl->assign('library_publication_view', 'multiple');

	// pagination
	include_once ICMS_ROOT_PATH . '/class/pagenav.php';
	$pagenav = new XoopsPageNav($item_count, $libraryConfig['number_publications_per_page'],
		$clean_start, 'start', 'category_id=' . $clean_category_id);
	$icmsTpl->assign('library_navbar', $pagenav->renderNav());
}

$icmsTpl->assign('library_module_home', library_getModuleName(true, true));

include_once 'footer.php';