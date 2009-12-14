/**
 * ...
 * @author Tony Polinelli
 */

package site.cms.common.formatters;
import poko.form.Formatter;

class ColorPreviewFormatter implements Formatter
{
	public function format(data:Dynamic)
	{
		return '<a href="#"><div style="width:20px;height:20px;background-color:#' + Std.string(data) + '" ></div></a>';
	}
}