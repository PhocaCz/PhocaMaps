<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die();
jimport( 'joomla.application.component.view');
class PhocaMapsViewRoute extends JViewLegacy
{
	protected $t;
	
	function display($tpl = null) {
		$app		= JFactory::getApplication();
		
		JHTML::stylesheet('media/com_phocamaps/css/phocamaps.css' );
		if (JFile::exists(JPATH_SITE.'/media/com_phocamaps/css/custom.css')) {
			JHTML::stylesheet('media/com_phocamaps/css/custom.css' );
		}
		
		//$this->t['printview'] 	= JRequest::getVar('print', 0, 'get', 'int');
		$this->t['from'] 	= $app->input->get('from', '', 'string');
		$this->t['to'] 		= $app->input->get('to', '', 'string');
		$this->t['lang'] 	= $app->input->get('lang', '', 'string');
	
		// Map params - language not used
		if ($this->t['lang'] == '') {
			$this->t['params'] = '{other_params:"sensor=false"}';
		} else {
			//$this->t['params'] = '{"language":"'.$item['map']->lang.'", "other_params":"sensor=false"}';
			$this->t['params'] = '{other_params:"sensor=false&language='.$this->t['lang'].'"}';
		}
	
		parent::display($tpl);
	}
}
?>