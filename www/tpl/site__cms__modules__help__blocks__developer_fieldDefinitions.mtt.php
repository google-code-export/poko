<?php

$this->buf .= '<h3>Definition Elements</h3>

<br/>
<h4>text</h4>
<p>Define the element as a piece of Text.</p>

<h4>number</h4>
<p>Define the element as a Number.</p>

<h4>bool</h4>
<p>Define the element as a Boolean (true/false). You can set different values for the lables of true and false. eg: "hot" / "cold" might evaluate as true/false in the DB. </p>


<h4>image</h4>
<p>Define the element as an image. </p>


<h4>richtext</h4>
<p>Define the element as a Rich (WYSIWYG) editable region. You can define a custom css file for the display. This is useful for making your region look more like the frontend. </p>


<h4>date</h4>
<p>Define a date. </p>


<h4>association</h4>
<p>An association is a one to many association. it will aloow you to select a value from a field of values found in another table.<br/>
<b>table:</b> the table that you want to select a value from <br/>
<b>field:</b> the field to select from (eg. the id field of a table)<br/>
<b>label:</b> the field to label the data that you are selecting (eg you might select the \'name\' field of a table)<br/>
<b>show as label:</b> whether or not to use the label field (really could be taken out couldnt it ;P )<br/>
</p>

<h4>multilink</h4>
<p>A multilink is a many to many association. You can select many values from a field in another table. <br/>
<b>With Table:</b> the table you want to link with. <br/>
<b>With Field:</b> the field to take data from.<br/>
<b>With Label:</b> the field to label this data.<br/>
<br/>
These linkages will need to be stored in a table (as do all many-to-many relationships) so you need to create a table and define it here:<br/>
<b>Link Table:</b> The table which holds these linkages<br/>
<b>Link Field 2:</b> Will store the id\'s of this table<br/>
<b>Link Field 2:</b> Will store the id\'s of the linked table<br/>
</p>

<h4>keyvalue</h4>
<p>This is a handy little component used for defining a list of key value pairs. </p>

<h4>read-only</h4>
<p>States it all!</p>

<h4>linkcategory</h4>
<p>Define the field to be used with a LinkTable Display. This field will be populated with information defining the table which has set a LinkValue - see next:</p>

<h4>linkvalue</h4>
<p>Stores the id of another table which has created this item using a LinkTable Display.</p>

<h4>hidden</h4>';

?>