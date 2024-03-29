<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
use Joomla\CMS\MVC\View\HtmlView;
use Joomla\CMS\Toolbar\ToolbarHelper;
use Joomla\CMS\Language\Text;
jimport( 'joomla.application.component.view' );

class PhocaMapsCpViewPhocaMapsIcons extends HtmlView
{
	protected $items;
	protected $pagination;
	protected $state;
	protected $t;
	protected $r;
	public $filterForm;
    public $activeFilters;

	function display($tpl = null) {

		$this->t			= PhocaMapsUtils::setVars('icon');
		$this->r		    = new PhocaMapsRenderAdminviews();
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
		$this->filterForm   = $this->get('FilterForm');
        $this->activeFilters = $this->get('ActiveFilters');



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

		ToolbarHelper::title( Text::_( 'COM_PHOCAMAPS_ICONS_EXT' ), 'pin' );

		if ($canDo->get('core.create')) {
			ToolbarHelper::addNew('phocamapsicon.add','JTOOLBAR_NEW');
		}

		if ($canDo->get('core.edit')) {
			ToolbarHelper::editList('phocamapsicon.edit','JTOOLBAR_EDIT');
		}
		if ($canDo->get('core.edit.state')) {

			ToolbarHelper::divider();
			ToolbarHelper::custom('phocamapsicons.publish', 'publish.png', 'publish_f2.png','JTOOLBAR_PUBLISH', true);
			ToolbarHelper::custom('phocamapsicons.unpublish', 'unpublish.png', 'unpublish_f2.png', 'JTOOLBAR_UNPUBLISH', true);
		}

		if ($canDo->get('core.delete')) {
			ToolbarHelper::deleteList( 'COM_PHOCAMAPS_WARNING_DELETE_ITEMS' , 'phocamapsicons.delete', 'COM_PHOCAMAPS_DELETE');
		}
		ToolbarHelper::divider();
		ToolbarHelper::help( 'screen.phocamaps', true );
	}

		protected function getSortFields() {
		return array(
			'a.ordering'	=> Text::_('JGRID_HEADING_ORDERING'),
			'a.title' 		=> Text::_($this->t['l'] . '_TITLE'),
			'a.published' 	=> Text::_($this->t['l'] . '_PUBLISHED'),
			//'language' 		=> JText::_('JGRID_HEADING_LANGUAGE'),
			'a.id' 			=> Text::_('JGRID_HEADING_ID'),
			'a.url' 		=> Text::_($this->t['l'] . '_URL')
		);
	}
}
?>
