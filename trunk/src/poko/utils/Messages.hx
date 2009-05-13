/**
 * ...
 * @author Tarwin Stroh-Spijer
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

	public function getMessages():List<Message>
	{
		return(get(MessageType.MESSAGE));
	}	
	
	public function getWarnings():List<Message>
	{
		return(get(MessageType.WARNING));
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