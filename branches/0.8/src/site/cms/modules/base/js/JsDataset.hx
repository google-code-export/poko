/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
 * All rights reserved.
 * Redistribution and use in source and binary forms, with or without
 * modification, are permitted provided that the following conditions are met:
 *
 *   - Redistributions of source code must retain the above copyright
 *     notice, this list of conditions and the following disclaimer.
 *   - Redistributions in binary form must reproduce the above copyright
 *     notice, this list of conditions and the following disclaimer in the
 *     documentation and/or other materials provided with the distribution.
 *
 * THIS SOFTWARE IS PROVIDED BY THE TOUCH MY PIXEL & CONTRIBUTERS "AS IS"
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE
 * ARE DISCLAIMED. IN NO EVENT SHALL THE TOUCH MY PIXEL & CONTRIBUTORS
 * BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE)
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF
 * THE POSSIBILITY OF SUCH DAMAGE.
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
				if (filterByAssocSelector.childNodes.length > 1) {
					if (Lib.document.getElementById("filter_assoc") != null)
						Lib.document.getElementById("filter_assoc").style.display = "inline";
				}else {
					if (Lib.document.getElementById("filter_normal") != null)
						Lib.document.getElementById("filter_normal").style.display = "inline";
				}
				filterByAssocSelector.onchange = function(e)
				{
					trace(filterByAssocSelector.value);
				}
			}
			
			var resetButton:Button =  cast Lib.document.getElementById("options_resetButton");
			resetButton.onclick = function (e:Event)
			{
				cast(Lib.document.getElementById("options_filterBy")).selectedIndex = 0;
				cast(Lib.document.getElementById("options_orderBy")).selectedIndex = 0;
				cast(Lib.document.getElementById("options_reset")).value = "true";
				resetButton.form.submit();
			}
			
			var submitButton:Button = cast Lib.document.getElementById("options_updateButton");
			submitButton.onclick = function (e:Event)
			{
				cast(Lib.document.getElementById("options_reset")).value = "false";
				submitButton.form.submit();
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
		
		switch (response.type)
		{
			case "association", "bool", "enum":
				Lib.document.getElementById("filter_assoc").style.display = "inline";
				
				var select:Select = cast Lib.document.getElementById("options_filterByAssoc");
				var options:Hash<Dynamic> = response.data;
				
				select.style.display = "block";
				select.innerHTML  = "<option value=\"\" >- select -</option>";
				
				for (option in options.keys())
				{
					select.innerHTML += "<option value=\"" + option + "\">" + options.get(option) + "</option>";
				}
			
			default:
				if(Lib.document.getElementById("filter_normal") != null)
					Lib.document.getElementById("filter_normal").style.display = "inline";
		}
	}
	
	public function updateData(_id:Int, _data:Dynamic, ?_hide:Bool = false)
	{
		remoting.api.updateData.call([_id, _data, _hide], onUpdateData);
	}
	
	public function onUpdateData(response:Dynamic)
	{
		if (response.error) {
			Lib.alert("Error updating record '" + response.id + "'");
		}else {
			if (response.hide) Lib.document.getElementById('dataset_row_' + response.id).style.display = 'none';
		}
	}
}