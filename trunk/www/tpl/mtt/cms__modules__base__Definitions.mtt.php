<?php

$this->buf .= '
	<h3>Manage ';
$this->buf .= _hxtemplo_string($ctx->pageLabel);
$this->buf .= 's</h3>
	
	';
if((_hxtemplo_is_true($ctx->pagesMode))) {
$this->buf .= '
	<form action="" method="POST">
		Create a ';
$this->buf .= _hxtemplo_string($ctx->pageLabel);
$this->buf .= '<br/>
		<input name="name"/>
		<input type="hidden" name="action" value="add"/>
		<button>Add</button>
	</form>
	<br/>
	
	
	
	<h4>Current ';
$this->buf .= _hxtemplo_string($ctx->pageLabel);
$this->buf .= 's</h4>
	';
}
$this->buf .= '
	<form action="" method="POST">
		<table class="greyTable" id="News">
			
			<tr>
				<td><b></b></td>
				<td><b>ord</b></td>
				<td><b>Name</b></td>
				';
if((_hxtemplo_is_true(!$ctx->pagesMode))) {
$this->buf .= '<td><b>Table</b></td>';
}
$this->buf .= '
				<td><b>Indents</b></td>
				<td><b>In Menu?</b></td>
				<td></td>
			</tr>
			
			';
$repeater_definition = _hxtemplo_repeater($ctx->assigned);  while($repeater_definition->hasNext()) {$ctx->definition = $repeater_definition->next(); 
$this->buf .= '
				
				<tr>
					<td><input type="checkbox" name="delete[';
$this->buf .= _hxtemplo_string($ctx->definition->id);
$this->buf .= ']&pagesMode=';
$this->buf .= _hxtemplo_string($ctx->pagesMode);
$this->buf .= '" value="1"/></td>
					<td><input type="input" name="order[';
$this->buf .= _hxtemplo_string($ctx->definition->id);
$this->buf .= ']" value="';
$this->buf .= _hxtemplo_string($ctx->definition->order);
$this->buf .= '" class="order"/></td>
					<td>';
$this->buf .= _hxtemplo_string($ctx->definition->name);
$this->buf .= '</td>
					';
if((_hxtemplo_is_true(!$ctx->pagesMode))) {
$this->buf .= '<td>';
$this->buf .= _hxtemplo_string($ctx->definition->table);
$this->buf .= '</td>';
}
$this->buf .= '
					<td>';
$this->buf .= _hxtemplo_string($ctx->definition->indents);
$this->buf .= '</td>
					<td>';
if($ctx->definition->showInMenu === 1) {
$this->buf .= ' &#x2714; ';
} else {
$this->buf .= ' &#x02610; ';
}
$this->buf .= '</td>
					<td><a href="?request=cms.modules.base.Definition&id=';
$this->buf .= _hxtemplo_string($ctx->definition->id);
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
		<button type="submit">undefine & order</button>
	</form>
	
	';
if((_hxtemplo_is_true(!$ctx->pagesMode))) {
$this->buf .= '
	<br/><br/>
	<h4>Undefined Tables</h4>
	<form action="" method="POST">
		<table class="greyTable" id="News">
			
			';
$ctx->c = 1;
$this->buf .= '
			';
$repeater_table = _hxtemplo_repeater($ctx->unassigned);  while($repeater_table->hasNext()) {$ctx->table = $repeater_table->next(); 
$this->buf .= '
				
				<tr>
					<td>';
$this->buf .= _hxtemplo_string($ctx->table->name);
$this->buf .= '</td>
					<td><a href="?request=cms.modules.base.Definitions&manage=true&action=define&define=';
$this->buf .= _hxtemplo_string($ctx->table->name);
$this->buf .= '">Define</a></td>
				</tr>
				
			';
}
$this->buf .= '	
		</table>
	</form>
	';
}
?>