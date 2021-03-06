<?php
/**
 * English language constants related to module information
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Module Info
// The name of this module

global $icmsModule;
define("_MI_LIBRARY_MD_NAME", "Library");
define("_MI_LIBRARY_MD_DESC", "A digital library for ImpressCMS - publish articles, files, images,
    videos and audio recordsing. Compliant with the Open Archives Initiative Protocol for
    Metadata Harvesting (OAIPMH)");
define("_MI_LIBRARY_PUBLICATIONS", "Publications");
define("_MI_LIBRARY_CATEGORIES", "Categories");
define("_MI_LIBRARY_COLLECTIONS", "Collections");
define("_MI_LIBRARY_ARCHIVES", "Open Archive");
define("_MI_LIBRARY_COLLECTION_VIEW_MODE", "Display collections index page in compact view?");
define("_MI_LIBRARY_COLLECTION_VIEW_MODEDSC", "Toggles how the collection index page is displayed.
    If you only have a few collections, select 'no' to display descriptive summaries of each (it
    is more attractive and aids user navigation). If you have a lot of collections, you might prefer
    just to list them in a table (select 'yes'). It is best to try and list all of your collections
    in a single view, if possible.");
define("_MI_LIBRARY_START_PAGE", "Start page");
define("_MI_LIBRARY_START_PAGE_DSC", "What page do you want to use as the home page for this
    module?");
define("_MI_LIBRARY_DEFAULT_LANGUAGE", "Default language");
define("_MI_LIBRARY_DEFAULT_LANGUAGE_DSC", "Used as the default option in the publication submission
    form to save you time");
define("_MI_LIBRARY_ENABLE_ARCHIVE", "Enable Open Archives (OAIPMH) web service?");
define("_MI_LIBRARY_ENABLE_ARCHIVE_DSC", "Do you want to share your publication metadata with
    external sites via the Open Archives Initiative Protocol for Metadata Harvesting? If enabled,
    the module will respond to incoming OAIPMH requests against its base URL. If not, it won't.
    OAIPMH allows specialised search engines to import your publication metadata for indexing.");
define("_MI_LIBRARY_FEDERATE", "Federate publication metadata by default?");
define("_MI_LIBRARY_FEDERATE_DSC", "Federated publications expose their metadata to external sites
    via the Open Archives Initiative Protocol for Metadata Harvesting. You can sets the default
    value for the federation setting in the add publication form here. You can override it, its
    just a convenience.");
define("_MI_LIBRARY_INSTRUCTIONS", "Instructions");
define("_MI_LIBRARY_IMAGE_HEIGHT", "Image publication maximum display height (pixels)");
define("_MI_LIBRARY_IMAGE_HEIGHTDSC", "The height that image-type publications will be
    displayed at in single view mode. Images will be scaled with aspect ratio preserved, according 
    to the largest dimension specified (width or height). So in reality, image scaling will be
    constrained by either the width or height you have specified, but not both.");
define("_MI_LIBRARY_IMAGE_WIDTH", "Image publication maximum display width (pixels)");
define("_MI_LIBRARY_IMAGE_WIDTHDSC", "The width that image-type publications will be
    displayed at in single view mode. Images will be scaled with aspect ratio preserved, according
    to the largest dimension specified (width or height). So in reality, image scaling will be
    constrained by either the width or height you have specified, but not both.");
define("_MI_LIBRARY_IMAGE_UPLOAD_HEIGHT", "Maximum HEIGHT of uploaded images (pixels)");
define("_MI_LIBRARY_IMAGE_UPLOAD_HEIGHTDSC", "This is the maximum height allowed for uploaded
	images. Don't forget that images will be automatically scaled for display, so its ok to allow
	bigger images than you plan to actually use. In fact, it gives your site a bit of flexibility
	should you decide to change the display settings later.");
define("_MI_LIBRARY_IMAGE_UPLOAD_WIDTH", "Maximum WIDTH of uploaded images (pixels)");
define("_MI_LIBRARY_IMAGE_UPLOAD_WIDTHDSC", "This is the maximum width allowed for uploaded images.
	Don't forget that images will be automatically scaled for display, so its ok to allow bigger
	images than you plan to actually use. In fact it gives your site a bit of flexibility should
	you decide to change the display settings later.");
define("_MI_LIBRARY_IMAGE_FILE_SIZE", "Maximum image FILE SIZE of uploaded images (bytes)");
define("_MI_LIBRARY_IMAGE_FILE_SIZEDSC", "This is the maximum size (in bytes) allowed for image
	uploads.");
define("_MI_LIBRARY_COLLECTIONSDSC", "Displays a list of library collections");
define("_MI_LIBRARY_NEW_ITEMS", "New publications");
define("_MI_LIBRARY_NEW_ITEMSDSC", "When looking at the new publications page");
define("_MI_LIBRARY_NEW_PUBLICATION_VIEW_MODE", "Display publications index page in compact
    view?");
define("_MI_LIBRARY_NEW_PUBLICATION_VIEW_MODEDSC", "Toggles how the new publication index page is
    displayed. Select 'no' to display descriptive summaries of each publication (it is more
    attractive and aids user navigation as screenshots are enabled). Select 'yes' to display a
    summary table.");
define("_MI_LIBRARY_NEW_VIEW_MODE", "Display new collections in compact view by default?");
define("_MI_LIBRARY_NEW_VIEW_MODEDSC", "This sets the default value in the 'add collection' form for
    convenience. You can override it. Compact view does not show publication descriptions, it is
    best for collections where member items do not normally have descriptions, like music albums.
    If your tracks do have descriptions, then it is best to choose extended view which is more
    attractive.");
define("_MI_LIBRARY_NUMBER_IN_RSS", "Number of publications in RSS feeds");
define("_MI_LIBRARY_NUMBER_IN_RSSDSC", "Controls the number of recent publications that will appear
    in RSS feeds throughout the module.");
define("_MI_LIBRARY_RECENT", "New publications");
define("_MI_LIBRARY_RECENTDSC", "Displays a list of the most recent publications");
define("_MI_LIBRARY_SCREENSHOT_HEIGHT", "Screenshot height (in pixels)");
define("_MI_LIBRARY_SCREENSHOT_HEIGHTDSC", "This value is used to scale the height that screenshot
    images are displayed at. Aspect ratio will be preserved.");
define("_MI_LIBRARY_SCREENSHOT_WIDTH", "Screenshot width (in pixels)");
define("_MI_LIBRARY_SCREENSHOT_WIDTHDSC", "Screenshots are the cover art/images displayed when a 
    publication is viewed in single view mode (except for 'image' type publications, which are 
    displayed at a considerably larger size by default. This value is used to scale the width that 
    images are displayed at. Aspect ratio will be preserved, so it will be the largest of the
    width and height preferences that is the constraint.");
define("_MI_LIBRARY_THUMBNAIL_HEIGHT", "Thumbnail height (in pixels)");
define("_MI_LIBRARY_THUMBNAIL_HEIGHTDSC", "Thumbnails are the cover art/images displayed when a
    publication is viewed inside a collection or listing, they (supposed) to be smaller than the
    screenshot images. Aspect ration will be preserved, so it will be the largest of the width and
    height preferences that is the constraint.");
define("_MI_LIBRARY_THUMBNAIL_WIDTH", "Thumbnail width (in pixels)");
define("_MI_LIBRARY_THUMBNAIL_WIDTHDSC", "This value is used to scale smaller versions of collection
    logos / album cover art, height will be scaled proportionately");
define("_MI_LIBRARY_NUMBER_PUBLICATIONS", "Number of publications to display on one page");
define("_MI_LIBRARY_NUMBER_PUBLICATIONSSDSC", "When viewing the publications index page, this is the
    maximum number of publications that will be displayed in a single view. If there are more,
    pagination controls will be inserted.");
define("_MI_LIBRARY_NUMBER_COLLECTIONS", "Number of collections to display on one page");
define("_MI_LIBRARY_NUMBER_COLLECTIONSDSC", "When viewing the collection index page, this is the
    maximum number of collections that will be displayed in a single view. If there are
    more, pagination controls will be inserted.");
define("_MI_LIBRARY_NEW", "New");

// display preferences
define("_MI_LIBRARY_DISPLAY_RELEASED", "Display collection release date");
define("_MI_LIBRARY_DISPLAY_RELEASEDDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_COLLECTION_PUBLISHER", "Display collection publisher field");
define("_MI_LIBRARY_DISPLAY_COLLECTION_PUBLISHERDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_TRACKCOUNT", "Display collection publication counter field");
define("_MI_LIBRARY_DISPLAY_TRACKCOUNTDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_COUNTER", "Display collection/publication views counter field");
define("_MI_LIBRARY_DISPLAY_COUNTERDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_CREATOR", "Display publication author field");
define("_MI_LIBRARY_DISPLAY_CREATORDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_DATE", "Display publication date field");
define("_MI_LIBRARY_DISPLAY_DATEDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_FORMAT", "Display publication format / file size field");
define("_MI_LIBRARY_DISPLAY_FORMATDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_PUBLICATION_PUBLISHER", "Display publication publisher field");
define("_MI_LIBRARY_DISPLAY_PUBLICATION_PUBLISHERDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_LANGUAGE", "Display publication language field");
define("_MI_LIBRARY_DISPLAY_LANGUAGEDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_RIGHTS", "Display publication rights field");
define("_MI_LIBRARY_DISPLAY_RIGHTSDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_DOWNLOAD_BUTTON", "Display download button");
define("_MI_LIBRARY_DISPLAY_DOWNLOAD_BUTTONDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_STREAMING_BUTTON", "Display play (streaming) button");
define("_MI_LIBRARY_DISPLAY_STREAMING_BUTTONDSC", "Toggles visibility in user-side templates");
define("_MI_LIBRARY_DISPLAY_PUBLICATION_SOURCE", "Display publication collection (source) field");
define("_MI_LIBRARY_DISPLAY_PUBLICATION_SOURCEDSC", "Toggles visibility in user-side templates");

// additional admin menu items
define("_MI_LIBRARY_TEMPLATES", "Templates");
define("_MI_LIBRARY_TEST_OAIPMH", "Test OAIPMH responses");

// notifications - categories
define("_MI_LIBRARY_GLOBAL_NOTIFY", "All content");
define("_MI_LIBRARY_GLOBAL_NOTIFY_DSC", "Notifications related to all collections and publications
    in this module");

define("_MI_LIBRARY_COLLECTION_NOTIFY", "Collection");
define("_MI_LIBRARY_COLLECTION_NOTIFY_DSC", "Notifications related to all publications in this
    collection");

define("_MI_LIBRARY_PUBLICATION_NOTIFY", "Publication");
define("_MI_LIBRARY_PUBLICATION_NOTIFY_DSC", "Notifications related to individual publications");

// notifications - events
define("_MI_LIBRARY_GLOBAL_PUBLICATION_PUBLISHED_NOTIFY", "New publication published");
define("_MI_LIBRARY_GLOBAL_PUBLICATION_PUBLISHED_NOTIFY_CAP", "Notify me when a new publication
    is published in any collection.");
define("_MI_LIBRARY_GLOBAL_PUBLICATION_PUBLISHED_NOTIFY_DSC", "Receive notification when a new
    publication is published.");
define("_MI_LIBRARY_GLOBAL_PUBLICATION_PUBLISHED_NOTIFY_SBJ",
		"New publication published at {X_SITENAME}");

define("_MI_LIBRARY_GLOBAL_COLLECTION_PUBLISHED_NOTIFY", "New collection published");
define("_MI_LIBRARY_GLOBAL_COLLECTION_PUBLISHED_NOTIFY_CAP",
		"Notify me when a new collection is published.");
define("_MI_LIBRARY_GLOBAL_COLLECTION_PUBLISHED_NOTIFY_DSC", "Receive notification when a new
    collection is published.");
define("_MI_LIBRARY_GLOBAL_COLLECTION_PUBLISHED_NOTIFY_SBJ",
		"New audio collection published at {X_SITENAME}");

define("_MI_LIBRARY_COLLECTION_PUBLICATION_PUBLISHED_NOTIFY", "New publication published");
define("_MI_LIBRARY_COLLECTION_PUBLICATION_PUBLISHED_NOTIFY_CAP", "Notify me when a new publication is
    published in this collection.");
define("_MI_LIBRARY_COLLECTION_PUBLICATION_PUBLISHED_NOTIFY_DSC", "Receive notification when a new
    publication is published in this collection.");
define("_MI_LIBRARY_COLLECTION_PUBLICATION_PUBLISHED_NOTIFY_SBJ",
		" New publication published at {X_SITENAME}");