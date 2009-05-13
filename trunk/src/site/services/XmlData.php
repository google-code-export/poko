<?="<?xml version=\"1.0\" encoding=\"UTF-8\"?>" ?>
<root>
	<data>
		<? foreach(iterate($data) as $row){ ?>
		<row>
			<? foreach(iterate($row) as $k=>$v){ ?>
			<<?=$k?>><![CDATA[<?=$v?>]]></<?=$k?>>
			<? } ?>
		</row>
		<? } ?>
	</data>
	<reponse><?=$response?></reponse>
</root>