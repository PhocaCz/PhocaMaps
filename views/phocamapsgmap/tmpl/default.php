<?php
defined('_JEXEC') or die('Restricted access');

echo '<div id="phocamaps" style="margin:0;padding:0;">';
$id		= '';
$map	= new PhocaMapsMap($id);
if ($this->type == 'marker') {
	$map->loadCoordinatesJS();
}
$map->loadAPI();

echo '<div align="center" style="margin:0;padding:0">';
echo '<div id="phocaMap'.$id.'" style="margin:0;padding:0;width:750px;height:480px"></div></div>';

echo $map->startJScData();

	echo $map->addAjaxAPI('maps', '3.x', '{"other_params":"sensor=false"}');
	echo $map->addAjaxAPI('search', '1');

	echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap', 'phocaGeoCoder', TRUE);
	echo $map->cancelEventFunction();
	echo $map->checkMapFunction();

	echo $map->startMapFunction();
	
		echo $map->setLatLng( $this->latitude, $this->longitude );
		echo $map->startMapOptions();
		echo $map->setMapOption('zoom', $this->zoom).','."\n";
		echo $map->setCenterOpt().','."\n";
		echo $map->setTypeControlOpt().','."\n";
		echo $map->setNavigationControlOpt().','."\n";
		echo $map->setMapOption('scaleControl', 1, TRUE ).','."\n";
		echo $map->setMapOption('scrollwheel', 1).','."\n";
		echo $map->setMapOption('disableDoubleClickZoom', 0).','."\n";
	//	echo $map->setMapOption('googleBar', $this->map->googlebar).','."\n";// Not ready yet
	//	echo $map->setMapOption('continuousZoom', $this->map->continuouszoom).','."\n";// Not ready yet
		echo $map->setMapTypeOpt()."\n";
		echo $map->endMapOptions();
		echo $map->setMap();
		
	//	echo $map->exportZoom($this->zoom, 'window.top.document.forms.adminForm.elements.zoom');
	//	echo $map->exportMarker('Global', $this->type, $this->latitude, $this->longitude, 'window.top.document.forms.adminForm.elements.latitude', 'window.top.document.forms.adminForm.elements.longitude');
		if ($this->type != 'marker') {
			echo $map->exportZoom($this->zoom, '', 'phocaSelectMap_jform_zoom');
		}
		
		if ($this->type == 'marker') {
			echo $map->exportMarker('Global', $this->type, $this->latitude, $this->longitude, '', '', 'phocaSelectMap_jform_latitude', 'phocaSelectMap_jform_longitude','phocaSelectMap_jform_gpslatitude', 'phocaSelectMap_jform_gpslongitude');
		} else {
			echo $map->exportMarker('Global', $this->type, $this->latitude, $this->longitude, '', '', 'phocaSelectMap_jform_latitude', 'phocaSelectMap_jform_longitude');
		}
		echo $map->setListener();
		echo $map->setGeoCoder();
		echo $map->endMapFunction();
		
	if ($this->type == 'marker') {
		echo $map->addAddressToMapFunction('Global', 'phocaAddressEl', $this->type, '', '', 'phocaSelectMap_jform_latitude', 'phocaSelectMap_jform_longitude','phocaSelectMap_jform_gpslatitude', 'phocaSelectMap_jform_gpslongitude');// no '.id.' - it is set in class
	} else {
		echo $map->addAddressToMapFunction('Global', 'phocaAddressEl', $this->type, '', '', 'phocaSelectMap_jform_latitude', 'phocaSelectMap_jform_longitude');// no '.id.' - it is set in class
	}

	echo $map->setInitializeFunction();
	
echo $map->endJScData();


echo '<div class="p-add-address">'
. '<form class="form-inline" action="#" onsubmit="addAddressToMap'.$id.'(); return false;">'
. '<span>'.JText::_('COM_PHOCAMAPS_SET_COORDINATES_BY_ADDRESS').' : </span>'
. ' <input type="text" name="phocaAddressNameEl'.$id.'" id="phocaAddressEl'.$id.'" value="" class="" style="display:inline;" size="30" />'
. ' <input type="submit" class="btn" name="find" value="'. JText::_('COM_PHOCAMAPS_SET').'" />'
. '</form>'
. '</div>';
echo '</div>';
?>
