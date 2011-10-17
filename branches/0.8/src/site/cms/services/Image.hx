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

package site.cms.services;

import poko.utils.ImageProcessor;

class Image extends site.cms.services.ImageBase
{
	public function new() 
	{
		super();
	}
	
	override function resizeImage( preset : String, image : ImageProcessor ) : Void
	{
		switch(app.params.get("preset"))
		{
			case "tiny":
				image.queueFitSize(40, 40);
			case "thumb":
				image.queueCropToAspect(100, 100);
				image.queueFitSize(100, 100);
			case "aspect": 
				var w:Int = Std.parseInt(app.params.get("w"));
				var h:Int = Std.parseInt(app.params.get("h"));
				image.queueCropToAspect(w, h);
			case "custom": 
				var w:Int = Std.parseInt(app.params.get("w"));
				var h:Int = Std.parseInt(app.params.get("h"));
				image.queueFitSize(w, h);
			case "gallery":
				image.queueFitSize(10, 10);
		}
	}
}