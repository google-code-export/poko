<!--

(function( window, undefined ) {
	
	var jsFileUploadInject = function() {
		return new jsFileUploadInject.fn.init();
	};
	
	var _info = navigator.userAgent;
	var _ns = false;
	var _ns6 = false;
	var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
	if (_info.indexOf("Opera") > 0) _ie = false;
	var _ns = (navigator.appName.indexOf("Netscape") >= 0 && ((_info.indexOf("Win") > 0 && _info.indexOf("Win16") < 0) || (_info.indexOf("Sun") > 0) || (_info.indexOf("Linux") > 0) || (_info.indexOf("AIX") > 0) || (_info.indexOf("OS/2") > 0) || (_info.indexOf("IRIX") > 0)));
	var _ns6 = ((_ns == true) && (_info.indexOf("Mozilla/5") >= 0));	
	
	var params = {};
	var extraParams = {};
	
	jsFileUploadInject.fn = jsFileUploadInject.prototype = {
		init: function()
		{
		},
		insert: function(theName, width, height, ftpUrl, ftpUsername, ftpPassword, ftpDirectory)
		{
			if(theName == null) theName = "fileupload";
			if(width == null) width = 250;
			if(height == null) height = 250;
			if(ftpDirectory == null) ftpDirectory = "";
			
			if (_ie == true) {
			  document.writeln('<OBJECT classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH="'+width+'" HEIGHT="'+height+'" NAME="'+theName+'" codebase="http://java.sun.com/update/1.4.2/jinstall-1_4-windows-i586.cab#Version=1,4,0,0">');
			}
			else if (_ns == true && _ns6 == false) { 
			  // BEGIN: Update parameters below for NETSCAPE 3.x and 4.x support.
			  document.write('<EMBED ');
			  document.write('type="application/x-java-applet;version=1.4" ');
			  document.write('CODE="jfileupload.upload.client.MApplet.class" ');
			  document.write('JAVA_CODEBASE="./" ');
			  document.write('ARCHIVE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar" ');
			  
			  document.write('NAME="'+theName+'" ');
			  document.write('WIDTH="'+width+'" ');
			  document.write('HEIGHT="'+height+'" ');
			  document.write('url="'+ftpUrl+'" ');
			  document.write('mode="ftp" ');	
			  document.write('scriptable=true ');
			  document.write('forward="../" ');
			  
			  document.write('regfile="license.oxw" ');
			  
			  document.write('param1="username" ');
			  document.write('value1="'+ftpUsername+'" ');
			  document.write('param2="password" ');
			  document.write('value2="'+ftpPassword+'" ');
			  document.write('param3="pasv" ');
			  document.write('value3="true" ');
			  document.write('param4="account" ');			  
			  document.write('value4="'+ftpDirectory+'" ');
			  
			  document.writeln('pluginspage="http://java.sun.com/products/plugin/index.html#download"><NOEMBED>');
			  // END
			}
			else {
			  document.write('<APPLET CODE="jfileupload.upload.client.MApplet.class" JAVA_CODEBASE="./" ARCHIVE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar" WIDTH="'+width+'" HEIGHT="'+height+'" NAME="'+theName+'">');
			}
			// BEGIN: Update parameters below for INTERNET EXPLORER, FIREFOX, SAFARI, OPERA, MOZILLA, NETSCAPE 6+ support.
			document.writeln('<PARAM NAME=CODE VALUE="jfileupload.upload.client.MApplet.class">');
			document.writeln('<PARAM NAME=CODEBASE VALUE="./">');
			document.writeln('<PARAM NAME=ARCHIVE VALUE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar">');
			document.writeln('<PARAM NAME="type" VALUE="application/x-java-applet;version=1.4">');
			
			document.writeln('<PARAM NAME=NAME VALUE="'+theName+'">');
			document.writeln('<PARAM NAME="url" VALUE="'+ftpUrl+'">');
			document.writeln('<PARAM NAME="mode" VALUE="ftp">');
			document.writeln('<PARAM NAME="scriptable" VALUE="true">');
			document.writeln('<PARAM NAME="forward" VALUE="../">');
			
			document.writeln('<PARAM NAME="regfile" VALUE="license.oxw">');
			
			document.writeln('<PARAM NAME="param1" VALUE="username">');
			document.writeln('<PARAM NAME="value1" VALUE="'+ftpUsername+'">');
			document.writeln('<PARAM NAME="param2" VALUE="password">');
			document.writeln('<PARAM NAME="value2" VALUE="'+ftpPassword+'">');
			document.writeln('<PARAM NAME="param3" VALUE="pasv">');
			document.writeln('<PARAM NAME="value3" VALUE="true">');
			document.writeln('<PARAM NAME="param4" VALUE="account">');
			document.writeln('<PARAM NAME="value4" VALUE="'+ftpDirectory+'">');			
			// END
			if (_ie == true) {
			  document.write('</OBJECT>');
			}
			else if (_ns == true && _ns6 == false) {
			  document.write('</NOEMBED></EMBED>');
			}
			else {
			  document.write('</APPLET>');
			}
		},
		setup: function()
		{
		},
		clear: function()
		{
		}
	}
	
	// Expose to window
	window.jsFileUploadInject = jsFileUploadInject;
	window.jsFileUploadInject.insert = window.jsFileUploadInject.fn.insert;
	
})(window);

jsFileUploadInject();
jsFileUploadInject.insert(null, 250, 250, "ftp://cityonahill.com.au", "media@cityonahill.com.au", "citiesaregood", null);

//-->