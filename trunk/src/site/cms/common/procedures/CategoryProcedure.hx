/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.common.procedures;

import site.cms.common.Procedure;

class CategoryProcedure extends Procedure
{

	public function new() {super();  }
	
	override public function postCreate(table:String, data:Dynamic, id:Dynamic):Bool
	{
		return(true);
	}
	
	override public function postUpdate(table:String, oldData:Dynamic, newData:Dynamic):Bool
	{
		return(true);
	}
	
	override public function postDelete(table:String, data:Dynamic):Bool
	{
		return(true);
	}
	
	override public function postDuplicate(table:String, oldData:Dynamic, newData:Dynamic):Bool
	{
		trace("CategoryProcedure: postDuplicate");
		trace(table);
		trace(oldData);
		trace(newData);
		return(true);
	}
	
}