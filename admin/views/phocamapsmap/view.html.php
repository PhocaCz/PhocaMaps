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

class phocaMapsCpViewPhocaMapsMap extends JViewLegacy
{
	protected $state;
	protected $item;
	protected $form;
	protected $t;

	public function display($tpl = null) {
		
		$this->t		= PhocaMapsUtils::setVars('map');
		$this->state	= $this->get('State');
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		JHTML::stylesheet( $this->t['s'] );
		JHTML::stylesheet( $this->t['css'] . 'icomoon/icomoon.css' );
		$this->addToolbar();
		parent::display($tpl);
	}
	
	protected function addToolbar() {
		
		require_once JPATH_COMPONENT.'/helpers/phocamapsmaps.php';
		JFactory::getApplication()->input->set('hidemainmenu', true);

		$user		= JFactory::getUser();
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $user->get('id'));
		//$canDo		= PhocamapsMapsHelper::getActions($this->state->get('filter.map_id'), $this->item->id);
		$canDo 		= PhocamapsMapsHelper::getActions($this->t, $this->state->get('filter.map_id'));
		//$paramsC 	= JComponentHelper::getParams('COM_PHOCAMAPS');

		

		$text = $isNew ? JText::_( 'COM_PHOCAMAPS_NEW' ) : JText::_('COM_PHOCAMAPS_EDIT');
		JToolbarHelper::title(   JText::_( 'COM_PHOCAMAPS_MAP' ).': <small><small>[ ' . $text.' ]</small></small>' , 'ph-earth');

		// If not checked out, can save the item.
		if (!$checkedOut && $canDo->get('core.edit')){
			JToolbarHelper::apply('phocamapsmap.apply', 'JTOOLBAR_APPLY');
			JToolbarHelper::save('phocamapsmap.save', 'JTOOLBAR_SAVE');
			JToolbarHelper::addNew('phocamapsmap.save2new', 'JTOOLBAR_SAVE_AND_NEW');
		}
	
		if (empty($this->item->id))  {
			JToolbarHelper::cancel('phocamapsmap.cancel', 'JTOOLBAR_CANCEL');
		}
		else {
			JToolbarHelper::cancel('phocamapsmap.cancel', 'JTOOLBAR_CLOSE');
		}

		JToolbarHelper::divider();
		JToolbarHelper::help( 'screen.phocamaps', true );
	}
}
?>
