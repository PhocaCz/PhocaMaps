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
 
class PhocaMapsCpViewPhocaMapsIcons extends JViewLegacy
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	
	function display($tpl = null) {
		
		$this->t			= PhocaMapsUtils::setVars('icon');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');

		JHTML::stylesheet( $this->t['s'] );
		
		foreach ($this->items as &$item) {
			$this->ordering[0][] = $item->id;
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
	
		require_once JPATH_COMPONENT.'/helpers/phocamapsicons.php';
	
		$state	= $this->get('State');
		$canDo	= PhocaMapsIconsHelper::getActions($this->t, $state->get('filter.icon_id'));
	
		JToolbarHelper::title( JText::_( 'COM_PHOCAMAPS_ICONS_EXT' ), 'pin' );
	
		if ($canDo->get('core.create')) {
			JToolbarHelper::addNew('phocamapsicon.add','JTOOLBAR_NEW');
		}
	
		if ($canDo->get('core.edit')) {
			JToolbarHelper::editList('phocamapsicon.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			JToolbarHelper::divider();
			JToolbarHelper::custom('phocamapsicons.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			JToolbarHelper::custom('phocamapsicons.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}
	
		if ($canDo->get('core.delete')) {
			JToolbarHelper::deleteList( 'COM_PHOCAMAPS_WARNING_DELETE_ITEMS' , 'phocamapsicons.delete', 'COM_PHOCAMAPS_DELETE');
		}
		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.phocamaps', true );
	}
	
		protected function getSortFields() {
		return array(
			'a.ordering'	=> JText::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> JText::_($this->t['l'] . '_TITLE'),
			'a.published' 	=> JText::_($this->t['l'] . '_PUBLISHED'),
			//'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> JText::_('JGRID_HEADING_ID'),
			'a.url' 		=> JText::_($this->t['l'] . '_URL')
		);
	}
}
?>