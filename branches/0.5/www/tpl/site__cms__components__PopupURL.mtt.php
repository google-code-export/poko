<?php

$this->buf .= '<a href="';
$this->buf .= _hxtemplo_string($ctx->contentUrl);
$this->buf .= '&width=';
$this->buf .= _hxtemplo_string($ctx->width);
$this->buf .= '&height=';
$this->buf .= _hxtemplo_string($ctx->height);
$this->buf .= '" title="Media Gallery" id="';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '_libraryOpen">';
$this->buf .= _hxtemplo_string($ctx->label);
$this->buf .= '</a>

<div id="';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '_jqmModalWindow" class="jqmWindow">
	<div id="jqmTitle">
		<button class="jqmClose">
			Close X
		</button>
		<span id="jqmTitleText">Title of modal window</span>
	</div>
	<iframe id="jqmContent" src=""></iframe>
</div>

<style>
	.jqmClose{ background:#FFDD00; border:1px solid #FFDD00; color:#000; clear:right; float:right; padding:0 5px; cursor:pointer; }
	.jqmClose:hover{ background:#FFF; }
	#jqmContent{ width:99%; height:99%; display: block; clear:both; margin:auto; margin-top:10px; background:#111; border:1px dotted #444; }
</style>

<script>
	
	$(\'#';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '_jqmModalWindow\').jqm({
		overlay: 70,
		modal: true,
		trigger: \'#';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= '_libraryOpen\',
		target: \'#jqmContent\',
		onHide: closeModal,
		onShow: openInFrame
	});	

</script>';

?>