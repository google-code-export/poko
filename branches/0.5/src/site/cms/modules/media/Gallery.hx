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

package site.cms.modules.media;

import php.FileSystem;
import poko.js.JsBinding;
import site.cms.modules.media.Index;


class Gallery extends MediaBase
{
	public var gallery:String;
	
	public var jsBinding:JsBinding;
	
	override public function pre()
	{
		super.pre();
		
		head.css.add("css/cms/media.css");
		
		head.js.add("js/cms/media/swfobject.js");
		head.js.add("js/cms/media/jquery.uploadify.v2.1.0.min.js");
		
		gallery = application.params.get("name");
		
		remoting.addObject("api", { getContent:getContent, deleteItem:deleteItem } );
		
		jsBinding = new JsBinding("site.cms.modules.media.js.JsGallery");
		jsBinding.queueCall("init", [gallery]);
	}
	
	override public function main()
	{
		setupLeftNav();
	}
	
	public function getContent():List<Dynamic>
	{
		return Lambda.list(FileSystem.readDirectory(imageRoot + "/" + gallery));
	}
	
	public function deleteItem(file:String)
	{
		FileSystem.deleteFile(imageRoot + "/" + gallery + "/" + file);
	}
}