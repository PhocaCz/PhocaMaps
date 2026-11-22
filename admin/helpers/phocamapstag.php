<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
use Joomla\CMS\Factory;
use Joomla\Utilities\ArrayHelper;

class PhocaMapsTag
{
	public static function getTags($markerId, $select = 0) {

		$db = Factory::getDBO();

		if ($select == 1) {
			$query = 'SELECT r.tagid';
		} else {
			$query = 'SELECT a.*';
		}
		$query .= ' FROM #__phocamaps_tags AS a'
				.' LEFT JOIN #__phocamaps_tags_ref AS r ON a.id = r.tagid'
				.' WHERE r.markerid = '.(int) $markerId
				.' ORDER BY a.ordering';
		$db->setQuery($query);

		if ($select == 1) {
			$tags = $db->loadColumn();
		} else {
			$tags = $db->loadObjectList();
		}

		return $tags;
	}

	public static function storeTags($tagsArray, $markerId) {

		if ((int)$markerId > 0) {
			$db = Factory::getDBO();
			$query = ' DELETE '
					.' FROM #__phocamaps_tags_ref'
					.' WHERE markerid = '. (int)$markerId;
			$db->setQuery($query);
			$db->execute();

			if (!empty($tagsArray)) {

				$values 		= array();

				foreach($tagsArray as $k => $v) {
					if ((int)$v > 0) {
						$values[] = ' ('.(int)$markerId.', '.(int)$v.')';
					}
				}

				if (!empty($values)) {
					$query = ' INSERT INTO #__phocamaps_tags_ref (markerid, tagid)'
							.' VALUES '.implode(',', $values);
					$db->setQuery($query);
					$db->execute();
				}
			}
		}
		return true;
	}

	public static function deleteMarkerTags($markerIds) {

		$markerIds = ArrayHelper::toInteger((array)$markerIds);
		if (!empty($markerIds)) {
			$db = Factory::getDBO();
			$db->setQuery('DELETE FROM #__phocamaps_tags_ref WHERE markerid IN ('.implode(',', $markerIds).')');
			$db->execute();
		}
		return true;
	}

	public static function getAllTags($order = 'ordering') {

		$db = Factory::getDBO();
		$query = 'SELECT a.id AS value, a.title AS text'
				.' FROM #__phocamaps_tags AS a'
				.' WHERE a.published = 1'
				.' ORDER BY a.'. ($order == 'title' ? 'title' : 'ordering');
		$db->setQuery($query);

		return $db->loadObjectList();
	}

	/*
	 * Condition for markers displayed in map (map view and content plugin)
	 * $tagsMode: 0 ... only markers of the map (filtered by tags if tags are selected)
	 *            1 ... markers with selected tags from all maps
	 */
	public static function getMarkerWhere($mapId, $tags = array(), $tagsMode = 0) {

		if (!is_array($tags)) {
			$tags = explode(',', (string)$tags);
		}
		$tags = array_unique(array_filter(ArrayHelper::toInteger($tags)));

		$where = array();
		if (empty($tags) || (int)$tagsMode != 1) {
			$where[] = 'c.id = '.(int) $mapId;
		}
		if (!empty($tags)) {
			$where[] = 'a.id IN (SELECT r.markerid FROM #__phocamaps_tags_ref AS r'
					.' INNER JOIN #__phocamaps_tags AS t ON t.id = r.tagid'
					.' WHERE t.published = 1 AND r.tagid IN ('.implode(',', $tags).'))';
		}

		return implode(' AND ', $where);
	}
}
?>