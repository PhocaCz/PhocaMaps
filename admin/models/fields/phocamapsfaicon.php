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
use Joomla\CMS\Factory;
use Joomla\CMS\Form\Field\TextField;
use Joomla\CMS\Language\Text;

/*
 * Marker icon with Font Awesome icon picker (icons of Font Awesome bundled with Joomla)
 * prefixfield, classfield ... names of the icon prefix and icon class fields filled by the picker
 * colorfield, iconcolorfield, spinfield ... names of the fields used in the marker preview
 */
class JFormFieldPhocaMapsFaicon extends TextField
{
	protected $type 		= 'PhocaMapsFaicon';

	protected function getInput() {

		$document	= Factory::getApplication()->getDocument();
		$wa			= $document->getWebAssetManager();

		$prefix		= $this->form->getField((string)$this->element['prefixfield']);
		$class		= $this->form->getField((string)$this->element['classfield']);
		if (!$prefix || !$class) {
			return parent::getInput();
		}

		if (empty($document->getScriptOptions('com_phocamaps.faicons'))) {
			$document->addScriptOptions('com_phocamaps.faicons', $this->getIcons(), false);
		}

		$data = '';
		foreach (array('color' => 'colorfield', 'icon-color' => 'iconcolorfield', 'spin' => 'spinfield') as $attribute => $name) {
			$previewField = $this->form->getField((string)$this->element[$name]);
			if ($previewField) {
				$data .= ' data-' . $attribute . '="' . htmlspecialchars($previewField->id, ENT_QUOTES, 'UTF-8') . '"';
			}
		}

		$wa->useStyle('fontawesome');
		$wa->registerAndUseStyle('com_phocamaps.leaflet.awesome', 'media/com_phocamaps/js/leaflet-awesome/leaflet.awesome-markers.css', array('version' => 'auto'));
		$wa->registerAndUseStyle('com_phocamaps.faicon', 'media/com_phocamaps/css/administrator/faicon.css', array('version' => 'auto'));
		$wa->registerAndUseScript('com_phocamaps.faicon', 'media/com_phocamaps/js/administrator/faicon.js', array('version' => 'auto'), array('defer' => true), array('core'));

		return '<div class="phocamaps-fa-picker" data-prefix="' . htmlspecialchars($prefix->id, ENT_QUOTES, 'UTF-8')
			. '" data-class="' . htmlspecialchars($class->id, ENT_QUOTES, 'UTF-8') . '"' . $data . '>'
			. parent::getInput()
			. '<details><summary><span class="phocamaps-fa-preview" aria-hidden="true"><span class="awesome-marker"><i></i></span></span>' . Text::_('COM_PHOCAMAPS_SELECT_FA_ICON') . '</summary>'
			. '<label>' . Text::_('JSEARCH_FILTER') . '<input type="search" class="form-control phocamaps-fa-filter" autocomplete="off"></label>'
			. '<div class="phocamaps-fa-status" role="status"></div><div class="phocamaps-fa-grid"></div></details>'
			. '</div>';
	}

	protected function getIcons() {

		$icons	= array();
		$file	= JPATH_ROOT . '/media/vendor/fontawesome-free/scss/_variables.scss';
		$source	= is_file($file) ? file_get_contents($file) : '';

		foreach (array('fa-icons' => 'fa-solid', 'fa-brand-icons' => 'fa-brands') as $map => $style) {
			if (preg_match('/\$' . preg_quote($map, '/') . ':\s*\((.*?)\n\);/s', $source, $matches)) {
				preg_match_all('/"([a-z0-9-]+)"\s*:\s*\$fa-var-([a-z0-9-]+)/', $matches[1], $names, PREG_SET_ORDER);
				foreach ($names as $name) {
					$icons[] = array('name' => $name[1], 'style' => $style);
				}
			}
		}
		return $icons;
	}
}
?>