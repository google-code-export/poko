<?php

$this->buf .= '<h3>Basic Concepts</h3>

<p>
	The base module of the CMS provides four main sections:
	<ol>
		<li>Pages</li>
		<li>Datasets</li>
		<li>definitions</li>
		<li>Users</li>
	</ol>
</p>
<p>
	The Definitions section is used to create Meta Data which the CMS uses to create datasets. 
	When a definition is applied to an existing databse table (use PhpMyAdmin or the like to create your table as you wish), the system uses this metadata to display the data (as a CRUD) in a useable and friendly way. 
	The Definition allows correct formatting of data (eg. Text, Number, Image, etc) and also information about which fields are editable, visible, etc. The same definition can be applied to many databse tables if needed.
</p>
<p>
	Pages are similar to Datasets, except that they only allow a single instance of the defined data. They do not require a database table to exist to store the data either. 
</p>';

?>