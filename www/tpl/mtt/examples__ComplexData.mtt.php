<?php

$this->buf .= '<h1>Complex Data</h1>

<p>In the example The projects page has a few complex linkages.</p>

<p>Accessing Data is done similar to how you normally would with relational databases - seeing as the data is stored in similar ways</p>

<br/>
<p><b>`Category`</b><br/>
The category field is an association (one to many) selecting data from the categories table. This link is stored in the `category` field</p>

<br/>
<p><b>`Services`</b> <br/>
Services is an extra called a Multi-link (many to many link) allows the linking to many values in the services table. This links are stored in the `example_project_services` table (as are normal many to many links)</p>

<br/>
<p><b>`Images`</b> <br/>
Images is an extra called a Link-View (allows you to manage another table an associate data to the record you are editing). You can assign many images to a project here. They are stored in the `example_images` table. This table is hidden from general editing in the cms, and is only used to associate images to project. Many tables could have images associated to them however (just create Link-Views on other datasets linking to the images table)</p>

<h3>View a Project</h3>
';
$this->buf .= $ctx->form1->getPreview();
$this->buf .= '


';
if(_hxtemplo_is_true($ctx->showProject)) {
$this->buf .= '

<b>Raw Data</b>
<table class="greyTable">
	<tr><td><b>id</b></td><td>';
$this->buf .= _hxtemplo_string($ctx->projectData->id);
$this->buf .= '</td></tr>
	<tr><td><b>name</b></td><td>';
$this->buf .= _hxtemplo_string($ctx->projectData->name);
$this->buf .= '</td></tr>
	<tr><td><b>Category</b></td><td>';
$this->buf .= _hxtemplo_string($ctx->projectData->category);
$this->buf .= '</td></tr>
	<tr><td><b>image</b></td><td><img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->projectData->heroimage);
$this->buf .= '"/></td></tr>
	<tr><td><b>description</b></td><td>';
$this->buf .= _hxtemplo_string($ctx->projectData->description);
$this->buf .= '</td></tr>
</table>

<br/>
<b>Linked Services (using multilink)</b>
<table class="greyTable">
	';
$repeater_service = _hxtemplo_repeater($ctx->projectServices);  while($repeater_service->hasNext()) {$ctx->service = $repeater_service->next(); 
$this->buf .= '
		<tr><td><b>';
$this->buf .= _hxtemplo_string($ctx->service->name);
$this->buf .= '</b></td><td>';
$this->buf .= $ctx->service->description;
$this->buf .= '</td></tr>
	';
}
$this->buf .= '
</table>


<br/>
<b>Linked Images (using linkview)</b>
<table class="greyTable">
	';
$repeater_image = _hxtemplo_repeater($ctx->projectImages);  while($repeater_image->hasNext()) {$ctx->image = $repeater_image->next(); 
$this->buf .= '
		<tr><td><b>';
$this->buf .= _hxtemplo_string($ctx->image->id);
$this->buf .= '</b></td><td><img src="?request=services.Image&preset=thumb&src=';
$this->buf .= _hxtemplo_string($ctx->image->image);
$this->buf .= '"/></td></tr>
	';
}
$this->buf .= '
</table>

';
}
?>