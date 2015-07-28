<?php
/**
 * Common file of the module included on all pages of the module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

if (!defined("LIBRARY_DIRNAME")) define("LIBRARY_DIRNAME",
		$modversion['dirname'] = basename(dirname(dirname(__FILE__))));
if (!defined("LIBRARY_URL")) define("LIBRARY_URL", ICMS_URL . '/modules/' . LIBRARY_DIRNAME . '/');
if (!defined("LIBRARY_ROOT_PATH")) define("LIBRARY_ROOT_PATH", ICMS_ROOT_PATH .'/modules/'
		. LIBRARY_DIRNAME .'/');
if (!defined("LIBRARY_IMAGES_URL")) define("LIBRARY_IMAGES_URL", LIBRARY_URL . 'images/');
if (!defined("LIBRARY_ADMIN_URL")) define("LIBRARY_ADMIN_URL", LIBRARY_URL . 'admin/');

// Include the common language file of the module
icms_loadLanguageFile('library', 'common');

include_once(LIBRARY_ROOT_PATH . "include/functions.php");

// Creating the module object to make it available throughout the module
$libraryModule = icms_getModuleInfo(LIBRARY_DIRNAME);
if (is_object($libraryModule)) {
	$library_moduleName = $libraryModule->getVar('name');
}

// Find if the user is admin of the module and make this info available throughout the module
$library_isAdmin = icms_userIsAdmin(LIBRARY_DIRNAME);

// Creating the module config array to make it available throughout the module
$libraryConfig = icms_getModuleConfig(LIBRARY_DIRNAME);

// creating the icmsPersistableRegistry to make it available throughout the module
global $icmsPersistableRegistry;
$icmsPersistableRegistry = IcmsPersistableRegistry::getInstance();