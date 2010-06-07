/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.common;
import poko.Application;

class Procedure
{
	public var application:Application;
	
	public function new()
	{
		application = Application.instance;
	}
	public function postCreate(table:String, data:Dynamic, id:Dynamic):Bool{ return(false); }
	public function postUpdate(table:String, oldData:Dynamic, newData:Dynamic):Bool { return(false); }
	public function postDelete(table:String, data:Dynamic):Bool { return(false); }
	public function postDuplicate(table:String, oldData:Dynamic, newData:Dynamic):Bool { return(false); }
}