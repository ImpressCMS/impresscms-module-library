<?php
/**
 * Generates individual RSS feeds for Library collections, and new items
 *
 * Also allows the attachment of media enclosures to individual publications,  thereby allowing
 * library clients to automatically retrieve media files from the feeds. It uses a modified
 * icmsfeed.php and rss template - these have been built into the module in the interests of
 * a zero post-installation config.
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

/** Include the module's header for all pages */
include_once 'header.php';
include_once ICMS_ROOT_PATH.'/header.php';

// ensures that entities are encoded before sending to XML processing
function encode_entities($field) {
	$field = htmlspecialchars(html_entity_decode($field, ENT_QUOTES, 'UTF-8'),
		ENT_NOQUOTES, 'UTF-8');
	return $field;
}

global $libraryConfig;
$clean_publication_id = '';

$clean_publication_id = isset($_GET['publication_id']) ? intval($_GET['publication_id']) : false;
$clean_start = isset($_GET['start']) ? intval($_GET['start']) : false;
$clean_limit = $libraryConfig['number_publications_in_rss'];

include_once ICMS_ROOT_PATH . '/modules/' . basename(dirname(__FILE__))
	. '/class/icmsfeed.php';
$library_feed = new IcmsFeed();
$library_publication_handler = icms_getModuleHandler('publication',
	basename(dirname(__FILE__)), 'library');

// generates a feed of recent publications across all publications
if (empty($clean_publication_id)) {
	$publication_title = _CO_LIBRARY_NEW;
	$site_name = encode_entities($icmsConfig['sitename']);

	$library_feed->title = $site_name . ' - ' . _CO_LIBRARY_NEW;
	$library_feed->url = ICMS_URL;
	$library_feed->description = _CO_LIBRARY_NEW_DSC . $site_name . '.';
	$library_feed->language = $libraryConfig['default_language'];
	$library_feed->charset = _CHARSET;
	$library_feed->category = $icmsModule->name();

	$url = ICMS_URL . 'images/logo.gif';
	$library_feed->image = array('title' => $url);
	$width = $libraryConfig['screenshot_width'];
	if ($width > 144) {
		$width = 144;
	}
	$library_feed->width = $width;
	$library_feed->atom_link = '"' . LIBRARY_URL . 'rss.php"';

} else {
	// generates a feed for a specific publication

	// need to remove html tags and problematic characters to meet RSS spec
	$publicationObj = $library_publication_handler->get($clean_publication_id);
	$site_name = encode_entities($icmsConfig['sitename']);
	$publication_title = encode_entities($publicationObj->getVar('title'));
	$publication_description = strip_tags($publicationObj->getVar('description'));
	$publication_description = encode_entities($publication_description);
	$url = $publicationObj->getImageDir() . $publicationObj->getVar('cover');
	$url = encode_entities($url);

	$library_feed->title = $site_name . ' - ' . $publication_title;
	$library_feed->url = ICMS_URL;
	$library_feed->description = htmlspecialchars($publication_description, ENT_QUOTES);
	$library_feed->language = $libraryConfig['default_language'];
	$library_feed->charset = _CHARSET;
	$library_feed->category = $icmsModule->name();

	$url = $publicationObj->getImageDir() . $publicationObj->getVar('cover');
	$library_feed->image = array('title' => $library_feed->title, 'url' => $url);
	$width = $libraryConfig['screenshot_width'];
	if ($width > 144) {
		$width = 144;
	}
	$library_feed->width = $width;
	$library_feed->atom_link = '"' . LIBRARY_URL . 'rss.php?collection_id='
		. $publicationObj->id() . '"';
}

$publicationArray = $library_publication_handler->getCollectionPublications($clean_start,
		$clean_limit, $clean_publication_id);

// prepare an array of publications associated with this collection
foreach($publicationArray as $publication) {
	$publicationObj = $library_publication_handler->get($publication['publication_id']);
	$creator = $publicationObj->getVar('creator', 'e');
	$creator = explode('|', $creator);
	foreach ($creator as &$individual) {
		$individual = encode_entities($individual);
	}
	$description = encode_entities($publication['description']);
	$file_size = $publicationObj->getVar('file_size', 'e');
	$title = encode_entities($publication['title']);
	$identifier = encode_entities($publication['identifier']);
	$link = encode_entities($publication['itemUrl']);

	$library_feed->feeds[] = array (
		'title' => $title,
		'link' => $link,
		'description' => $description,
		'author' => $creator,
		// pubdate must be a RFC822-date-time EXCEPT with 4-digit year or the feed won't validate
		'pubdate' => date(DATE_RSS, $publicationObj->getVar('date', false)),
		'guid' => $link,
		'category' => $publication_title,
		// added the possibility to include media enclosures in the feed & template
		'enclosure' => '<enclosure length="' . $file_size . '" type="'
			. $publicationObj->get_mimetype() . '" url="' . $identifier . '" />'
	);
}

// validation issue:
// single and double quotes in collection title generate no-html-recommended warnings
// (although feed is valid). it looks like the quotes are converted to html entities during
// template assignment which is downstream of this file - can this behaviour be overridden?

$library_feed->render();