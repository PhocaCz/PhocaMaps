<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');

class PhocaMapsCpViewPhocaMapsGMap extends JViewLegacy
{
	protected $latitude;
	protected $longitude;
	protected $zoom;
	protected $type;
	protected $t;
	protected $p;
	
	public function display($tpl = null) {

		$paramsC			= JComponentHelper::getParams('com_phocamaps') ;
		$app 				= JFactory::getApplication();
		
		$this->t	= PhocaMapsUtils::setVars();
		JHTML::stylesheet( $this->t['s'] );
		$this->latitude			= $app->input->get( 'lat', '50', 'get', 'string' );
		$this->longitude		= $app->input->get( 'lng', '-30', 'get', 'string' );
		$this->zoom				= $app->input->get( 'zoom', '2', 'get', 'string' );
		$this->type				= $app->input->get( 'type', 'map', 'get', 'string' );
		
		$this->p['enable_ssl'] 	= $paramsC->get('load_api_ssl', 0);
		$this->p['map_type']	= $paramsC->get( 'map_type', 2 );
		
		$document	= JFactory::getDocument();
		$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:#ffffff;} \n" 
			." </style> \n");
		
		
		
		
		if ($this->p['map_type'] == 2) {
			parent::display('osm');
		} else {
			parent::display($tpl);
		}
	}
}
?>