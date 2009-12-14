package poko.system;
import poko.Poko;


class Url
{
	var uri:String;
	
	public function new(uri:String)
	{
		this.uri = uri;
	}
	
	public function getSegments():Array<String>
	{
		var config = Poko.instance.config;
		
		var s = uri;
		s = StringTools.startsWith(uri, config.indexPath) ? uri.substr(config.indexPath.length) : s; 
		s = StringTools.startsWith(uri, config.indexPath + config.indexFile) ? uri.substr((config.indexPath + config.indexFile).length):s;
		
		if(StringTools.startsWith(s, "/")) s = s.substr(1);
		if(StringTools.endsWith(s, "/")) s = s.substr(0, s.length-1);
		
		return s.split("/");
	}
}
