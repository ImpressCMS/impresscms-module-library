<?php
/**
 * English language constants commonly used in the module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

global $icmsModule;
$module_name = $icmsModule->getVar('dirname');

// publication
define("_CO_LIBRARY_PUBLICATION_TYPE", "Type");
define("_CO_LIBRARY_PUBLICATION_TYPE_DSC", "<strong>Important!</strong> Select the type of
    publication very carefully. This controls how the publication will be displayed.");
define("_CO_LIBRARY_PUBLICATION_TITLE", "Title");
define("_CO_LIBRARY_PUBLICATION_TITLE_DSC", " Title of the publication.");
define("_CO_LIBRARY_PUBLICATION_CREATOR", "Author");
define("_CO_LIBRARY_PUBLICATION_CREATOR_DSC", " Separate multiple authors with a pipe '|' character.
    Use a convention for consistency, eg. John Smith|Jane Doe");
define("_CO_LIBRARY_PUBLICATION_DESCRIPTION", "Description (summary)");
define("_CO_LIBRARY_PUBLICATION_DESCRIPTION_DSC", " A summary description or abstract of the
	publication. It is displayed when multiple publications are listed on a page and in OAIPMH
	responses. It is an important field for user-side presentation. Always supply a description if
	you can.");
define("_CO_LIBRARY_PUBLICATION_EXTENDED_TEXT", "Extended (full) text");
define("_CO_LIBRARY_PUBLICATION_EXTENDED_TEXT_DSC", "Optional. This is an alternate description
	that is shown in single publication view. If it is left empty, the description field will be
	used instead. You only need to use this field if you want to have a full description that is
	too long to view comfortably when multiple publications are listed on a page.");
define("_CO_LIBRARY_PUBLICATION_PUBLISHER", "Publisher");
define("_CO_LIBRARY_PUBLICATION_PUBLISHER_DSC", " Publisher of the publication.");
define("_CO_LIBRARY_PUBLICATION_CONTRIBUTOR", "Contributor(s)");
define("_CO_LIBRARY_PUBLICATION_CONTRIBUTOR_DSC", " Co-authors or collaborators, separate multiple
    contributors with a pipe '|' character.");
define("_CO_LIBRARY_PUBLICATION_DATE", "Date");
define("_CO_LIBRARY_PUBLICATION_DATE_DSC", " ");
define("_CO_LIBRARY_PUBLICATION_FORMAT", "Format");
define("_CO_LIBRARY_PUBLICATION_FORMAT_DSC", " You can add more file formats (mimetypes) to this
    list by authorising the Library module to use them in <a href=\"" . ICMS_URL
		. "/modules/system/admin.php?fct=mimetype\">System => Mimetypes</a>.");
define("_CO_LIBRARY_PUBLICATION_FILE_SIZE", "File size");
define("_CO_LIBRARY_PUBLICATION_FILE_SIZE_DSC", "Enter in BYTES, it will be converted to human
    readable automatically. This is the size of the file specified in the URL field, if any.");
define("_CO_LIBRARY_PUBLICATION_IDENTIFIER", "URL");
define("_CO_LIBRARY_PUBLICATION_IDENTIFIER_DSC", " The link to download the associated file (if any).
    If there isn't one then leave this blank.");
define("_CO_LIBRARY_PUBLICATION_CATEGORIES", "Categories");
define("_CO_LIBRARY_PUBLICATION_CATEGORIES_DSC", "A publication may belong to more than one category.
	Categories are managed in the ImTagging module, and are not available if it is not installed.");
define("_CO_LIBRARY_PUBLICATION_SOURCE", "Collection");
define("_CO_LIBRARY_PUBLICATION_SOURCE_DSC", " A collection of which this publication is a part,
    for example, a scientific journal an article belongs to, an album a soundtrack is included in,
    or an event at which a presentation was made.");
define("_CO_LIBRARY_PUBLICATION_LANGUAGE", "Language");
define("_CO_LIBRARY_PUBLICATION_LANGUAGE_DSC", " Language of the publication, if any.");
define("_CO_LIBRARY_PUBLICATION_RIGHTS", "Rights");
define("_CO_LIBRARY_PUBLICATION_RIGHTS_DSC", " The license under which this publication is distributed.
    In most countries, artistic works are copyright (even if you don't declare it) unless you
    specify another license.");
define("_CO_LIBRARY_PUBLICATION_STATUS", "Status");
define("_CO_LIBRARY_PUBLICATION_STATUS_DSC", "Toggle this publication online or offline");
define("_CO_LIBRARY_PUBLICATION_FEDERATED", "Federated");
define("_CO_LIBRARY_PUBLICATION_FEDERATED_DSC", "Syndicate this publication's metadata with other
    sites (cross site search) via the Open Archives Initiative Protocol for Metadata Harvesting");
define("_CO_LIBRARY_PUBLICATION_SUBMISSION_TIME", "Submission time");
define("_CO_LIBRARY_PUBLICATION_SUBMITTER", "Submitter");
define("_CO_LIBRARY_PUBLICATION_OAI_IDENTIFIER", "OAI Identifier");
define("_CO_LIBRARY_PUBLICATION_OAI_IDENTIFIER_DSC", "Used to uniquely identify this publication across
    federated sites, and prevents publications being duplicated or imported multiple times. Should never
    be changed under any circumstance. Complies with the
    <a href=\"http://www.openarchives.org/OAI/2.0/guidelines-oai-identifier.htm\">OAI Identifier
    Format specification</a>.");

// additional items
define("_CO_LIBRARY_PUBLICATION_PUBLISHED_ON", "Published");
define("_CO_LIBRARY_PUBLICATION_BY", "By");
define("_CO_LIBRARY_PUBLICATION_PUBLICATION", "publication");
define("_CO_LIBRARY_PUBLICATION_COUNTER", "view");
define("_CO_LIBRARY_PUBLICATION_DOWNLOAD", "Download");
define("_CO_LIBRARY_PUBLICATION_PLAY", "Play");
define("_CO_LIBRARY_PUBLICATION_ENCLOSURES", "Feed includes enclosures");
define("_CO_LIBRARY_CONTENTS", "Contents");

// Dublic Core Metadata Initiative Type Vocabulary
define("_CO_LIBRARY_TEXT", "Text");
define("_CO_LIBRARY_SOUND", "Sound");
define("_CO_LIBRARY_IMAGE", "Image");
define("_CO_LIBRARY_MOVINGIMAGE", "Video");
define("_CO_LIBRARY_DATASET", "Dataset");
define("_CO_LIBRARY_SOFTWARE", "Software");
define("_CO_LIBRARY_COLLECTION", "Collection");

// publication
define("_CO_LIBRARY_PUBLICATION_COMPACT_VIEW", "Compact view");
define("_CO_LIBRARY_PUBLICATION_COMPACT_VIEW_DSC", "Do you want to display this collection in compact
    form (a simple list of contents, best for albums and similar where member publications don't 
    usually carry descriptions) or in expanded view with descriptions and other metadata?");
define("_CO_LIBRARY_PUBLICATION_COVER", "Image");
define("_CO_LIBRARY_PUBLICATION_COVER_DSC", "Upload 'Image' type publications, publication covers
    and album art here. Maximum image width, height and file size can be adjusted in preferences.
	Image types are	currently restricted to PNG, GIF and JPG.");
define("_CO_LIBRARY_PUBLICATION_PLAY_ALL", "Play all tracks in this publication");
define("_CO_LIBRARY_PUBLICATION_RSS_URL", "/modules/library/rss.php?collection_id=");
define("_CO_LIBRARY_PUBLICATION_RSS_BUTTON", "/modules/library/img/rss.png");
define("_CO_LIBRARY_PUBLICATION_TRACKS", "publication");
define("_CO_LIBRARY_NEW", "Recent publications");
define("_CO_LIBRARY_NEW_DSC", "The most recent publications across all collections from ");
define("_CO_LIBRARY_PUBLICATION_STREAM", "Play");

// archive
define("_CO_LIBRARY_ARCHIVE_ENABLED", "Archive online");
define("_CO_LIBRARY_ARCHIVE_ENABLED_DSC", "Toggle this archive online (yes) or offline (no).");
define("_CO_LIBRARY_ARCHIVE_TARGET_MODULE", "Target module");
define("_CO_LIBRARY_ARCHIVE_TARGET_MODULE_DSC", "Select the module you wish to enable the OAIPMH
    (federation) service for. Currently only the Library module is supported.");
define("_CO_LIBRARY_ARCHIVE_METADATA_PREFIX", "Metadata prefix");
define("_CO_LIBRARY_ARCHIVE_METADATA_PREFIX_DSC", " Indicates the XML metadata schemes supported
    by this archive. Presently only Dublin Core is supported (oai_dc).");
define("_CO_LIBRARY_ARCHIVE_NAMESPACE", "Namespace");
define("_CO_LIBRARY_ARCHIVE_NAMESPACE_DSC", "Used to construct unique identifiers for records. 
    Default is to use your domain name. Changing this is not recommended as it helps people
    identify your repository as the source of a record that has been shared with other archives.");
define("_CO_LIBRARY_ARCHIVE_GRANULARITY", "Granularity");
define("_CO_LIBRARY_ARCHIVE_GRANULARITY_DSC", " The granularity of datestamps. The OAIPMH permits 
    two levels of granularity, this implementation supports the most fine grained option
    (YYYY-MM-DDThh:mm:ssZ).");
define("_CO_LIBRARY_ARCHIVE_DELETED_RECORD", "Deleted record support");
define("_CO_LIBRARY_ARCHIVE_DELETED_RECORD_DSC", " Does the archive support tracking of deleted
    records? This implementation does not currently support deleted records.");
define("_CO_LIBRARY_ARCHIVE_EARLIEST_DATE_STAMP", "Earliest date stamp");
define("_CO_LIBRARY_ARCHIVE_EARLIEST_DATE_STAMP_DSC", " The datestamp for the oldest record in
    your archive.");
define("_CO_LIBRARY_ARCHIVE_ADMIN_EMAIL", "Admin email");
define("_CO_LIBRARY_ARCHIVE_ADMIN_EMAIL_DSC", " The email address for the administrator of this
    archive. Be aware that this address is reported in response to incoming OAIPMH requests.");
define("_CO_LIBRARY_ARCHIVE_PROTOCOL_VERSION", "Protocol version");
define("_CO_LIBRARY_ARCHIVE_PROTOCOL_VERSION_DSC", " The OAIPMH protocol version implemented by
    this repository. Currently only version 2.0 is supported.");
define("_CO_LIBRARY_ARCHIVE_REPOSITORY_NAME", "Repository name");
define("_CO_LIBRARY_ARCHIVE_REPOSITORY_NAME_DSC", " The name of your archive.");
define("_CO_LIBRARY_ARCHIVE_BASE_URL", "Base URL");
define("_CO_LIBRARY_ARCHIVE_BASE_URL_DSC", " The target URL to which incoming OAIPMH requests for
    your archive should be sent.");
define("_CO_LIBRARY_ARCHIVE_COMPRESSION", "Compression");
define("_CO_LIBRARY_ARCHIVE_COMPRESSION_DSC", " Indicates what types of compression are supported
    by this archive. Presently only gzip is supported.");
define("_CO_LIBRARY_ARCHIVE_ABOUT_THIS_ARCHIVE", "Our publication collection is an Open Archive");
define("_CO_LIBRARY_ARCHIVE_OAIPMH_TARGET", "This website implements the 
    <a href=\"http://www.openarchives.org/pmh/\">Open Archives Initiative Protocol for Metadata
    Harvesting</a> (OAIPMH). Compliant harvesters can access our publication metadata from the
    OAIPMH target below. OAIPMH queries should be directed to the Base URL specified below.");
define("_CO_LIBRARY_ARCHIVE_NOT_AVAILABLE", "Sorry, Open Archive functionality is not enabled at
    this time.");
define("_CO_LIBRARY_ARCHIVE_NOT_CONFIGURED", "Library is currently configured to refuse incoming
    OAIPMH requests, sorry");
define("_CO_LIBRARY_ARCHIVE_MUST_CREATE", "Error: An archive object must be created before OAIPMH
    requests can be handled. Please create one via the Open Archive tab in Library administration.");