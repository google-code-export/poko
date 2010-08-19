/**
 * ...
 * @author Matt Benton
 */

package poko.utils;

import haxe.Md5;
import haxe.Timer;

class EmailForwarder
{
	public static var defaultProxyURL : String = "http://www.mattbenton.net/email_forwarder.php";
	public static var defaultProxyCode : String = "iamawesome";
	public static var useProxy : Bool = true;
	public static var disableHtml : Bool = false;
	
	public static function forwardEmail( to : String, subject : String, message : String, headers : String, ?proxyUrl : String = null, ?proxyCode : String = null ) : Bool
	{
		proxyUrl = ( proxyUrl == null ) ? defaultProxyURL : proxyUrl;
		proxyCode = ( proxyCode == null ) ? defaultProxyCode : proxyCode;
		
		if ( useProxy )
		{
			var curl = new CurlManager( proxyUrl );
			var hash = Md5.encode( CurlManager.buildPostString( { to : to, subject : subject, message : message, headers : headers } ) + proxyCode );
			var paramStr = curl.setPost( { to : to, subject : subject, message : message, headers : headers, hash : hash } );
			
			var response = curl.get();
			return response == "OK";
		}
		else
		{
			PhpTools.mail( to, subject, message, headers );
			return true;
		}
	}
	
	public static function forwardMultipart( to : String, subject : String, from : String, plainMessage : String, htmlMessage : String, ?proxyUrl : String = null, ?proxyCode : String = null ) : Bool
	{
		var notice_text = "This is a multi-part message in MIME format.";

		var semi_rand = Md5.encode( Std.string( Timer.stamp() ) );
		var mime_boundary = "==MULTIPART_BOUNDARY_" + semi_rand;
		var mime_boundary_header = '"' + mime_boundary + '"';

		var body = notice_text + "\n\n";
		body += "--" + mime_boundary + "\n";
		body += "Content-Type: text/plain; charset=us-ascii\n";
		body += "Content-Transfer-Encoding: 7bit\n\n";

		body += plainMessage + "\n\n";
		
		if ( !disableHtml )
		{
			body += "--" + mime_boundary + "\n";
			body += "Content-Type: text/html; charset=us-ascii\n";
			body += "Content-Transfer-Encoding: 7bit\n\n";

			body += htmlMessage + "\n\n";
		}

		body += "--" + mime_boundary + "--";
		
		var headers = "From: " + from + "\n" + "MIME-Version: 1.0\n" + "Content-Type: multipart/alternative;\n" + "     boundary=" + mime_boundary_header;
		
		return forwardEmail( to, subject, body, headers, proxyUrl, proxyCode );
	}	
}