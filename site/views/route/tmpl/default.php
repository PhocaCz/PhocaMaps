<?php defined('_JEXEC') or die('Restricted access');
echo '<div id="phocamapsprintroute">';
$id		= '';
$map	= new PhocaMapsMap();

echo $map->getIconPrintScreen();

//$map->loadAPI();
$map->loadAPI();

echo $map->startJScData();
echo $map->addAjaxAPI('maps', '3.x', $this->t['params']);
echo $map->createDirection(1);
echo $map->setDirectionFunction();
echo $map->directionInitializeFunctionSpecificMap($this->t['from'], $this->t['to']);
echo $map->directionInitializeFunction();
echo $map->endJScData();
echo $map->loadAPI();// must be loaded as last

echo '<div id="directionsPanel'.$id.'" ></div>';
echo PhocaMapsHelper::getInfo();
echo '</div>';


