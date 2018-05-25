<?php
/* @package Joomla
 * @copyright Copyright (C) Open Source Matters. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL, see LICENSE.php
 * @extension Phoca Extension
 * @copyright Copyright (C) Jan Pavelka www.phoca.cz
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 */
defined('_JEXEC') or die;
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('dropdown.init');
JHtml::_('formbehavior.chosen', 'select');
$class		= $this->t['n'] . 'RenderAdminViews';
$r 			=  new $class();
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->escape($this->state->get('list.ordering'));
$listDirn	= $this->escape($this->state->get('list.direction'));
$canOrder	= $user->authorise('core.edit.state', $this->t['o']);
$saveOrder	= $listOrder == 'a.ordering';
if ($saveOrder) {
	$saveOrderingUrl = 'index.php?option='.$this->t['o'].'&task='.$this->t['tasks'].'.saveOrderAjax&tmpl=component';
	JHtml::_('sortablelist.sortable', 'categoryList', 'adminForm', strtolower($listDirn), $saveOrderingUrl, false, true);
}
$sortFields = $this->getSortFields();

echo $r->jsJorderTable($listOrder);

//echo '<div class="clearfix"></div>';

echo $r->startForm($this->t['o'], $this->t['tasks'], 'adminForm');
echo $r->startFilter();
//echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
//echo $r->selectFilterLanguage('JOPTION_SELECT_LANGUAGE', $this->state->get('filter.language'));
//echo $r->selectFilterCategory(PhocaMapsSelectMap::options($this->t['o']), 'COM_PHOCAMAPS_SELECT_MAP', $this->state->get('filter.map_id'));
echo $r->endFilter();

echo $r->startMainContainer();
echo $r->startFilterBar();
echo $r->inputFilterSearch($this->t['l'].'_FILTER_SEARCH_LABEL', $this->t['l'].'_FILTER_SEARCH_DESC',
							$this->escape($this->state->get('filter.search')));
echo $r->inputFilterSearchClear('JSEARCH_FILTER_SUBMIT', 'JSEARCH_FILTER_CLEAR');
echo $r->inputFilterSearchLimit('JFIELD_PLG_SEARCH_SEARCHLIMIT_DESC', $this->pagination->getLimitBox());
echo $r->selectFilterDirection('JFIELD_ORDERING_DESC', 'JGLOBAL_ORDER_ASCENDING', 'JGLOBAL_ORDER_DESCENDING', $listDirn);
echo $r->selectFilterSortBy('JGLOBAL_SORT_BY', $sortFields, $listOrder);


echo $r->startFilterBar(2);
echo $r->selectFilterPublished('JOPTION_SELECT_PUBLISHED', $this->state->get('filter.state'));
echo $r->endFilterBar();

echo $r->endFilterBar();		

echo $r->startTable('categoryList');

echo $r->startTblHeader();

