<?php
/**
 * Footer page included at the end of each page on user side of the mdoule
 *
 * @copyright	GPL 2.0 or later
 * @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
 * @since		1.0
 * @author		Madfish <simon@isengard.biz>
 * @package		library
 * @version		$Id$
 */

if (!defined("ICMS_ROOT_PATH")) die("ICMS root path not defined");

$icmsTpl->assign("library_adminpage", library_getModuleAdminLink());
$icmsTpl->assign("library_is_admin", $library_isAdmin);
$icmsTpl->assign('library_url', LIBRARY_URL);
$icmsTpl->assign('library_images_url', LIBRARY_IMAGES_URL);

$xoTheme->addStylesheet(LIBRARY_URL . 'module'.((defined("_ADM_USE_RTL") &&
	_ADM_USE_RTL)?'_rtl':'').'.css');

include_once(ICMS_ROOT_PATH . '/footer.php');