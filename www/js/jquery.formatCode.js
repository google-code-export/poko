$(document).ready(function() {
	formatCodeTags();
});

/*
This example will format the comments (lines containing //) and initally hide the entire code block.

<pre class="code" lang="php" style="width:600px;display:none">
// The same can be displayed in PHP as easily
foreach ($products as $product) {
	product-&gt;id
	product-&gt;name
}
</pre>
*/

function formatCodeTags() {
	$("pre[class='code']").each(function(idx) {
		var ele = $(this);
		var lines = ele.text().split("\n");
		var code = "";
		var buttonId = "codeButton__" + idx;
		var blockId = "codeBlock__" + idx;
		ele.attr("id", blockId);
		var lang = ele.attr("lang");
		var classes = (lang != "") ? " " + lang : "";
		ele.before('<a id="'+buttonId+'" href="#" class="codeToggle">Toggle '+lang.toUpperCase()+' Code</a><br/>');
		$("#"+buttonId).click(function(){
			var block = $("#"+blockId);
			block.css("display", block.css("display")=="none" ? "block" : "none");
		});
		$.each(lines, function(i, line) {
			if ( line.indexOf("//") != -1 )
				line = '<span class="comment'+classes+'">'+line+'</span>';
			if ( line != "" )
				code += line + "<br/>";
		});
		ele.html(code);
	});
}