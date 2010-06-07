<?php

$this->buf .= '
<h3>Manage Galleries</h3>
	
<br/><br/>

<form action="" method="POST">
	<input name="newGallery"/>
	<input type="hidden" name="action" value="add"/>
	<button>Add Gallery</button>
</form>


<form action="" method="POST">
	<table class="greyTable" id="Galleries">
		<tr>
			<td>&nbsp;</td>
			<td><b>name</b></td>
			<td>&nbsp;</td>
		</tr>
		
		';
$repeater_element = _hxtemplo_repeater($ctx->galleries);  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
			<tr>
				<td><input type="checkbox" name="delete[]" value="';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '"/></td>
				<td>';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '</td>
				<td><a href="?request=cms.modules.media.Gallery&id=';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '&definition=';
$this->buf .= _hxtemplo_string($ctx->element->name);
$this->buf .= '">edit</a></td>
			</tr>	
		';
}
$this->buf .= '
		
	</table>
	<br/>
	<input type="hidden" name="action" value="update"/>
	<input type="submit" name="submitted_delete" value="delete"/>
</form>';

?>