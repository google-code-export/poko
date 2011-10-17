<?php

$this->buf .= '	';
if(_hxtemplo_is_true(!$ctx->linkMode)) {
$this->buf .= '
	<h3>';
$this->buf .= _hxtemplo_string($ctx->label);
$this->buf .= '</h3>
	';
}
$this->buf .= '
	
	<div id="datasetActionsWrapper">
		<a href="?request=cms.modules.base.DatasetItem&action=add&dataset=';
$this->buf .= _hxtemplo_string($ctx->dataset);
$this->buf .= '&linkMode=';
$this->buf .= _hxtemplo_string($ctx->linkMode);
$this->buf .= '&linkToField=';
$this->buf .= _hxtemplo_string($ctx->linkToField);
$this->buf .= '&linkTo=';
$this->buf .= _hxtemplo_string($ctx->linkTo);
$this->buf .= '&linkValueField=';
$this->buf .= _hxtemplo_string($ctx->linkValueField);
$this->buf .= '&linkValue=';
$this->buf .= _hxtemplo_string($ctx->linkValue);
$this->buf .= '&siteMode=';
$this->buf .= _hxtemplo_string($ctx->siteMode);
$this->buf .= '&autofilterBy=';
$this->buf .= _hxtemplo_string($ctx->autoFilterValue);
$this->buf .= '&autofilterByAssoc=';
$this->buf .= _hxtemplo_string($ctx->autoFilterByAssocValue);
$this->buf .= '"><img src="./res/cms/add.png" class="qTip" title="Add new item to list"/></a>
		';
if(_hxtemplo_is_true(!$ctx->linkMode) && _hxtemplo_is_true($ctx->showFiltering) || _hxtemplo_is_true($ctx->showOrderBy)) {
$this->buf .= ' | <a href="#" onClick="return(toggleFilterOrder());"><img class="qTip" src="./res/cms/find.png" title="Show/hide filteringer and ordering options."/></a>';
}
$this->buf .= '
		| <a href="#" id="datasetCheckAll"><img src="./res/cms/check_all.png" class="qTip" title="Check all"/></a>
		';
if(_hxtemplo_is_true($ctx->allowCsv)) {
$this->buf .= ' | <a href="?request=cms.services.Csv&dataset=';
$this->buf .= _hxtemplo_string($ctx->dataset);
$this->buf .= '&autofilterBy=';
$this->buf .= _hxtemplo_string($ctx->autoFilterValue);
$this->buf .= '&autofilterByAssoc=';
$this->buf .= _hxtemplo_string($ctx->autoFilterByAssocValue);
$this->buf .= '"><img src="./res/cms/table_download.png" class="qTip" title="Download CSV"/></a>';
}
$this->buf .= '
	</div>
	
	';
if(_hxtemplo_is_true(!$ctx->linkMode)) {
$this->buf .= '
		<div id="filterOrderWrapper">
		';
$this->buf .= $ctx->optionsForm->getOpenTag();
$this->buf .= '
			';
$this->buf .= $ctx->optionsForm->getElement('reset');
$this->buf .= '
			<div id="filterOptions">
			';
if(_hxtemplo_is_true($ctx->showFiltering)) {
$this->buf .= '
			
				Filter By ';
$this->buf .= $ctx->optionsForm->getElement('filterBy');
$this->buf .= '
				<div id="filter_normal">
					';
$this->buf .= $ctx->optionsForm->getElement('filterByOperator');
$this->buf .= '
					';
$this->buf .= $ctx->optionsForm->getElement('filterByValue');
$this->buf .= '
				</div>
				<div id="filter_assoc">
					';
$this->buf .= $ctx->optionsForm->getElement('filterByAssoc');
$this->buf .= '
				</div>
			';
}
$this->buf .= '
			</div>
			
			<div id="orderOptions">
			';
if(_hxtemplo_is_true($ctx->showOrderBy)) {
$this->buf .= '
				Order By 
				';
$this->buf .= $ctx->optionsForm->getElement('orderBy');
$this->buf .= '
				';
$this->buf .= $ctx->optionsForm->getElement('orderByDirection');
$this->buf .= '	
			';
}
$this->buf .= '
			</div>
			
			';
if((_hxtemplo_is_true($ctx->showOrderBy) || _hxtemplo_is_true($ctx->showFiltering))) {
$this->buf .= '
				<div id="filterOrderButtons">
					';
$this->buf .= $ctx->optionsForm->getElement('resetButton');
$this->buf .= '
					';
$this->buf .= $ctx->optionsForm->getElement('updateButton');
$this->buf .= '
				</div>
			';
}
$this->buf .= '
		';
$this->buf .= $ctx->optionsForm->getCloseTag();
$this->buf .= '	
		</div>
	';
}
$this->buf .= '
	
	';
if($ctx->definition->helpList !== null && $ctx->definition->helpList !== '') {
$this->buf .= '
		<div id="cmsDatasetItemOverallHelp">';
$this->buf .= $ctx->controller->nl2br($ctx->definition->helpList);
$this->buf .= '</div>
	';
}
$this->buf .= '	
	
	';
