<?php

$this->buf .= '
<ul>
';
$repeater_item = _hxtemplo_repeater($ctx->navItems);  while($repeater_item->hasNext()) {$ctx->item = $repeater_item->next(); 
$this->buf .= '
	';
if($ctx->item->label === $ctx->selected) {
$this->buf .= '
		<li>';
$this->buf .= _hxtemplo_string($ctx->item->label);
$this->buf .= '</li>
	';
} else {
$this->buf .= '
		<li><a href="';
$this->buf .= _hxtemplo_string($ctx->item->url);
$this->buf .= '">';
$this->buf .= _hxtemplo_string($ctx->item->label);
$this->buf .= '</a></li>
	';
}
$this->buf .= '
';
}
$this->buf .= '
</ul>';

?>