<?php

$this->buf .= '
	<h3>Manage ';
$this->buf .= _hxtemplo_string($ctx->pageLabel);
$this->buf .= ' : "';
$this->buf .= _hxtemplo_string($ctx->definition->name);
$this->buf .= '"</h3>
	
	';
$this->buf .= $ctx->form1->getPreview();
$this->buf .= '
	
	<br/><br/>
	<h4>Defined Fields</h4>
	
	';
if(_hxtemplo_is_true($ctx->pagesMode)) {
$this->buf .= '
	<form action="" method="POST">
		<input name="elementName"/>
		<input type="hidden" name="action" value="addElement"/>
		<button>Add Field</button>
	</form>
	';
}
$this->buf .= '
	
	<form action="" method="POST">
		<table class="greyTable" id="News">
			<tr>
				<td colspan="2"><b>order</b></td>
				<td><b>field</b></td>
				';
if(_hxtemplo_is_true(!$ctx->pagesMode)) {
$this->buf .= '<td><b>field type</b></td>';
}
$this->buf .= '
				<td><b>definition</b></td>
				<td><b>vis</b></td>
				<td><b>flt</b></td>
				<td><b>ord</b></td>
				<td>&nbsp;</td>
			</tr>
			
			';
$repeater_element = _hxtemplo_repeater($ctx->definition->elements);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
				
				<tr>
					<td><input type="checkbox" name="delete[]" value="';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '"/></td>
					<td><input type="input" name="order[]" value="';
$this->buf .= _hxtemplo_string($repeater_element->number);
$this->buf .= '" class="order"/></td>
					<td>';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '</td>
					';
if(_hxtemplo_is_true(!$ctx->pagesMode)) {
$this->buf .= '<td>(';
if($ctx->element->dbtype !== null) {$this->buf .= _hxtemplo_string($ctx->element->dbtype);}
$this->buf .= ')</td>';
}
$this->buf .= '
					<td>(';
$this->buf .= _hxtemplo_string($ctx->element->type);
$this->buf .= ')</td>
					<td><a id="checkboxToggle_showInList_';
$this->buf .= _hxtemplo_string($repeater_element->index);
$this->buf .= '" href="javascript:" onClick="';
$this->buf .= _hxtemplo_string($ctx->jsBind->getCall('toggleCheckbox', new _hx_array(array($ctx->element->name, $repeater_element->index, 'showInList'))));
$this->buf .= '">';
if(_hxtemplo_is_true($ctx->element->showInList)) {
$this->buf .= ' &#x2714; ';
} else {
$this->buf .= ' &#x02610; ';
}
$this->buf .= '</a></td>
					<td><a id="checkboxToggle_showInFiltering_';
$this->buf .= _hxtemplo_string($repeater_element->index);
$this->buf .= '" href="javascript:" onClick="';
$this->buf .= _hxtemplo_string($ctx->jsBind->getCall('toggleCheckbox', new _hx_array(array($ctx->element->name, $repeater_element->index, 'showInFiltering'))));
$this->buf .= '">';
if(_hxtemplo_is_true($ctx->element->showInFiltering)) {
$this->buf .= ' &#x2714; ';
} else {
$this->buf .= ' &#x02610; ';
}
$this->buf .= '</a></td>
					<td><a id="checkboxToggle_showInOrdering_';
$this->buf .= _hxtemplo_string($repeater_element->index);
$this->buf .= '" href="javascript:" onClick="';
$this->buf .= _hxtemplo_string($ctx->jsBind->getCall('toggleCheckbox', new _hx_array(array($ctx->element->name, $repeater_element->index, 'showInOrdering'))));
$this->buf .= '">';
if(_hxtemplo_is_true($ctx->element->showInOrdering)) {
$this->buf .= ' &#x2714; ';
} else {
$this->buf .= ' &#x02610; ';
}
$this->buf .= '</a></td>
					<td><a href="?request=cms.modules.base.DefinitionElement&id=';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '&definition=';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '&pagesMode=';
$this->buf .= _hxtemplo_string($ctx->pagesMode);
$this->buf .= '">edit</a></td>
				</tr>
				
			';
}
$this->buf .= '
		</table>
		<br/>
		<input type="hidden" name="action" value="update"/>
		<input type="submit" name="submitted_delete" value="delete & order"/>
	</form>
	
	';
if(_hxtemplo_is_true(!$ctx->pagesMode)) {
$this->buf .= '
	<br/><br/>
	<h4>Undefined Fields</h4>
	<table class="greyTable" id="News">
		<tr>
				<td><b>field</b></td>
				<td><b>type</b></td>
				<td></td>
		</tr>
		
		';
$repeater_element = _hxtemplo_repeater($ctx->undefinedFields);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
		<tr>
			<td>';
$this->buf .= _hxtemplo_string($ctx->element->Field);
$this->buf .= '</td>
			<td>(';
$this->buf .= _hxtemplo_string($ctx->element->Type);
$this->buf .= ')</td>
			<td><a href="?request=cms.modules.base.Definition&id=';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '&action=define&define=';
$this->buf .= _hxtemplo_string($ctx->element->Field);
$this->buf .= '">Define</a></td>
		</tr>
		';
}
$this->buf .= '
	</table>
	
	<br/><br/>
	<h4>Extras</h4>
	<a href="?request=cms.modules.base.Definition&id=';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '&action=addExtra&extra=multilink">Add \'Multilink\'</a><br/>
	<a href="?request=cms.modules.base.Definition&id=';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '&action=addExtra&extra=linkdisplay">Add \'Link-Display\'</a>
	';
}
$this->buf .= '
	
	';

?>