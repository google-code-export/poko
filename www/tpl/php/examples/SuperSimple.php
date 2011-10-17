<html>
	<head>
		<title>Super Simple Poko Example</title>
	</head>
	<body>
		<p><a href="#" onclick="history.back(); return false;">Back</a></p>
		<p>This is the simplest Poko example. It doesn't use templates, components or even Templo templates. It's a simple .hx and .php file with the same name.</p>
		<p>The next paragraph is set by the script!</p>
		<p><?=$myString?></p>
		<p>And the next paragraph calls a function ...</p>
		<p><?=$controller->myStringBold()?></p>
	</body>
</html>