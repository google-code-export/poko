/**
 * ...
 * @author Tonypee
 */

package poko.views;
import poko.views.renderers.HaxeTemplate;
import poko.views.renderers.HTemplate;
import poko.views.renderers.Php;
import poko.views.renderers.Templo;

class Parse 
{

	public static function template(template:String, ?data:Dynamic) 
	{
		var ext = template.substr(template.lastIndexOf(".") + 1);

		return switch(ext.toUpperCase())
		{
			case "TPL": Templo.parse(template, data);
			case "PHP": Php.parse(template, data);
			case "HT": HTemplate.parse(template, data);
		}
	}
	
}