echo $r->thOrdering('JGRID_HEADING_ORDERING', $listDirn, $listOrder);
echo $r->thCheck('JGLOBAL_CHECK_ALL');
echo '<th class="ph-icon">'.JText::_('COM_PHOCAMAPS_ICON').'</th>'."\n";
echo '<th class="ph-title-short">'.JHTML::_('grid.sort',  	$this->t['l'].'_TITLE', 'a.title', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-published">'.JHTML::_('grid.sort',  $this->t['l'].'_PUBLISHED', 'a.published', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-url">'.JHTML::_('grid.sort',  $this->t['l'].'_URL', 'a.url', $listDirn, $listOrder ).'</th>'."\n";
//echo '<th class="ph-language">'.JHTML::_('grid.sort',  	'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder ).'</th>'."\n";
echo '<th class="ph-id">'.JHTML::_('grid.sort',  		$this->t['l'].'_ID', 'a.id', $listDirn, $listOrder ).'</th>'."\n";

echo $r->endTblHeader();
			
echo '<tbody>'. "\n";

$originalOrders = array();	
$parentsStr 	= "";		
$j 				= 0;

if (is_array($this->items)) {
	foreach ($this->items as $i => $item) {
	//	if ($i >= (int)$this->pagination->limitstart && $j < (int)$this->pagination->limit) {
			$j++;

$urlEdit		= 'index.php?option='.$this->t['o'].'&task='.$this->t['task'].'.edit&id=';
$orderkey   	= array_search($item->id, $this->ordering[0]);		
$ordering		= ($listOrder == 'a.ordering');			
$canCreate		= $user->authorise('core.create', $this->t['o']);
$canEdit		= $user->authorise('core.edit', $this->t['o']);
$canCheckin		= $user->authorise('core.manage', 'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange		= $user->authorise('core.edit.state', $this->t['o']) && $canCheckin;
$linkEdit 		= JRoute::_( $urlEdit.(int) $item->id );


$iD = $i % 2;
echo "\n\n";
//echo '<tr class="row'.$iD.'" sortable-group-id="0" item-id="'.$item->id.'" parents="0" level="0">'. "\n";
echo '<tr class="row'.$iD.'" sortable-group-id="0" >'. "\n";
echo $r->tdOrder($canChange, $saveOrder, $orderkey, $item->ordering);
echo $r->td(JHtml::_('grid.id', $i, $item->id), "small hidden-phone");

$imgU = '';
if ($item->url != '') {
	$imgU =  '<img src="'.$item->url.'" alt="" />';
}
echo $r->td($imgU, "small hidden-phone");

$checkO = '';
if ($item->checked_out) {
	$checkO .= JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, $this->t['tasks'].'.', $canCheckin);
}
if ($canCreate || $canEdit) {
	$checkO .= '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
} else {
	$checkO .= $this->escape($item->title);
}
$checkO .= ' <span class="smallsub">(<span>'.JText::_($this->t['l'].'_FIELD_ALIAS_LABEL').':</span>'. $this->escape($item->alias).')</span>';
echo $r->td($checkO, "small hidden-phone");
echo $r->td(JHtml::_('jgrid.published', $item->published, $i, $this->t['tasks'].'.', $canChange), "small hidden-phone");


$url = '';
if ($item->url != '') {
	$url .= '<span>'.$item->url.'</span>';
}
if ($item->urls != '') {
	$url .= '<br /><span>'.$item->urls.'</span>';
}
echo $r->td($url, "small hidden-phone");				

//echo $r->tdLanguage($item->language, $item->language_title, $this->escape($item->language_title));
echo $r->td($item->id, "small hidden-phone");

echo '</tr>'. "\n";
						
		//}
	}
}
echo '</tbody>'. "\n";

echo $r->tblFoot($this->pagination->getListFooter(), 8);
echo $r->endTable();


echo $r->formInputs($listOrder, $listDirn, $originalOrders);
echo $r->endMainContainer();
echo $r->endForm();


/*
 
defined('_JEXEC') or die;
JHtml::_('behavior.tooltip');
$user		= JFactory::getUser();
$userId		= $user->get('id');
$listOrder	= $this->state->get('list.ordering');
$listDirn	= $this->state->get('list.direction');
$canOrder	= $user->authorise('core.edit.state', 'com_phocamaps');
$saveOrder	= 'a.ordering';
?>

<form action="<?php echo JRoute::_('index.php?option=com_phocamaps&view=phocamapsicons'); ?>" method="post" name="adminForm" id="adminForm">
	<fieldset id="filter-bar">
		<div class="filter-search fltlft">
			<label class="filter-search-lbl" for="filter_search"><?php echo JText::_('JSEARCH_FILTER_LABEL'); ?></label>
			<input type="text" name="filter_search" id="filter_search" value="<?php echo $this->state->get('filter.search'); ?>" title="<?php echo JText::_('COM_PHOCAMAPS_SEARCH_IN_TITLE'); ?>" />
			<button type="submit"><?php echo JText::_('JSEARCH_FILTER_SUBMIT'); ?></button>
			<button type="button" onclick="document.id('filter_search').value='';this.form.submit();"><?php echo JText::_('JSEARCH_FILTER_CLEAR'); ?></button>
		</div>
		<div class="filter-select fltrt">
			
			<select name="filter_published" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_PUBLISHED');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('jgrid.publishedOptions', array('archived' => 0, 'trash' => 0)), 'value', 'text', $this->state->get('filter.state'), true);?>
			</select>
			<?php /*
			<select name="filter_language" class="inputbox" onchange="this.form.submit()">
				<option value=""><?php echo JText::_('JOPTION_SELECT_LANGUAGE');?></option>
				<?php echo JHtml::_('select.options', JHtml::_('contentlanguage.existing', true, true), 'value', 'text', $this->state->get('filter.language'));?>
			</select>  ?>

		</div>
	</fieldset>
	<div class="clearfix"> </div>
	
<table class="adminlist">
<thead>
	<tr>
		<th width="1%">
			<input type="checkbox" name="toggle" value="" onclick="checkAll(this)" />
		</th>

		<th width="1%" align="center">
			<?php echo JText::_('COM_PHOCAMAPS_ICON'); ?>
		</th>
		
		<th width="20%" align="center">
			<?php echo JHtml::_('grid.sort', 'COM_PHOCAMAPS_TITLE', 'a.title', $listDirn, $listOrder); ?>
		</th>
		
		<th width="20%" align="center">
			<?php echo JHtml::_('grid.sort', 'COM_PHOCAMAPS_URL', 'a.url', $listDirn, $listOrder); ?>
		</th>
		
		<th width="5%">
			<?php echo JHtml::_('grid.sort', 'COM_PHOCAMAPS_PUBLISHED', 'a.published', $listDirn, $listOrder); ?>
		</th>
		

		<th width="10%">
			<?php echo JHtml::_('grid.sort',  'JGRID_HEADING_ORDERING', 'a.ordering', $listDirn, $listOrder);
			if ($canOrder && $saveOrder) {
				echo JHtml::_('grid.order',  $this->items, 'filesave.png', 'phocamapsicons.saveorder');
			} ?>
		</th>
		
		<?php /*<th width="5%">
			<?php echo JHtml::_('grid.sort', 'JGRID_HEADING_LANGUAGE', 'a.language', $listDirn, $listOrder); ?>
		</th>  ?>
		<th width="1%" nowrap="nowrap">
			<?php echo JHtml::_('grid.sort', 'COM_PHOCAMAPS_ID', 'a.id', $listDirn, $listOrder); ?>
		</th>
		
	</tr>
</thead>

<tbody>
<?php foreach ($this->items as $i => $item) {
$ordering	= ($listOrder == 'a.ordering');
$linkEdit	= JRoute::_( 'index.php?option=com_phocamaps&task=phocamapsicon.edit&id='.(int) $item->id );

$canCheckin	= $user->authorise('core.manage',		'com_checkin') || $item->checked_out==$user->get('id') || $item->checked_out==0;
$canChange	= $user->authorise('core.edit.state',	'com_phocamaps') && $canCheckin;
$canCreate	= $user->authorise('core.create',		'com_phocamaps');
?>
<tr class="row<?php echo $i % 2; ?>">
	<td class="center">
		<?php echo JHtml::_('grid.id', $i, $item->id); ?>
	</td>
	
	
	
	<?php if ($item->checked_out) {
		echo JHtml::_('jgrid.checkedout', $i, $item->editor, $item->checked_out_time, 'phocamapsicons.', $canCheckin); 
	}
	
	echo '<td align="center">';
	if ($item->url != '') {
	
		echo '<img src="'.$item->url.'" alt="" />';
	}
	echo '</td>';
	
	echo '<td>';
	if ($canCreate || $canEdit) {
		echo '<a href="'. JRoute::_($linkEdit).'">'. $this->escape($item->title).'</a>';
	} else {
		echo $this->escape($item->title);
	}
	echo '</td>';
	
	echo '<td>';
	
	if ($item->url != '') {
		echo '<span>'.$item->url.'</span>';
	}
	if ($item->urls != '') {
		echo '<br /><span>'.$item->urls.'</span>';
	}
	
	echo '</td>';
	
	echo '<td class="center">';
	echo JHtml::_('jgrid.published', $item->published, $i, 'phocamapsicons.', $canChange);
	echo '</td>';
	

$cntx = 'phocamapsicons';
echo '<td class="order">';
if ($canChange) {
	if ($saveOrder) {
		if ($listDirn == 'asc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, true, $cntx.'.orderup', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, true, $cntx.'.orderdown', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		} else if ($listDirn == 'desc') {
			echo '<span>'. $this->pagination->orderUpIcon($i, true, $cntx.'.orderdown', 'JLIB_HTML_MOVE_UP', $ordering).'</span>';
			echo '<span>'.$this->pagination->orderDownIcon($i, $this->pagination->total, true, $cntx.'.orderup', 'JLIB_HTML_MOVE_DOWN', $ordering).'</span>';
		}
	}
	$disabled = $saveOrder ?  '' : 'disabled="disabled"';
	echo '<input type="text" name="order[]" size="5" value="'.$item->ordering.'" '.$disabled.' class="text-area-order" />';
} else {
	echo $item->ordering;
}
echo '</td>';

/*

	<td class="center">
	<?php
	if ($item->language=='*') {
		echo JText::_('JALL');
	} else {
		echo $item->language_title ? $this->escape($item->language_title) : JText::_('JUNDEFINED');
	}
	?>
</td>  ?>
	
	<td class="center"><?php echo (int) $item->id; ?></td>
</tr>
<?php } ?>
</tbody>
			
<tfoot>
	<tr>
		<td colspan="10">
			<?php echo $this->pagination->getListFooter(); ?>
		</td>
	</tr>
</tfoot>
</table>

<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" value="0" />
<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
<?php echo JHtml::_('form.token'); ?>
</form> */
?>