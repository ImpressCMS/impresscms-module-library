<?php

/**
 * Provides links to test the Library module's response to incoming OAIPMH requests
 *
 * Exteneral metadata harvesters compliant with the Open Archives Initiative Protocol for Metadata
 * harvesting can submit queries requesting information about the Library repository or its records.
 * This file allows admins to test, visualise and debug the response if necessary. It is also
 * included for educational purposes :)
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

include_once("admin_header.php");
icms_cp_header();
$icmsModule->displayAdminMenu(3, _AM_LIBRARY_PUBLICATIONS);

$id = '';
$publicationObjects = array();
$url = LIBRARY_URL . 'oaipmh_target.php?verb=';

// explanatory text - use the links below to test responses to incoming OAIPMH requests
echo _AM_LIBRARY_TEST_OAIPMH . '<br />';

// check if any publications have been entered, throw warnings if not
// if there is, take the identifier of the first records as an example
$criteria = new CriteriaCompo();
$criteria->add(new Criteria('status', '1'));
$criteria->add(new Criteria('federated', '1'));
$criteria->setLimit(1);
$library_publication_handler = icms_getModuleHandler('publication', 
		basename(dirname(dirname(__FILE__))),'library');
$publicationObjects = $library_publication_handler->getObjects($criteria);
if (!empty($publicationObjects)) {
	$first_publication = array_shift($publicationObjects);
	$id = $first_publication->getVar('oai_identifier');
} else {
	// warning to enter some records first
	echo '<p>&nbsp;</p><p><strong><span style="color:#red;">'
		. _AM_LIBRARY_ENTER_RECORDS . '</span></strong></p><p>&nbsp;</p>';
}

// check if archive functionality is enabled
if ($libraryConfig['library_enable_archive'] == 0) {
	echo '<p><strong><span style="color:#red;">' . _AM_LIBRARY_ARCHIVE_DISABLED
		. '</span></strong></p>';
}

// check archive object exists
$library_archive_handler = icms_getModuleHandler('archive', 
		basename(dirname(dirname (__FILE__))), 'library');
$archive_exists = $library_archive_handler->getCount();
if ($archive_exists == 0) {
	echo '<p><strong><span style="color:#red;">' . _CO_LIBRARY_ARCHIVE_MUST_CREATE
		. '</span></strong></p>';
}

// links to trigger OAIPMH requests
echo '<ul><li><a href="' . $url . 'Identify">' . _AM_LIBRARY_TEST_IDENTIFY
	. '</a>' . _AM_LIBRARY_TEST_IDENTIFY_DSC . '</li>
<li><a href="' . $url . 'GetRecord&amp;metadataPrefix=oai_dc&amp;identifier=' . $id . '">' 
	. _AM_LIBRARY_TEST_GET_RECORD . '</a>' . _AM_LIBRARY_TEST_GET_RECORD_DSC . '</li>
<li><a href="' . $url . 'ListIdentifiers&amp;metadataPrefix=oai_dc">' 
	. _AM_LIBRARY_TEST_LIST_IDENTIFIERS . '</a>' . _AM_LIBRARY_TEST_LIST_IDENTIFIERS_DSC . '</li>
<li><a href="' . $url . 'ListMetadataFormats">' ._AM_LIBRARY_TEST_LIST_METADATA_FORMATS . '</a>'
	. _AM_LIBRARY_TEST_LIST_METADATA_FORMATS_DSC . '</li>
<li><a href="' . $url . 'ListRecords&amp;metadataPrefix=oai_dc">' 
	. _AM_LIBRARY_TEST_LIST_RECORDS . '</a>' . _AM_LIBRARY_TEST_LIST_RECORDS_DSC . '</li>
<li><a href="' . $url . 'ListSets">' . _AM_LIBRARY_TEST_LIST_SETS
	. '</a>' . _AM_LIBRARY_TEST_LIST_SETS_DSC . '</li></ul>';

// more information
echo _AM_LIBRARY_TEST_MORE_INFO;

icms_cp_footer();
