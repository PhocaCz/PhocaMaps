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
defined('JPATH_BASE') or die;
jimport('joomla.form.formfield');

class JFormFieldPhocaSelectMap extends JFormField
{
	public $type = 'PhocaSelectMap';

	protected function getInput()
	{
		// Initialize variables.
		$html = array();
		
		// Initialize some field attributes.
		$size		= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
		$maxLength	= $this->element['maxlength'] ? ' maxlength="'.(int) $this->element['maxlength'].'"' : '';
		$class		= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
		$readonly	= ((string) $this->element['readonly'] == 'true') ? ' readonly="readonly"' : '';
		$disabled	= ((string) $this->element['disabled'] == 'true') ? ' disabled="disabled"' : '';
		
		$maptype	= ( (string)$this->element['maptype'] ? $this->element['maptype'] : '' );
		
		if ($this->id == 'jform_latitude') {
			// One link for latitude, longitude, zoom
			$lat	= $this->form->getValue('latitude');
			$lng	= $this->form->getValue('longitude');
			$zoom	= $this->form->getValue('zoom');
			$suffix	= '';
			if ($lat != '') { $suffix .= '&amp;lat='.$lat;}
			if ($lng != '') { $suffix .= '&amp;lng='.$lng;}
			if ($zoom != '' && (int)$zoom > 0) { $suffix .= '&amp;zoom='.$zoom;}
			if ($maptype != '') { $suffix .= '&amp;type='.$maptype;}
			
			$link = 'index.php?option=com_phocamaps&amp;view=phocamapsgmap&amp;tmpl=component&amp;field='.$this->id. $suffix;
		
			// Load the modal behavior script.
			JHtml::_('behavior.modal', 'a.modal_'.$this->id);
		
		}
		
		// Initialize JavaScript field attributes.
		$onchange = (string) $this->element['onchange'];
		$onchangeOutput = ' onChange="'.(string) $this->element['onchange'].'"';

		$idA	= 'pgselectmap';
		
		// Build the script.
		$script = array();
		$script[] = '	function phocaSelectMap_'.$this->id.'(title) {';
		$script[] = '		document.getElementById("'.$this->id.'_id").value = title;';
		$script[] = '		'.$onchange;
		//$script[] = '		SqueezeBox.close();';
		$script[] = '	}';
		
		
		// Hide Info box on start
		if ($this->id == 'jform_latitude') {
			$script[] = ' jQuery(document).ready(function() {';
			$script[] = '    jQuery(\'#'.$idA.'\').on(\'shown.bs.modal\', function () {';
			$script[] = '	    jQuery(\'#phmPopupInfo\').html(\'\');';
			$script[] = '	  })';
			$script[] = ' })';
		}
		

		// Add the script to the document head.
		JFactory::getDocument()->addScriptDeclaration(implode("\n", $script));
		
		if ($this->id == 'jform_latitude') {
			/*$html[] = '<div class="input-append">';
			$html[] = '<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$class.$size.$disabled.$readonly.$onchangeOutput.$maxLength.' />';
			$html[] = '<a class="modal_'.$this->id.' btn" title="'.JText::_('COM_PHOCAMAPS_FORM_SELECT_COORDINATES').'"'
					.' href="'.($this->element['readonly'] ? '' : $link).'"'
					.' rel="{handler: \'iframe\', size: {x: 780, y: 580}}">'
					. JText::_('COM_PHOCAMAPS_FORM_SELECT_COORDINATES').'</a>';
			$html[] = '</div>'. "\n";*/
			
			$html[] = '<div class="input-append">';
			$html[] = '<span class="input-append"><input type="text" id="' . $this->id . '_id" name="' . $this->name . '"'
				. ' value="' . $this->value . '" '.$class.$size.$disabled.$readonly.$onchangeOutput.$maxLength.' />';
			$html[] = '<a href="#'.$idA.'" role="button" class="btn " data-toggle="modal" title="' . JText::_('COM_PHOCAMAPS_FORM_SELECT_COORDINATES') . '">'
				. '<span class="icon-list icon-white"></span> '
				. JText::_('COM_PHOCAMAPS_FORM_SELECT_COORDINATES') . '</a></span>';
			$html[] = '</div>'. "\n";		
			
			$html[] = JHtml::_(
				'bootstrap.renderModal',
				$idA,
				array(
					'url'    => $link,
					'title'  => JText::_('COM_PHOCAMAPS_FORM_SELECT_COORDINATES'),
					'width'  => '780px',
					'height' => '580px',
					'modalWidth' => '50',
					'bodyHeight' => '70',
					'footer' => '<div id="phmPopupInfo" class="ph-info-modal"></div><button type="button" class="btn" data-dismiss="modal" aria-hidden="true">'
						. JText::_('COM_PHOCAMAPS_CLOSE') . '</button>'
				)
			);
			
			
			
			
			
		} else {
			$html[] = '<div class="fltlft">';
			$html[] = '	<input type="text" id="'.$this->id.'_id" name="'.$this->name.'" value="'. $this->value.'"' .
					' '.$class.$size.$disabled.$readonly.$onchangeOutput.$maxLength.' />';
			$html[] = '</div>'. "\n";
		}


		return implode("\n", $html);
	}
}