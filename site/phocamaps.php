<?php
/*
 * @package Joomla 1.5
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
if(!defined('DS')) define('DS', DIRECTORY_SEPARATOR);
require_once( JPATH_COMPONENT.DS.'controller.php' );
require_once( JPATH_COMPONENT.DS.'helpers'.DS.'route.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocamaps'.DS.'helpers'.DS.'phocamapspath.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocamaps'.DS.'helpers'.DS.'phocamaps.php' );
require_once( JPATH_ADMINISTRATOR.DS.'components'.DS.'com_phocamaps'.DS.'helpers'.DS.'phocamapsmap.php' );

// Require specific controller if requested
if($controller = JRequest::getWord('controller')) {
    $path = JPATH_COMPONENT.DS.'controllers'.DS.$controller.'.php';
    if (file_exists($path)) {
        require_once $path;
    } else {
        $controller = '';
    }
}
// Create the controller
$classname    = 'PhocaMapsController'.ucfirst($controller);
$controller   = new $classname( );

// Perform the Request task
$controller->execute( JFactory::getApplication()->input->get('task') );

// Redirect if set by the controller
$controller->redirect();
?>