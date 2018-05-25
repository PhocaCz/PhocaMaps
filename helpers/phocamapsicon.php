<?php
/*
 * @package Joomla 3.8
 * @copyright Copyright (C) 2005 Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 *
 * @component Phoca Component
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

/* Google Maps Version 3 */
class PhocaMapsIcon
{
	function __construct() {}
	
	public static function getIconData($icon) {
		
		// Icon Grey
		$i[1]['name']	= 'igrey';
		$i[1]['size']	= '20,24';
		$i[1]['point1']	= '0,0';
		$i[1]['point2']	= '5,30';
		$i[1]['sizes']	= '32,24';
		$i[1]['point1s']= '0,0';
		$i[1]['point2s']= '5,30';
		$i[1]['cord']	= '[14,1,16,2,18,3,19,4,19,5,19,6,19,7,18,8,18,9,17,10,16,11,16,12,15,13,14,14,14,15,15,16,17,17,17,18,18,19,17,20,14,21,10,22,7,23,2,23,2,22,2,21,2,20,1,19,1,18,1,17,1,16,1,15,0,14,0,13,0,12,0,11,0,10,0,9,7,8,7,7,8,6,8,5,9,4,9,3,10,2,11,1]';
		$i[1]['type']	= 'poly';
		
		// Icon Yellow
		$i[2]['name']	= 'iyellow';
		$i[2]['size']	= '26,30';
		$i[2]['point1']	= '0,0';
		$i[2]['point2']	= '5,30';
		$i[2]['sizes']	= '41,30';
		$i[2]['point1s']= '0,0';
		$i[2]['point2s']= '5,30';
		$i[2]['cord']	= '[18,1,19,2,21,3,23,4,24,5,24,6,24,7,24,8,23,9,23,10,22,11,22,12,21,13,20,14,20,15,19,16,19,17,18,18,17,19,18,20,20,21,22,22,22,23,22,24,22,25,18,26,15,27,12,28,8,29,4,29,4,28,3,27,3,26,3,25,3,24,3,23,2,22,2,21,2,20,2,19,2,18,1,17,1,16,1,15,1,14,1,13,1,12,1,11,9,10,10,9,10,8,11,7,11,6,12,5,12,4,13,3,14,2,14,1]';
		$i[2]['type']	= 'poly';
		
		// Icon Home
		$i[3]['name']	= 'ihome';
		$i[3]['size']	= '26,26';
		$i[3]['point1']	= '0,0';
		$i[3]['point2']	= '15,30';
		$i[3]['sizes']	= '39,26';
		$i[3]['point1s']= '0,0';
		$i[3]['point2s']= '15,30';
		$i[3]['cord']	= '[13,1,20,2,20,3,20,4,20,5,20,6,20,7,20,8,20,9,21,10,23,11,23,12,23,13,22,14,22,15,22,16,22,17,22,18,22,19,22,20,22,21,22,22,22,23,22,24,2,24,2,23,2,22,2,21,2,20,2,19,2,18,2,17,2,16,2,15,3,14,1,13,1,12,1,11,3,10,4,9,5,8,6,7,7,6,8,5,9,4,10,3,10,2,11,1]';
		$i[3]['type']	= 'poly';
		
		// Icon Home
		$i[4]['name']	= 'igreen';
		$i[4]['size']	= '26,26';
		$i[4]['point1']	= '0,0';
		$i[4]['point2']	= '15,30';
		$i[4]['sizes']	= '39,26';
		$i[4]['point1s']= '0,0';
		$i[4]['point2s']= '15,30';
		$i[4]['cord']	= '[15,2,18,3,19,4,20,5,21,6,22,7,23,8,23,9,23,10,23,11,23,12,23,13,23,14,23,15,23,16,22,17,22,18,21,19,20,20,19,21,17,22,21,23,21,24,16,25,8,25,4,24,4,23,8,22,6,21,5,20,4,19,3,18,3,17,2,16,2,15,2,14,2,13,2,12,2,11,2,10,2,9,2,8,3,7,4,6,5,5,6,4,7,3,10,2]';
		$i[4]['type']	= 'poly';
		
		// Icon Star
		$i[5]['name']	= 'istar';
		$i[5]['size']	= '26,26';
		$i[5]['point1']	= '0,0';
		$i[5]['point2']	= '15,30';
		$i[5]['sizes']	= '39,26';
		$i[5]['point1s']= '0,0';
		$i[5]['point2s']= '15,30';
		$i[5]['cord']	= '[13,0,13,1,13,2,14,3,14,4,15,5,15,6,15,7,21,8,25,9,25,10,23,11,22,12,21,13,20,14,19,15,19,16,19,17,19,18,19,19,19,20,20,21,20,22,20,23,20,24,19,25,6,25,5,24,5,23,5,22,5,21,5,20,5,19,6,18,6,17,6,16,6,15,5,14,4,13,3,12,2,11,0,10,0,9,4,8,9,7,9,6,10,5,10,4,11,3,11,2,12,1,12,0]';
		$i[5]['type']	= 'poly';
		
		// Icon Info Home
		$i[6]['name']	= 'iinfoh';
		$i[6]['size']	= '25,33';
		$i[6]['point1']	= '0,0';
		$i[6]['point2']	= '11,33';
		$i[6]['sizes']	= '42,33';
		$i[6]['point1s']= '0,0';
		$i[6]['point2s']= '11,33';
		$i[6]['cord']	= '[22,0,23,1,24,2,24,3,24,4,24,5,24,6,24,7,24,8,24,9,24,10,24,11,24,12,24,13,24,14,24,15,24,16,24,17,24,18,24,19,24,20,24,21,23,22,22,23,20,24,19,25,18,26,18,27,17,28,16,29,16,30,15,31,15,32,9,32,9,31,8,30,8,29,7,28,6,27,6,26,5,25,4,24,2,23,1,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,1,1,2,0]';
		$i[6]['type']	= 'poly';
		
		// Icon Info Info
		$i[7]['name']	= 'iinfoi';
		$i[7]['size']	= '25,33';
		$i[7]['point1']	= '0,0';
		$i[7]['point2']	= '11,33';
		$i[7]['sizes']	= '42,33';
		$i[7]['point1s']= '0,0';
		$i[7]['point2s']= '11,33';
		$i[7]['cord']	= '[22,0,23,1,24,2,24,3,24,4,24,5,24,6,24,7,24,8,24,9,24,10,24,11,24,12,24,13,24,14,24,15,24,16,24,17,24,18,24,19,24,20,24,21,23,22,22,23,20,24,19,25,18,26,18,27,17,28,16,29,16,30,15,31,15,32,9,32,9,31,8,30,8,29,7,28,6,27,6,26,5,25,4,24,2,23,1,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,1,1,2,0]';
		$i[7]['type']	= 'poly';
		
		// Icon Info Post
		$i[8]['name']	= 'iinfop';
		$i[8]['size']	= '25,33';
		$i[8]['point1']	= '0,0';
		$i[8]['point2']	= '11,33';
		$i[8]['sizes']	= '42,33';
		$i[8]['point1s']= '0,0';
		$i[8]['point2s']= '11,33';
		$i[8]['cord']	= '[22,0,23,1,24,2,24,3,24,4,24,5,24,6,24,7,24,8,24,9,24,10,24,11,24,12,24,13,24,14,24,15,24,16,24,17,24,18,24,19,24,20,24,21,23,22,22,23,20,24,19,25,18,26,18,27,17,28,16,29,16,30,15,31,15,32,9,32,9,31,8,30,8,29,7,28,6,27,6,26,5,25,4,24,2,23,1,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,1,1,2,0]';
		$i[8]['type']	= 'poly';
		
		// Icon Info Phone
		$i[9]['name']	= 'iinfoph';
		$i[9]['size']	= '25,33';
		$i[9]['point1']	= '0,0';
		$i[9]['point2']	= '11,33';
		$i[9]['sizes']	= '42,33';
		$i[9]['point1s']= '0,0';
		$i[9]['point2s']= '11,33';
		$i[9]['cord']	= '[22,0,23,1,24,2,24,3,24,4,24,5,24,6,24,7,24,8,24,9,24,10,24,11,24,12,24,13,24,14,24,15,24,16,24,17,24,18,24,19,24,20,24,21,23,22,22,23,20,24,19,25,18,26,18,27,17,28,16,29,16,30,15,31,15,32,9,32,9,31,8,30,8,29,7,28,6,27,6,26,5,25,4,24,2,23,1,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,1,1,2,0]';
		$i[9]['type']	= 'poly';
		
		// Icon Info Zoom
		$i[10]['name']	= 'iinfoz';
		$i[10]['size']	= '25,33';
		$i[10]['point1']	= '0,0';
		$i[10]['point2']	= '11,33';
		$i[10]['sizes']	= '42,33';
		$i[10]['point1s']= '0,0';
		$i[10]['point2s']= '11,33';
		$i[10]['cord']	= '[22,0,23,1,24,2,24,3,24,4,24,5,24,6,24,7,24,8,24,9,24,10,24,11,24,12,24,13,24,14,24,15,24,16,24,17,24,18,24,19,24,20,24,21,23,22,22,23,20,24,19,25,18,26,18,27,17,28,16,29,16,30,15,31,15,32,9,32,9,31,8,30,8,29,7,28,6,27,6,26,5,25,4,24,2,23,1,22,0,21,0,20,0,19,0,18,0,17,0,16,0,15,0,14,0,13,0,12,0,11,0,10,0,9,0,8,0,7,0,6,0,5,0,4,0,3,0,2,1,1,2,0]';
		$i[10]['type']	= 'poly';
		
		if(isset($i[$icon])) {
			return $i[$icon];
		} else {
			return false;
		}
	}
}
?>