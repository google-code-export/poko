<?php

$this->buf .= '	<h3>Manage Pages</h3>
	
	<form action="" method="POST">
		Create a Page<br/>
		<input name="name"/>
		<input type="hidden" name="action" value="add"/>
		<button>Add</button>
	</form>
	<br/>
	
	Existing Pages<br/>
	<form action="" method="POST">
		<table class="greyTable" id="News">
			
			';
$ctx->c = 1;
$this->buf .= '
			';
$repeater_definition = _hxtemplo_repeater($ctx->definitions);  while($repeater_definition->hasNext()) {$ctx->definition = $repeater_definition->next(); 
$this->buf .= '
				
				<tr>
					<td><input type="checkbox" name="delete[';
$this->buf .= _hxtemplo_string($ctx->definition->id);
$this->buf .= ']" value="1"/></td>
					<td>';
$this->buf .= _hxtemplo_string($ctx->definition->name);
$this->buf .= '</td>
					<td><a href="?request=cms.modules.base.Definition&id=';
$this->buf .= _hxtemplo_string($ctx->definition->id);
$this->buf .= '">edit Definitions</a></td>
				</tr>
				
			';
}
$this->buf .= '
		</table>
		<br/>
		<input type="hidden" name="action" value="delete"/>
		<button>Delete checked</button>
	</form>';

?>