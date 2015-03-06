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
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
if (!JFactory::getUser()->authorise('core.manage', 'com_phocamaps')) {
	return JError::raiseWarning(404, JText::_('JERROR_ALERTNOAUTHOR'));
}
jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocamaps.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocamapsmap.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocamapsutils.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'phocamapsrenderadmin.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'renderadminview.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'renderadminviews.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'map.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'html'.DS.'batch.php' );

jimport('joomla.application.component.controller');
$controller	= JControllerLegacy::getInstance('PhocaMapsCp');
$controller->execute(JFactory::getApplication()->input->get('task'));
$controller->redirect();
?>