<?php
defined( '_JEXEC' ) or die( 'Restricted access' );

class TablePhocaMapsMarker extends JTable
{
	function __construct( &$db ) {
		parent::__construct( '#__phocamaps_marker', 'id', $db );
	}
	
	function check(){
		
		if (trim( $this->catid ) == '') {
			$this->setError( JText::_( 'COM_PHOCAMAPS_ERROR_MAP_NOT_SELECTED') );
			return false;
		}
		
		if (trim( $this->title ) == '') {
			$this->setError( JText::_( 'COM_PHOCAMAPS_ERROR_TITLE_NOT_SET') );
			return false;
		}

		if (empty($this->alias)) {
			$this->alias = $this->title;
		}
		$this->alias = JApplication::stringURLSafe($this->alias);
		if (trim(str_replace('-', '', $this->alias)) == '') {
			$this->alias = JFactory::getDate()->format("Y-m-d-H-i-s");
		}

		return true;
	}
}
?>