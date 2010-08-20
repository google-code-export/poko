/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Matt Benton <matt@mattbenton.net> 
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

package poko.form.elements;

import poko.form.FormElement;
import poko.Poko;

using StringTools;

class LocationSelector extends FormElement
{
	public var defaultLocation : String;
	public var popupWidth : Int;
	public var popupHeight : Int;
	public var searchAddress : Bool;
	public var googleMapsKey : String;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?attibutes:String="") 
	{
		super();

		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		
		// Attempt to access Google Maps API Key from Poko CMS Settings.
		var tmp = Poko.instance.db.requestSingle("SELECT * FROM _settings WHERE `key`='googleMapsApiKey'");
		if ( tmp != null )
			googleMapsKey = tmp.value;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		var s:StringBuf = new StringBuf();
		
		var location : String = ( value == null || value == "" ) ? defaultLocation : value;
		
		var popupUrl = "tpl/php/cms/components/LocationSelector.php?eName=" + n + "&location=" + StringTools.urlEncode(location) + "&popupWidth=" + popupWidth + "&popupHeight=" + popupHeight + "&searchAddress=" + searchAddress + "&key=" + googleMapsKey;
		var popupFeatures = "width=" + popupWidth + ",height=" + popupHeight + ",resizable=0,width=620,height=450,toolbar=0,location=0,status=0";
		
		s.add("<input type=\"text\" name=\"" + n + "\" id=\"" + n + "\" value=\"" + value + "\" size=\"50\" /> \n");
		s.add('<a id="'+n+"_edit"+'" href="#">Edit Location</a>\n');
		s.add("<script type=\"text/javascript\">			\n");
		s.add("		$(function() {							\n");
		s.add("			$('#" + n + "_edit').click( function() {	\n");
		s.add("				var mapWindow = open('" + popupUrl + "','locationSelector','" + popupFeatures + "'); \n");
		s.add("				if ( mapWindow.opener == null ) mapWindow.opener = self; \n");
		s.add("			});									\n");
		s.add("		});										\n");
		s.add("</script> 									\n");
	
		return s.toString();
	}
	
	public function toString() :String
	{
		return render();
	}
}