<?php
/*
 * @package Joomla 3.8
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Gallery
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

class PhocaMapsPath extends JObject
{
	function __construct() {}
	
	public static function getInstance() {
		static $instance;
		if (!$instance) {
			$instance = new PhocaMapsPath();
			$instance->kml_abs 			= JPATH_ROOT . '/phocamapskml/';
			$instance->kml_rel			= JURI::base(true) . '/phocamapskml/';
			$instance->kml_rel_full		= JURI::base() . 'phocamapskml/';
		}
		return $instance;
	}

	public static function getPath() {
		$instance 	= PhocaMapsPath::getInstance();
		return $instance;
	}

}
?>