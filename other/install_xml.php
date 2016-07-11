<?php
/*********** XML PARAMETERS AND VALUES ************/
$xml_item = "component";// component | template
$xml_file = "phocamaps.xml";		
$xml_name = "com_phocamaps";
$xml_creation_date = "10/07/2016";
$xml_author = "Jan Pavelka (www.phoca.cz)";
$xml_author_email = "";
$xml_author_url = "www.phoca.cz";
$xml_copyright = "Jan Pavelka";
$xml_license = "GNU/GPL";
$xml_version = "3.0.4";
$xml_description = "Phoca Maps";
$xml_copy_file = 1;//Copy other files in to administration area (only for development), ./front, ./language, ./other
$xml_script_file = 'install/script.php';

$xml_menu = array (0 => "COM_PHOCAMAPS", 1 => "option=com_phocamaps", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu.png");

$xml_submenu[0] = array (0 => "COM_PHOCAMAPS_CONTROLPANEL", 1 => "option=com_phocamaps", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu-cp.png", 3 => 'COM_PHOCAMAPS_CONTROLPANEL', 4 => 'phocamapscp');

$xml_submenu[1] = array (0 => "COM_PHOCAMAPS_MAPS", 1 => "option=com_phocamaps&view=phocamapsmaps", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu-map.png", 3 => 'COM_PHOCAMAPS_MAPS', 4 => 'phocamapsmaps');

$xml_submenu[2] = array (0 => "COM_PHOCAMAPS_MARKERS", 1 => "option=com_phocamaps&view=phocamapsmarkers", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu-marker.png", 3 => 'COM_PHOCAMAPS_MARKERS', 4 => 'phocamapsmarkers');

$xml_submenu[3] = array (0 => "COM_PHOCAMAPS_ICONS", 1 => "option=com_phocamaps&view=phocamapsicons", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu-icon.png", 3 => 'COM_PHOCAMAPS_ICONS', 4 => 'phocamapsicons');

$xml_submenu[4] = array (0 => "COM_PHOCAMAPS_INFO", 1 => "option=com_phocamaps&view=phocamapsinfo", 2 => "media/com_phocamaps/images/administrator/images/icon-16-pmap-menu-info.png", 3 => 'COM_PHOCAMAPS_INFO', 4 => 'phocamapsinfo');

$xml_install_file = 'install.phocamaps.php'; 
$xml_uninstall_file = 'uninstall.phocamaps.php';
/*********** XML PARAMETERS AND VALUES ************/
?>