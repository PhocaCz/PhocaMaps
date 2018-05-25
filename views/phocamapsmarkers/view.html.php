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
 
class PhocaMapsCpViewPhocaMapsMarkers extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaMapsUtils::setVars('marker');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		
		$paramsC 					= JComponentHelper::getParams('com_phocamaps');
		$this->t['maps_api_key']	= $paramsC->get( 'maps_api_key', '' );
		$this->t['map_type']		= $paramsC->get( 'map_type', 2 );
		//$this->t['load_api_ssl'] 	= $paramsC->get( 'load_api_ssl', 1 );

		JHTML::stylesheet( $this->t['s'] );
		
		foreach ($this->items as &$item) {
			$this->ordering[$item->catid][] = $item->id;
		}
		
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			throw new Exception(implode("\n", $errors), 500);
			return false;
		}
		
		$this->addToolbar();
		parent::display($tpl);
		
	}
	
	function addToolbar() {
	
		require_once JPATH_COMPONENT.'/helpers/phocamapsmarkers.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaMapsMarkersHelper::getActions($this->t, $state->get('filter.marker_id'));
		$user  	= JFactory::getUser();
		$bar 	= JToolbar::getInstance('toolbar');
	
		JToolbarHelper::title( JText::_( 'COM_PHOCAMAPS_MARKERS' ), 'location' );
	
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('phocamapsmarker.add','JTOOLBAR_NEW');
		}
	
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('phocamapsmarker.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::divider();
			JToolbarHelper::custom('phocamapsmarkers.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom('phocamapsmarkers.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList( 'COM_PHOCAMAPS_WARNING_DELETE_ITEMS', 'phocamapsmarkers.delete', 'COM_PHOCAMAPS_DELETE');
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
			'a.id' 			=> JText::_('JGRID_HEADING_ID'),
			'a.catid' 		=> JText::_($this->t['l'] . '_MAP')
		);
	}
}
?>