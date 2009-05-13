/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base.js;

import poko.js.JsRequest;
import haxe.Serializer;
import haxe.Unserializer;
import js.Lib;
import site.cms.modules.base.js.JsKeyValueInput;

class JsDatasetItem extends JsRequest
{	
	public var valueHolder:JQuery;
	public var table:JQuery;
	public var properties:Dynamic;
	public var id:String;
	
	override public function main()
	{
	}
	
	public function setupKeyValueInput(id, properties:String)
	{
		this.id = id;
		this.properties = properties;
		
		valueHolder = new JQuery("#" + id);
		table = new JQuery("#" + id + "_keyValueTable");
		var val = valueHolder.val();
		var data:Array<KeyValuePair> = [];
		if (val != "") 
			data = Unserializer.run(val);
		
		if (data.length != 0) {
			var remove = false;
			for (item in data) {
				addKeyValueInput(item.key, item.value, remove);
				remove = true;
			}
		}else {
			addKeyValueInput("", "", false);
		}
	}
	
	public function addKeyValueInput(?keyValue:String = "", ?valueValue:String = "", removeable:Bool = true)
	{
		var keyElement = properties.keyIsMultiline == "1" ? JQuery.create('textarea', { style:"height:"+properties.keyHeight+"px; width:"+properties.keyWidth+"px;" }, [keyValue] ) : JQuery.create('input', { type:"text", value:keyValue, style:"width:"+properties.keyWidth+"px;" }, [] );
		var valueElement = properties.valueIsMultiline == "1" ? JQuery.create('textarea', { style:"height:"+properties.valueHeight+"px; width:"+properties.valueWidth+"px;" }, [valueValue] ) : JQuery.create('input', { type:"text", value:valueValue, style:"width:"+properties.valueWidth+"px;" }, [] );
		var removeElement = removeable ? JQuery.create('a', { href:"#", onclick: getRawCall("removeKeyValueInput(this)") + "; return(false);" }, "remove") : null;
		
		new JQuery("#" + id + "_keyValueTable tr:last").after(
			JQuery.create('tr', { }, [
				JQuery.create('td', { valign:"top" }, [keyElement]),
				JQuery.create('td', { valign:"top" }, [valueElement]),
				JQuery.create('td', { valign:"top" }, [removeElement])
			])
		);
	}
	
	public function removeKeyValueInput(link)
	{
		new JQuery(link).parent().parent().remove();
	}
	
	public function flushKeyValueInputs()
	{
		var data:Array<KeyValuePair> = [];
		
		new JQuery("#" + id + "_keyValueTable tr").each(function(int, html) {
			var items = new JQuery(html).find("td").children("input,textarea");
			if(items.length > 0){
				data.push({ key: Reflect.field(items[0], "value"), value: Reflect.field(items[1], "value") });
			}
		});
		valueHolder.val(Serializer.run(data));
		return(true);
	}
}