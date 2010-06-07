/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tarwin Stroh-Spijer <tarwin@touchmypixel.com>
 * Contributors:
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

package poko.utils;

class Messages 
{
	private var messages:List<Message>;
	
	public function new() 
	{
		messages = new List();
	}
	
	public function addMessage(text:String)
	{
		messages.push(new Message(text, MessageType.MESSAGE));
	}
	
	public function addWarning(text:String)
	{
		messages.push(new Message(text, MessageType.WARNING));
	}
	
	public function addError(text:String)
	{
		messages.push(new Message(text, MessageType.ERROR));
	}
	
	public function addDebug(text:String)
	{
		messages.push(new Message(text, MessageType.DEBUG));
	}	

	public function getMessages():List<Message>
	{
		return(get(MessageType.MESSAGE));
	}
	
	public function getWarnings():List<Message>
	{
		return(get(MessageType.WARNING));
	}
	
	public function getDebugs():List<Message>
	{
		return(get(MessageType.DEBUG));
	}	
	
	public function getAll():List<Message>
	{
		return(messages);
	}	
	
	public function getErrors():List<Message>
	{
		return(get(MessageType.ERROR));
	}	
	
	public function get(type:MessageType)
	{
		var l:List<Message> = new List();
		var m:Message;
		for (m in messages) {
			if (Std.string(m.type) == Std.string(type)) {
				l.add(m);
			}
		}
		return(l);		
	}
	
	public function clearAll()
	{
		messages = new List();
	}
	
	public function toString():String
	{
		return(Std.string(messages));
	}
}

enum MessageType
{
	DEBUG;
	ERROR;
	MESSAGE;
	WARNING;
}

class Message
{
	public var text:String;
	public var type:MessageType;
	
	public function new(text:String, ?type:MessageType)
	{
		if (type == null) type = MessageType.MESSAGE;
		this.text = text;
		this.type = type;
	}
	
	public function toString():String
	{
		return(text);
	}
}