<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
 defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');
JHtml::_('behavior.keepalive');
JHtml::_('formbehavior.chosen', 'select');

$class		= $this->t['n'] . 'RenderAdminView';
$r 			=  new $class();
?>
<script type="text/javascript">
Joomla.submitbutton = function(task)
{
	if (task != '<?php echo $this->t['task'] ?>.cancel' && document.id('jform_catid').value == '') {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED')) . ' - '. $this->escape(JText::_('COM_PHOCAMAPS_ERROR_MAP_NOT_SELECTED'));?>');
	} else if (task == '<?php echo $this->t['task'] ?>.cancel' || document.formvalidator.isValid(document.getElementById('adminForm'))) {
		Joomla.submitform(task, document.getElementById('adminForm'));
	}
	else {
		alert('<?php echo $this->escape(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'));?>');
	}
}
</script><?php
echo $r->startForm($this->t['o'], $this->t['task'], $this->item->id, 'adminForm', 'adminForm');
// First Column
echo '<div class="span10 form-horizontal">';
$tabs = array (
'general' 		=> JText::_($this->t['l'].'_GENERAL_OPTIONS'),
'publishing' 	=> JText::_($this->t['l'].'_PUBLISHING_OPTIONS'),
'design'		=> JText::_($this->t['l'].'_DESIGN_SETTINGS_GOOGLE_MAPS'),
'osm_design'		=> JText::_($this->t['l'].'_DESIGN_SETTINGS_OPENSTREETMAP')
);
echo $r->navigation($tabs);

echo '<div class="tab-content">'. "\n";

echo '<div class="tab-pane active" id="general">'."\n";
$formArray = array ('title', 'alias','latitude','longitude', 'gpslatitude', 'gpslongitude', 'catid', 'ordering', 'markerwindow', 'contentwidth', 'contentheight', 'displaygps');
echo $r->group($this->form, $formArray);
$formArray = array('description');
echo $r->group($this->form, $formArray, 1);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="publishing">'."\n";
foreach($this->form->getFieldset('publish') as $field) {
	echo '<div class="control-group">';
	if (!$field->hidden) {
		echo '<div class="control-label">'.$field->label.'</div>';
	}
	echo '<div class="controls">';
	echo $field->input;
	echo '</div></div>';
}
echo '</div>';

echo '<div class="tab-pane" id="design">'."\n";
$formArray = array ('icon', 'iconext');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";

echo '<div class="tab-pane" id="osm_design">'."\n";
$formArray = array ('osm_icon', 'osm_marker_color', 'osm_icon_color', 'osm_icon_prefix', 'osm_icon_spin', 'osm_icon_class');
echo $r->group($this->form, $formArray);
echo '</div>'. "\n";



echo '</div>';//end tab content
echo '</div>';//end span10
// Second Column
echo '<div class="span2"></div>';//end span2
echo $r->formInputs();
echo $r->endForm();
?>
