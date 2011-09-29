<?php
/**
 * Check requirements of the module
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$failed_requirements = array();

/* ImpressCMS Builtd needs to be at lest 19 */
if (ICMS_VERSION_BUILD < 19) {
	$failed_requirements[] = _AM_LIBRARY_REQUIREMENTS_ICMS_BUILD;
}

if (count($failed_requirements) > 0) {
	icms_cp_header();
	$icmsAdminTpl->assign('failed_requirements', $failed_requirements);
	$icmsAdminTpl->display(LIBRARY_ROOT_PATH . 'templates/library_requirements.html');
	icms_cp_footer();
	exit;
}

/* Library needs imTagging */
$imtaggingModule = icms_getModuleInfo('imtagging');
if (!$imtaggingModule) {
	$failed_requirements[] = _AM_LIBRARY_REQUIREMENTS_IMTAGGING;
}
if (count($failed_requirements) > 0) {
	icms_cp_header();
	$icmsAdminTpl->assign('failed_requirements', $failed_requirements);
	$icmsAdminTpl->display(LIBRARY_ROOT_PATH . 'templates/library_requirements.html');
	icms_cp_footer();
	exit;
}