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

package site.examples;

import poko.form.elements.LocationSelector;
import poko.form.Form;
import poko.utils.html.ScriptType;
import site.examples.templates.DefaultTemplate;

class Locations extends DefaultTemplate
{
	public var form:Form;
	
	override public function main()
	{
		scripts.addExternal(ScriptType.css, "css/formatCode.css");
		scripts.addExternal(ScriptType.js, "js/jquery.formatCode.js");
		
		form = new Form("form");
		
		var location = new LocationSelector("location", "Simple Location");
		form.addElement(location);
		
		var location2 = new LocationSelector("location2", "Location with default");
		location2.defaultLocation = "-37.797871, 144.986099";
		form.addElement(location2);
		
		var location3 = new LocationSelector("location3", "Location with search");
		location3.searchAddress = true;
		form.addElement(location3);
		
		var location4 = new LocationSelector("location4", "Advanced Location");
		location4.searchAddress = true;
		location4.defaultLocation = "-25.641526373065755, 133.41796875";
		location4.googleMapsKey = "ABQIAAAAPEZwP3fTiAxipcxtf7x-gxT2yXp_ZAY8_ufC3CFXhHIE1NvwkxRPwWSQQtyYryiI5S6KBZMsOwuCsw";
		location4.popupWidth = 800;
		location4.popupHeight = 600;
		form.addElement(location4);
		
		form.populateElements();
	}
}