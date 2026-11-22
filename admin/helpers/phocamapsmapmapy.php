<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );
use Joomla\CMS\Factory;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Uri\Uri;
use Joomla\CMS\Language\Text;

/*
 * Mapy.com (Seznam.cz) base map rendered by Leaflet. Uses its own "Mapy.com" options only,
 * marker clustering is the only option shared with OpenStreetMap.
 */
class PhocaMapsMapMapy extends PhocaMapsMapOsm
{
	public $apikey			= '';
	public $mapset			= 'basic';
	public $namesoverlay	= 1;
	public $switcher		= 1;
	public $suggest			= 0;
	public $routing			= 0;
	public $pointinfo		= 0;
	public $panorama		= 0;
	public $staticmap		= 0;

	function __construct($id = '') {

		$app 					= Factory::getApplication();
		$paramsC 				= ComponentHelper::getParams('com_phocamaps');
		$this->apikey			= trim((string)$paramsC->get( 'mapy_api_key', '' ));
		$this->mapset			= $paramsC->get( 'mapy_mapset', 'basic' );
		$this->namesoverlay		= $paramsC->get( 'mapy_names_overlay', 1 );
		$this->switcher			= $paramsC->get( 'mapy_switcher', 1 );
		$this->currentposition	= $paramsC->get( 'mapy_current_position', 1 );
		$this->fullscreen		= $paramsC->get( 'mapy_full_screen', 1 );
		$this->zoomwheel		= $paramsC->get( 'mapy_zoom_wheel', 1 );
		$this->zoomcontrol		= $paramsC->get( 'mapy_zoom_control', 1 );
		$this->search			= $paramsC->get( 'mapy_search', 0 );
		$this->suggest			= $paramsC->get( 'mapy_suggest', 0 );
		$this->routing			= $paramsC->get( 'mapy_routing', 0 );
		$this->pointinfo		= $paramsC->get( 'mapy_point_info', 0 );
		$this->panorama			= $paramsC->get( 'mapy_panorama', 0 );
		$this->staticmap		= $paramsC->get( 'mapy_static_map', 0 );
		$this->markerclustering	= $paramsC->get( 'marker_clustering', 0 );
		$this->router			= 0;
		$this->easyprint		= 0;
		$this->id				= $id;

		if (!in_array($this->mapset, array('basic', 'outdoor', 'winter', 'aerial'), true)) {
			$this->mapset = 'basic';
		}

		if ($app->isClient('administrator')) {
			// Coordinates are set by clicking the map, so the API tools listening to clicks are not used here
			$this->fullscreen 		= 1;
			$this->search			= 1;
			$this->zoomwheel		= 1;
			$this->zoomcontrol		= 1;
			$this->currentposition 	= 1;
			$this->suggest			= 0;
			$this->routing			= 0;
			$this->pointinfo		= 0;
			$this->panorama			= 0;
			$this->staticmap		= 0;
		}
	}

	/*
	 * Settings of the map (Map - Advanced Settings (Mapy.com)), empty value means global setting
	 */
	public function setMapOptions($item) {

		$names	= array(
			'mapset'			=> 'mapy_mapset',
			'namesoverlay'		=> 'mapy_names_overlay',
			'switcher'			=> 'mapy_switcher',
			'zoomcontrol'		=> 'mapy_zoom_control',
			'zoomwheel'			=> 'mapy_zoom_wheel',
			'currentposition'	=> 'mapy_current_position',
			'fullscreen'		=> 'mapy_full_screen',
			'search'			=> 'mapy_search',
			'suggest'			=> 'mapy_suggest',
			'routing'			=> 'mapy_routing',
			'pointinfo'			=> 'mapy_point_info',
			'panorama'			=> 'mapy_panorama',
			'staticmap'			=> 'mapy_static_map'
		);
		foreach ($names as $property => $name) {
			if (isset($item->$name) && $item->$name !== '') {
				$this->$property = $item->$name;
			}
		}

		if (!in_array($this->mapset, array('basic', 'outdoor', 'winter', 'aerial'), true)) {
			$this->mapset = 'basic';
		}
	}

	/*
	 * Marker appearance from the Mapy.com tab of the marker, without icon the OpenStreetMap appearance is used
	 */
	public function getMarkerIconOptions($marker) {

		if (!isset($marker->mapy_icon) || trim($marker->mapy_icon) == '') {
			return parent::getMarkerIconOptions($marker);
		}

		return array(
			'icon'			=> $marker->mapy_icon,
			'markercolor'	=> $marker->mapy_marker_color,
			'iconcolor'		=> $marker->mapy_icon_color,
			'prefix'		=> $marker->mapy_icon_prefix,
			'spin'			=> $marker->mapy_icon_spin,
			'class'			=> $marker->mapy_icon_class
		);
	}
	function loadAPI() {
		parent::loadAPI();
		$document	= Factory::getDocument();
		$document->addScript(Uri::root(true) . '/media/com_phocamaps/js/mapy/mapy.js', array('version' => 'auto'));
		$document->addStyleSheet(Uri::root(true) . '/media/com_phocamaps/js/mapy/mapy.css', array('version' => 'auto'));
	}

