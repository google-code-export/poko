/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.base.formElements;


import poko.Application;
import poko.form.Form;
import poko.form.FormElement;
import poko.form.Validator;
import poko.utils.JsBinding;
import site.cms.common.InputTypeDef;
import haxe.Serializer;

class LinkTable extends FormElement
{
	public var linkTable:String;
	public var linkCategory:String;
	public var linkValue:Int;
	
	public function new(name:String, label:String, linkTable:String, linkCategory:String, linkValue:Int, ?validatorsKey:Array<Validator>, ?validatorsValue:Array<Validator>, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.attributes = attibutes;
		this.linkTable = linkTable;
		this.linkCategory = linkCategory;
		this.linkValue = linkValue;
	}
	
	override public function render():String
	{
		var str = "";
		
		var linkDefId = site.cms.common.Definition.tableToDefinitionId(linkTable);
		var linkCategoryField = null;
		var linkValueField = null;
		var linkdef = new site.cms.common.Definition(linkDefId);
		for (el in linkdef.elements)
		{
			if (el.type == "linkcategory")
			{
				linkCategoryField = el.name;
				break;
			}
		}
		
		for (el in linkdef.elements)
		{
			if (el.type == "linkvalue")
			{
				linkValueField = el.name;
				break;
			}
		}
		
		if (linkValue == null)
		{
			str += "Please edit link tables after adding item.";
		}
		else if(linkValueField == null || linkCategoryField == null)
		{
			if (linkValueField == null) str += "Could not find a 'linkcategory' in dataset definition<br/>";
			if (linkCategoryField==null) str += "Could not find both 'linkvalue' in dataset definition<br/>";
		} 
		else 
		{
			var url = "?request=cms.modules.base.Dataset&dataset=" + linkDefId + "&linkMode=true";
			url += "&linkCategoryField=" + linkCategoryField;
			url += "&linkCategory=" + linkCategory;
			url += "&linkValueField=" + linkValueField;
			url += "&linkValue=" + linkValue;
			
			str += "<iframe width=\"630\" height=\"300\" src=\""+url+"\">";
			str += name;
			str += "</iframe>";
		}
		
		
		return str;
	}
	
	public function toString():String
	{
		return render();
	}
}