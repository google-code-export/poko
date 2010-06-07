<?php

$this->buf .= '
';
$this->buf .= $ctx->header;
$this->buf .= '

';
$repeater_section = _hxtemplo_repeater($ctx->sections->keys());  while($repeater_section->hasNext()) {$ctx->section = $repeater_section->next(); 
$this->buf .= '
	';
if(_hxtemplo_is_true(!$ctx->sectionsIsSeperator->get($ctx->section))) {
$this->buf .= '
		<h3>';
$this->buf .= _hxtemplo_string($ctx->section);
$this->buf .= '</h3>
		<ul>
			
		';
$repeater_item = _hxtemplo_repeater($ctx->sections->get($ctx->section));  while($repeater_item->hasNext()) {$ctx->item = $repeater_item->next(); 
$this->buf .= '
			';
if($ctx->item->link !== null) {
$this->buf .= '
				<li><a href="?request=';
$this->buf .= _hxtemplo_string($ctx->item->link);
$this->buf .= '">';
$this->buf .= $ctx->item->indents;$this->buf .= $ctx->item->title;
$this->buf .= ' </a></li>
			';
} else {
$this->buf .= '
				<li>';
$this->buf .= $ctx->item->indents;$this->buf .= $ctx->item->title;
$this->buf .= '</li>
			';
}
$this->buf .= '
		';
}
$this->buf .= '

		
		</ul>
	';
} else {
$this->buf .= '
		<hr class="leftNavigationSeperator"/>
	';
}
$this->buf .= '
	
';
}
$this->buf .= '

<div id="leftNavigationFooter">
	';
$this->buf .= $ctx->footer;
$this->buf .= '
</div>';

?>