if($ctx->paginationLinks !== '') {
$this->buf .= '
	<div class="datasetPagination">
		';
$this->buf .= $ctx->paginationLinks;
$this->buf .= '
	</div>
	';
}
$this->buf .= '
	
	';
if($ctx->tabFields !== null) {
$this->buf .= '
		<div id="datasetTabFields">
			<ul>
				';
$repeater_f = _hxtemplo_repeater($ctx->tabFields);  while($repeater_f->hasNext()) {$ctx->f = $repeater_f->next(); 
$this->buf .= '
					';
$ctx->cc = '';
$this->buf .= '
					';
if($ctx->f->value === $ctx->tabFilter) {
$this->buf .= ' ';
$ctx->cc = 'datasetTabFieldSelected';
$this->buf .= ' ';
}
$this->buf .= '
					<li class="';
$this->buf .= _hxtemplo_string($ctx->cc);
$this->buf .= '"><a href="?request=cms.modules.base.Dataset&dataset=';
$this->buf .= _hxtemplo_string($ctx->dataset);
$this->buf .= '&linkMode=';
$this->buf .= _hxtemplo_string($ctx->linkMode);
$this->buf .= '&linkToField=';
$this->buf .= _hxtemplo_string($ctx->linkToField);
$this->buf .= '&linkTo=';
$this->buf .= _hxtemplo_string($ctx->linkTo);
$this->buf .= '&linkValueField=';
$this->buf .= _hxtemplo_string($ctx->linkValueField);
$this->buf .= '&linkValue=';
$this->buf .= _hxtemplo_string($ctx->linkValue);
$this->buf .= '&siteMode=';
$this->buf .= _hxtemplo_string($ctx->siteMode);
$this->buf .= '&autofilterBy=';
$this->buf .= _hxtemplo_string($ctx->autoFilterValue);
$this->buf .= '&autofilterByAssoc=';
$this->buf .= _hxtemplo_string($ctx->autoFilterByAssocValue);
$this->buf .= '&tabFilter=';
$this->buf .= _hxtemplo_string($ctx->f->value);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->f->key);
$this->buf .= '</a></li>
				';
}
$this->buf .= '
			</ul>
		</div>
	';
}
$this->buf .= '
	
	<form action="" method="POST">
		<table class="greyTable datasetListTable">
			
			
			<tr>
			<td><img src="./res/cms/help.png" class="qTip" title="<b>Deleting</b><br /><br />To remove items just select the check box next to them and then click the \'delete and order\' button."/></td>
			';
if((_hxtemplo_is_true($ctx->isOrderingEnabled))) {
$this->buf .= ' 
				<td><img src="./res/cms/help.png" class="qTip" title="<b>Ordering</b><br /><br />To order elements change the numbers in the boxes below and then click the \'delete and order\' button.<br /><br />If you have elements 1, 2, 3 and 4 and you want to move 4 between 1 and 2 then put any number between 1 and 2 in the box ie 1.5"/></td>
			';
}
$this->buf .= '
			
			';
$repeater_field = _hxtemplo_repeater($ctx->fieldLabels);  while($repeater_field->hasNext()) {$ctx->field = $repeater_field->next(); 
$this->buf .= '
			
				<td><b>';
$this->buf .= _hxtemplo_string($ctx->field);
$this->buf .= '</b></td>
			
			';
}
$this->buf .= '
			
			<td></td>
			</tr>
			';
$ctx->cols = 0;
$this->buf .= '
			';
