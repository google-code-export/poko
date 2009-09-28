/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.modules.media.js;
import js.Dom;
import js.Lib;
import poko.js.JsRequest;


class JsGallery extends JsRequest
{
	var gallery:String;
	
	override public function main()
	{
		gallery = application.params.get("name");
		
		untyped new JQuery('#uploadify').uploadify({
			uploader  : 'res/cms/media/uploadify.swf',
			script    : 'res/cms/media/uploadify.php',
			cancelImg : 'res/cms/media/cancel.png',
			auto      : true,
			folder    : './res/media/galleries/'+gallery,
			multi	: true,
			fileExt	: '*.jpg;*.gif;*.png',
			onAllComplete	: updateContent
		});
		
		updateContent();
	}
	
	public function updateContent():Void
	{
		remoting.api.getContent.call([], onContent);
	}
	
	private function onContent(content:List<Dynamic>):Void
	{
		var container = new JQuery("#imageContent");
		container.html("");
		
		for (item in content)
		{			
			var el = getPreview(item);
			if(el != null)
			{
				var atts:Dynamic = { };
				atts.imageName = item;
				Reflect.setField(atts, "class", "galleryItem");
				var div = JQuery.create("div", atts, []);
				
				atts = {};
				Reflect.setField(atts, "class", "gallerItemDelete");
				atts.src = "res/cms/delete.png";
				var ths = this;
				var del = JQuery.create("a", { href:"#"}, [JQuery.create("img", atts)]);
				del.click(function(e) {
					ths.delete(div[0], item);
				});
				div.append(el);
				div.append(del);
				container.append(div);
			}
		}
				
	}
	
	private function delete(el:HtmlDom, file:String):Void
	{
		el.parentNode.removeChild(el);
		remoting.api.deleteItem.call([file]);
	}
	
	public function getPreview(item:String)
	{
		var ext:String = item.substr(item.lastIndexOf(".") + 1);
		return switch(ext.toUpperCase())
		{
			case "JPG", "GIF", "PNG":  
				JQuery.create("img", { src:'?request=services.Image&preset=thumb&src=../media/galleries/'+gallery+'/' + item });
		}
	}
	
	public function cancelUploads()
	{
		untyped new JQuery('#uploadify').uploadifyClearQueue();
		
		updateContent();
	}
}