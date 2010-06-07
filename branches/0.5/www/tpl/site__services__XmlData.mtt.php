<?php
$this->buf .= _hxtemplo_string('<?xml version="1.0" encoding="UTF-8"?>');
$this->buf .= '
<root>
	<data>
		';
$repeater_row = _hxtemplo_repeater($ctx->data);  while($repeater_row->hasNext()) {$ctx->row = $repeater_row->next(); 
$this->buf .= '
		<row>
			';
$repeater_key = _hxtemplo_repeater($ctx->Reflect->fields($ctx->row));  while($repeater_key->hasNext()) {$ctx->key = $repeater_key->next(); 
$this->buf .= '
			';
$this->buf .= _hxtemplo_string(_hxtemplo_add(_hxtemplo_add('<',$ctx->key),'>'));
$this->buf .= '
				<![CDATA[';
$this->buf .= _hxtemplo_string($ctx->Reflect->field($ctx->row, $ctx->key));
$this->buf .= ']]>
			';
$this->buf .= _hxtemplo_string(_hxtemplo_add(_hxtemplo_add('</',$ctx->key),'>'));
$this->buf .= '
			';
}
$this->buf .= '
		</row>
		';
}
$this->buf .= '
	</data>
	<reponse>';
$this->buf .= _hxtemplo_string($ctx->response);
$this->buf .= '</reponse>
</root>';

?>