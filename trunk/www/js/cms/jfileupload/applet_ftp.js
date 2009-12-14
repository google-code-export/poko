<!--
var _info = navigator.userAgent;
var _ns = false;
var _ns6 = false;
var _ie = (_info.indexOf("MSIE") > 0 && _info.indexOf("Win") > 0 && _info.indexOf("Windows 3.1") < 0);
if (_info.indexOf("Opera") > 0) _ie = false;
var _ns = (navigator.appName.indexOf("Netscape") >= 0 && ((_info.indexOf("Win") > 0 && _info.indexOf("Win16") < 0) || (_info.indexOf("Sun") > 0) || (_info.indexOf("Linux") > 0) || (_info.indexOf("AIX") > 0) || (_info.indexOf("OS/2") > 0) || (_info.indexOf("IRIX") > 0)));
var _ns6 = ((_ns == true) && (_info.indexOf("Mozilla/5") >= 0));
if (_ie == true) {
  document.writeln('<OBJECT classid="clsid:8AD9C840-044E-11D1-B3E9-00805F499D93" WIDTH="250" HEIGHT="250" NAME="fileupload" codebase="http://java.sun.com/update/1.4.2/jinstall-1_4-windows-i586.cab#Version=1,4,0,0">');
}
else if (_ns == true && _ns6 == false) { 
  // BEGIN: Update parameters below for NETSCAPE 3.x and 4.x support.
  document.write('<EMBED ');
  document.write('type="application/x-java-applet;version=1.4" ');
  document.write('CODE="jfileupload.upload.client.MApplet.class" ');
  document.write('JAVA_CODEBASE="./" ');
  document.write('ARCHIVE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar" ');
  document.write('NAME="fileupload" ');
  document.write('WIDTH="250" ');
  document.write('HEIGHT="250" ');
  document.write('url="ftp://localhost" ');
  document.write('param1="username" ');
  document.write('value1="anonymous" ');
  document.write('param2="password" ');
  document.write('value2="something@somewhere.com" ');
  document.write('param3="pasv" ');
  document.write('value3="true" ');
  document.write('mode="ftp" ');
  document.write('scriptable=true ');
  document.writeln('pluginspage="http://java.sun.com/products/plugin/index.html#download"><NOEMBED>');
  // END
}
else {
  document.write('<APPLET CODE="jfileupload.upload.client.MApplet.class" JAVA_CODEBASE="./" ARCHIVE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar" WIDTH="250" HEIGHT="250" NAME="fileupload">');
}
// BEGIN: Update parameters below for INTERNET EXPLORER, FIREFOX, SAFARI, OPERA, MOZILLA, NETSCAPE 6+ support.
document.writeln('<PARAM NAME=CODE VALUE="jfileupload.upload.client.MApplet.class">');
document.writeln('<PARAM NAME=CODEBASE VALUE="./">');
document.writeln('<PARAM NAME=ARCHIVE VALUE="lib/jfileupload.jar,lib/ftpimpl.jar,lib/cnet.jar,lib/clogging.jar">');
document.writeln('<PARAM NAME=NAME VALUE="fileupload">');
document.writeln('<PARAM NAME="type" VALUE="application/x-java-applet;version=1.4">');
document.writeln('<PARAM NAME="scriptable" VALUE="true">');
document.writeln('<PARAM NAME="url" VALUE="ftp://localhost">');
document.writeln('<PARAM NAME="param1" VALUE="username">');
document.writeln('<PARAM NAME="value1" VALUE="anonymous">');
document.writeln('<PARAM NAME="param2" VALUE="password">');
document.writeln('<PARAM NAME="value2" VALUE="something@somewhere.com">');
document.writeln('<PARAM NAME="param3" VALUE="pasv">');
document.writeln('<PARAM NAME="value3" VALUE="true">');
document.writeln('<PARAM NAME="mode" VALUE="ftp">');
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
//-->
