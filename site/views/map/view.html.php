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
class PhocaMapsViewMap extends JViewLegacy
{
	protected $t;
	protected $map;
	protected $marker;
	
	function display($tpl = null) {
		
		$document		= JFactory::getDocument();		
		$app			= JFactory::getApplication();
		$this->t['p']	= $app->getParams();
		
		// PLUGIN WINDOW - we get information from plugin
		$get			= '';
		$get['tmpl']	= $app->input->get( 'tmpl', '', 'string' );
		
		JHTML::stylesheet('media/com_phocamaps/css/phocamaps.css' );
		if (JFile::exists(JPATH_SITE.'/media/com_phocamaps/css/custom.css')) {
			JHTML::stylesheet('media/com_phocamaps/css/custom.css' );
		}
		$this->t['enable_kml']				= $this->t['p']->get( 'enable_kml', 0 );
		$this->t['display_print_route']		= $this->t['p']->get( 'display_print_route', 1 );
		$this->t['close_opened_window']		= $this->t['p']->get( 'close_opened_window', 0 );
		$this->t['load_api_ssl']			= (int)$this->t['p']->get( 'load_api_ssl', 0 );
		// Moved to marker table
		//$this->t['width_marker_content']	= $this->t['p']->get( 'width_marker_content', '' );
		//$this->t['height_marker_content']	= $this->t['p']->get( 'height_marker_content', '' );
		//$this->t['open_marker_window']		= $this->t['p']->get( 'open_marker_window', 0 );
			
		// MODEL
		$model			= $this->getModel();
		$item			= $model->getData();
		$this->map		= $item['map'];
		$this->marker	= $item['marker'];
		
		
		
		if( (!isset($this->map)) || (isset($this->map) && $this->map == null) ) {
			echo '<div id="phocamaps"><div class="error">'.JText::_('COM_PHOCAMAPS_WARNING_SELECT_MAP').'</div></div>';
			return true;
		}
		
		// Plugin information
		$this->t['pluginmap'] = 0;
		if (isset($get['tmpl']) && $get['tmpl'] == 'component') {
			$this->t['pluginmap'] = 1;
			// NO SCROLLBAR if windows is called by plugin but if there is a route form, display it
			if (isset($this->map->displayroute) && $this->map->displayroute == 1) {
				$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{background:#ffffff;text-align:left;} \n" 
			." </style> \n");
			} else {
				$document->addCustomTag( "<style type=\"text/css\"> \n" 
			." html,body, .contentpane{overflow:hidden;background:#ffffff;} \n" 
			." </style> \n");
			}
		}
		
		// Display Description
		$this->t['description'] = '';
		if (isset($this->map->description) && $this->map->description != '' && $this->t['pluginmap'] == 0) {
			$this->t['description'] = '<div class="pm-desc">'.$this->map->description.'</div>';
		}
		
		// Check Width and Height
		$this->t['fullwidth'] = 0;
		if (!isset($this->map->width)) {
			$this->map->width = 400;
		}
		if (isset($this->map->width) && (int)$this->map->width < 1) {
			$this->t['fullwidth'] = 1;
		}
		if (!isset($this->map->height) || (isset($this->map->height) && (int)$this->map->height < 1)) {
			$this->map->height = 400;
		}
		if (!isset($this->map->zoom) || (isset($this->map->zoom) && (int)$this->map->zoom < 1)) {
			$this->map->zoom = 2;
		}
		
		// Map Langugage
		
		
		$this->t['params'] = '';
		if (!isset($this->map->lang) || (isset($this->map->lang) && $this->map->lang == '')) {
			$this->t['params'] 		= '{other_params:"sensor=false"}';
			$this->t['paramssearch'] 	= '';
			$this->t['lang']			= '';
		} else {
			//$this->t['params'] = '{"language":"'.$this->map->lang.'", "other_params":"sensor=false"}';
			$this->t['params'] 		= '{other_params:"sensor=false&language='.$this->map->lang.'"}';
			$this->t['paramssearch'] 	= '{"language":"'.$this->map->lang.'"}';
			$this->t['lang']			= $this->map->lang;
		}
		
		// Design
		$this->t['border'] = '';
		if (isset($this->map->border)) {
			switch ($this->map->border) {
				case 1:
					$this->t['border'] = '-grey';
				break;
				case 2:
					$this->t['border'] = '-greywb';
				break;
				case 3:
					$this->t['border'] = '-greyrc';
				break;
				case 4:
					$this->t['border'] = '-black';
				break;
			}
		}
		
		// Plugin - no border
		if ($this->t['pluginmap'] == 1) {
			$this->t['border'] 	= '';
			$this->t['stylesite'] 	= 'margin:10px;';
		} else {
			$this->t['stylesite'] 	= 'margin:0;padding:0;margin-top:10px;';
		}
		
		$this->t['stylesitewidth']	= '';
		if ($this->t['fullwidth'] == 1) {
			$this->t['stylesitewidth'] = 'style="width:100%"';
		}
		
		// Parameters
		if (isset($this->map->continuouszoom) && (int)$this->map->continuouszoom == 1) {
			$this->map->continuouszoom = 1;
		} else {
			$this->map->continuouszoom = 0;
		}
		
		if (isset($this->map->doubleclickzoom) && (int)$this->map->doubleclickzoom == 1) {
			$this->map->disabledoubleclickzoom = 0;
		} else {
			$this->map->disabledoubleclickzoom = 1;
		}
		
		if (isset($this->map->scrollwheelzoom) && (int)$this->map->scrollwheelzoom == 1) {
			$this->map->scrollwheelzoom = 1;
		} else {
			$this->map->scrollwheelzoom = 0;
		}
		
		// Since 1.1.0 zoomcontrol is alias for navigationcontrol
		if (empty($this->map->zoomcontrol)) {
			$this->map->zoomcontrol = 0;
		}
		
		if (empty($this->map->scalecontrol)) {
			$this->map->scalecontrol = 0;
		}
		
		if (empty($this->map->typecontrol)) {
			$this->map->typecontrol = 0;
		}
		if (empty($this->map->typecontrolposition)) {
			$this->map->typecontrolposition = 0;
		}
		
		
		if (empty($this->map->typeid)) {
			$this->map->typeid = 0;
		}
		
		
		// Display Direction
		$this->t['displaydir'] = 0;
		if (isset($this->map->displayroute) && $this->map->displayroute == 1) {
			if (isset($this->marker) && !empty($this->marker)) {
				$this->t['displaydir'] = 1;
			}
		}
		
		// KML Support
		$this->t['load_kml'] = FALSE;
		if($this->t['enable_kml'] == 1) {
			jimport( 'joomla.filesystem.folder' ); 
			jimport( 'joomla.filesystem.file' );
			$path = PhocaMapsPath::getPath();
			if (isset($this->map->kmlfile) && JFile::exists($path->kml_abs . $this->map->kmlfile)) {
				$this->t['load_kml'] = $path->kml_rel_full . $this->map->kmlfile;
			}
		}
		
		
		parent::display($tpl);
	}
}
