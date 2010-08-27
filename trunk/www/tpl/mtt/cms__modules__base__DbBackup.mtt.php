<?php

$this->buf .= '	<div style="margin-bottom:50px;">
		<h3>Restore Backups</h3>
		
		';
if($ctx->restoreFile !== null && $ctx->restoreFile !== '') {
$this->buf .= '
			';
if($ctx->confirmRestore === null || _hxtemplo_is_true(!$ctx->confirmRestore)) {
$this->buf .= '
			<p style="color:red;">
				You are about to restore the database with data saved on ';
$this->buf .= $ctx->restoreDate;
$this->buf .= '.<br/>
				Any changes to data made since then will be lost.<br/><br/>
				Are you sure you want to restore the database?<br/><br/>
			</p>
			<form action="" method="POST">
				<input type="hidden" name="restore" value="';
$this->buf .= $ctx->restoreFile;
$this->buf .= '"/>
				<input type="hidden" name="confirmRestore" value="1"/>
				<table>
					<tr>
						<td><input type="submit" value="Yes I am sure!"/></td>
						<td><a href="?request=cms.modules.base.DbBackup" style="font-weight:bold;padding-left:30px;color:blue">CANCEL</a></td>
					</tr>
				</table>
			</form>
			
			';
} else {
$this->buf .= '
			Restored!<br/><br/>
			Execution time: ';
$this->buf .= $ctx->executionTime;
$this->buf .= '
			';
}
$this->buf .= '
			
		';
} else {
$this->buf .= '
		
		<style type="text/css">
			#dbBackupTable td {
				padding: 2px 10px 2px 10px;
			}
		</style>
		
		<form action="" method="POST">
			<table id="dbBackupTable" border="1" cellpadding="2" cellspacing="2">
				<tr>
					<td><b>Backup Date</b></td>
					<td><b>Size</b></td>
					<td><b>Database</b></td>
					<td><b>Host</b></td>
					<td><b>Comment</b></td>
					<td><b>Download</b></td>
					<td><b>Restore?</b></td>
				</tr>
				';
$repeater_backup = _hxtemplo_repeater($ctx->backups);  while($repeater_backup->hasNext()) {$ctx->backup = $repeater_backup->next(); 
$this->buf .= '
				<tr>
					<td>';
$this->buf .= $ctx->backup->date;
$this->buf .= '</td>
					<td>';
$this->buf .= $ctx->backup->size;
$this->buf .= '</td>
					<td>';
$this->buf .= $ctx->backup->dbName;
$this->buf .= '</td>
					<td>';
$this->buf .= $ctx->backup->dbHost;
$this->buf .= '</td>
					<td>';
$this->buf .= $ctx->backup->comment;
$this->buf .= '</td>
					<td><a href="';
$this->buf .= $ctx->backup->url;
$this->buf .= '">Download</a></td>
					<td align="center"><input type="radio" name="restore" value="';
$this->buf .= $ctx->backup->filename;
$this->buf .= '"/></td>
				</tr>
				';
}
$this->buf .= '
			</table>
			<input type="submit" value="Restore"/>
		</form>
		';
}
$this->buf .= '
		
	</div>	
	
	';
if($ctx->restoreFile === null) {
$this->buf .= '
	<div>
		<h3>Backup Database</h3>
		<form action="" method="POST">
			<input type="hidden" name="createBackup" value="1"/>
			<table border="1">
				<tr>
					<td>Tables</td>
					<td>
						<select multiple="multiple" size="';
$this->buf .= _hxtemplo_string($ctx->listSize);
$this->buf .= '" name="tables[]">
							';
$repeater_table = _hxtemplo_repeater($ctx->tables);  while($repeater_table->hasNext()) {$ctx->table = $repeater_table->next(); 
$this->buf .= '
							<option value="';
$this->buf .= $ctx->table;
$this->buf .= '">';
$this->buf .= $ctx->table;
$this->buf .= '&nbsp;&nbsp;&nbsp;&nbsp;</option>
							';
}
$this->buf .= '
						</select>
					</td>
				</tr>
				<tr><td>Comment</td><td><input type="text" size="80" name="comment" value=""/></td></tr>
				<tr><td></td><td><input type="submit" value="Create Backup"/></td></tr>
			</table>
		</form>
	</div>
	';
}
?>