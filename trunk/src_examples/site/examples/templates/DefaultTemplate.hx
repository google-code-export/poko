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

package site.examples.templates;

import poko.controllers.HtmlController;
import poko.utils.html.ScriptList;
import poko.utils.html.ScriptType;
import site.examples.components.Navigation;

class DefaultTemplate extends HtmlController
{	
	public var navigation:Navigation;
	
	public var scripts : ScriptList;
	
	override public function init()
	{
		super.init();
		
		head.title = "haXe poko examples site";
		
		//app.defaultJS.add("js/firebug-lite.js");
	/*	head.css.add("css/reset.css");
		head.css.add("css/fonts.css");
		head.css.add("css/normal.css");
		
		head.css.add("css/cms/cms.css");*/
		
		scripts = new ScriptList();
		//scripts.addExternal(ScriptType.css , "css/reset.css");
		//scripts.addExternal(ScriptType.css, "css/fonts.css");
		scripts.addExternal(ScriptType.css, "css/normal.css");
		scripts.addExternal(ScriptType.css, "css/cms/cms.css");
		
		navigation = new Navigation();
		navigation.addLink("Test Page",			"examples.TestPage");
		navigation.addLink("Pages", 			"examples.Pages");
		navigation.addLink("Basic data",	 	"examples.Basic");
		navigation.addLink("Forms", 			"examples.Forms");
		navigation.addLink("Dates", 			"examples.Dates");
		navigation.addLink("Image Processing", "examples.ImageProcessing");
		navigation.addLink("Complex Data", 		"examples.ComplexData");
		
		navigation.setSelectedByRequest(app.params.get('request'));
	}
}