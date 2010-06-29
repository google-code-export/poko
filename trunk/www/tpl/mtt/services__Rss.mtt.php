<?php
$this->buf .= _hxtemplo_string('<?xml version="1.0" encoding="UTF-8"?>');
$this->buf .= '

<rss version="2.0">
	<channel>
		<title>';
$this->buf .= _hxtemplo_string($ctx->data->title);
$this->buf .= '</title>
		<description>';
$this->buf .= _hxtemplo_string($ctx->data->description);
$this->buf .= '</description>
		<generator>';
$this->buf .= _hxtemplo_string($ctx->data->generator);
$this->buf .= '</generator>
		<link>';
$this->buf .= _hxtemplo_string($ctx->data->link);
$this->buf .= '</link>
		
		';
$repeater_item = _hxtemplo_repeater($ctx->data->items);  while($repeater_item->hasNext()) {$ctx->item = $repeater_item->next(); 
$this->buf .= '
		<item>
			<title>';
$this->buf .= _hxtemplo_string($ctx->item->title);
$this->buf .= '</title>
			<description><![CDATA[';
$this->buf .= _hxtemplo_string($ctx->item->description);
$this->buf .= ']]></description>
			<link>';
$this->buf .= _hxtemplo_string($ctx->item->link);
$this->buf .= '</link>
			<guid>';
$this->buf .= _hxtemplo_string($ctx->item->guid);
$this->buf .= '</guid>
			<pubDate>';
$this->buf .= _hxtemplo_string($ctx->item->pubdate);
$this->buf .= '</pubDate>
		</item>
		';
}
$this->buf .= '
			
	</channel>
</rss>';

?>