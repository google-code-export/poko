<?php

$this->buf .= '
	<h3>Manage ';
$this->buf .= _hxtemplo_string($ctx->pageLabel);
$this->buf .= ' : "';
$this->buf .= _hxtemplo_string($ctx->definition->name);
$this->buf .= '"</h3>
	
	<div id="definitionOptions">
		<div id=""><a href="#" onclick="$(\'#definitionOptionsForm\').toggle(); return false;"><img src="./res/cms/cog.png" title="Toggle Definitions Options" align="absmiddle"/> Toggle Definitions Options</a></div>
		<div id="definitionOptionsForm" style="display: none;">
		
		';
$this->buf .= $ctx->form1->getOpenTag();
$this->buf .= '
			
			';
$this->buf .= $ctx->form1->getErrors();
$this->buf .= '	
			
				';
if($ctx->form1->getFieldsets() !== null) {
$this->buf .= '
					';
$repeater_fieldset = _hxtemplo_repeater($ctx->form1->getFieldsets());  while($repeater_fieldset->hasNext()) {$ctx->fieldset = $repeater_fieldset->next(); 
$this->buf .= '
						';
if($ctx->fieldset->name !== '__submit' && _hxtemplo_length($ctx->fieldset->elements) > 0) {
$this->buf .= ' 
							
							';
$this->buf .= $ctx->fieldset->getOpenTag();
$this->buf .= '
								<table class="greyTable">
									';
$repeater_element = _hxtemplo_repeater($ctx->fieldset->elements);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
									<tr>
										<td class="labelTd">';
$this->buf .= $ctx->element->getLabel();
$this->buf .= ' ';
if($ctx->element->description !== null && $ctx->element->description !== '') {
$this->buf .= '<img class="qTip" src="./res/cms/help.png" title="';
$this->buf .= $ctx->controller->nl2br($ctx->element->description);
$this->buf .= '" align="absmiddle"/>';
}
$this->buf .= '</td>
										<td class="contentTd">';
$this->buf .= $ctx->element;
$this->buf .= '</td>
									</tr>
									';
}
$this->buf .= '
								</table>
							';
$this->buf .= $ctx->fieldset->getCloseTag();
$this->buf .= '
							
						';
}
$this->buf .= '
					';
}
$this->buf .= '
				';
} else {
$this->buf .= '
					';
$repeater_element = _hxtemplo_repeater($ctx->elements);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
						<tr><td>';
$this->buf .= $ctx->element->getLabel();
$this->buf .= '</td><td>';
$this->buf .= $ctx->element;
$this->buf .= '</td></tr>
					';
}
$this->buf .= '
				';
}
$this->buf .= '
				
				<tr><td colspan="2">';
$this->buf .= $ctx->form1->submitButton;
$this->buf .= '</td></tr>

		';
$this->buf .= $ctx->form1->getCloseTag();
$this->buf .= '
		
		</div>
	</div>
	
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
	
<script>
		
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
	
</script>';

?>