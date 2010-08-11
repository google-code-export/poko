/**
 * ...
 * @author Tonypee
 */

package poko.js;
import js.Lib;

class JsDom implements Dynamic
{
	public function new() {}
	
	public function resolve(id)
	{
		return Lib.document.getElementById(id);
	}	
}
