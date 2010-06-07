<?php

$this->buf .= '<h3>About Definitions</h3>

<div class="description">
	
	<p>
		<strong>Definitions</strong> are used as the basis for setting up <strong>Datasets</strong>. 
		A <strong>Definition</strong> defines metadata about a group of "elements". These elements may match the fields of a table in your database. 
	</p>	
	<p>
		When a <strong>Definition</strong> is assigned to a database table (from the manage page of the Datasets section) a <strong>Dataset</strong> is created. 
		When elements in the definition match database table (via their names) the data is made editable in the dataset and formatted according to the definition. 
		eg. \'Image\' definitions produce a preview of the image and a file upload box. 
	</p>

	<p>
		A definition can be assigned to many database tables.
	</p>

</div>';

?>