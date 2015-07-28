<?php

/**
 * Classes responsible for managing Archive objects and responding to OAIPMH requests
 *
 * A mimimal implementation of the Open Archives Initiative Protocol for Metadata Harvesting (OAIPMH)
 * Requests are received against the oaipmh_target.php file. Responses are XML streams as per the
 * OAIPMH specification, which defines a standard vocabulary and response format.
 *
 * @copyright	Copyright Madfish (Simon Wilkinson) 2010
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish (Simon Wilkinson) <simon@isengard.biz>
 * @package		archive
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistabelSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/include/functions.php');

class LibraryArchive extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler ArchivePostHandler object
	 */
	public function __construct(& $handler) {
		global $icmsConfig;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('archive_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('metadata_prefix', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setMetadataPrefix());
		$this->quickInitVar('namespace', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setNamespace());
		$this->quickInitVar('granularity', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setGranularity());
		$this->quickInitVar('deleted_record', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setDeletedRecord());
		$this->quickInitVar('earliest_date_stamp', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setEarliestDateStamp());
		$this->quickInitVar('admin_email', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setAdminEmail());
		$this->quickInitVar('protocol_version', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setProtocolVersion());
		$this->quickInitVar('repository_name', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setRepositoryName());
		$this->quickInitVar('base_url', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setBaseUrl());
		$this->quickInitVar('compression', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setCompression());
		$this->initCommonVar('counter');
		$this->initCommonVar('dohtml');
		$this->initCommonVar('dobr');
		$this->initCommonVar('docxode');

		$this->doMakeFieldreadOnly('metadata_prefix');
		$this->doMakeFieldreadOnly('namespace');
		$this->doMakeFieldreadOnly('granularity');
		$this->doMakeFieldreadOnly('deleted_record');
		$this->doMakeFieldreadOnly('earliest_date_stamp');
		$this->doMakeFieldreadOnly('protocol_version');
		$this->doMakeFieldreadOnly('base_url');
		$this->doMakeFieldreadOnly('compression');

		$this->IcmsPersistableSeoObject();
	}

	/**
	 * Overriding the IcmsPersistableObject::getVar method to assign a custom method on some
	 * specific fields to handle the value before returning it
	 *
	 * @param str $key key of the field
	 * @param str $format format that is requested
	 * @return mixed value of the field that is requested
	 */
	public function getVar($key, $format = 's') {
		if ($format == 's' && in_array($key, array ())) {
			return call_user_func(array ($this,	$key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Generates a standard header for OAIPMH responses
	 *
	 * @return string
	 */
	public function oai_header() {
		$header = '';
		$timestamp = time();

		$timestamp = gmdate(DATE_ISO8601, $timestamp); // convert timestamp to UTC format
		$timestamp = str_replace('+0000', 'Z', $timestamp); // UTC designator 'Z' is OAI spec

		// build header

		$header .= '<?xml version="1.0" encoding="UTF-8" ?>';
		$header .= '<OAI-PMH xmlns="http://www.openarchives.org/OAI/2.0/"
            xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
            xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/
            http://www.openarchives.org/OAI/2.0/OAI-PMH.xsd">';
		$header .= '<responseDate>' . $timestamp . '</responseDate>'; // must be UTC timestamp
		return $header;
	}

	/**
	 * Generates a standard footer for OAIPMH responses
	 *
	 * @return string
	 */
	public function oai_footer() {
		$footer ='</OAI-PMH>';
		return $footer;
	}

	////////// OPEN ARCHIVE INITIATIVE METHODS - MINIMAL IMPLEMENTATION AS PER THE GUIDELINES //////

	/**
	 * Returns basic information about the respository
	 *
	 * @return string
	 */
	public function identify() {
		// input validation: none required
		// throws: badArgument (how? no arguments are accepted so there is nothing to test for)
		$response = $deletedRecord = '';

		$response = $this->oai_header();
		$response .= '<request verb="Identify">' . $this->getVar('base_url') . '</request>' .
			'<Identify>' .
			'<repositoryName>' .  $this->getVar('repository_name') . '</repositoryName>' .
			'<baseURL>' . $this->getVar('base_url') . '</baseURL>' .
			'<protocolVersion>' .  $this->getVar('protocol_version') . '</protocolVersion>' .
			'<adminEmail>' . $this->getVar('admin_email') . '</adminEmail>' .
			'<earliestDatestamp>' . $this->getVar('earliest_date_stamp') . '</earliestDatestamp>' .
			'<deletedRecord>' .  $this->getVar('deleted_record') . '</deletedRecord>' .
			'<granularity>' . $this->getVar('granularity') . '</granularity>' .
			'<compression>' . $this->getVar('compression') . '</compression>' .
			'</Identify>';
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (spec/XML requirement), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	/**
	 * Returns information about the available metadata formats this repository supports (only oai_dc)
	 *
	 * @param string $identifier
	 * @return string
	 */
	public function listMetadataFormats($identifier = null) {

		// accepts an optional identifier to enquire about formats available for a particular record
		// throws badArgument (how? there are no required arguments; if identifier is wrong the
		// the appropriate error = idDoesNotExist
		// throws noMetadataFormats (not necessary to implement, as oai_dc is hardwired and native
		// for everything)

		$response = '';
		$valid = true;

		$response = $this->oai_header();
		$response .= '<request verb="ListMetadataFormats"';
		if (!empty($identifier)) {
			$response .= ' identifier="' . $identifier . '"';
		}

		$response .= '>' . $this->getVar('base_url') . '</request>';

		// check if optional identifier is set, if so this request is regarding a particular record

		if (empty($identifier)) {

			// This archive only supports unqualified Dublin Core as its native format
			$response .= '<ListMetadataFormats>';
			$response .= '<metadataFormat>';
			$response .= '<metadataPrefix>' . $this->getVar('metadata_prefix') . '</metadataPrefix>';
			$response .= '<schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>';
			$response .= '<metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>';
			$response .= '</metadataFormat>';
			$response .= '</ListMetadataFormats>';
		} else { // an optional identifier has been provided, just check it exists

			// only search for publications that are set as i) online and ii) federated
			$library_publication_handler = icms_getModuleHandler('publication',
				basename(dirname(dirname(__FILE__))), 'library');
			$criteria = icms_buildCriteria(array('oai_identifier' => $identifier,
				'status' => '1', 'federated' => '1'));

			// this should return an array with only one publication object
			$publication_array = $library_publication_handler->getObjects($criteria);

			// extract the publication object
			$publicationObj = array_shift($publication_array);

			// if an object was in fact returned proceed to process
			if (!empty($publicationObj)) {
				if ($publicationObj->getVar('oai_identifier') == $identifier) {

					// This archive only supports unqualified Dublin Core as its native format
					$response .= '<ListMetadataFormats>';
					$response .= '<metadataFormat>';
					$response .= '<metadataPrefix>' . $this->getVar('metadata_prefix')
						. '</metadataPrefix>';
					$response .= '<schema>http://www.openarchives.org/OAI/2.0/oai_dc.xsd</schema>';
					$response .= '<metadataNamespace>http://www.openarchives.org/OAI/2.0/oai_dc/</metadataNamespace>';
					$response .= '</metadataFormat>';
					$response .= '</ListMetadataFormats>';
				}
			} else {
				// otherwise throw idDoesNotExist (record doesn't exist, or is offline, or not federated)
				$response .= $this->throw_error('idDoesNotExist', 'Record identifier does not exist');
			}
		}
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	/**
	 * Returns multiple records (headers only), supports selective harvesting based on time ranges
	 *
	 * @global mixed $xoopsDB
	 * @param string $metadataPrefix
	 * @param string $from
	 * @param string $until
	 * @param string $set
	 * @param string $resumptionToken
	 * @return string
	 */
	public function listIdentifiers($metadataPrefix = null, $from = null, $until = null,
			$set = null, $resumptionToken = null) {

		$valid = true;
		$haveResults = false; // flag if any records were returned by query

		$response = $this->oai_header();
		$response .= '<request verb="ListIdentifiers" metadataPrefix="'
				. $this->getVar('metadata_prefix') . '"';
		if (!empty($from)) {
			$response .= ' from="' . $from . '"';
		}
		if (!empty($until)) {
			$response .= ' until="' . $until . '"';
		}
		if (!empty($set)) {
			$response .= ' set="' . $set . '"';
		}
		if (!empty($resumptionToken)) {
			$response .= ' resumptionToken="' . $resumptionToken . '"';
		}
		$response .= '>' . $this->getVar('base_url') . '</request>';

		// VALIDATE INPUT

		// this archive does not support resumption tokens
		if (!empty($resumptionToken)) {
			// throws badResumptionToken
			$valid = false;
			$response .= $this->throw_error('badResumptionToken', 'This archive does not support '
				. 'resumption tokens, you get it all in one hit or not at all.');
		}

		if (!empty($set)) {
			// throws noSetHierarchy
			$valid = false;
			$response .= $this->throw_error('noSetHierarchy', 'This archive does not support sets.');
		}

		if (empty($metadataPrefix)) {
			// throws badArgument
			$valid = false;
			$response .= $this->throw_error('badArgument', 'Missing required argument: metadataPrefix');
		} else {
			if ($metadataPrefix !== 'oai_dc') {
				// throws cannotDisseminateFormat
				$valid = false;
				$response .= $this->throw_error('cannotDisseminateFormat', 'This archive only '
					. 'supports unqualified Dublin Core metadata format');
			}
		}

		// convert timestamps to ....UNIX timestamps
		if (!empty($from)) {
			$valid_timestamp = '';
			$from = str_replace('Z', '', $from);
			$from = str_replace('T', ' ', $from);
			$valid_timestamp = $this->validate_datetime($from);
			if ($valid_timestamp == false) {
				$valid = $false;
				$response .= $this->throw_error('badArgument', 'Invalid datetime: from');
			} else { // convert to unix timestamp for easy DB search & manipulation
				// YYYY-MM-DD HH:MM:SS => HH MM SS MM DD YYYY
				$from = strtotime($from);
				if ($from == false) { // if $from precedes unix epoch, throw error
					$valid = $false;
					$response .= $this->throw_error('badArgument', 'Invalid datetime: from');
				}
			}
		}

		if (!empty($until)) {
			$until = str_replace('Z', '', $until);
			$until = str_replace('T', ' ', $until);
			$valid_timestamp = $this->validate_datetime($until);
			if ($valid_timestamp == false) {
				$valid = $false;
				$response .= $this->throw_error('badArgument', 'Invalid datetime: until');
			} else { // convert to unix timestamp for easy DB search & manipulation
				// YYYY-MM-DD HH:MM:SS => HH MM SS MM DD YYYY
				$until = strtotime($until);
				if ($until == false) { // if $until precedes unix epoch, throw error
					$valid = $false;
					$response .= $this->throw_error('badArgument', 'Invalid datetime: until');
				}
			}
		}

		// this needs to lookup all records within the specified time range

		if ($valid == true) {
			global $xoopsDB;
			$sql = "SELECT `oai_identifier`,`submission_time` FROM " . $xoopsDB->prefix
				. "_library_publication WHERE";
			if (!empty($from) || !empty($until)) {
				if (!empty($from)) {
					$sql .= " `submission_time` >= '" . $from . "'";
				}
				if (!empty($from) && !empty($until)) {
					$sql .= " AND";
				}
				if (!empty ($until)) {
					$sql .= " `submission_time` <= '" . $until . "'";
				}
				$sql .= " AND";
			}
			$sql .= " `federated` = '1' AND `status` = '1' ";
			$rows = $this->handler->query($sql);

			// if an object was in fact returned proceed to process
			if (!empty($rows)) {
				// generate the headers and spit out the xml
				$records = $datestamp = '';
				foreach($rows as $publication) {
					$haveResults = true;
					$datestamp = $this->handler->timestamp_to_oaipmh_time($publication['submission_time']);
					$records .= '<header>';
					$records .= '<identifier>' . $publication['oai_identifier'] . '</identifier>';
					$records .= '<datestamp>' . $datestamp . '</datestamp>';
					$records .= '</header>';
					unset($datestamp);
				}
			}
			if ($haveResults == true) {
				$response .= '<ListIdentifiers>' . $records . '</ListIdentifiers>';
			} else {
				$response .= $this->throw_error('noRecordsMatch', 'No records match the request '
					. 'parameters');
			}
		}
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	/**
	 * Returns the set structure of repository (sets are not supported in this implementation)
	 *
	 * @param string $resumptionToken
	 * @return string
	 */
	public function listSets($resumptionToken = null) {
		// accepts optional resumptionToken
		// throws badArgument (no need to implement, as resumption tokens are not accepted)

		$response = '';

		$response = $this->oai_header();
		$response .= '<request verb="ListSets">' . $this->getVar('base_url') . '</request>';

		// this archive does not support sets or resumption tokens so the response is fixed
		if (!empty($resumptionToken)) {
			// throws badResumptionToken
			$response .= $this->throw_error('badResumptionToken', 'This archive does not support '
				. 'resumption tokens, you get it all in one hit or not at all.');
		}
		// throws noSetHierarchy
		$response .= $this->throw_error('noSetHierarchy', 'This archive does not support sets.');
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	/**
	 * Returns a single complete record based on its unique oai_identifier
	 *
	 * @param string $identifier
	 * @param strimg $metadataPrefix
	 * @return string
	 */
	public function getRecord($identifier = null, $metadataPrefix = null) {
		$record = $response = $dc_identifier = '';
		$valid = true;
		$schema = 'oai-identifier.xsd';
		$haveResult = false;
		$url = ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/publication.php?publication_id=';
		$library_publication_handler = icms_getModuleHandler('publication' ,
				basename(dirname(dirname (__FILE__))), 'library');
		$library_rights_handler = icms_getModuleHandler('rights',
				basename(dirname(dirname(__FILE__))), 'library');

		$response = $this->oai_header();
		$response .= '<request verb="GetRecord" identifier="' . $identifier
			. '" metadataPrefix="' . $metadataPrefix . '">' . $this->getVar('base_url')
			. '</request>';

		// input validation:
		if (empty($identifier) ) {
			// throws badArgument
			$valid = false;
			$response .= $this->throw_error('badArgument', 'Required argument missing: identifier');
		}

		if (empty($metadataPrefix)) {
			// throws badArgument
			$valid = false;
			$response .= $this->throw_error('badArgument',
				'Required arguments missing: metadataPrefix');
		} else {
			if ($metadataPrefix !== 'oai_dc') {
				// throws cannotDisseminateFormat
				$valid = false;
				$response .= $this->throw_error('cannotDisseminateFormat', 'This archive only '
					. 'supports unqualified Dublin Core metadata format');
			}
		}

		// lookup record
		if ($valid == true) {

			// only select records that are marked as online AND federated
			$criteria = icms_buildCriteria(array('oai_identifier' => $identifier,
				'status' => '1', 'federated' => '1'));

			// this should return an array with only one publication object, because the
			// identifier is unique
			$publication_array = $library_publication_handler->getObjects($criteria);

			// extract the publication object
			$publicationObj = array_shift($publication_array);


			// if an object was in fact returned proceed to process
			if (!empty($publicationObj)) {
				$haveResult = true;
				$publication = $publicationObj->toArray();

				// lookup human readable equivalents of the keys
				// the dc_identifer must be a URL pointing at the source repository record
				// this is necessary to give credit to the source repository, and to encourage
				// sharing of records - anyone clicking on an identifier link in an external archive
				// will be bounced back to the source archive

				// unique oai_identifier is already set

				// dc_identifier - a URL that an external repository can use to link to the original
				$publication['identifier'] = $publication['itemUrl'];

				// timestamp
				$publication['submission_time'] = strtotime($publication['submission_time']);

				// creator
				if ($publication['creator']) {
					$creators = $publicationObj->getVar('creator', 'e');
					$publication['creator'] = explode('|', $creators);
				}

				// contributor
				if ($publication['contributor']) {
					$contributors = $publicationObj->getVar('contributor', 'e');
					$publication['contributor'] = explode('|', $contributors);
				}

				// format
				if ($publication['format']) {
					$publication['format'] = $publicationObj->get_mimetype();
				}

				// source (URL to source collection)
				$source = $publicationObj->getVar('source', 'e');

				if (!empty($source)) {
					$publication['source'] = $url . $source;
				} else {
					unset($publication['source']);
				}

				// relation - these are determined by looking for records with a common source
				// (in the case of a collection, the source is itself)
				// but it might be more robust to look for common source oai_identifier
				if (!empty($source) || $publication['type'] == 'Collection') {
					if ($publication['type'] == 'Collection') {
						$source = $publication['publication_id'];
					}

					// search for publications with the same source
					$criteria = icms_buildCriteria(array('source' => $source, 'status' => '1',
						'federated' => 1));
					$relatedList = $library_publication_handler->getList($criteria);

					// delete the current publication from the list to avoid duplicates
					unset($relatedList[$publication['publication_id']]);

					// prepare a list of related URLs
					$related = array();

					foreach($relatedList as $key => $value) {
						$related[] = $url . $key;
					}

					$publication['relation'] = $related;
					unset($related);
				}

				// language - use ISO 639-1 language codes
				if ($publication['language']) {
					$publication['language'] = $publicationObj->getVar('language', 'e');
				}

				// rights
				if ($publication['rights']) {
					$publication['rights'] =
						$publicationObj->rightsName($publicationObj->getVar('rights', 'e'));
				}

				$response .= '<GetRecord>';

				// this populates the record in oai_dc xml
				$response .= $this->record_to_xml($publication);
				$response .= '</GetRecord>';
			}
			if ($haveResult == false) {
				// throws idDoesNotExist
				$response .= $this->throw_error('idDoesNotExist', 'Record ID does not exist, or '
					. 'has not been selected for federation');
			}
		}
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	// Returns multiple records (harvest entire repository, or within specified time range)

	/**
	 *
	 * @global mixed $xoopsDB
	 * @param string $metadataPrefix
	 * @param string $from
	 * @param string $until
	 * @param string $set
	 * @param string $resumptionToken
	 * @return string
	 */
	public function listRecords($metadataPrefix = null, $from = null, $until = null,
			$set = null, $resumptionToken = null) {

		$haveResults = false; // flags if any records were returned by query
		$valid = true; // if any part of the request is invalid, this will be set to false => exit
		$url = ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
			. '/publication.php?publication_id=';

		$response = $this->oai_header();
		$response .= '<request verb="ListRecords" metadataPrefix="' . $metadataPrefix . '"';

		if (!empty($from)) {
			$response .= ' from="' . $from . '"';
		}

		if (!empty($until)) {
			$response .= ' until="' . $until . '"';
		}

		if (!empty($set)) {
			$response .= ' set="' . $set . '"';
		}

		if (!empty($resumptionToken)) {
			$response .= ' resumptionToken="' . $resumptionToken . '"';
		}
		$response .= '>' . $this->getVar('base_url') . '</request>';

		// VALIDATE INPUT

		// this archive does not support resumption tokens
		if (!empty($resumptionToken)) {
			// throws badResumptionToken
			$valid = false;
			$response .= $this->throw_error('badResumptionToken', 'This archive does not support '
				. 'resumption tokens, you get it all in one hit or not at all.');
		}
		if (!empty($set)) {
			// throws noSetHierarchy
			$valid = false;
			$response .= $this->throw_error('noSetHierarchy', 'This archive does not support sets');
		}

		if (empty($metadataPrefix)) {
			$valid = false;
			$response .= $this->throw_error('badArgument', 'Missing required argument: '
				. 'metadataPrefix');
		} else {
			if ($metadataPrefix !== 'oai_dc') {
				$valid = false;
				$response .= $this->throw_error('cannotDisseminateFormat', 'This archive only '
					. 'supports unqualified Dublin Core metadata format');
			}
		}

		// validate from
		if (!empty($from)) {
			$valid_timestamp = '';
			$from = str_replace('Z', '', $from);
			$from = str_replace('T', ' ', $from);
			$valid_timestamp = $this->validate_datetime($from);
			if ($valid_timestamp == false) {
				$valid = $false;
				$response .= $this->throw_error('badArgument', 'Invalid datetime: from');
			} else {
				$valid_timestamp = $time = '';
				$time = $from;
				$valid_timestamp = $this->not_Before_Earliest_Datestamp($time);
				if ($valid_timestamp == false) {
					$valid = false;
					$response .= $this->throw_error('badArgument', 'Invalid datetime: from '
						. 'precedes earliest datestamp, your harvester should check this with an '
						. 'Identify request');
				}
			}
		}

		// validate until
		if (!empty($until)) {
			$until = str_replace('Z', '', $until);
			$until = str_replace('T', ' ', $until);
			$valid_timestamp = $this->validate_datetime($until);
			if ($valid_timestamp == false) {
				$valid = $false;
				$response .= $this->throw_error('badArgument', 'Invalid datetime: until');
			} else {
				$valid_timestamp = $time = '';
				$time = $until;
				$valid_timestamp = $this->not_Before_Earliest_Datestamp($time);
				if ($valid_timestamp == false) {
					$valid = false;
					$response .= $this->throw_error('badArgument', 'Invalid datetime: until '
						. 'precedes earliest datestamp, your harvester should check this with an '
						. 'Identify request');
				}
			}
		}

		// check that from precedes until
		if (!empty($from) && !empty($until)) {
			$valid_timestamp = '';
			$valid_timestamp = $this->from_precedes_until();
			if ($valid_timestamp == false) {
				$valid = false;
				$response .= $this->throw_error('badArgument', 'Invalid datetime: until parameter '
					. 'precedes from parameter');
			}
		}

		// lookup all records within the specified time range
		if ($valid == true) {
			$from = strtotime($from);
			$until = strtotime($until);
			$library_publication_handler = icms_getModuleHandler('publication',
				basename(dirname(dirname(__FILE__))), 'library');
			$sql = $rows = '';
			global $xoopsDB;

			$sql = "SELECT * from " . $xoopsDB->prefix('library_publication') . " WHERE";
			if (!empty($from) || !empty($until)) {
				if (!empty($from)) {
					$sql .= " `submission_time` >= '" . $from . "'";
				}
				if (!empty($from) && !empty($until)) {
					$sql .= " AND";
				}
				if (!empty ($until)) {
					$sql .= " `submission_time` <= '" . $until . "'";
				}
				$sql .= " AND";
			}
			$sql .= " `federated` = '1' AND `status` = '1' ";

			$publicationArray = array();
			$publicationArray = $library_publication_handler->getObjects(null, true, true, $sql);

			// if an object was in fact returned proceed to process
			if (empty($publicationArray)) {
				// throw noRecordsMatch
				$response .= $this->throw_error('noRecordsMatch', 'No records match the request '
					. 'parameters');
			}
		}
		// if there are some publications
		if (!empty($publicationArray)) {
			$records = '';
			$haveResults = true;

			// prepare lookup arrays for converting publication keys to human readable values
			// doing this outside of the main loop avoids massive numbers of redundant queries

			// get additional handlers required to process records
			$library_rights_handler = icms_getModuleHandler('rights',
				basename(dirname(dirname(__FILE__))), 'library');
			$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');

			// populate the lookup arrays
			// the idea here is to minimise queries by setting up reference arrays that can be
			// reused instead in each iteration of the loops instead of hitting the database

			$publicationLookupArray = $rightsLookupArray = $formatLookupArray = array();
			$sql = $rows = '';

			$sql = "SELECT `publication_id`, `oai_identifier` from "
				. $xoopsDB->prefix('library_publication');
			$rows = $this->handler->query($sql);
			$publicationLookupArray = array();

			// if an object was in fact returned proceed to process
			if (empty($rows)) {
				// throw noRecordsMatch
				$response .= $this->throw_error('noRecordsMatch', 'No records match the request '
					. 'parameters');
			} else {
				foreach ($rows as $publication) {
					$publicationLookupArray[$publication['publication_id']] =
						$publication['oai_identifier'];
				}
			}

			// getList seems to prepend a space to each value, why?
			$rightsLookupArray = $library_rights_handler->getList();
			foreach($rightsLookupArray as $key => &$value) {
				$value = trim($value);
			}
			// mimetypes need mimetypeid and name (extension might be better?) only
			//$mimetype_list = include ICMS_ROOT_PATH . '/class/mimetypes.inc.php';
			$sql = $result = $row = $rows = '';
			$sql = "SELECT `mimetypeid`, `name` from " . $xoopsDB->prefix('system_mimetype');
			if (!empty($rows)) {
				foreach ($rows as $mimetype)
					$formatLookupArray[$mimetype['mimetypeid']] = $mimetype['name'];
			}

			// generate the headers and spit out the xml for each record
			foreach($publicationArray as $publicationObj) {
				$publication = $publicationObj->toArray();

				// lookup human readable equivalents of the keys

				// dc_identifier - a URL back to the original resource / source archive
				$publication['identifier'] = $publication['itemUrl'];

				// date
				$publication['submission_time'] = strtotime($publication['submission_time']);

				// format
				if ($publication['format']) {
					$publication['format'] = $publicationObj->get_mimetype();
				}

				if ($publication['creator']) {
					$creators = $publicationObj->getVar('creator', 'e');
					$publication['creator'] = explode('|', $creators);
				}

				// contributor
				if ($publication['contributor']) {
					$contributors = $publicationObj->getVar('contributor', 'e');
					$publication['contributor'] = explode('|', $contributors);
				}

				// source (URL to source collection)
				$source = $publicationObj->getVar('source', 'e');
				if (!empty($source)) {
					$publication['source'] = $url . $publicationObj->getVar('source', 'e');
				} else {
					unset($publication['source']);
				}

				// rights
				if (!empty($publication['rights'])) {
					$publication['rights'] = $rightsLookupArray[
							$publicationObj->getVar('rights', 'e')];
				}

				// relation - these are determined by looking for records with a common source
				// (in the case of a collection, the source is itself)
				// DISABLED: Relation is very resource-intensive to calculate, so have only enabled
				// it for Collection-type publications.

				if ($publication['type'] == 'Collection') {

					$source = $publication['publication_id'];

					// search for publications with the same source
					$criteria = icms_buildCriteria(array('source' => $source, 'status' => '1',
						'federated' => 1));
					$relatedList = $library_publication_handler->getList($criteria);

					// delete the current publication from the list to avoid duplicates
					unset($relatedList[$publication['publication_id']]);

					// prepare a list of related work URLs
					$related = array();

					foreach($relatedList as $key => $value) {
						$related[] = $url . $key;
					}

					$publication['relation'] = $related;
					unset($related);
				}

				// language - ISO 639-1 two letter codes
				if ($publication['language']) {
					$publication['language'] = $publicationObj->getVar('language', 'e');
				}

				$records .= $this->record_to_xml($publication);
			}
		}
		if ($haveResults == true) {
			$response .= '<ListRecords>' . $records . '</ListRecords>';
		} else {
			// if no publications are found, throw a noRecordsMatch error
			$response .= $this->throw_error('noRecordsMatch', 'No records match the request '
				. 'parameters');
		}
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	/**
	 * Returns a fixed response (error message) to any non-recognised verb parameter
	 * @return string
	 */
	public function BadVerb() {
		$response = '';

		$response = $this->oai_header();
		$response .= '<request>' . $this->getVar('base_url') . '</request>';
		$response .= $this->throw_error('badVerb', 'Bad verb, request not compliant with '
			. 'OAIPMH specification');
		$response .= $this->oai_footer();

		// check if the character encoding is UTF-8 (required by XML), if not, convert it
		$response = $this->data_to_utf8($response);
		return $response;
	}

	////////// END OPEN ARCHIVES INITIATIVE API //////////

	// UTILITIES

	/**
	 * Utility function for displaying error messages to bad OAIPMH requests
	 *
	 * @param string $error
	 * @param string $message
	 * @return string
	 */
	public function throw_error($error, $message) {

		$response = '';

		switch ($error) {
			case "badArgument":
				$response = '<error code="badArgument">' . $message . '</error>';
				break;

			case "cannotDisseminateFormat":
				$response = '<error code="cannotDisseminateFormat">' . $message . '</error>';
				break;

			case "idDoesNotExist":
				$response = '<error code="idDoesNotExist">' . $message . '</error>';
				break;

			case "badResumptionToken":
				$response = '<error code="badResumptionToken">' . $message . '</error>';
				break;

			case "noSetHierarchy":
				$response = '<error code="noSetHierarchy">' . $message . '</error>';
				break;

			case "noMetadataFormats":
				$response = '<error code="noMetadataFormats">' . $message . '</error>';
				break;

			case "noRecordsMatch":
				$response = '<error code="noRecordsMatch">' . $message . '</error>';
				break;

			case "badVerb":
				$response = '<error code="badVerb">' . $message . '</error>';
				break;
		}
		return $response;
	}

	/**
	 * Template for converting a single database record to OAIPMH spec XML
	 *
	 * Generates the output for each record.
	 */
	public function record_to_xml($record) {

		// initialise
		$xml = $datestamp = '';
		$dublin_core_fields = array(
			'title',
			'identifier',
			'creator',
			'contributor',
			'date',
			'type',
			'format',
			'relation',
			'description',
			'subject',
			'language',
			'publisher',
			'coverage',
			'rights',
			'source');

		// adjust the datestamp to match the OAI spec
		$datestamp = $record['submission_time'];
		$datestamp = $this->handler->timestamp_to_oaipmh_time($record['submission_time']);

		// remove any html tags from the description field of the record
		$record['description'] = strip_tags($record['description']);

		// build and populate template
		$xml .= '<record>';
		$xml .= '<header>';
		$xml .= '<identifier>' . $record['oai_identifier'] . '</identifier>';
		$xml .= '<datestamp>' . $datestamp . '</datestamp>';
		$xml .= '</header>';
		$xml .= '<metadata>';
		$xml .= '<oai_dc:dc
			xmlns:oai_dc="http://www.openarchives.org/OAI/2.0/oai_dc/"
			xmlns:dc="http://purl.org/dc/elements/1.1/"
			xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
			xsi:schemaLocation="http://www.openarchives.org/OAI/2.0/oai_dc/
			http://www.openarchives.org/OAI/2.0/oai_dc.xsd">';

		////////// iterate through optional and repeatable Dublic Core fields //////////

		foreach($dublin_core_fields as $dc_field) {
			$dc_value = '';
			$dc_value = $record[$dc_field];
			if (!empty($dc_value)) {
				if (is_array($dc_value)) {
					foreach($dc_value as $subvalue) {
						$xml .= '<dc:' . $dc_field . '>' . $subvalue . '</dc:' . $dc_field . '>';
					}
				} else {
					$xml .= '<dc:' . $dc_field . '>' . $dc_value . '</dc:' . $dc_field . '>';
				}
			}
		}
		$xml .= '</oai_dc:dc>';
		$xml .= '</metadata>';
		$xml .= '</record>';
		return $xml;
	}

	/**
	 * Checks that a requested time range does not occur before the repository's earliest timestamp
	 *
	 * @param string $time
	 * @return bool
	 */

	public function not_before_earliest_datestamp($time) {
		$request_date_stamp = $time;
		$earliest_date_stamp = $this->getEarliestDateStamp();

		$request_date_stamp = str_replace('Z', '', $request_date_stamp);
		$request_date_stamp = str_replace('T', ' ', $request_date_stamp);
		$request_date_stamp = strtotime($request_date_stamp);
		$earliest_date_stamp = str_replace('Z', '', $earliest_date_stamp);
		$earliest_date_stamp = str_replace('T', ' ', $earliest_date_stamp);
		$earliest_date_stamp = strtotime($earliest_date_stamp);

		if ($request_date_stamp >= $earliest_date_stamp) {
			$validity = true;
		} else {
			$validity = false;
		}
		return $validity;
	}

	// validate datetime syntax, also checks data does not exceed reasonable values

	/**
	 * Validates the datetime syntax, also checks that data does not exceed reasonable values
	 *
	 * @param string $time
	 * @return bool
	 */
	public function validate_datetime($time) {
		$valid = true;

		if (!preg_match("/^([1-3][0-9]{3,3})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][0-9]|3[0-1])\s([0-1]
            [0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9])$/", $time)) {
			$valid = false;
		}

		////////// EXPLANATION OF THE DATETIME VALIDATION REGEX //////////
		//
		// This is effectively the same as the readable expression:
		// (1000-3999)-(1-12)-(1-31) (00-24):(00-59):(00-59)
		//
		// Broken down:
		// Year: ([1-3][0-9]{3,3}) Matches 1000 to 3999, easily changed.
		// Month: (0?[1-9]|1[0-2]) Matches 1 to 12
		// Day: (0?[1-9]|[1-2][1-9]|3[0-1]) Matches 1 to 31
		// Hour: ([0-1][0-9]|2[0-4]) Matches 00 to 24
		// Minute: ([0-5][0-9]) Matches 00 to 59
		// Second: ([0-5][0-9]) Same as above.
		//
		// Notes:
		// The "?" allows for the preceding digit to be optional,
		// ie: "2008-1-22" and "2008-01-22" are both valid.
		// The "^" denies input before the year, so " 2008" or "x2008" is invalid.
		// The "$" works to deny ending input.
		//
		// From: http://www.webdeveloper.com/forum/showthread.php?t=178277
		//
		////////////////////////////////////////////////////////////////

		return $valid;
	}

	/**
	 * Forces the XML response to be sent in UTF8, converts it in some other character set.
	 *
	 * @param <type> $data
	 * @return <type>
	 */
	public function data_to_utf8($data) {
		$converted = '';

		if (_CHARSET !== 'utf-8') {
			$charset = strtoupper(_CHARSET);
			$converted = iconv($charset, 'UTF-8', $data);
		} else {
			return $data;
		}
	}
}

class LibraryArchiveHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		$this->IcmsPersistableObjectHandler($db, 'archive', 'archive_id', 'repository_name',
			'base_url', 'library');
	}

	// INITIALISE DEFAULT ARCHIVE VALUES BECAUSE MOST OF THESE ARE FIXED

	/**
	 * Returns the only metadataprefix supported by this repository (oai_dc)
	 * @return string
	 */
	public function setMetadataPrefix() {
		return 'oai_dc';
	}

	/**
	 * One of several functions used to build a unique identifier for each record
	 * @return string
	 */
	public function setNamespace() {
		$namespace = ICMS_URL;
		$namespace = str_replace('http://', '', $namespace);
		$namespace = str_replace('https://', '', $namespace);
		$namespace = str_replace('www.', '', $namespace);
		return $namespace;
	}

	/**
	 * Returns the timestamp granularity supported by this repository in OAIPMH datetime format
	 *
	 * This implementation supports seconds-level granularity, which is the maximum.
	 *
	 * @return string
	 */
	public function setGranularity() {
		return 'YYYY-MM-DDThh:mm:ssZ';
	}

	/**
	 * Returns whether this repository supports deleted record tracking (no)
	 *
	 * @return string
	 */
	public function setDeletedRecord() {
		return 'no';
	}

	/**
	 * Sets the earliest datestamp attribute for this repository, using the Unix epoch as default
	 *
	 * If there are records in the repository, the oldest datestamp will be reported as that of
	 * the oldest record. For safety reasons, this will include offline and non-federated records
	 * so if a records online or federation status changes, nothing will be broken. If there are
	 * no records, the beginning of the Unix epoch will be used as the earliest datestamp value.
	 *
	 * @return string
	 */
	public function setEarliestDatestamp() {
		$library_publication_handler = icms_getModuleHandler('publication',
			basename(dirname(dirname(__FILE__))), 'library');
		$criteria = new CriteriaCompo();
		$criteria->setSort('submission_time');
		$criteria->setOrder('ASC');
		$criteria->setLimit(1);
		$publicationObj = $library_publication_handler->getObjects($criteria);
		$oldest_publication = array_shift($publicationObj);
		if (!empty($oldest_publication)) {
			$earliest_timestamp = $this->timestamp_to_oaipmh_time(
				$oldest_publication->getVar('submission_time', 'e'));
			return $earliest_timestamp;
		} else {
			return '1970-01-01T00:00:00Z';
		}
	}

	/**
	 * Converts a timestamp into the OAIPMH datetime format
	 *
	 * @param string $timestamp
	 * @return string
	 */
	public function timestamp_to_oaipmh_time($timestamp) {
		$format = 'Y-m-d\TH:i:s\Z';
		$oai_date_time = date($format, $timestamp);
		return $oai_date_time;
	}

	/**
	 * Returns the repository's admin email address, as per the OAIPMH spec requirements
	 *
	 * @global mixed $icmsConfig
	 * @return string
	 */
	public function setAdminEmail() {
		global $icmsConfig;
		return $icmsConfig['adminmail'];
	}

	/**
	 * Returns the OAIPMH version in use by this repository (2.0, the current version)
	 * @return string
	 */
	public function setProtocolVersion() {
		return '2.0';
	}

	/**
	 * Returns the name of the repository, default value is the site name in global preferences.
	 *
	 * A different respository name can be set within the Archive object.
	 *
	 * @global mixed $icmsConfig
	 * @return string
	 */
	public function setRepositoryName() {
		global $icmsConfig;
		$repository_name = $icmsConfig['sitename'] . ' - ' . $icmsConfig['slogan'];
		return $repository_name;
	}

	/**
	 * Returns the base URL, which is the URL against which OAIPMH requests should be sent
	 *
	 * @global mixed $icmsConfig
	 * @global mixed $icmsModule
	 * @return string
	 */
	public function setBaseUrl() {
		global $icmsConfig, $icmsModule;
		$base_url = ICMS_URL . '/modules/' . $icmsModule->getVar('dirname') . '/oaipmh_target.php';
		return $base_url;
	}

	/**
	 * Returns the compression scheme(s) supported by this repository (only gzip)
	 *
	 * @return string
	 */
	public function setCompression() {
		return 'gzip';
	}
}