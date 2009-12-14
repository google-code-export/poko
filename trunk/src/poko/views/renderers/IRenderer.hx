/**
 * ...
 * @author Tony Polinelli
 */

package poko.views.renderers;

class IRenderer
{
	public var data:Dynamic;
	public var template:String;
	public function assign(field:String, value:Dynamic):Void;		
	public function render():String;
}