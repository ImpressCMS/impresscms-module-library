<?php

/**
 * Classes responsible for managing Library publication objects
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// including the IcmsPersistableSeoObject
include_once ICMS_ROOT_PATH . '/kernel/icmspersistableseoobject.php';
include_once(ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/include/functions.php');

class LibraryPublication extends IcmsPersistableSeoObject {

	/**
	 * Constructor
	 *
	 * @param object $handler LibraryPostHandler object
	 */
	public function __construct(& $handler) {
		global $libraryConfig;
		global $icmsUser;

		$this->IcmsPersistableObject($handler);

		$this->quickInitVar('type', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('publication_id', XOBJ_DTYPE_INT, true);
		$this->quickInitVar('title', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('identifier', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('creator', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('contributor', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('description', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('extended_text', XOBJ_DTYPE_TXTAREA, false);
		$this->quickInitVar('format', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('file_size', XOBJ_DTYPE_INT, false);
		$this->quickInitVar('cover', XOBJ_DTYPE_IMAGE, false);
		$this->quickInitVar('date', XOBJ_DTYPE_STIME, false);
		$this->quickInitVar('publisher', XOBJ_DTYPE_TXTBOX, false);
		$this->initNonPersistableVar('categories', XOBJ_DTYPE_INT, 'category',
			false, false, false, true);
		$this->quickInitVar('source', XOBJ_DTYPE_TXTBOX, false);
		$this->quickInitVar('language', XOBJ_DTYPE_TXTBOX, false, false, false,
			$libraryConfig['default_language']);
		$this->quickInitVar('rights', XOBJ_DTYPE_TXTBOX, true);
		$this->quickInitVar('compact_view', XOBJ_DTYPE_INT, false, false, false, 0);
		$this->quickInitVar('status', XOBJ_DTYPE_INT, true, false, false, 1);
		$this->quickInitVar('federated', XOBJ_DTYPE_INT, true, false, false,
			$libraryConfig['library_default_federation']);
		$this->quickInitVar('submission_time', XOBJ_DTYPE_LTIME, true);
		$this->quickInitVar('submitter', XOBJ_DTYPE_INT, true);
		$this->initCommonVar('counter');
		$this->initCommonVar('dohtml', false, 1);
		$this->initCommonVar('dobr');
		$this->quickInitVar ('publication_notification_sent', XOBJ_DTYPE_INT);
		$this->quickInitVar('oai_identifier', XOBJ_DTYPE_TXTBOX, true, false, false,
			$this->handler->setOaiId());

		$this->IcmsPersistableSeoObject();

		$this->setControl('description', 'dhtmltextarea');
		$this->setControl('extended_text', 'dhtmltextarea');

		$this->setControl('type', array(
			'name' => 'select',
			'itemHandler' => 'publication',
			'method' => 'getType',
			'module' => 'library',
			'onSelect' => 'submit'));

		$this->setControl('format', array(
			'itemHandler' => 'publication',
			'method' => 'getModuleMimeTypes',
			'module' => 'library'));

		$this->setControl('categories', array(
			'name' => 'select_multi',
			'itemHandler' => 'publication',
			'method' => 'getCategoryOptions',
			'module' => 'library'));

		$this->setControl('rights', array(
			'itemHandler' => 'rights',
			'method' => 'getRights',
			'module' => 'sprockets'));

		$this->setControl('source', array(
			'itemHandler' => 'publication',
			'method' => 'getCollections',
			'module' => 'library'));

		$this->setControl('language', array(
			'name' => 'select',
			'itemHandler' => 'publication',
			'method' => 'getLanguage',
			'module' => 'library'));

		$this->setControl('submitter', 'user');
		$this->setControl('compact_view', 'yesno');
		$this->setControl('status', 'yesno');
		$this->setControl('federated', 'yesno');

		// all publications can have a cover now
		$this->setControl('cover', array('name' => 'image'));
		$url = ICMS_URL . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$path = ICMS_ROOT_PATH . '/uploads/' . basename(dirname(dirname(__FILE__))) . '/';
		$this->setImageDir($url, $path);
		
		// hide the compact_view field, it is only used in publications of type collection
		$this->doHideFieldFromForm('compact_view');

		// force html and don't allow user to change; necessary for RSS feed integrity
		$this->doHideFieldFromForm('dohtml');

		// hide the notification status field, its for internal use only
		$this->hideFieldFromForm ('publication_notification_sent');
		$this->hideFieldFromSingleView ('publication_notification_sent');

		// make the oai_identifier read only for OAIPMH archive integrity purposes
		// since external sites may harvest this data, the identifier has to remain
		// constant so that they can avoid duplicating records
		$this->doMakeFieldreadOnly('oai_identifier');
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
		if ($format == 's' && in_array($key, array ('creator', 'contributor', 'date', 'file_size',
				'source', 'rights', 'language', 'status', 'federated', 'submitter', 'format'))) {
			return call_user_func(array ($this, $key));
		}
		return parent :: getVar($key, $format);
	}

	/**
	 * Load categories linked to this post
	 *
	 * @return void
	 */
	function loadCategories() {
		$imtaggingModule = icms_getModuleInfo('imtagging');
		if ($imtaggingModule) {
			$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
			$ret = $imtagging_category_link_handler->getCategoriesForObject($this->id(), $this->handler);
			$this->setVar('categories', $ret);
		}
	}

	function categories() {
		$ret = '';
		$imtaggingModule = icms_getModuleInfo('imtagging');
		if ($imtaggingModule) {
			$ret = $this->getVar('categories', 'n');
			$ret = $this->vars['categories']['value'];
		}
		if (is_array($ret)) {
			return $ret;
		} else {
			(int)$ret > 0 ? array((int)$ret) : false;
		}
	}

	/*
     * Converts status field to clickable icon that can change status
	*/
	public function status() {
		$button = '';
		$type = $this->getVar('type', 'e');
		$status = $this->getVar('status', 'e');

		if ($type == 'Collection') {
			$button = '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/collection.php?publication_id=' . $this->getVar('publication_id')
				. '&amp;op=changeStatus">';
		} else {
			$button = '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/publication.php?publication_id=' . $this->getVar('publication_id')
				. '&amp;op=changeStatus">';
		}
		if ($status == false) {
			$button .= '<img src="../images/button_cancel.png" alt="Offline" title="Offline" /></a>';
		} else {
			$button .= '<img src="../images/button_ok.png" alt="Online" title="Online" /></a>';
		}
		return $button;
	}

	/*
     * Converts federated field to human readable value
	*/

	public function federated() {
		$button = '';
		$type = $this->getVar('type', 'e');
		$federated = $this->getVar('federated', 'e');

		if ($type == 'Collection') {
			$button = '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/collection.php?publication_id=' . $this->getVar('publication_id')
				. '&amp;op=changeFederated">';
		} else {
			$button = '<a href="' . ICMS_URL . '/modules/' . basename(dirname(dirname(__FILE__)))
				. '/admin/publication.php?publication_id=' . $this->getVar('publication_id')
				. '&amp;op=changeFederated">';
		}
		if ($federated == false) {
			$button .= '<img src="../images/button_cancel.png" alt="Offline" title="Not Federated" /></a>';
		} else {
			$button .= '<img src="../images/button_ok.png" alt="Online" title="Federated" /></a>';
		}
		return $button;
	}

	/*
     * Converts mimetype id to human readable value (extension)
	*/
	public function format() {
		if ($this->getVar('format', 'e') !== 0) {
		$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');
		$mimetypeObj = $system_mimetype_handler->get($this->getVar('format', 'e'));
		$mimetype = $mimetypeObj->getVar('extension');
		return $mimetype;
		} else {
			return false;
		}
	}

	/*
     * Converts user id to human readable user name
	*/
	public function submitter() {
		return library_getLinkedUnameFromId($this->getVar('submitter', 'e'));
	}

	/*
     * Converts pipe-delimited creator field to comma separated for user side presentation
	*/
	public function creator() {
		$creator = $this->getVar('creator', 'e');
		return str_replace("|", ", ",  $creator);
	}

	/*
     * Converts pipe-delimited contributor field to comma separated for user side presentation
	*/
	public function contributor() {
		$contributor = $this->getVar('contributor', 'e');
		return str_replace("|", ", ", $contributor);
	}

	/*
     * Formats the date in a sane (non-American) way
	*/
	public function date() {
		$date = $this->getVar('date', 'e');
		$date = date('j/m/Y', $date);
		return $date;
	}

	/*
     * Converts the source (publication/collection) id to a human readable title
	*/
	public function source() {
		$source = $this->getVar('source', 'e');
		$library_publication_handler = icms_getModuleHandler('publication',
			basename(dirname(dirname(__FILE__))), 'library');
		$publication_object = $library_publication_handler->get($source);
		$source = $publication_object->title();
		$source_link = '<a href="./publication.php?publication_id='
			. $publication_object->id() . '">' . $source . '</a>';
		return $source_link;
	}

	/*
     * Converts the rights id to a human readable title
	*/
	public function rights() {
		$sprocketsModule = icms_getModuleInfo('sprockets');
		
		if ($sprocketsModule) {
			$rights_id = $this->getVar('rights', 'e');
			$sprockets_rights_handler = icms_getModuleHandler('rights',
				$sprocketsModule->dirname(), 'sprockets');
			$rights_object = $sprockets_rights_handler->get($rights_id);
			$rights = $rights_object->getItemLink();
			return $rights;
		} else {
			return false;
		}
	}

	/*
     * Converts the rights id to human readable title without link, for use in OAIPMH response
	*/
	public function rightsName($rights_id) {
		
		$sprocketsModule = icms_getModuleInfo('sprockets');
		$sprockets_rights_handler = icms_getModuleHandler('rights',
			$sprocketsModule->dirname(), 'sprockets');
		$rights_object = $sprockets_rights_handler->get($rights_id);
		$rights_name = $rights_object->getVar('title', 'e');
		return $rights_name;
	}

	/*
     * Converts the language key to a human readable title
	*/
	public function language() {
		$language_key = $this->getVar('language', 'e');
		$language_list = $this->handler->getLanguage();
		return $language_list[$language_key];
	}

	/*
     * Utility to convert bytes to a more readable form (KB, MB etc)
	*/
	public function file_size() {
		$unit = $value = $output = '';
		$bytes = $this->getVar('file_size', 'e');

		if ($bytes == 0 || $bytes < 1024) {
			$unit = ' bytes';
			$value = $bytes;
		} elseif ($bytes > 1023 && $bytes < 1048576) {
			$unit = ' KB';
			$value = ($bytes / 1024);
		} elseif ($bytes > 1048575 && $bytes < 1073741824) {
			$unit = ' MB';
			$value = ($bytes / 1048576);
		} else {
			$unit = ' GB';
			$value = ($bytes / 1073741824);
		}
		$value = round($value, 2);
		$output = $value . ' ' . $unit;
		return $output;
	}

	/*
     * Convert mimetype id to human readable name (extension)
	*/
	public function get_format_name() {
		$format = $this->getVar('format', 'e');
		if (!empty($format)) {
			$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');
			$mimetypeObject = $system_mimetype_handler->get($format);
			$format = '.' . $mimetypeObject->getVar('extension');
			return $format;
		} else {
			return false;
		}
	}

	/*
     * Returns a list of mimetypes but NOT using the core mimetype handler
	*/
	public function get_mimetype() {
		// there is a core file that has a nice list of mimetypes
		// however some library clients don't observe the standard
		// may need to revisit this in light of prevailing library client behaviour
		$mimetype_list = include ICMS_ROOT_PATH . '/class/mimetypes.inc.php';

		// lookup the format extension using the system_mimetype id
		$format_extension = $this->get_format_name();

		// should probably handle exception where the mimetype isn't in the list
		// should be a rare event though

		$mimetype = $mimetype_list[$format_extension];
		if ($mimetype) {
			return $mimetype;
		} else {
			return; // null
		}
	}

	/**
	 * Returns a html snippet for inserting an RSS feed button/link into a smarty template variable
	 *
	 * @return string
	 */
	public function get_rss_button() {
		return '<a href="./rss.php?publication_id=' . $this->id()
			. '" title="' . _CO_LIBRARY_PUBLICATION_ENCLOSURES . '">'
			. '<img src="' . './images/rss.png" alt="RSS"' . ' /></a>';
	}

	/*
     * Adds a parameter (m3u_flag = 1) to a publication URL that will trigger the file to be streamed
	*/
	public function get_m3u($itemUrl) {
		if (!empty($itemUrl)) {
			return $itemUrl . '&amp;m3u_flag=1';
		} else {
			return null;
		}
	}

	public function stream_audio() {
		$identifier = $type = '';
		$identifier = $this->getVar('identifier');
		$type = $this->getVar('type');
		if (!empty ($identifier) && $type == 'Sound') {

			// send playlist headers to the browser, followed by the audio file URL as contents
			// the iso-8859-1 charset is standard for m3u, do not insert breaks in this line of code!
			header('Content-Type: audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls; charset=iso-8859-1');
			header("Content-Disposition:inline;filename=stream_publication.m3u");

			// there is a less widely recognised m3u8 playlist for utf-8, but support is probably
			// less widespread. if you want to use that then substitute the two code lines below
			// for the two above
			//
			// header ('Content-Type: audio/x-mpegurl audio/mpeg-url application/x-winamp-playlist audio/scpls audio/x-scpls; charset=utf-8');
			// header("Content-Disposition:inline;filename=stream_publication.m3u8");

			echo $identifier;
			exit;
		}
	}

	/**
	 * Prepare publication for user-side display and contextualise fields according to publication type
	 *
	 * Contextualisation is about adjusting the standard form fields so that they make sense for
	 * different kinds of publications. For example, only soundtracks should have streaming links,
	 * image publications should be displayed at larger sizes than cover art, collections should know
	 * how many items are inside them etc etc. Publications are assigned to different sub-templates
	 * here as well to help present them appropriately on the user side.
	 *
	 * @param object $publication LibraryPublication object
	 */
	public function prepare_publication_for_display($single_view = true) {

		global $libraryConfig;

		$library_publication_handler = icms_getModuleHandler('publication',
			basename(dirname(dirname(__FILE__))), 'library');

		if (!is_array($this)) {
			$publication = $this->toArray();
		}
		
		$publication['counter']++;

		// if there is no identifier, then format and file size should not be displayed unless
		// it is an image type
		if (!$publication['identifier'] && $publication['type'] !== 'Image') {
			unset($publication['format']);
			unset($publication['file_size']);
		}

		// if source field is empty unset it
		$source = $this->getVar('source', 'e');
		if(empty($source)) {
			unset($publication['source']);
		}

		if (!empty($publication['format'])) {
			$publication['format'] = $this->getVar ('format');
		}

		// link title to target file in single view, itemUrl in multiple view
		if ($single_view) {
			if (empty($publication['identifier'])) {
				$publication['title'] = $this->getVar('title', 'e');
			} else {
				$publication['title'] = '<a href="' . $publication['identifier'] . '">'
					. $publication['title'] . '</a>';
				$publication['download'] = '<a href="' . $publication['identifier']
					. '" title="' . _CO_LIBRARY_PUBLICATION_DOWNLOAD . '"><img src="'
					. LIBRARY_IMAGES_URL . 'download.png" alt="Download publication" /></a>';
			}
		} else {
			$publication['title'] = $publication['itemLink'];
			if (!empty($publication['identifier'])) {
				$publication['download'] = '<a href="' . $publication['identifier']
					. '" title="' . _CO_LIBRARY_PUBLICATION_DOWNLOAD . '"><img src="'
					. LIBRARY_IMAGES_URL . 'download.png" alt="Download publication" /></a>';
			}
		}

		// in single view, the extended text is displayed in preference to the description
		if ($single_view) {
			// need to strip tags and trim extended_text to check if its empty
			// because tinymce still inserts <p> tags and spaces even if the form was empty
			$stripped_text = trim(strip_tags($publication['extended_text']));
			if (!empty($stripped_text)) {
				$publication['description'] = $publication['extended_text'];
			}
		}

		// change language key to human readable
		if ($publication['language']) {
			$publication['language'] = $this->getVar('language', 's');
		}

		// prepare cover for display
		// the $libraryConfig argument (screenshot_width, thumbnail_width) toggles image width
		if (!empty($publication['cover'])) {
			$publication['cover_path'] = '/uploads/' . basename(dirname(dirname(__FILE__)))
				. '/publication/' . $publication['cover'];
			if ($single_view) {
				$publication['cover_width'] = $libraryConfig['screenshot_width'];
				$publication['cover_height'] = $libraryConfig['screenshot_height'];
				if (empty($publication['identifier'])) {
					unset($publication['cover_link']);
				} else {
					$publication['cover_link'] = $publication['identifier'];
				}
			} else {
				$publication['cover_width'] = $libraryConfig['thumbnail_width'];
				$publication['cover_height'] = $libraryConfig['thumbnail_height'];
				$publication['cover_link'] = $publication['itemUrl'];
			}
		}

		// contextualise fields according to publication type
		switch ($publication['type']) {
			case "Text":
				break;

			case "Image":
				if ($single_view) {
					$publication['cover_width'] = $libraryConfig['image_width'];
					$publication['cover_height'] = $libraryConfig['image_height'];
				} else {
					$publication['cover_width'] = $libraryConfig['thumbnail_width'];
					$publication['cover_height'] = $libraryConfig['thumbnail_height'];
				}
				if ($publication['language']) {
					unset($publication['language']);
				}
				break;

			case "MovingImage":
				break;

			case "Sound":
			// add streaming link if there is an identifier field
				if (!empty($publication['identifier'])) {
					$publication['streaming'] = '<a href="'
						. $this->get_m3u($publication['itemUrl'])
						. '" title="' . _CO_LIBRARY_PUBLICATION_PLAY . '">'
						. '<img src="' . LIBRARY_IMAGES_URL
						. 'stream.png" alt="Stream publication" /></a>';
				}
				break;

			case "Collection":
				// add an item count if its a collection type
				$criteria = icms_buildCriteria(array(
					'source' => $publication['publication_id'],
					'status' => '1')
				);
				$publication['item_count'] = $library_publication_handler->getCount($criteria);
				$publication['rss_button'] = $this->get_rss_button();
				break;
		}

		// assign subtemplate appropriate to this resource type
		$publication['subtemplate'] = $this->assign_subtemplate();
		return $publication;
	}

	/**
	 * Determines what subtemplate to use to display the publication, based on its type.
	 */
	public function assign_subtemplate() {
		$publication_type = $this->getVar('type');

		switch ($publication_type) {

			case "Text":
				return 'db:library_text.html';
				break;

			case "Sound":
				return 'db:library_sound.html';
				break;

			case "Image":
				return 'db:library_image.html';
				break;

			case "MovingImage":
				return 'db:library_movingimage.html';
				break;

			case "Dataset":
				return 'db:library_dataset.html';
				break;

			case "Software":
				return 'db:library_software.html';
				break;

			case "Collection":
				return 'db:library_collection.html';
				break;

			default:
				return 'db:library_text.html';
		}
	}

	/*
     * Sends notifications to subscribers when a new publication is published, called by afterSave()
	*/
	function sendNotifPublicationPublished() {
		$item_id = $this->id();
		$source_id = $this->getVar('source', 'e');
		$module_handler = xoops_getHandler('module');
		$module = $module_handler->getByDirname('library');
		$module_id = $module->getVar('mid');
		$notification_handler = xoops_getHandler ('notification');

		$tags = array();
		$tags['ITEM_TITLE'] = $this->title();
		$tags['ITEM_URL'] = $this->getItemLink(true);
		$tags['PUBLICATION_NAME'] = $this->getVar('source', 's');

		// global notification
		$notification_handler->triggerEvent('global', 0, 'publication_published', $tags,
				array(), $module_id, 0);

		// collection-specific notification
		/*$notification_handler->triggerEvent('collection', $source_id,
            'programme_soundtrack_published', $tags, array(), $module_id, 0);*/
	}
}

class LibraryPublicationHandler extends IcmsPersistableObjectHandler {

	/**
	 * Constructor
	 */
	public function __construct(& $db) {
		global $libraryConfig;
		$this->IcmsPersistableObjectHandler($db, 'publication', 'publication_id', 'title',
			'description', 'library');

		// enable image upload for cover art purposes. This should use the core mimetype manager
		// when its functionality improves.
		$mimetypes = array('image/jpeg', 'image/png', 'image/gif');
		$this->enableUpload($mimetypes, $libraryConfig['image_file_size'],
			$libraryConfig['image_upload_width'], $libraryConfig['image_upload_height']);
	}
		
	/**
	 * Prepare collection for user-side display and contextualise fields according to type
	 *
	 * Contextualisation is about adjusting the standard form fields so that they make sense for
	 * different kinds of publications. For example, only soundtracks should have streaming links,
	 * image publications should be displayed at larger sizes than cover art, collections should know
	 * how many items are inside them etc etc. Publications are assigned to different sub-templates
	 * here as well to help present them appropriately on the user side.
	 *
	 * @param array of $publication LibraryPublication objects
	 * @return array
	 */
	
	public function prepare_collection_for_display($raw_publication_array) {

		global $libraryConfig;

		// get handlers to build buffers
		// $library_publication_handler = icms_getModuleHandler('publication',
		//	basename(dirname(dirname(__FILE__))), 'library');

		foreach ($raw_publication_array as $value) {
			if (!is_array($value)) {
				$publication = $this->toArray();
			}
		}
		
		$publication['counter']++;

		// if there is no identifier, then format and file size should not be displayed unless
		// it is an image type
		if (!$publication['identifier'] && $publication['type'] !== 'Image') {
			unset($publication['format']);
			unset($publication['file_size']);
		}

		// if source field is empty unset it
		$source = $this->getVar('source', 'e');
		if(empty($source)) {
			unset($publication['source']);
		}

		if (!empty($publication['format'])) {
			$publication['format'] = $this->getVar ('format');
		}

		// link title to target file in single view, itemUrl in multiple view
		if ($single_view) {
			if (empty($publication['identifier'])) {
				$publication['title'] = $this->getVar('title', 'e');
			} else {
				$publication['title'] = '<a href="' . $publication['identifier'] . '">'
					. $publication['title'] . '</a>';
				$publication['download'] = '<a href="' . $publication['identifier']
					. '" title="' . _CO_LIBRARY_PUBLICATION_DOWNLOAD . '"><img src="'
					. LIBRARY_IMAGES_URL . 'download.png" alt="Download publication" /></a>';
			}
		} else {
			$publication['title'] = $publication['itemLink'];
			if (!empty($publication['identifier'])) {
				$publication['download'] = '<a href="' . $publication['identifier']
					. '" title="' . _CO_LIBRARY_PUBLICATION_DOWNLOAD . '"><img src="'
					. LIBRARY_IMAGES_URL . 'download.png" alt="Download publication" /></a>';
			}
		}

		// in single view, the extended text is displayed in preference to the description
		if ($single_view) {
			// need to strip tags and trim extended_text to check if its empty
			// because tinymce still inserts <p> tags and spaces even if the form was empty
			$stripped_text = trim(strip_tags($publication['extended_text']));
			if (!empty($stripped_text)) {
				$publication['description'] = $publication['extended_text'];
			}
		}

		// change language key to human readable
		if ($publication['language']) {
			$publication['language'] = $this->getVar('language', 's');
		}

		// prepare cover for display
		// the $libraryConfig argument (screenshot_width, thumbnail_width) toggles image width
		if (!empty($publication['cover'])) {
			$publication['cover_path'] = '/uploads/' . basename(dirname(dirname(__FILE__)))
				. '/publication/' . $publication['cover'];
			if ($single_view) {
				$publication['cover_width'] = $libraryConfig['screenshot_width'];
				$publication['cover_height'] = $libraryConfig['screenshot_height'];
				if (empty($publication['identifier'])) {
					unset($publication['cover_link']);
				} else {
					$publication['cover_link'] = $publication['identifier'];
				}
			} else {
				$publication['cover_width'] = $libraryConfig['thumbnail_width'];
				$publication['cover_height'] = $libraryConfig['thumbnail_height'];
				$publication['cover_link'] = $publication['itemUrl'];
			}
		}

		// contextualise fields according to publication type
		switch ($publication['type']) {
			case "Text":
				break;

			case "Image":
				if ($single_view) {
					$publication['cover_width'] = $libraryConfig['image_width'];
					$publication['cover_height'] = $libraryConfig['image_height'];
				} else {
					$publication['cover_width'] = $libraryConfig['thumbnail_width'];
					$publication['cover_height'] = $libraryConfig['thumbnail_height'];
				}
				if ($publication['language']) {
					unset($publication['language']);
				}
				break;

			case "MovingImage":
				break;

			case "Sound":
			// add streaming link if there is an identifier field
				if (!empty($publication['identifier'])) {
					$publication['streaming'] = '<a href="'
						. $this->get_m3u($publication['itemUrl'])
						. '" title="' . _CO_LIBRARY_PUBLICATION_PLAY . '">'
						. '<img src="' . LIBRARY_IMAGES_URL
						. 'stream.png" alt="Stream publication" /></a>';
				}
				break;

			case "Collection":
				// add an item count if its a collection type
				$criteria = icms_buildCriteria(array(
					'source' => $publication['publication_id'],
					'status' => '1')
				);
				$publication['item_count'] = $library_publication_handler->getCount($criteria);
				$publication['rss_button'] = $this->get_rss_button();
				break;
		}

		// assign subtemplate appropriate to this resource type
		$publication['subtemplate'] = $this->assign_subtemplate();
		return $publication;
	}
	
	public function getPublications() {
		return $this->getList();
	}

	/**
	 * returns a list of publications of type collection
	 */
	public function getCollections() {
		$criteria = icms_buildCriteria(array('type' => 'Collection'));
		$collectionList = array(0 => '---') + $this->getList($criteria);
		return $collectionList;
	}

	/*
         * Provides global search functionality for Library module, only searches publications presently
	*/
	public function getPublicationsForSearch($queryarray, $andor, $limit, $offset, $userid) {
		$criteria = new CriteriaCompo();
		$criteria->setStart($offset);
		$criteria->setLimit($limit);

		if ($userid != 0) {
			$criteria->add(new Criteria('submitter', $userid));
		}
		if ($queryarray) {
			$criteriaKeywords = new CriteriaCompo();
			for ($i = 0; $i < count($queryarray); $i++) {
				$criteriaKeyword = new CriteriaCompo();
				$criteriaKeyword->add(new Criteria('title', '%' . $queryarray[$i] . '%',
					'LIKE'), 'OR');
				$criteriaKeyword->add(new Criteria('description', '%' . $queryarray[$i]
					. '%', 'LIKE'), 'OR');
				$criteriaKeywords->add($criteriaKeyword, $andor);
				unset ($criteriaKeyword);
			}
			$criteria->add($criteriaKeywords);
		}
		$criteria->add(new Criteria('status', true));
		return $this->getObjects($criteria, true, false);
	}

	/*
         * Returns a list of publications in a collection
	*/
	public function getCollectionPublications($start = 0, $limit = 10, $publication_id = false) {
		$criteria = $this->getLibraryCriteria($start, $limit, $publication_id);
		$ret = $this->getObjects($criteria, true, false);
		return $ret;
	}

	/**
     * Get options for a category select box with hierarchy from ImTagging module (recursive)
     *
     * @param XoopsObjectTree $tree
     * @param string $fieldName
     * @param int $key
     * @param string $prefix_curr
     * @param array $ret
     *
     * @return array
     */
    public function getCategoryOptions() {
		$options = '';
		$imtaggingModule = icms_getModuleInfo('imtagging');
		if ($imtaggingModule) {
			include_once(ICMS_ROOT_PATH . "/modules/imtagging/class/icmspersistabletree.php");

			$categories = array();
			$imtagging_category_handler = icms_getModuleHandler('category', 'imtagging');

			$categories = $imtagging_category_handler->getObjects();
			$category_tree = new IcmsPersistableTree($categories, 'category_id', 'category_pid');
			$options = $this->getOptions($category_tree, 'category_title', 0, '', $ret);
		}
		return $options;
	}

	public function getOptions($tree, $fieldName, $key, $prefix_curr = '', &$ret) {
		if ($key > 0) {
            $value = $tree->_tree[$key]['obj']->getVar($tree->_myId);
			$ret[$key] = $prefix_curr.$tree->_tree[$key]['obj']->getVar($fieldName);
            $prefix_curr .= "- ";
        }
        if (isset($tree->_tree[$key]['child']) && !empty($tree->_tree[$key]['child'])) {
            foreach ($tree->_tree[$key]['child'] as $childkey) {
                $this->getOptions($tree, $fieldName, $childkey, $prefix_curr, $ret);
            }
        }
        return $ret;
	}

	/**
	 * Returns the Dublin Core Metadata Initiative Type Vocabulary
	 *
	 * This is a standard vocabulary for describing the 'type' element of the DCMI metadata set.
	 * It is also a key aspect of this module. Every resource has a type assigned to it. The
	 * module uses the type field to decide what sub-template should be used to display the object.
	 * For example, and audio template will display streaming links, an image template will have
	 * a big centre area for displaying the image etc etc. Not all elements of the vocabulary
	 * are supported, only those that are are listed here. The type is also used to show/hide
	 * relevant fields on the data submission form, see admin/publication.php case changedField
	 * to see how the form is changed according to publication type.
	 *
	 * @return mixed
	 */
	public function getType() {
		$dcmi_type_vocabulary = array(
				'Text' => _CO_LIBRARY_TEXT, // text viewed on screen (eg news), downloadable publications
				'Sound' => _CO_LIBRARY_SOUND, // sound files
				'Image' => _CO_LIBRARY_IMAGE, // image files
				'MovingImage' => _CO_LIBRARY_MOVINGIMAGE, // downloadable file, support embedding of external viewer
				'Dataset' => _CO_LIBRARY_DATASET, // downloadable files
				'Software' => _CO_LIBRARY_SOFTWARE, // downloadable files
				'Collection' => _CO_LIBRARY_COLLECTION // aggregations of resource (eg. music album)
				// 'Event',
				// 'InteractiveResource',
				// 'PhysicalObject',
				// 'Service',
				// 'StillImage'
		);
		return $dcmi_type_vocabulary;
	}

	/*
	 * Returns a list of mimetypes that Library is authorised to use, hiding certain image types
	 *
	 * Used to make a dropdown list of audio mimetypes for use in the publication submission form.
	 * It's a bit ugly but it works, so long as the admin has made sensible choices about what
	 * mimetypes the module is authorised to use.
	*/
	public function getModuleMimeTypes() {
		global $xoopsDB;
		$moduleMimetypes = array();
		$moduleMimetypes[0] = '---';
		
		$criteria = new CriteriaCompo();
		$criteria->add(new Criteria('dirname', '%' . basename(dirname(dirname(__FILE__)))
			. '%', 'LIKE'));
		$sql = 'SELECT mimetypeid, dirname, extension FROM '
			. $xoopsDB->prefix('system_mimetype');
		$rows = $this->query($sql, $criteria);
		if (count($rows) > 0) {
			foreach($rows as $row) {
				$moduleMimetypes[$row['mimetypeid']] = $row['extension'];
			}
			asort($moduleMimetypes);
		}
		return $moduleMimetypes;
	}

	/*
         * Filters searches for publications
	*/
	public function getLibraryCriteria($start = 0, $limit = 10, $publication_id = false) {
		global $icmsUser;

		$criteria = new CriteriaCompo();
		if ($start) {
			$criteria->setStart($start);
		}
		if ($limit) {
			$criteria->setLimit(intval($limit));
		}
		if ($publication_id) {
			$criteria->add(new Criteria('source', $publication_id));
		}
		$criteria->setSort('submission_time');
		$criteria->setOrder('DESC');

		return $criteria;
	}

	/*
     * Used to assemble a unique oai_identifier for a record, as per the OAIPMH specs.
     *
     * The identifier is comprised of a metadata prefix, namespace (domain) and timestamp. It should
     * uniquely identify the record within a one-second resolution. You MUST NOT change the
     * oai_identifier once it is set, it is used to identify duplicate records that may be held
     * by multiple sites, and it prevents metadata harvesters from importing duplicates.
	*/
	public function getMetadataPrefix() {
		$metadataPrefix = 'oai';
		return $metadataPrefix;
	}

	/*
     * Used to assemble a unique oai_identifier for a record, as per the OAIPMH specs.
	*/
	public function getNamespace() {
		$namespace = '';
		$namespace = ICMS_URL;
		$namespace = str_replace('http://', '', $namespace);
		$namespace = str_replace('https://', '', $namespace);
		$namespace = str_replace('www.', '', $namespace);
		return $namespace;
	}

	/*
     * Used to assemble a unique identifier for a record, as per the OAIPMH specs
	*/
	public function setOaiId() {
		$id = '';
		$prefix = $this->getMetadataPrefix();
		$namespace = $this->getNamespace();
		$timestamp = time();
		$id = $prefix . ":" . $namespace . ":" . $timestamp;
		return $id;
	}

	// METHODS FOR ADMIN TABLE FILTERS

	/*
     * Returns an array of mimetype extensions for the admin side format filter, using the id as key
	*/
	public function format_filter() {
		// only display mimetypes actually in use
		$mimetype_id_string = $sql = $rows = '';
		$mimetypeArray = array();
		$mimetypeArray[0] = '---';
		$criteria = null;

		global $xoopsDB;

		$sql = 'SELECT DISTINCT `format` FROM ' . $this->table;
		$rows = $this->query($sql, $criteria);
		if (count($rows) > 0) {
			$mimetype_id_string = ' WHERE `mimetypeid` IN (';
			foreach($rows as $row) {
				$mimetype_id_string .= $row['format'] . ',';
			}
			$mimetype_id_string = rtrim($mimetype_id_string, ',');
			$mimetype_id_string .= ') ';
		}

		// use the distinct mimetype ids to get the relevant mimetype objects
		$system_mimetype_handler = icms_getModuleHandler('mimetype', 'system');
		$criteria = new CriteriaCompo();
		$criteria->setSort('extension');
		$criteria->setOrder('ASC');
		$sql = 'SELECT * FROM ' . $xoopsDB->prefix('system_mimetype') . $mimetype_id_string;
		$rows = $this->query($sql, $criteria);
		foreach($rows as $row) {
			$mimetypeArray[$row['mimetypeid']] = $row['extension'];
		}
		return $mimetypeArray;
	}

	/*
     * Returns a list of collection names for the admin side collection (source) filter
	*/
	public function source_filter() {
		$criteria = $criteria = icms_buildCriteria(array('type' => 'Collection'));
		$library_publication_handler = icms_getModuleHandler('publication',
			basename(dirname(dirname(__FILE__))),  'library');
		$publication_array = array(0 => '---') + $library_publication_handler->getList($criteria);
		return $publication_array;
	}

	public function status_filter() {
		return array(0 => 'Offline', 1 => 'Online');
	}

	public function federated_filter() {
		return array(0 => 'No', 1 => 'Yes');
	}

	public function rights_filter() {
		
		$sprocketsModule = icms_getModuleInfo('sprockets');
		$sprockets_rights_handler = icms_getModuleHandler('rights',
			$sprocketsModule->dirname(), 'sprockets');
		$rights_array = array(0 => '---') + $sprockets_rights_handler->getList();
		return $rights_array;
	}

	/*
     * Returns an array of languages using ISO 639-1 two-letter language codes as keys
     *
     * Accurate as of 29 September 2009.
	*/
	public function getLanguage() {
		return include ICMS_ROOT_PATH . '/modules/' . basename(dirname(dirname(__FILE__)))
			. '/include/language.inc.php';
	}

	/**
	 * Toggles the status or federation of a soundtrack
	 *
	 * @param int $soundtrack_id
	 * @param str $field
	 * @return int $visibility
	 */
	public function change_status($publication_id, $field) {
		$visibility = '';
		$publicationObj = $this->get($publication_id);
		if ($publicationObj->getVar($field, 'e') == true) {
			$publicationObj->setVar($field, 0);
			$visibility = 0;
		} else {
			$publicationObj->setVar($field, 1);
			$visibility = 1;
		}
		$this->insert($publicationObj, true);
		return $visibility;
	}

	public function updateComments($publication_id, $total_num) {
		$publicationObj = $this->get($publication_id);
		if ($publicationObj && !$publicationObj->isNew()) {
			$publicationObj->setVar('post_comments', $total_num);
			$this->insert($publicationObj, true);
		}
	}

	/**
	 * Triggers notifications, called when a publication is inserted or updated
	 *
	 * @param object $obj LibraryPublication object
	 * @return bool
	 */
	protected function afterSave(& $obj) {
		// triggers notification event for subscribers
		if (!$obj->getVar('publication_notification_sent') &&
				$obj->getVar ('status', 'e') == 1) {
			$obj->sendNotifPublicationPublished();
			$obj->setVar('publication_notification_sent', true);
			$this->insert ($obj);
		}

		// storing categories
		$imtagging_category_link_handler = icms_getModuleHandler('category_link', 'imtagging');
		$imtagging_category_link_handler->storeCategoriesForObject($obj);
		return true;
	}

	/**
	 * Deletes notification subscriptions, called when a publication is deleted
	 *
	 * @global <type> $icmsModule
	 * @param object $obj LibraryPublication object
	 * @return bool
	 */
	protected function afterDelete(& $obj) {
		global $icmsModule;
		$notification_handler =& xoops_gethandler('notification');
		$module_handler = xoops_getHandler('module');
		$module = $module_handler->getByDirname('library');
		$module_id = $module->getVar('mid');
		$category = 'global';
		$item_id = $obj->id();

		// delete publication bookmarks
		$category = 'publication';
		$notification_handler->unsubscribeByItem($module_id, $category, $item_id);

		// delete category_links

		return true;
	}
}