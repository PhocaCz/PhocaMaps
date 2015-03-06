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
defined('_JEXEC') or die();
class PhocaMapsHelper
{	
	public static function strTrimAll($input) {
		$output	= '';;
	    $input	= trim($input);
	    for($i=0;$i<strlen($input);$i++) {
	        if(substr($input, $i, 1) != " ") {
	            $output .= trim(substr($input, $i, 1));
	        } else {
	            $output .= " ";
	        }
	    }
	    return $output;
	}

	public static function getPhocaVersion($component = 'com_phocamaps') {
		$component = 'com_phocamaps';
		$folder = JPATH_ADMINISTRATOR .DS. 'components'.DS.$component;
		
		if (JFolder::exists($folder)) {
			$xmlFilesInDir = JFolder::files($folder, '.xml$');
		} else {
			$folder = JPATH_SITE .DS. 'components'.DS.$component;
			if (JFolder::exists($folder)) {
				$xmlFilesInDir = JFolder::files($folder, '.xml$');
			} else {
				$xmlFilesInDir = null;
			}
		}

		$xml_items = '';
		if (count($xmlFilesInDir))
		{
			foreach ($xmlFilesInDir as $xmlfile)
			{
				if ($data = JApplicationHelper::parseXMLInstallFile($folder.DS.$xmlfile)) {
					foreach($data as $key => $value) {
						$xml_items[$key] = $value;
					}
				}
			}
		}
		
		if (isset($xml_items['version']) && $xml_items['version'] != '' ) {
			return $xml_items['version'];
		} else {
			return '';
		}
	}
	
	public static function getInfo() {
		return '<div style="text-align: right; color: rgb(211, 211, 211); clear: both; margin-top: 10px;margin-bottom:10px;">Powered by <a href="http://www.phoca.cz" style="text-decoration: none;" target="_blank" title="Phoca.cz">Phoca</a> <a href="http://www.phoca.cz/phocamaps" style="text-decoration: none;" target="_blank" title="Phoca Maps">Maps</a></div>';	
	}
	
	public static function getAliasName($name) {
		
	}
	
	public static function fixImagePath($description) {
		$description = str_replace('<img src="'.JURI::root(true).'/', '', $description);// no double
		$description = str_replace('<img src="', '<img src="'.JURI::root(true).'/', $description);
		return $description;
	}
}
?>