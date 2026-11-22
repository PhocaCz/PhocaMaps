<?php
/*
 * @package		Joomla.Framework
 * @copyright	Copyright (C) 2005 - 2010 Open Source Matters, Inc. All rights reserved.
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License version 2 or later;
 */
defined('_JEXEC') or die();
use Joomla\CMS\Form\FormField;

require_once JPATH_ADMINISTRATOR.'/components/com_phocamaps/helpers/phocamapstag.php';

class JFormFieldPhocaMapsTags extends FormField
{
	protected $type 		= 'PhocaMapsTags';
	protected $layout		= 'joomla.form.field.list-fancy-select';

	protected function getInput() {

		// Marker form: selected tags are stored in tags reference table, other forms (menu item): field value
		if ($this->form->getName() == 'com_phocamaps.phocamapsmarker') {
			$id 		= (int) $this->form->getValue('id');
			$activeTags = array();
			if ($id > 0) {
				$activeTags	= PhocaMapsTag::getTags($id, 1);
			}
		} else {
			$activeTags = (array)$this->value;
		}

		$data               = $this->getLayoutData();
		$data['options']    = (array)PhocaMapsTag::getAllTags();
		$data['value']      = $activeTags;

		return $this->getRenderer($this->layout)->render($data);
	}
}
?>