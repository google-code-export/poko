<?php
if(_hxtemplo_length($ctx->showOnlyLibraries) === 0 || _hxtemplo_length($ctx->showOnlyLibraries) > 1) {
$this->buf .= '
	';
$this->buf .= $ctx->form->getPreview();
$this->buf .= '
';
}
$this->buf .= '

';
if($ctx->currentView === 'thumbs') {
$this->buf .= '
<div id="galleryViewType">View as Thumbs | <a href="?request=cms.modules.media.MediaSelector&viewType=list&libraryViewThumb=1&libraryViewList=1&from=';
$this->buf .= _hxtemplo_string($ctx->from);
$this->buf .= '&form1_galleryList=';
$this->buf .= _hxtemplo_string($ctx->gallery);
$this->buf .= '&elementId=';
$this->buf .= _hxtemplo_string($ctx->elementId);
$this->buf .= '">List</a></div>
';
} else {
$this->buf .= '
<div id="galleryViewType">View as <a href="?request=cms.modules.media.MediaSelector&viewType=thumbs&libraryViewThumb=1&libraryViewList=1&from=';
$this->buf .= _hxtemplo_string($ctx->from);
$this->buf .= '&form1_galleryList=';
$this->buf .= _hxtemplo_string($ctx->gallery);
$this->buf .= '&elementId=';
$this->buf .= _hxtemplo_string($ctx->elementId);
$this->buf .= '">Thumbs</a> | List</div>
';
}
$this->buf .= '

<div id="imageContent">
	
	';
if($ctx->currentView === 'thumbs') {
$this->buf .= '
		';
$repeater_item = _hxtemplo_repeater($ctx->items);  while($repeater_item->hasNext()) {$ctx->item = $repeater_item->next(); 
$this->buf .= '
			<div class="galleryItem">
				';
$this->buf .= $ctx->controller->getItem($ctx->item);
$this->buf .= '
			</div>
		';
}
$this->buf .= '
		';
if(_hxtemplo_length($ctx->items) === 0) {
$this->buf .= '
			<div>No files</div>
		';
}
$this->buf .= '
	';
} else {
$this->buf .= '
		<div class="galleryList">
		';
$repeater_item = _hxtemplo_repeater($ctx->items);  while($repeater_item->hasNext()) {$ctx->item = $repeater_item->next(); 
$this->buf .= '
			<div class="galleryListItem">
				';
$this->buf .= $ctx->controller->getItem($ctx->item);
$this->buf .= '
			</div>
		';
}
$this->buf .= '
		</div>
	';
}
$this->buf .= '
	
</div>
<div style="clear:both;"></div>
';

?>