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


package poko.form.elements;

import poko.Application;
import poko.form.Form;
import poko.form.FormElement;

class Richtext extends FormElement
{
	public var width:Float;
	public var height:Float;
	public var content_css:String;
	public var mode:RichtextMode;
	
	public function new(name:String, label:String, ?value:String, ?required:Bool=false, ?attibutes:String="") 
	{
		super();
		this.name = name;
		this.label = label;
		this.value = value;
		this.required = required;
		this.attributes = attibutes;
		
		width = 300;
		height = 300;
		content_css = "css/cms/richtext_default.css";
		
		mode = RichtextMode.SIMPLE;
	}
	
	override public function render():String
	{
		var n = form.name + "_" +name;
		
		if(content_css != "") content_css += "?" + Date.now().getTime();
		
		var str:StringBuf = new StringBuf();
		str.add("\n <textarea name=\"" + n + "\" id=\"" + n + "\" >"+value+"</textarea>");
		str.add("\n <script>					");
		str.add("\n	tinyMCE.init({ 				");
		str.add("\n		mode : \"exact\", 		");
		str.add("\n		elements : \""+n+"\",	");
		
		str.add("\n		theme : \""+(mode == RichtextMode.SIMPLE ? "simple" : "advanced")+"\",	 	");
		
		str.add("\n		width : \""+width+"\",	 			");
		str.add("\n		height : \""+height+"\",	 		");
		str.add("\n		content_css : \"" + content_css + "\",	");
		
		switch(mode)
		{ 
			case SIMPLE:
			case FORMAT:
				str.add("\n plugins : \"advlink,inlinepopups,paste\", ");	
				str.add("\n theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,link,unlink,|,pastetext,cleanup,removeformat\", ");
				str.add("\n theme_advanced_buttons2 : \"\", ");
				str.add("\n theme_advanced_toolbar_location : \"top\", ");
				str.add("\n theme_advanced_statusbar_location : \"bottom\", ");
				str.add("\n theme_advanced_resizing : true, ");
			case SIMPLE_TABLES:
				str.add("\n plugins : \"table,advlink,inlinepopups,paste\", ");
				str.add("\n theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,outdent,indent,|,link,unlink,|,pastetext,cleanup,removeformat\", ");
				str.add("\n theme_advanced_buttons2 : \"table,tablecontrols\", ");
				str.add("\n theme_advanced_buttons3 : \"\", ");
				
				str.add("\n theme_advanced_toolbar_location : \"top\", ");
				str.add("\n theme_advanced_statusbar_location : \"bottom\", ");
				str.add("\n theme_advanced_resizing : true, ");
			case ADVANCED:
				str.add("\n plugins : \"safari,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template\", ");
				str.add("\n theme_advanced_buttons1 : \"bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect\", ");
				str.add("\n theme_advanced_buttons2 : \"cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup\", ");
				str.add("\n theme_advanced_buttons3 : \"tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen\", ");
				str.add("\n theme_advanced_buttons4 : \"insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak\", ");
				str.add("\n theme_advanced_toolbar_location : \"top\", ");
				str.add("\n theme_advanced_toolbar_align : \"left\", ");
				str.add("\n theme_advanced_statusbar_location : \"bottom\", ");
				str.add("\n theme_advanced_resizing : false, ");
		}
		str.add("\n file_browser_callback : 'myFileBrowser', ");
		
		str.add("\n	}); 						");
		str.add("\n </script>\n					");
		
		if (!isValid()) str.add(" required");
		
		return str.toString();
	}
	
	public function toString() :String
	{
		return render();
	}
}

enum RichtextMode
{
	SIMPLE;
	FORMAT;
	SIMPLE_TABLES;
	ADVANCED;
}