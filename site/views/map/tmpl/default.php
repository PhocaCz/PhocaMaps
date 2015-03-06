<?php defined('_JEXEC') or die('Restricted access');

$app				= JFactory::getApplication();
if ($app->input->get( 'print', '', 'int' ) == 1 || $app->input->get( 'tmpl', '', 'string' ) == 'component') {

	$foutput = '<div style="clear:both"></div>';
	echo '<div id="phocamaps" class="phocamaps'.$this->t['p']->get( 'pageclass_sfx' ).'">';
} else {
	echo '<div id="phocamaps" class="phocamaps'.$this->t['p']->get( 'pageclass_sfx' ).'">';
	if ( $this->t['p']->def( 'show_page_heading', 1 ) ) {
		echo '<h1>'. $this->t['p']->get('page_heading') . '</h1>';
	}
	$foutput = PhocaMapsHelper::getInfo();
}


if ((!isset($this->map->longitude))
		|| (!isset($this->map->latitude))
		|| (isset($this->map->longitude) && $this->map->longitude == '')
		|| (isset($this->map->latitude) && $this->map->latitude == '')) {
	echo '<p>' . JText::_('COM_PHOCAMAPS_GOOGLE_MAPS_ERROR_FRONT') . '</p>';
} else {
	echo $this->t['description'];
	
	$id		= '';
	$map	= new PhocaMapsMap($id);
	$map->loadAPI('jsapi', (int)$this->t['load_api_ssl']);
	$map->loadGeoXMLJS();
	$map->loadBase64JS();
	
	// Map Box
	if ($this->t['border'] == '') {
		echo '<div class="phocamaps-box" align="center" style="'.$this->t['stylesite'].'">';
		if ($this->t['fullwidth'] == 1) {
			echo '<div id="phocaMap'.$id.'" style="margin:0;padding:0;width:100%;height:'.$this->map->height.'px"></div>';
		} else {
			echo '<div id="phocaMap'.$id.'" style="margin:0;padding:0;width:'.$this->map->width.'px;height:'.$this->map->height.'px"></div>';
		}
		echo '</div>';
	} else {
		echo '<div id="phocamaps-box"><div class="pmbox'.$this->t['border'].'" '. $this->t['stylesitewidth'].'><div><div><div>';
		if ($this->t['fullwidth'] == 1) {
			echo '<div id="phocaMap'.$id.'" style="width:100%;height:'.$this->map->height.'px"></div>';
		} else {
			echo '<div id="phocaMap'.$id.'" style="width:'.$this->map->width.'px;height:'.$this->map->height.'px"></div>';
		}
		echo '</div></div></div></div></div>';
	}

	// Direction
	if ($this->t['displaydir']) {
			
		$countMarker 	= count($this->marker);
		$form 			= '';
		if ((int)$countMarker > 1) {
		
			$form .= ' ' . JText::_('COM_PHOCAMAPS_TO').': <select name="pmto'.$id.'" id="toPMAddress'.$id.'">';
			foreach ($this->marker as $key => $markerV) {
				if ((isset($markerV->longitude) && $markerV->longitude != '')
				&& (isset($markerV->latitude) && $markerV->latitude != '')) {
					$form .= '<option value="'.$markerV->latitude.','.$markerV->longitude.'">'.$markerV->title.'</option>';
				}
			}
			$form .= '</select>';
		} else if ((int)$countMarker == 1) {
		
			foreach ($this->marker as $key => $markerV) {
				if ((isset($markerV->longitude) && $markerV->longitude != '')
				&& (isset($markerV->latitude) && $markerV->latitude != '')) {
					$form .= '<input name="pmto'.$id.'" id="toPMAddress'.$id.'" type="hidden" value="'.$markerV->latitude.','.$markerV->longitude.'" />';
				}
			}
		
		}
		
		if ($form != '') {
			echo '<div class="pmroute">';
			echo '<form class="form-inline" action="#" onsubmit="setPhocaDir'.$id.'(this.pmfrom'.$id.'.value, this.pmto'.$id.'.value); return false;">';
			echo JText::_('COM_PHOCAMAPS_FROM_ADDRESS').': <input class="pm-input-route input" type="text" size="30" id="fromPMAddress'.$id.'" name="pmfrom'.$id.'" value=""/>';
			echo $form;
			echo ' <input name="pmsubmit'.$id.'" type="submit" class="pm-input-route-btn btn" value="'.JText::_('COM_PHOCAMAPS_GET_ROUTE').'" />';
			echo '</form></div>';
			echo '<div id="phocaDir'.$id.'">';
			if ($this->t['display_print_route'] == 1) {
				echo '<div id="phocaMapsPrintIcon'.$id.'" style="display:none"></div>';
			}
			echo '</div>';
		}
	}	

	// $id is not used anymore as this is added in methods of Phoca Maps Class
	// e.g. 'phocaMap' will be not 'phocaMap'.$id as the id will be set in methods
	
	echo $map->startJScData();
	echo $map->addAjaxAPI('maps', '3', $this->t['params']);
	echo $map->addAjaxAPI('search', '1', $this->t['paramssearch']);

	echo $map->createMap('phocaMap', 'mapPhocaMap', 'phocaLatLng', 'phocaOptions','tstPhocaMap', 'tstIntPhocaMap', FALSE, FALSE, $this->t['displaydir']);
	echo $map->cancelEventFunction();
	echo $map->checkMapFunction();
	echo $map->startMapFunction();

		echo $map->setLatLng( $this->map->latitude, $this->map->longitude );

		echo $map->startMapOptions();
		echo $map->setMapOption('zoom', $this->map->zoom).','."\n";
		echo $map->setCenterOpt().','."\n";
		echo $map->setTypeControlOpt($this->map->typecontrol, $this->map->typecontrolposition).','."\n";
		echo $map->setNavigationControlOpt($this->map->zoomcontrol).','."\n";
		echo $map->setMapOption('scaleControl', $this->map->scalecontrol, TRUE ).','."\n";
		echo $map->setMapOption('scrollwheel', $this->map->scrollwheelzoom).','."\n";
		echo $map->setMapOption('disableDoubleClickZoom', $this->map->disabledoubleclickzoom).','."\n";
	//	echo $map->setMapOption('googleBar', $this->map->googlebar).','."\n";// Not ready yet
	//	echo $map->setMapOption('continuousZoom', $this->map->continuouszoom).','."\n";// Not ready yet
		echo $map->setMapTypeOpt($this->map->typeid)."\n";
		echo $map->endMapOptions();
		if ($this->t['close_opened_window'] == 1) {
			echo $map->setCloseOpenedWindow();
		}
		echo $map->setMap();
		
		// Markers
		jimport('joomla.filter.output');
		if (isset($this->marker) && !empty($this->marker)) {
		
			$iconArray = array(); // add information about created icons to array and check it so no duplicity icons js code will be created
			foreach ($this->marker as $key => $markerV) {
			
				if ((isset($markerV->longitude) && $markerV->longitude != '')
				&& (isset($markerV->latitude) && $markerV->latitude != '')) {
					
					$hStyle = 'font-size:120%;margin: 5px 0px;font-weight:bold;';
					$text = '<div style="'.$hStyle.'">' . addslashes($markerV->title) . '</div>';
					
					// Try to correct images in description
					$markerV->description = PhocaMapsHelper::fixImagePath($markerV->description);
					$markerV->description = str_replace('@', '&#64;', $markerV->description);
					$text .= '<div>'. PhocaMapsHelper::strTrimAll(addslashes($markerV->description)).'</div>';
					if ($markerV->displaygps == 1) {
						$text .= '<div class="pmgps"><table border="0"><tr><td><strong>'. JText::_('COM_PHOCAMAPS_GPS') . ': </strong></td>'
								.'<td>'.PhocaMapsHelper::strTrimAll(addslashes($markerV->gpslatitude)).'</td></tr>'
								.'<tr><td></td>'
								.'<td>'.PhocaMapsHelper::strTrimAll(addslashes($markerV->gpslongitude)).'</td></tr></table></div>';
					}
					
					
					if(empty($markerV->icon)) {
						$markerV->icon = 0;
					}
					if(empty($markerV->title)){
						$markerV->title = '';
					}
					if(empty($markerV->description)){
						$markerV->description = '';
					}
					
					$iconOutput = $map->setMarkerIcon($markerV->icon, $markerV->iconext, $markerV->iurl, $markerV->iobject, $markerV->iurls, $markerV->iobjects, $markerV->iobjectshape);
					echo $map->outputMarkerJs($iconOutput['js'], $markerV->icon, $markerV->iconext);
					
					echo $map->setMarker($markerV->id,$markerV->title,$markerV->description,$markerV->latitude, $markerV->longitude, $iconOutput['icon'], $iconOutput['iconid'], $text, $markerV->contentwidth, $markerV->contentheight,  $markerV->markerwindow, $iconOutput['iconshadow'], $iconOutput['iconshape'], $this->t['close_opened_window'] );
					
				}
			}
		}

		if ($this->t['load_kml']) {
			echo $map->setKMLFile($this->t['load_kml']);
		}
		
		if ($this->t['displaydir']) {
			echo $map->setDirectionDisplayService('phocaDir');
		}
		echo $map->setListener();
		echo $map->endMapFunction();

		if ($this->t['displaydir']) {
			
			echo $map->setDirectionFunction($this->t['display_print_route'], $this->map->id, $this->map->alias, $this->t['lang']);
		}
		
		echo $map->setInitializeFunction();
	echo $map->endJScData();
}


echo $foutput;
echo '</div>';
?>
