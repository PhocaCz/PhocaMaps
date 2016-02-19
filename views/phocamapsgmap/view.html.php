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
	protected $enable_ssl;
	
	public function display($tpl = null) {

		$this->t	= PhocaMapsUtils::setVars();
		JHTML::stylesheet( $this->t['s'] );
		$this->latitude		= JRequest::getVar( 'lat', '50', 'get', 'string' );
		$this->longitude	= JRequest::getVar( 'lng', '-30', 'get', 'string' );
		$this->zoom			= JRequest::getVar( 'zoom', '2', 'get', 'string' );
		$this->type			= JRequest::getVar( 'type', 'map', 'get', 'string' );
		$this->enable_ssl = JComponentHelper::getParams('com_phocamaps')->get('load_api_ssl');
		
		$document	= JFactory::getDocument();
		$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:#ffffff;} \n" 
			." </style> \n");
		
		parent::display($tpl);
	}
}
?>
