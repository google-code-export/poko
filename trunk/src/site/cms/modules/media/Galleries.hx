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
import site.cms.modules.media.Index;


class Galleries extends MediaBase
{
	public var galleries:List<Dynamic>;
	
	override public function pre()
	{
		super.pre();
		
		if (application.params.get("action")) process();
	}
	
	override public function main()
	{
		
		galleries = new List();
		var dir = FileSystem.readDirectory(imageRoot);
		for (d in dir)
		{
			if (FileSystem.isDirectory(imageRoot + "/" +d) && d != "." && d != "..") 
			{
				galleries.add( { name:d } );
			}
		}
			
		setupLeftNav();
	}
	
	private function process():Void
	{
		switch(application.params.get("action"))
		{
			case "add":
				var dir = imageRoot + "/" + application.params.get("newGallery");
				if (!FileSystem.exists(dir))
				{
					FileSystem.createDirectory(dir);
					application.messages.addMessage("Gallery Added");
				} else {
					application.messages.addError("A Gallery of this name already exists");
				}
				
		}
	}
}