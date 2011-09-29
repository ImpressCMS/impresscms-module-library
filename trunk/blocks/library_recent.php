<?php
/**
 * Recent librarys block file
 *
 * This file holds the functions needed for the recent librarys block
 *
 * @copyright	http://smartfactory.ca The SmartFactory
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		marcan aka Marc-André Lanciault <marcan@smartfactory.ca>
 * Modified for use in the Library module by Madfish
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

function library_recent_show($options) {
	include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
		. '/include/common.php');
	$library_publication_handler = icms_getModuleHandler('publication',
		basename(dirname(dirname(__FILE__))), 'library');
	$criteria = new CriteriaCompo();
	$criteria->setStart(0);
	$criteria->setLimit($options[1]);

	// only include publications that are set online
	$criteria->add(new Criteria('status', true));

	// optionally filter track listing by collection
	if (intval($options[0])) {
		$criteria->add(new Criteria('source', $options[0]));
	}

	$criteria->setSort('submission_time');
	$criteria->setOrder('DESC');
	$block['publications'] = $library_publication_handler->getObjects($criteria, true, false);
	foreach($block['publications'] as $key => &$value) {
		$value = $value['itemLink'] . ' (' . $value['date'] . ')';
	}
	// also need to consider permissions and status
	return $block;
}

function library_recent_edit($options) {
	include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
		. '/include/common.php');
	include_once(ICMS_ROOT_PATH . '/class/xoopsform/formselect.php');
	$library_publication_handler = icms_getModuleHandler('publication',
		basename(dirname(dirname(__FILE__))), 'library');

	$form = '<table><tr>';
	// optionally display results from a single collection
	$form .= '<td>' . _MB_LIBRARY_LIBRARY_RECENT_COLLECTION . '</td>';
	// Parameters XoopsFormSelect: ($caption, $name, $value = null, $size = 1, $multiple = false)
	$form_select = new XoopsFormSelect('', 'options[]', $options[0], '1', false);
	$publication_list = $library_publication_handler->getList();
	$publication_list = array(0 => 'All') + $publication_list;
	$form_select->addOptionArray($publication_list);
	$form .= '<td>' . $form_select->render() . '</td></tr>';
	// select number of recent publications to display in the block
	$form .= '<tr><td>' . _MB_LIBRARY_LIBRARY_RECENT_LIMIT . '</td>';
	$form .= '<td>' . '<input type="text" name="options[]" value="' . $options[1] . '"/></td>';
	$form .= '</tr></table>';
	return $form;
}