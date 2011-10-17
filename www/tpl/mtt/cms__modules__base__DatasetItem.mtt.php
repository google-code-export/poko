<?php
if(_hxtemplo_is_true(!$ctx->linkMode)) {
$this->buf .= '
	<h3>
		';
$this->buf .= _hxtemplo_string($ctx->label);
$this->buf .= ' 
		';
if($ctx->pagesMode === false && $ctx->id !== null) {
$this->buf .= ' 
			&gt; row ';
$this->buf .= _hxtemplo_string($ctx->id);
$this->buf .= ' 
		';
}
$this->buf .= '
	</h3>
';
}
$this->buf .= '

';
$this->buf .= $ctx->form->getErrors();
$this->buf .= '

';
if($ctx->definition->helpItem !== null && $ctx->definition->helpItem !== '') {
$this->buf .= '
	<div id="cmsDatasetItemOverallHelp">';
$this->buf .= $ctx->controller->nl2br($ctx->definition->helpItem);
$this->buf .= '</div>
';
}
$this->buf .= '

';
$this->buf .= $ctx->form->getOpenTag();
$this->buf .= '

<table class="greyTable datasetItem" width="100%">

	';
$repeater_element = _hxtemplo_repeater($ctx->form->getElements());  while($repeater_element->hasNext()) {$ctx->element = $repeater_element->next(); 
$this->buf .= '
		';
if(($ctx->element->getType() === 'site.cms.modules.base.formElements.LinkTo')) {
$this->buf .= '
			<tr>
				<td colspan="2" class="contentTd">';
$this->buf .= $ctx->element->getLabel();
$this->buf .= ' <img src="./res/cms/help.png"/> <br/> ';
$this->buf .= $ctx->element;
$this->buf .= '</td>
			</tr>
		';
}else if(($ctx->element->getType() === 'site.cms.modules.base.formElements.LinkTable')) {
$this->buf .= '
			<tr>
				<td class="contentTd" colspan="2">
					';
$this->buf .= $ctx->element->getLabel();
$this->buf .= ' ';
if($ctx->element->description !== null && $ctx->element->description !== '') {
$this->buf .= '<img class="qTip" src="./res/cms/help.png" title="';
$this->buf .= $ctx->controller->nl2br($ctx->element->description);
$this->buf .= '" align="absmiddle"/>';
}
$this->buf .= '
					<br/><br/>
					';
$this->buf .= $ctx->element;
$this->buf .= '
				</td>
			</tr>
		';
}else if(($ctx->element->getType() !== 'poko.form.elements.Hidden')) {
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
} else {
$this->buf .= '
			';
$this->buf .= $ctx->element;
$this->buf .= '
		';
}
$this->buf .= '
	';
}
$this->buf .= '

</table>

';
$this->buf .= $ctx->form->getCloseTag();
$this->buf .= '

<script>

	';
if(_hxtemplo_is_true($ctx->linkMode)) {
$this->buf .= '
			//$(document).ready(function(){
				var el = $("#form1___submit", top.document);
				el.text("Editing linked data");
				el[0].disabled = true;
			//});
	';
}
$this->buf .= '

	//$(document).ready(function(){
		
		$(".resizableFrame").resizable();
		
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
	//});
	
</script>';

?>