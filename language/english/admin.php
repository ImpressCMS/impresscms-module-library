<?php
/**
 * English language constants used in admin section of the module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */
if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

// Requirements
define("_AM_LIBRARY_REQUIREMENTS", "Library Requirements");
define("_AM_LIBRARY_REQUIREMENTS_INFO", "We've reviewed your system, unfortunately it doesn't meet
    all the requirements needed for Library to function. Below are the requirements needed.");
define("_AM_LIBRARY_REQUIREMENTS_ICMS_BUILD", "Library requires at least ImpressCMS 1.1.1 RC 1.");
define("_AM_LIBRARY_REQUIREMENTS_IMTAGGING", "Library requires the ImTagging module to be installed
	to provide category support.");
define("_AM_LIBRARY_REQUIREMENTS_SUPPORT", "Should you have any question or concerns, please visit
    our forums at <a href='http://community.impresscms.org'>http://community.impresscms.org</a>.");

// Publication
define("_AM_LIBRARY_PUBLICATIONS", "Publications");
define("_AM_LIBRARY_PUBLICATIONS_DSC", "All publications in the module");
define("_AM_LIBRARY_PUBLICATION_CREATE", "Add a publication");
define("_AM_LIBRARY_PUBLICATION", "Publication");
define("_AM_LIBRARY_PUBLICATION_CREATE_INFO", "Fill-out the following form to create a new
    publication.");
define("_AM_LIBRARY_PUBLICATION_EDIT", "Edit this publication");
define("_AM_LIBRARY_PUBLICATION_EDIT_INFO", "Fill-out the following form in order to edit this
    publication.");
define("_AM_LIBRARY_PUBLICATION_MODIFIED", "The publication was successfully modified.");
define("_AM_LIBRARY_PUBLICATION_CREATED", "The publication has been successfully created.");
define("_AM_LIBRARY_PUBLICATION_VIEW", "Publication info");
define("_AM_LIBRARY_PUBLICATION_VIEW_DSC", "Here is the info about this publication.");
define("_AM_LIBRARY_PUBLICATION_ONLINE", "Publication switched online.");
define("_AM_LIBRARY_PUBLICATION_OFFLINE", "Publication switched offline.");
define("_AM_LIBRARY_PUBLICATION_FEDERATED", "Publication is now federated.");
define("_AM_LIBRARY_PUBLICATION_NOT_FEDERATED", "Publication is no longer federated.");

// Collection
define("_AM_LIBRARY_COLLECTIONS", "Collection");
define("_AM_LIBRARY_COLLECTIONS_DSC", "All collections in the module");
define("_AM_LIBRARY_COLLECTION_CREATE", "Add a collection");
define("_AM_LIBRARY_COLLECTION", "Collection");
define("_AM_LIBRARY_COLLECTION_CREATE_INFO", "Fill-out the following form to create a new collection.");
define("_AM_LIBRARY_COLLECTION_EDIT", "Edit this collection");
define("_AM_LIBRARY_COLLECTION_EDIT_INFO", "Fill-out the following form in order to edit this
    collection.");
define("_AM_LIBRARY_COLLECTION_MODIFIED", "The collection was successfully modified.");
define("_AM_LIBRARY_COLLECTION_CREATED", "The collection has been successfully created.");
define("_AM_LIBRARY_COLLECTION_VIEW", "Collection info");
define("_AM_LIBRARY_COLLECTION_VIEW_DSC", "Here is the info about this collection.");
define("_AM_LIBRARY_COLLECTION_ONLINE", "Collection switched online.");
define("_AM_LIBRARY_COLLECTION_OFFLINE", "Collection switched offline.");
define("_AM_LIBRARY_COLLECTION_FEDERATED", "Collection is now federated.");
define("_AM_LIBRARY_COLLECTION_NOT_FEDERATED", "Collection is no longer federated");

define("_AM_LIBRARY_NO_UPLOAD_DIRECTORY", "<p><strong>Warning</strong>: The directory 
    <strong>/uploads/library</strong> does not exist. Please create it manually to allow publication
    logos and cover art to be stored.</p>");
define("_AM_LIBRARY_UPLOAD_NOT_WRITABLE", "<p><strong>Warning</strong>: The directory
    /uploads/library</strong> is not writeable by the server. Please change the permissions
    (chmod) on this directory to 777, otherwise you will not be able to upload publication logos or
    cover art.</p>");
define("_AM_LIBRARY_MUST_CREATE_COLLECTION", "<p><strong>Warning</strong>: No collections currently
    exist. You must create at least one collection before you can add publications as every
    publication must be assigned to a collection. Submission of publications will fail if you
    ignore this warning.</p>");
define("_AM_LIBRARY_MUST_AUTHORISE_MIMETYPES", "<p><strong>Warning</strong>: You must authorise
    Library to use at least one audio file type (mimetype) before you can upload publications. Visit
    System => Mimetypes. Click the edit button on relevant entries (eg. MP3, WMA) and add Library
    to the list of modules allowed to use them.</p>");

// Archive
define("_AM_LIBRARY_ARCHIVES", "Archives");
define("_AM_LIBRARY_ARCHIVES_DSC", "All archives in the module");
define("_AM_LIBRARY_ARCHIVE_CREATE", "Add a archive");
define("_AM_LIBRARY_ARCHIVE", "Archive");
define("_AM_LIBRARY_ARCHIVE_CREATE_INFO", "Fill-out the following form to create a new archive.");
define("_AM_LIBRARY_ARCHIVE_EDIT", "Edit this archive");
define("_AM_LIBRARY_ARCHIVE_EDIT_INFO", "Fill-out the following form in order to edit this
    archive.");
define("_AM_LIBRARY_ARCHIVE_MODIFIED", "The archive was successfully modified.");
define("_AM_LIBRARY_ARCHIVE_CREATED", "The archive has been successfully created.");
define("_AM_LIBRARY_ARCHIVE_VIEW", "Archive info");
define("_AM_LIBRARY_ARCHIVE_VIEW_DSC", "Here is the info about this archive.");
define("_AM_LIBRARY_ARCHIVE_NO_ARCHIVE","<strong>Archive status: <span style=\"color:#red;\">None.
    </span></strong> Create an Archive object below if you want to enable the Open Archives Initiative
    Protocol for Metadata Harvesting.<br />");
define("_AM_LIBRARY_ARCHIVE_ONLINE", "<strong>Archive status: <span style=\"color:#green;\">Enabled.
    </span></strong> Library has permission to serve metadata in response to incoming OAIPMH
    requests.");
define("_AM_LIBRARY_ARCHIVE_OFFLINE","<strong>Archive status: <span style=\"color:#red;\"> Offline.
    </span></strong> You must enable archive functionality in module preferences if you want
    Library to serve metadata in response to incoming OAIPMH requests.");

// Instructions
define("_AM_LIBRARY_INSTRUCTIONS_DSC",
		"<h1>Using the Library module</h1>
<h2>Purpose</h2>
<p>The Library module allows you to set up a 'digital library' to share publications and multimedia.
You can use it to:</p>
<ul><li>Publish downloadable books, images, sound recordings, video, software, datasets
and other files.</li>
<li>Display text articles, images, and video on screen and stream audio files.</li>
<li>Publish collections of the above, for example music albums, conference presentations or
scientific journals.</li>
<li>Publish regular Podcast programmes via RSS feed.</li>
<li>Federate your library via the Open Archives Initiative Protocol for Metadata Harvesting.</li></ul>
<h2>Features</h2>
<ul><li>Organise your publications into categories and collections.</li>
<li>Stream audio files, including entire albums.</li>
<li>Individual RSS feeds with media enclosures for each collection and category (W3C validated).</li>
<li>Configurable compact/extended views for collections, the collections index page and publications
index page.</li>
<li>Configurable rights (license) management system and per-publication rights control.</li>
<li>Configurable user-side metadata display - choose what fields you want to show.</li>
<li>Two blocks - recent publications and list of collections.</li>
<li>Can participate in distributed digital library systems and cross-site search -
this module implements the Open Archives Initiative Protocol for Metadata Harvesting.</li>
<li>Use of standard Unqualified Dublin Core fields for object description.</li>
<li>Use of Dublin Core Metadata Initiative Type Vocabulary to contextualise publication display.</li>
<li>Dynamic image resizing using Nachenko's excellent resized_image Smarty plugin (configured in
module preferences).</li>
<li>It's a native IPF module.</li></ul>
<h2>Post-installation set up</h2>
<p>If you wish to organise your collection using categories, then you must install the ImTagging
module first (bundled for convenience) and create one or more categories. Otherwise, Library is
ready to use out of the box.
<h2>Usage</h2>
<p>Start by creating some categories in the ImTagging module, or by creating some collections
under the collections tab. These are used to organise your publications. Then you can start adding
individual publications under the Publication tab. As you add each publication, you can choose which
category and/or collection it should be associated with. It is best to enter as many of the details
as you can, this will make the module look nicer on the user side. Screenshots and cover art are
particularly important, as they provide navigation cues (they are linked) and make the module much
more user friendly.</p>
<h2>Streaming of audio files</h2>
<p>Library lets people start listening to soundtracks without having to download the whole thing
first. Audio files can be situated anywhere, they do not need to be on the same server as your
website. Streaming is handled by dynamically generating a .m3u playlist file containing the track
URL. This launches the client's media player which will request the file from the host server,
wherever that is. Of course, streaming only works if there is an adequate connection speed between
the client and the host server. If the server is (say) a hopelessly overloaded shared webhosting
machine it may not be up to the task.</p><br />
<p>The bitrate at which the track has been recorded is also very important. Obviously a 32KB/s
voice recording is going to be a lot easier to stream than a 128KB/s music track.</p>
<h2>RSS feeds with enclosures</h2>
<p>Each collection you create has its own RSS feed with media file enclosures for downloadable
files. Good feed readers and podcasting clients can automatically download publications
referenced in the RSS feed. For Android-based smartphones, I thoroughly recommend the BeyondPod
podcasting client. The Rythymbox media player in Ubuntu is also capable of downloading soundtracks
automatically.</p>
<h2>Open Archives Initiative Protocol for Metadata Harvesting (OAIPMH)</h2>
<p>OAIPMH is a protocol for automated data exchange via XML. It allows external metadata harvesters
(specialised search engines) to periodically query your site and import your records or
find new and updated ones. It is mainly used by high-end digital library systems and universities
at present. The idea is that libraries that expose their content via OAIPMH can share metadata,
that is to say, they can index each others content. So when a user searches the local database they
can search the content of *all* participating libraries simultaneously. It's basically a mechanism
for enabling cross-site search or federation of content.</p><br />
<p>Library can participate in such distributed digital library systems. All you need to do is to
inform the administrators of OAIPMH harvesters of the base URL for submitting OAIPMH requests to
the library module (see the Base URL field in the Archive tab). You have full control over which
records you share as each publication has a 'federation' switch. Only records set as federated
will be exposed by the OAIPMH service.</p>
<h3>Only metadata is shared, not the underlying publication file</h3>
<p>Please note that the underlying resource (eg. downloadable files) are not shared by the
OAIPMH service, only the metadata (title, description and similar fields), together with a link to
the original source. If someone finds one of your publications in an external library database,
they will be directed back to your site when they access the underlying resource. So your site still
gets a visit and credit for providing the publication, and you also get a back link from external
sites that are hosting your records. Cool, huh?</p>
<h2>Legal responsibilities</h2>
<p>Please respect the copyright and intellectual property rights of others. Enough said?</p>
<h2>Support</h2>
<p>Please direct support questions to the <a href=\"http://community.impresscms.org\">ImpressCMS
Community Forums</a>.</p>
<h2>Copyright notice</h3>
<p>This software is Copyright 2010 by Madfish (Simon Wilkinson), who is the author and rights
holder. The software is distributed free of charge under the
<a href=\"http://www.gnu.org/licenses/old-licenses/gpl-2.0.html\">
GNU General Public License (GPL) Version 2</a>, with provision to use the code in derivative works
under any later version of the GPL.</p>
<h2>Acknowledgements</h2>
<p>This module was developed using the excellent ImBuilding module. It is an expanded version of
the Podcast module.</p>
");

// Test OAIPMH responses
define("_AM_LIBRARY_TEST_IDENTIFY", "Identify");
define("_AM_LIBRARY_TEST_IDENTIFY_DSC", " (provides information about the archive).");
define("_AM_LIBRARY_TEST_GET_RECORD", "GetRecord");
define("_AM_LIBRARY_TEST_GET_RECORD_DSC", " (retrieves a single specified record).");
define("_AM_LIBRARY_TEST_LIST_IDENTIFIERS", "ListIdentifiers");
define("_AM_LIBRARY_TEST_LIST_IDENTIFIERS_DSC", " (retrieves headers of multiple records).");
define("_AM_LIBRARY_TEST_LIST_METADATA_FORMATS", "ListMetadataFormats");
define("_AM_LIBRARY_TEST_LIST_METADATA_FORMATS_DSC", " (displays the available formats metadata can
    be requested in).");
define("_AM_LIBRARY_TEST_LIST_RECORDS", "ListRecords");
define("_AM_LIBRARY_TEST_LIST_RECORDS_DSC", " (retrieves multiple full records, possibly everything).");
define("_AM_LIBRARY_TEST_LIST_SETS", "ListSets");
define("_AM_LIBRARY_TEST_LIST_SETS_DSC", " (displays available sets, currently not supported).");

// Warnings on test-oaipmh response page

define("_AM_LIBRARY_ENTER_RECORDS", "You must enter some publications AND set them as FEDERATED 
    before using the test links below!");

define("_AM_LIBRARY_ARCHIVE_DISABLED", "Open Archive functionality is currently disabled, you must
    turn it on (module preferences) to use the test links!");

// Info

define("_AM_LIBRARY_TEST_OAIPMH",
		"<h1>Testing OAIPMH responses</h1>
<p>The OAIPMH protocol specifies a number of requests that external metadata harvesters can use
to retrieve metadata from your site. The links below allow you to see the XML response of the
Library module to incoming OAIPMH requests. This is best viewed in Firefox, which will show you the
document tree (do not view with Chrome as it does not parse XML).</p>
");

define("_AM_LIBRARY_TEST_MORE_INFO",
		"<p>These will only work if:</p>
<ul><li>The archive functionality is enabled (view the archive object, it is the first setting.)</li>
<li>You have some publications entered into the database.</li>
<li>At least one publication has federation enabled (this exposes the publication via the OAIPMH
service) AND is set as online.</li></ul>
<p>Please note that this is a minimal implementation that does not support sets or resumption
tokens. It does support selective harvesting of records based on time ranges delineated in the
query (see the spec for details about 'from' and 'until' arguments).</p>
<h2>About the Open Archives Initiative Protocol for Metadata Harvesting</h2>
<p>For more information about the OAIPMH, what it does and how to use it, please visit the 
<a href=\"http://www.openarchives.org/OAI/openarchivesprotocol.html\">Open Archives Initiative
website</a>. Detailed specifications are provided there. Of particular interest are the:</p>
<ul><li><a href=\"http://www.openarchives.org/OAI/2.0/guidelines.htm\">
Implementation guidelines</a></li>
<li><a href=\"http://www.openarchives.org/OAI/2.0/guidelines-repository.htm\">
Guidelines for repository implementers</a></li>
<li><a href=\"http://dublincore.org/documents/dces/\">
Dublin Core Metadata Element Set Version 1.1</a></li></ul>
");