if(_hxtemplo_length($ctx->data) > 0) {
$this->buf .= '
				';
$repeater_row = _hxtemplo_repeater($ctx->data);  while($repeater_row->hasNext()) {$ctx->row = $repeater_row->next(); 
$this->buf .= '
					<tr>
						';
$ctx->cols = _hxtemplo_add($ctx->cols,1);
$this->buf .= ' <td><input type="checkbox" name="delete[]" value="';
$this->buf .= _hxtemplo_string($ctx->row->cms_primaryKey);
$this->buf .= '"/></td>
						
						';
if(_hxtemplo_is_true($ctx->isOrderingEnabled)) {
$this->buf .= '
							';
$ctx->cols = _hxtemplo_add($ctx->cols,1);
$this->buf .= '
							<td><input type="input" name="order[';
$this->buf .= _hxtemplo_string($ctx->row->cms_primaryKey);
$this->buf .= ']" value="';
$this->buf .= _hxtemplo_string($ctx->row->dataset__orderField);
$this->buf .= '" class="order"/></td>
						';
}
$this->buf .= '
						
						';
$repeater_field = _hxtemplo_repeater($ctx->fields);  while($repeater_field->hasNext()) {$ctx->field = $repeater_field->next(); 
$this->buf .= '
							
							';
$ctx->cols = _hxtemplo_add($ctx->cols,_hxtemplo_length($ctx->fields));
$this->buf .= '
							<td>';
$this->buf .= $ctx->controller->preview($ctx->row, $ctx->field);
$this->buf .= '</td>
						
						';
}
$this->buf .= '
						
						';
$ctx->cols = _hxtemplo_add($ctx->cols,1);
$this->buf .= '
						<td class="cmsDatasetListRecordAction">
							<a class="qTip" title="Edit" href="?request=cms.modules.base.DatasetItem&action=edit&dataset=';
$this->buf .= _hxtemplo_string($ctx->dataset);
$this->buf .= '&id=';
$this->buf .= _hxtemplo_string($ctx->row->cms_primaryKey);
$this->buf .= '&linkMode=';
$this->buf .= _hxtemplo_string($ctx->linkMode);
$this->buf .= '&linkToField=';
$this->buf .= _hxtemplo_string($ctx->linkToField);
$this->buf .= '&linkTo=';
$this->buf .= _hxtemplo_string($ctx->linkTo);
$this->buf .= '&linkValueField=';
$this->buf .= _hxtemplo_string($ctx->linkValueField);
$this->buf .= '&linkValue=';
$this->buf .= _hxtemplo_string($ctx->linkValue);
$this->buf .= '&siteMode=';
$this->buf .= _hxtemplo_string($ctx->siteMode);
$this->buf .= '&autofilterBy=';
$this->buf .= _hxtemplo_string($ctx->autoFilterValue);
$this->buf .= '&autofilterByAssoc=';
$this->buf .= _hxtemplo_string($ctx->autoFilterByAssocValue);
$this->buf .= '"><img src="./res/cms/pencil.png"/></a>
							<a class="qTip" title="Duplicate" href="?request=cms.modules.base.Dataset&action=duplicate&dataset=';
$this->buf .= _hxtemplo_string($ctx->dataset);
$this->buf .= '&id=';
$this->buf .= _hxtemplo_string($ctx->row->cms_primaryKey);
$this->buf .= '&linkMode=';
$this->buf .= _hxtemplo_string($ctx->linkMode);
$this->buf .= '&linkToField=';
$this->buf .= _hxtemplo_string($ctx->linkToField);
$this->buf .= '&linkTo=';
$this->buf .= _hxtemplo_string($ctx->linkTo);
$this->buf .= '&linkValueField=';
$this->buf .= _hxtemplo_string($ctx->linkValueField);
$this->buf .= '&linkValue=';
$this->buf .= _hxtemplo_string($ctx->linkValue);
$this->buf .= '&siteMode=';
$this->buf .= _hxtemplo_string($ctx->siteMode);
$this->buf .= '&autofilterBy=';
$this->buf .= _hxtemplo_string($ctx->autoFilterValue);
$this->buf .= '&autofilterByAssoc=';
$this->buf .= _hxtemplo_string($ctx->autoFilterByAssocValue);
$this->buf .= '"><img src="./res/cms/page_copy.png"/></a>
						</td>
					</tr>
				';
}
$this->buf .= '
			';
} else {
$this->buf .= '
				<tr><td colspan="';
$this->buf .= _hxtemplo_string($ctx->cols);
$this->buf .= '">No Content</td></tr>
			';
}
$this->buf .= '
			</table>
			
			<input type="hidden" name="action" value="update"/>
		
			<input type="submit" id="datasetDeleteButton" name="submitted_delete" value="delete checked"/>
			';
if(_hxtemplo_is_true($ctx->isOrderingEnabled)) {
$this->buf .= '<input type="submit" id="datasetOrderButton" name="submitted_order" value="order"/>';
}
$this->buf .= '
	</form>
	
	';
if($ctx->paginationLinks !== '') {
$this->buf .= '
	<div class="datasetPagination">
		';
$this->buf .= $ctx->paginationLinks;
$this->buf .= '
	</div>
	';
}
$this->buf .= '
	
	<script>
		';
if(_hxtemplo_is_true($ctx->linkMode)) {
$this->buf .= '
				$(document).ready(function(){
					var el = $("#form1___submit", top.document);
					el.text("Update");
					el[0].disabled = false;
				});
		';
}
$this->buf .= '	
	
		$(\'.qTip\').qtip({
			tooltip: \'bottomRight\',
			style: \'cream\',
			show: {
				delay: 200,
				solo: true,
				effect: {
					type: \'none\'
				}
			},
			hide: {
				effect: {
					type: \'none\'
				}
			}
		});
		
		';
if(_hxtemplo_is_true(!$ctx->currentFilterSettings->enabled)) {
$this->buf .= '
			$("#filterOrderWrapper").hide();
		';
}
$this->buf .= '
		
		function toggleFilterOrder()
		{
			$("#filterOrderWrapper").toggle();
			return(false);
		}
		
		var allChecked = false;
		
		$(document).ready(function(){
			$("#datasetDeleteButton").click(function(){
				if(confirm("Delete checked rows?")){
					return true;
				}else{
					return false;
				}
			});
			$("#datasetCheckAll").click(function(){
				allChecked = !allChecked;
				$(".datasetListTable input[type=checkbox]").attr("checked", allChecked);
			});
		});
	</script>	';

?>