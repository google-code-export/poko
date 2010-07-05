/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package poko.utils;
import php.Exception;
import php.Web;

using StringTools;

class CurlManager 
{
	private var resource:String;
	private var currentUrl:String;
	private var userAgent:String;
	
	public var lastRequest:String;
	
	public function new(?url:String)
	{
		if (url != null) {
			resource = Curl.init();
			setUrl(url);
		}else {
			resource = Curl.init();
		}
		
		setUserAgent("haXe CurlManager");
	}
	
	public function setUrl(url:String)
	{
		currentUrl = url;
		Curl.setOption(resource, Curl.OPTION_URL, url);
		
		// if we've got a secure URL then set security options - SSL YAY
		if(url.substr(0, 5) == "https"){
			Curl.setOption(resource, Curl.OPTION_SSL_VERIFY_PEER, true);
			Curl.setOption(resource, Curl.OPTION_SLL_VERIFY_HOST, true);
			if (Web.getHostName() == "localhost") {
				Curl.setOption(resource, Curl.OPTION_CERTIFICATE_AUTHORITY_BUNDLE_FILE_LOCATION, "c:\\xampp\\ca-bundle.crt");
			}			
		}
	}
	
	public function setUserAgent(agent:String)
	{
		userAgent = agent;
		Curl.setOption(resource, Curl.OPTION_USER_AGENT, userAgent);
	}
	
	public function exists(accept400AsOk:Bool = false, ?url)
	{
		if (url == null && currentUrl == null) throw new Exception("No URL defined");
		lastRequest = Curl.execToVariable(resource);
		if (lastRequest != null) {
			if (!accept400AsOk) {
				var c = httpCode();
				if (c >= 400 && c < 500) return false;
			}
			return true;
		}
		return false;
	}
	
	public function httpCode()
	{
		if (lastRequest == null) throw new Exception("No resource");
		return Std.parseInt(Curl.getInfo(resource, Curl.INFO_HTTP_CODE));
	}
	
	public function get(?url)
	{
		if (url == null && currentUrl == null) throw new Exception("No URL defined");
		lastRequest = Curl.execToVariable(resource);
		return(lastRequest);
	}
	
	public function print(?url)
	{
		if (url == null && currentUrl == null) throw new Exception("No URL defined");
		lastRequest = null;
		return(Curl.execToOutput(resource));
	}	
	
	public function setPost( params : Dynamic ) : String
	{
		var paramStr = buildPostString( params );		
		Curl.setOption( resource, Curl.OPTION_POST, true );
		Curl.setOption( resource, Curl.OPTION_POST_FIELDS, paramStr );
		return paramStr;
	}
	
	public static function buildPostString( params : Dynamic ) : String
	{
		var paramStr = "";
		for ( param in Reflect.fields( params ) )
		{
			if ( paramStr != "" )
				paramStr += "&";
			paramStr += param + "=" + cast( Reflect.field( params, param ), String ).urlEncode();
		}
		return paramStr;
	}
	
	public function getLastError()
	{
		if (currentUrl == null) throw new Exception("No URL defined");
		return Curl.getLastError(resource);
	}
	
	public function close()
	{
		currentUrl = null;
		Curl.close(resource);
	}
}