/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package poko.utils;

class Curl 
{
	/**
	 * TRUE to return the transfer as a string of the return value of curl_exec() instead of outputting it out directly.
	 */
	public static inline var OPTION_RETURN_TRANSFER = "CURLOPT_RETURNTRANSFER";
	public static inline var OPTION_URL = "CURLOPT_URL"; // string
	public static inline var OPTION_POST = "CURLOPT_POST"; // bool
	public static inline var OPTION_POST_FIELDS = "CURLOPT_POSTFIELDS"; // URLVariables string (encoded) (like flash)
	public static inline var OPTION_SSL_VERIFY_PEER = "CURLOPT_SSL_VERIFYPEER"; // bool
	public static inline var OPTION_SLL_VERIFY_HOST = "CURLOPT_SSL_VERIFYHOST"; // bool
	public static inline var OPTION_USER_AGENT = "CURLOPT_USERAGENT"; // string
	public static inline var OPTION_CERTIFICATE_AUTHORITY_BUNDLE_FILE_LOCATION = "CURLOPT_CAINFO"; // string
	
	public static inline var INFO_HTTP_CODE = "CURLINFO_HTTP_CODE";
	
	public static inline function init(url:String = null):String
	{		
		return untyped __call__("curl_init", url);
	}
	
	public static inline function close(resource:String)
	{
		return untyped __call__("curl_close", resource);
	}
	
	public static inline function execToOutput(resource:String):Bool
	{
		return untyped __call__("curl_exec", resource);
	}
	
	public static inline function execToVariable(resource:String):String
	{
		setOption(resource, OPTION_RETURN_TRANSFER, true);
		var val = untyped __call__("curl_exec", resource);
		if (val == false) {
			return null;
		}else {
			return cast val;
		}
	}
	
	public static inline function setOption(resource:String, option:String, value:Dynamic):Bool
	{
		return untyped __call__("curl_setopt", resource, untyped __php__(option), value);
	}
	
	public static inline function getInfo(resource:String, option:String)
	{
		return untyped __call__("curl_getinfo", resource, untyped __php__(option));
	}
	
	public static inline function getLastError(resource:String):String
	{
		return untyped __call__("curl_error", resource);
	}	
}