	function setMapType() {

		$app = Factory::getApplication();

		if ($this->apikey == '') {
			$app->enqueueMessage(Text::_('COM_PHOCAMAPS_ERROR_MAPY_API_KEY_NOT_SET'), 'error');
			return false;
		}

		$site = $app->isClient('site');

		$options = array(
			'mapset'		=> $this->mapset,
			'names'			=> (string)(int)$this->namesoverlay,
			'switcher'		=> (string)(int)$this->switcher,
			'search'		=> $site ? (string)(int)$this->search : '0',
			'suggest'		=> (string)(int)$this->suggest,
			'routing'		=> (string)(int)$this->routing,
			'point_info'	=> (string)(int)$this->pointinfo,
			'panorama'		=> (string)(int)$this->panorama,
			'static_map'	=> (string)(int)$this->staticmap
		);

		$text = array(
			'mapset'	=> Text::_('COM_PHOCAMAPS_FIELD_MAPY_MAPSET_LABEL'),
			'basic'		=> Text::_('COM_PHOCAMAPS_MAPY_MAPSET_BASIC'),
			'outdoor'	=> Text::_('COM_PHOCAMAPS_MAPY_MAPSET_OUTDOOR'),
			'winter'	=> Text::_('COM_PHOCAMAPS_MAPY_MAPSET_WINTER'),
			'aerial'	=> Text::_('COM_PHOCAMAPS_MAPY_MAPSET_AERIAL'),
			'tile_error'=> Text::_('COM_PHOCAMAPS_MAPY_TILE_ERROR')
		);
		foreach (array('names', 'search', 'find', 'empty', 'loading', 'request_error', 'route', 'route_start', 'route_end',
			'route_clear', 'route_hint', 'point_info', 'panorama', 'elevation', 'timezone', 'car_fast', 'car_short',
			'foot_fast', 'foot_hiking', 'bike_road', 'bike_mountain', 'live_only', 'static_map', 'matrix') as $name) {
			$text[$name] = Text::_('COM_PHOCAMAPS_MAPY_' . strtoupper($name));
		}

		$config = array(
			'apiKey'	=> $this->apikey,
			'lang'		=> substr($app->getLanguage()->getTag(), 0, 2),
			'text'		=> $text
		);

		$flags = JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT;
		$this->output[] = 'PhocaMapsMapy.create(map'.$this->name.$this->id.', '.json_encode($options, $flags).', '.json_encode($config, $flags).');';
		return true;
	}

	/*
	 * On the website the search is a part of the Mapy.com tools panel (see setMapType()),
	 * the search control is used for picking coordinates in administration only.
	 */
	public function renderSearch($markerId = '', $position = '') {

		if ($markerId == '' || $this->search == 0 || $this->apikey == '') {
			return '';
		}

		$lang		= substr(Factory::getApplication()->getLanguage()->getTag(), 0, 2);
		$url		= 'https://api.mapy.com/v1/geocode?limit=5&lang='.rawurlencode($lang).'&apikey='.rawurlencode($this->apikey).'&query=';
		$position	= $position != '' ? $position : 'topright';

		$o 	= array();
		$o[] = 'map'.$this->name.$this->id.'.addControl(new L.Control.Search({';
		$o[] = '	url: function(text) { return '.json_encode($url).' + encodeURIComponent(text); },';
		$o[] = '	formatData: function(json) {';
		$o[] = '		var records = {};';
		$o[] = '		(json && json.items ? json.items : []).forEach(function(item) {';
		$o[] = '			if (item.position) {';
		$o[] = '				records[[item.name, item.location].filter(Boolean).join(\', \')] = L.latLng(item.position.lat, item.position.lon);';
		$o[] = '			}';
		$o[] = '		});';
		$o[] = '		return records;';
		$o[] = '	},';
		$o[] = '	filterData: function(text, records) { return records; },';
		$o[] = '	marker: L.circleMarker([0,0],{radius:30}),';
		$o[] = '	autoCollapse: true,';
		$o[] = '	autoType: false,';
		$o[] = '	minLength: 3,';
		$o[] = '	position: \''.$position.'\',';
		$o[] = '	textErr: \''.Text::_('COM_PHOCAMAPS_SEARCH_LOCATION_NOT_FOUND').'\',';
		$o[] = '	textCancel: \''.Text::_('COM_PHOCAMAPS_SEARCH_CANCEL').'\',';
		$o[] = '	textPlaceholder: \''.Text::_('COM_PHOCAMAPS_SEARCH_SEARCH').'\',';
		$o[] = '	moveToLocation: function(latlng, title, map) {';
		$o[] = '		map'.$this->name.$this->id.'.setView(latlng, 7);';
		$o[] = '		phmInputMarker(latlng.lat, latlng.lng);';
		$o[] = '		phmMoveMarker(marker'.$markerId.', latlng.lat, latlng.lng);';
		$o[] = '	}';
		$o[] = '}));';

		$this->output[] = implode("\n", $o);
		return true;
	}
}
?>