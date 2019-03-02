<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die;
if (!JFactory::getUser()->authorise('core.manage', 'com_phocamaps')) {
	throw new Exception(JText::_('COM_PHOCAMAPS_ERROR_ALERTNOAUTHOR'), 404);
	return false;
}
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
require_once( JPATH_COMPONENT.'/controller.php' );
require_once( JPATH_COMPONENT.'/helpers/phocamaps.php' );
require_once( JPATH_COMPONENT.'/helpers/phocamapsmap.php' );
require_once( JPATH_COMPONENT.'/helpers/phocamapsmaposm.php' );
require_once( JPATH_COMPONENT.'/helpers/phocamapsutils.php' );
require_once( JPATH_COMPONENT.'/helpers/phocamapsrenderadmin.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadminview.php' );
require_once( JPATH_COMPONENT.'/helpers/renderadminviews.php' );
require_once( JPATH_COMPONENT.'/helpers/html/map.php' );
require_once( JPATH_COMPONENT.'/helpers/html/batch.php' );

jimport('joomla.application.component.controller');
$controller	= JControllerLegacy::getInstance('PhocaMapsCp');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>