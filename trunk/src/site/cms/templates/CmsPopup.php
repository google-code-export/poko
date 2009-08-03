<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="Title" content="<?=$head->title?>">
		<meta name="Generator" content="haxe(haxe.org) - fwork" />
		<meta name="Description" content="<?=$head->description?>" />
		<meta name="Meta" content="<?=$head->meta?>" />
		<meta name="Keywords" content="<?=$head->keywords?>" />
		<meta name="Publisher" content="<?=$head->publisher?>">
		<meta name="Date" content="<?=$head->date?>">
		<title><?=$head->title?></title>
		<?=$head->getJs()?>
		<?=$head->getCss()?>
		<!--[if IE 6]><?=$head->getCssIe6()?><![endif]-->
		<!--[if IE 7]><?=$head->getCssIe7()?><![endif]-->	
		<link rel="shortcut icon" href="<?=$head->favicon?>" >
	</head>
	<body>
		<div id="haxe:trace"></div>
		<div id="container">
			<?if(countOf($messages)){?>
				<div class="messageBlock" id="messageMessages"><ul>
					<?foreach(iterate($messages) as $m){?>
						<li><?=$m?></li>
					<?}?>
				</ul></div>
			<?}?>
			<?if(countOf($warnings)){?>
				<div class="messageBlock" id="messageWarnings"><ul>
					<?foreach(iterate($warnings) as $m){?>
						<li><?=$m?></li>
					<?}?>
				</ul></div>
			<?}?>
			<?if(countOf($errors)){?>
				<div class="messageBlock" id="messageErrors"><ul>
					<?foreach(iterate($errors) as $m){?>
						<li><?=$m?></li>
					<?}?>
				</ul></div>
			<?}?>
			
			<div id="content"><? includeRequestContent() ?></div>
			
			<div style="clear:both"></div>
		</div>
	</body>
</html>

