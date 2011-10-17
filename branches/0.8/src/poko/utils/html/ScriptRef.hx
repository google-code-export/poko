/**
 * ...
 * @author Matt Benton
 */

package poko.utils.html;

typedef ScriptRef =
{
	var type : ScriptType;
	//var type : String;
	var isExternal : Bool;
	var value : String;
	var condition : String;
	var media : String;
	var priority : Int;
}