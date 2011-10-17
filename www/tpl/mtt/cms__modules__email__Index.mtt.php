<?php

$this->buf .= '	<div style="margin-bottom:50px;">
		<h3>Create &amp; Send Email</h3>
		
		';
if($ctx->form->submittedButtonName !== 'sendBtn') {
$this->buf .= '
		<p style="margin-bottom:10px;">
			A total of <strong>';
$this->buf .= _hxtemplo_string($ctx->userCount);
$this->buf .= '</strong> users will received this email.
		</p>
		';
}
$this->buf .= '
		
		';
$this->buf .= $ctx->form->getOpenTag();
$this->buf .= '
		
		';
$this->buf .= $ctx->form->getErrors();
$this->buf .= '
		
		';
if($ctx->form->submittedButtonName === 'previewBtn' && _hxtemplo_is_true($ctx->form->isValid())) {
$this->buf .= '
			<div>
				<div style="background-color:#eeeeee;padding:5px;width:500px;margin-bottom:10px;margin-top:20px">';
$this->buf .= $ctx->previewStr;
$this->buf .= '</div>
				<input type="hidden" name="emailForm_body" value="';
$this->buf .= $ctx->editStr;
$this->buf .= '"/>
				<input type="hidden" name="emailForm_fromName" value="';
$this->buf .= $ctx->form->getElement('fromName')->value;
$this->buf .= '"/>
				<input type="hidden" name="emailForm_fromEmail" value="';
$this->buf .= $ctx->form->getElement('fromEmail')->value;
$this->buf .= '"/>
				<input type="hidden" name="emailForm_emailSubject" value="';
$this->buf .= $ctx->form->getElement('emailSubject')->value;
$this->buf .= '"/>
				<div style="display:inline">';
$this->buf .= $ctx->form->getElement('editBtn');
$this->buf .= '</div>
				<div style="display:inline;margin-left:400px">';
$this->buf .= $ctx->form->getElement('sendBtn');
$this->buf .= '</div>
			</div>
		';
}else if($ctx->form->submittedButtonName === 'sendBtn' && _hxtemplo_is_true($ctx->form->isValid())) {
$this->buf .= '
			<p>Your mail was sent to <strong>';
$this->buf .= _hxtemplo_string($ctx->userCount);
$this->buf .= '</strong> users!</p>
		';
} else {
$this->buf .= '
			<p>
				<em>The following variables are available:</em><br/>
				<ul style="list-style-type: square; margin-left: 30px;margin-bottom: 20px;">
				';
$repeater_v = _hxtemplo_repeater($ctx->emailVars);  while($repeater_v->hasNext()) {$ctx->v = $repeater_v->next(); 
$this->buf .= '
					<li><b>';
$this->buf .= $ctx->controller->encodeVar($ctx->v->name);
$this->buf .= '</b> - ';
$this->buf .= $ctx->v->desc;
$this->buf .= '</li> 
				';
}
$this->buf .= '
				</ul>
			</p>
		
			<table>
				<tr><td width="100">';
$this->buf .= $ctx->form->getLabel('fromName');
$this->buf .= '</td><td>';
$this->buf .= $ctx->form->getElement('fromName');
$this->buf .= '</td></tr>
				<tr><td>';
$this->buf .= $ctx->form->getLabel('fromEmail');
$this->buf .= '</td><td>';
$this->buf .= $ctx->form->getElement('fromEmail');
$this->buf .= '</td></tr>
				<tr><td>';
$this->buf .= $ctx->form->getLabel('emailSubject');
$this->buf .= '</td><td>';
$this->buf .= $ctx->form->getElement('emailSubject');
$this->buf .= '</td></tr>
				<tr><td colspan="2">';
$this->buf .= $ctx->form->getElement('body');
$this->buf .= '</td></tr>
				<tr><td colspan="2">';
$this->buf .= $ctx->form->getElement('previewBtn');
$this->buf .= '</td></tr>
			</table>
		';
}
$this->buf .= '	
		
		';
$this->buf .= $ctx->form->getCloseTag();
$this->buf .= '
	</div>';

?>