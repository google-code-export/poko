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


package site.cms.components;

import poko.system.Component;
import php.io.File;
import php.io.FileInput;
import php.Web;

class LeftNavigation extends Component
{
	public var header:String;
	public var content:String;
	public var footer:String;
	
	public var sections:Hash < List < Dynamic >> ;
	public var sectionsIsSeperator:Hash < Bool >;

	public function new() 
	{
		super();
		sections = new Hash();
		sectionsIsSeperator = new Hash();
	}
	
	override public function init()
	{	
	}
	
	override public function main() 
	{
	}
	
	public function addSection(name:String, ?isSeperator:Bool = false) 
	{
		sections.set(name, new List());
		sectionsIsSeperator.set(name, isSeperator);
	}
	
	public function addLink(section:String, title:String, link:String, ?indents:Int=0, ?external=false) 
	{
		var indentsData = [];
		indentsData[0] = "";
		/*indentsData[1] = "&#x02EA;&nbsp;";
		indentsData[2] = "&nbsp;&#x02EA;&nbsp;";
		indentsData[3] = "&nbsp;&nbsp;&#x02EA;&nbsp;";
		indentsData[4] = "&nbsp;&nbsp;&nbsp;&#x02EA;&nbsp;";*/
		indentsData[1] = "<img src=\"./res/cms/tree_kink.png\" />";
		indentsData[2] = "&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		indentsData[3] = "&nbsp;&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		indentsData[4] = "&nbsp;&nbsp;&nbsp;<img src=\"./res/cms/tree_kink.png\" />";
		var ind = indentsData[indents];
		
		sections.get(section).add( { title:title, link:link, external:external, indents:ind } );
	}
}