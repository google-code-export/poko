/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package poko.apis;

import php.Lib;
import hxjson2.JSON;

class MailChimp 
{
	public var lastError:String;
	
	private var dataCenter:String;
	private var apiKey:String;
	private var listId:String;
	
	// 53bc82d7e3
	// 978db14746f8f98e38b56aecb3a2dab1-us1
	
	public function new(apiKey:String, listId:String, ?dataCenter:String = null) 
	{
		this.apiKey = apiKey;
		this.listId = listId;
		this.dataCenter = (dataCenter == null) ? apiKey.substr(33, 3) : dataCenter;
	}
	
	public function subscribe(emailAddress:String, firstName:String, ?lastName:String = "")
	{
		var h:Hash<String> = new Hash();
		h.set("FNAME", firstName);
		h.set("LNAME", lastName);
		var merges = Lib.associativeArrayOfHash(h);
				
		var h2:Hash<Dynamic> = new Hash();
		h2.set("email_address", emailAddress);
		h2.set("apikey", apiKey);
		h2.set("merge_vars", merges);
		h2.set("id", listId);
		h2.set("double_optin", true);
		h2.set("update_existing", false);
		h2.set("send_welcome", true);
		h2.set("email_type", 'html');
		var data = Lib.associativeArrayOfHash(h2);
		
		var payload = untyped __call__("json_encode", data);
		 
		var submitUrl = "http://" + dataCenter + ".api.mailchimp.com/1.3/?method=listSubscribe";
		 
		var ch = untyped __call__("curl_init");
		untyped __call__("curl_setopt", ch, untyped __php__("CURLOPT_URL"), submitUrl);
		untyped __call__("curl_setopt", ch, untyped __php__("CURLOPT_RETURNTRANSFER"), true);
		untyped __call__("curl_setopt", ch, untyped __php__("CURLOPT_POST"), true);
		untyped __call__("curl_setopt", ch, untyped __php__("CURLOPT_POSTFIELDS"), StringTools.urlEncode(payload));
		 
		var result = Std.string(untyped __call__("curl_exec", ch));
		
		untyped __call__("curl_close", ch);
		
		if (result == "true"){
			return true;
		}else{
			var returnData = JSON.decode(result);
			lastError = returnData.error;
			return false;
		}
	}
}