/**
 * ...
 * @author Tarwin Stroh-Spijer
 */

package site.cms.modules.base.js;

import poko.js.JsRequest;
import haxe.Serializer;
import haxe.Unserializer;
import js.Dom;
import js.Lib;

class JsDataset extends JsRequest
{	
	override public function main()
	{
		hideFilterOptions();
		
		var ths = this;
		
		var filterBySelector:Select = cast Lib.document.getElementById("options_filterBy");
		if(filterBySelector != null){
			filterBySelector.onchange = function(e) ths.showFilterOptions(filterBySelector.value); 
			if (filterBySelector.selectedIndex > 0)
			{
				var filterByAssocSelector:Select  = cast Lib.document.getElementById("options_filterByAssoc");
				if (filterByAssocSelector.childNodes.length > 1){
					if (Lib.document.getElementById("filter_assoc") != null)
						Lib.document.getElementById("filter_assoc").style.display = "inline";
				}else {
					if (Lib.document.getElementById("filter_normal") != null)
						Lib.document.getElementById("filter_normal").style.display = "inline";
				}
			}
			
			var resetButton:Button =  cast Lib.document.getElementById("options_resetButton");
			resetButton.onclick = function (e:Event)
			{
				cast(Lib.document.getElementById("options_filterBy")).selectedIndex = 0;
				cast(Lib.document.getElementById("options_orderBy")).selectedIndex = 0;
				resetButton.form.submit();
			}
		}
	}
	
	public function showFilterOptions(field)
	{
		if (field == "") {
			hideFilterOptions();
			return;
		}
		
		remoting.api.getFilterInfo.call([field], onGetFilterInfo);
	}
	
	public function hideFilterOptions()
	{
		if (Lib.document.getElementById("filter_normal") != null)
			Lib.document.getElementById("filter_normal").style.display = "none";
		if (Lib.document.getElementById("filter_assoc") != null)
			Lib.document.getElementById("filter_assoc").style.display = "none";
	}
	
	public function onGetFilterInfo(response:Dynamic)
	{
		hideFilterOptions();
		 
		if (response.type == "association")
		{
			Lib.document.getElementById("filter_assoc").style.display = "inline";
			
			var select:Select = cast Lib.document.getElementById("options_filterByAssoc");
			var options:Hash<Dynamic> = response.data;
			
			select.style.display = "block";
			select.innerHTML  = "<option value=\"\" >- select -</option>";
			
			for (option in options.keys())
				select.innerHTML += "<option value=\"" + option + "\">" + options.get(option) + "</option>";
			
		} else {
			if(Lib.document.getElementById("filter_normal") != null)
				Lib.document.getElementById("filter_normal").style.display = "inline";
		}
	}
}