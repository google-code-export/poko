<?php

$this->buf .= '<h1>Using Poko CMS</h1>

<p>
	Use the following login details to demo the Poko Content Management System.
	<ul>
		<li>
			<strong>General User</strong>
			<ul>
				<li>Username: <em>user</em></li>
				<li>Password: <em>pass</em></li>
			</ul>
		</li>
		<li style="padding-top: 5px">
			<strong>Administrator</strong>
			<ul>
				<li>Username: <em>admin</em></li>
				<li>Password: <em>pass</em></li>
			</ul>
		</li>
	</ul>
	<p>Click here to access the <strong><a href="?request=cms.Index">Poko CMS</a></strong> login page.</p><br/>
</p>


<h3>CMS Backup &amp; Restore</h3>
<p>
	Poko CMS provides a basic backup feature that allows administrators to backup and restore their data.<br/><br/>
	<em>Backup &amp; Restore Features:</em>
	<ul>
		<li>Automatically saves backups to directory on server for easy restoration</li>
		<li>Ability to add user defined comments to backup files</li>
		<li>Ability to backup entire database or individual tables</li>
		<li>Easy restoration of previous backups</li>
		<li>Restoration safeguard that automatically creates a backup of data being overwritten</li>
	</ul>
</p>
<p>
	This demo ships with two backups:
	<ul>
		<li>Poko CMS Base Structure - A backup of the minimum database tables needed to implement Poko CMS in your project</li>
		<li>Poko CMS &amp; Example Data - A backup of the Poko CMS tables and all the example data that ships with this demo</li>
	</ul>
</p>
<p>
	<strong>Note: A phpMyAdmin SQL dump is also exists in the root/trunk directory of the distribution for initial setup of the CMS.</strong>
</p>

<br/>
<h3>CMS Emailer</h3>
<p>
	The current version of Poko CMS also comes with limited support for sending emails to a list of users defined in a database table.<br/><br/>
	This allows administrators and editors of the CMS to create a generic email template formatted using a WYM Editor, where certain variables such as the <br/>
	user\'s <em>id</em>, <em>name</em> and <em>email</em> can be substituted into the body of the email.
</p>';

?>