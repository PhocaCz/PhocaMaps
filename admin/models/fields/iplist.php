<?php
/**
 * @package n3t Debug
 * @author Pavel Poles - n3t.cz
 * @copyright (C) 2016 - 2025 - Pavel Poles - n3t.cz
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( '_JEXEC' ) or die;

use Joomla\CMS\Form\Field\TextareaField;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Version;
use Joomla\Utilities\IpHelper;

if (Version::MAJOR_VERSION == 3) {
  FormHelper::loadFieldClass('textarea');
  class_alias('JFormFieldTextarea', '\\Joomla\\CMS\\Form\\Field\\TextareaField');
}

class JFormFieldIPList extends TextareaField
{
	protected $type = 'IPList';

	protected function getInput()
	{
    $button = '<br />';
    $onclick = "var input = document.getElementById('jform_".$this->element['name']."'); input.value +=  (input.value ? '\\n' : '') + '" . IpHelper::getIp() . "'; return false;";
    $button.= '<button class="btn btn-success" onclick="'.$onclick.'" href="#">' . Text::_('COM_PHOCAMAPS_FIELD_IP_FILTER_ADD_CURRENT') . '</button>';
    $onclick = "document.getElementById('jform_".$this->element['name']."').value = ''; return false;";
    $button.= ' <button class="btn btn-danger" onclick="' . $onclick . '">' . Text::_('COM_PHOCAMAPS_FIELD_IP_FILTER_CLEAR') . '</button>';
    return parent::getInput().$button;
	}
}
