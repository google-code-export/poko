/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.js;

class JsTest extends poko.js.JsRequest
{
	public function new()
	{
		super();
	}
	
	public function calltest(v1, v2, v3)
	{
		trace("testies XXXX");
		trace(v1);
		trace(v2);
		trace(v3);
		
	}
}