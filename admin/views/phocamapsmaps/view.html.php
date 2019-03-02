<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view' );
 
class PhocaMapsCpViewPhocaMapsMaps extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaMapsUtils::setVars('map');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$paramsC 					= JComponentHelper::getParams('com_phocamaps');
		$this->t['maps_api_key']	= $paramsC->get( 'maps_api_key', '' );
		$this->t['map_type']		= $paramsC->get( 'map_type', 2 );
		//$this->t['load_api_ssl'] 	= $paramsC->get( 'load_api_ssl', 1 );
		
		// Preprocess the list of items to find ordering divisions.
		foreach ($this->items as &$item) {
			$this->ordering[0][] = $item->id;
		}

		JHTML::stylesheet( $this->t['s'] );
		JHTML::stylesheet( $this->t['css'] . 'icomoon/icomoon.css' );
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocamapsmaps.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaMapsMapsHelper::getActions($this->t, $state->get('filter.map_id'));
		$user  	= JFactory::getUser();
		$bar 	= JToolbar::getInstance('toolbar');
	
		JToolbarHelper::title( JText::_( 'COM_PHOCAMAPS_MAPS' ), 'ph-earth' );
	
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('phocamapsmap.add','JTOOLBAR_NEW');
		}
	
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('phocamapsmap.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::divider();
			JToolbarHelper::custom('phocamapsmaps.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom('phocamapsmaps.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList( 'COM_PHOCAMAPS_WARNING_DELETE_ITEMS' , 'phocamapsmaps.delete', 'COM_PHOCAMAPS_DELETE');
		}
		
		// Add a batch button
		if ($user->authorise('core.edit'))
		{
			JHtml::_('bootstrap.renderModal', 'collapseModal');
			$title = JText::_('JTOOLBAR_BATCH');
			$dhtml = "<button data-toggle=\"modal\" data-target=\"#collapseModal\" class=\"btn btn-small\">
						<i class=\"icon-checkbox-partial\" title=\"$title\"></i>
						$title</button>";
			$bar->appendButton('Custom', $dhtml, 'batch');
		}
		
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.phocamaps', true );
	}
	
	protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'a.published' 	=> JText::_($this->t['l'] . '_PUBLISHED'),
			'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID')
		);
	}
}
?>