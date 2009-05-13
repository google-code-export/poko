/**
 * Service base - largely outdated by remoting functionality
 * @author Tony Polinelli
 */

package poko;
import php.Sys;
import php.Web;

class Service extends Request
{
	public var method:String;
	public var data:Dynamic;
	public var allowedMethods:List<String>;
	
	public function new() 
	{
		data = { };
		allowedMethods = new List();
		super();
	}
	
	override public function pre()
	{
		method = application.params.get("method");
	}
	
	override public function main()
	{
		if (checkMethodAccess()) 
		{ 
			Reflect.callMethod(this, method, []);
		}
	}
	
	private function checkMethodAccess():Bool
	{
		var passed:Bool = false;
		
		for (allowed in allowedMethods) 
			if (allowed == method) passed = true;
		
		if (!passed){
			forbidden();
			return false;
		} else {
			return true;
		}
	}
	
	private function forbidden():Void
	{
		setRequestTemplate("codes/403.php");
		
		Web.setHeader("content-type", "text/html");
		Web.setReturnCode(403);
	}
}