<?php
/**
 * Configuring the admin side menu for the module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

$i = 0;

$adminmenu[$i]['title'] = _MI_LIBRARY_PUBLICATIONS;
$adminmenu[$i]['link'] = 'admin/publication.php';

$i++;
$adminmenu[$i]['title'] = _MI_LIBRARY_COLLECTIONS;
$adminmenu[$i]['link'] = 'admin/collection.php';

$i++;
$adminmenu[$i]['title'] = _MI_LIBRARY_ARCHIVES;
$adminmenu[$i]['link'] = 'admin/archive.php';

global $icmsConfig;

$libraryModule = icms_getModuleInfo(basename(dirname(dirname(__FILE__))));

if (isset($libraryModule)) {

	$i = 0;
	
	$headermenu[$i]['title'] = _CO_ICMS_GOTOMODULE;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/' . $libraryModule->dirname();

	$i++;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod='
		. $libraryModule->mid();

	$i++;
	$headermenu[$i]['title'] = _MI_LIBRARY_TEMPLATES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=tplsets&op=listtpl&tplset='
		. $icmsConfig['template_set'] . '&moddir=' . $libraryModule->dirname();
	
	$i++;
	$headermenu[$i]['title'] = _CO_ICMS_UPDATE_MODULE;
	$headermenu[$i]['link'] = ICMS_URL
		. '/modules/system/admin.php?fct=modulesadmin&op=update&module='
		. $libraryModule->dirname();

	$i++;
	$headermenu[$i]['title'] = _MI_LIBRARY_TEST_OAIPMH;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/' . $libraryModule->dirname()
		. '/admin/test_oaipmh.php';

	$i++;
	$headermenu[$i]['title'] = _MODABOUT_ABOUT;
	$headermenu[$i]['link'] = ICMS_URL . '/modules/' . $libraryModule->dirname()
		. '/admin/about.php';
}
