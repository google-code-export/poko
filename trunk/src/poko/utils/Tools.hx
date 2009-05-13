/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package poko.utils;

class Tools 
{

	public function new() 
	{
		
	}
	
	public static inline function MysqlTimestamp():String
	{
		return DateTools.format(Date.now(), "%Y%m%d%H%M%S");
	}
	
}