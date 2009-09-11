$estr = function() { return js.Boot.__string_rec(this,''); }
site = {}
site.cms = {}
site.cms.modules = {}
site.cms.modules.base = {}
site.cms.modules.base.helper = {}
site.cms.modules.base.helper.MenuDef = function(headings,items) { if( headings === $_ ) return; {
	this.headings = (headings != null?headings:new Array());
	this.items = (items != null?items:new Array());
	this.numberOfSeperators = 0;
}}
site.cms.modules.base.helper.MenuDef.__name__ = ["site","cms","modules","base","helper","MenuDef"];
site.cms.modules.base.helper.MenuDef.prototype.addHeading = function(name) {
	this.headings.push({ name : name, isSeperator : false});
}
site.cms.modules.base.helper.MenuDef.prototype.addItem = function(id,type,name,heading,indent) {
	if(indent == null) indent = 0;
	this.items.push({ id : id, type : type, name : name, heading : heading, indent : indent});
}
site.cms.modules.base.helper.MenuDef.prototype.addSeperator = function() {
	this.numberOfSeperators++;
	this.headings.push({ name : "__sep" + this.numberOfSeperators + "__", isSeperator : true});
}
site.cms.modules.base.helper.MenuDef.prototype.headings = null;
site.cms.modules.base.helper.MenuDef.prototype.items = null;
site.cms.modules.base.helper.MenuDef.prototype.numberOfSeperators = null;
site.cms.modules.base.helper.MenuDef.prototype.__class__ = site.cms.modules.base.helper.MenuDef;
site.cms.modules.base.helper.MenuItemType = { __ename__ : ["site","cms","modules","base","helper","MenuItemType"], __constructs__ : ["PAGE","DATASET","NULL","PAGE_ROLL"] }
site.cms.modules.base.helper.MenuItemType.DATASET = ["DATASET",1];
site.cms.modules.base.helper.MenuItemType.DATASET.toString = $estr;
site.cms.modules.base.helper.MenuItemType.DATASET.__enum__ = site.cms.modules.base.helper.MenuItemType;
site.cms.modules.base.helper.MenuItemType.NULL = ["NULL",2];
site.cms.modules.base.helper.MenuItemType.NULL.toString = $estr;
site.cms.modules.base.helper.MenuItemType.NULL.__enum__ = site.cms.modules.base.helper.MenuItemType;
site.cms.modules.base.helper.MenuItemType.PAGE = ["PAGE",0];
site.cms.modules.base.helper.MenuItemType.PAGE.toString = $estr;
site.cms.modules.base.helper.MenuItemType.PAGE.__enum__ = site.cms.modules.base.helper.MenuItemType;
site.cms.modules.base.helper.MenuItemType.PAGE_ROLL = ["PAGE_ROLL",3];
site.cms.modules.base.helper.MenuItemType.PAGE_ROLL.toString = $estr;
site.cms.modules.base.helper.MenuItemType.PAGE_ROLL.__enum__ = site.cms.modules.base.helper.MenuItemType;
poko = {}
poko.js = {}
poko.js.JsRequest = function(p) { if( p === $_ ) return; {
	null;
}}
poko.js.JsRequest.__name__ = ["poko","js","JsRequest"];
poko.js.JsRequest.prototype.application = null;
poko.js.JsRequest.prototype.call = function(method,args) {
	var func = Reflect.field(this,method);
	if(func == null) {
		haxe.Log.trace("Method does not exist: " + method,{ fileName : "JsRequest.hx", lineNumber : 57, className : "poko.js.JsRequest", methodName : "call"});
		return;
	}
	var a = haxe.Unserializer.run(args);
	{
		var _g = 0, _g1 = Reflect.fields(a);
		while(_g < _g1.length) {
			var f = _g1[_g];
			++_g;
			var field = Reflect.field(a,f);
			if(Std["is"](field,poko.js.JsBinding)) a[f] = this.application.resolveRequest(field.jsRequest);
		}
	}
	func.apply(this,a);
}
poko.js.JsRequest.prototype.getCall = function(method,args) {
	var str = this.getThis() + ".call('" + method + "', ";
	str += "'" + haxe.Serializer.run(args) + "'";
	str += ")";
	return str;
}
poko.js.JsRequest.prototype.getRawCall = function(str) {
	return this.getThis() + "." + str;
}
poko.js.JsRequest.prototype.getThis = function() {
	return "poko.js.JsApplication.instance.resolveRequest('" + Type.getClassName(Type.getClass(this)) + "')";
}
poko.js.JsRequest.prototype.init = function() {
	this.remoting = haxe.remoting.HttpAsyncConnection.urlConnect(js.Lib.window.location.href);
	this.remoting.setErrorHandler(function(e) {
		haxe.Log.trace("Remoting Error : " + Std.string(e),{ fileName : "JsRequest.hx", lineNumber : 48, className : "poko.js.JsRequest", methodName : "init"});
	});
}
poko.js.JsRequest.prototype.main = function() {
	null;
}
poko.js.JsRequest.prototype.remoting = null;
poko.js.JsRequest.prototype.__class__ = poko.js.JsRequest;
site.cms.modules.base.js = {}
site.cms.modules.base.js.JsDatasetItem = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsDatasetItem.__name__ = ["site","cms","modules","base","js","JsDatasetItem"];
site.cms.modules.base.js.JsDatasetItem.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsDatasetItem.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsDatasetItem.prototype.flushWymEditors = function() {
	var c = 0;
	while(c > -1) {
		var editor = JQuery.wymeditors(c);
		if(editor == null) {
			c = -1;
		}
		else {
			c++;
			editor.update();
		}
	}
	return true;
}
site.cms.modules.base.js.JsDatasetItem.prototype.id = null;
site.cms.modules.base.js.JsDatasetItem.prototype.main = function() {
	var el = new JQuery("#form1___cancel").click(function(e) {
		js.Lib.window.history.back();
	});
}
site.cms.modules.base.js.JsDatasetItem.prototype.properties = null;
site.cms.modules.base.js.JsDatasetItem.prototype.setupShowHideElements = function(affector,elements,value,hideOnValue) {
	this.showHideElements(elements,value,hideOnValue);
	var _elements = elements;
	var _hideOnValue = hideOnValue;
	var _affector = affector;
	var _t = this;
	var e = new JQuery("input[name=form1_" + affector + "]").change(function(e) {
		_t.showHideElements(_elements,new JQuery("input[name=form1_" + affector + "]:checked").val(),_hideOnValue);
	});
}
site.cms.modules.base.js.JsDatasetItem.prototype.showHideElements = function(elements,value,hideOnValue) {
	if(elements != null) {
		var els = elements.split(",");
		{
			var _g = 0;
			while(_g < els.length) {
				var el = els[_g];
				++_g;
				var e = new JQuery("label[for=form1_" + el + "]").parent().parent();
				if(value == hideOnValue) e.hide();
				else e.show();
			}
		}
	}
}
site.cms.modules.base.js.JsDatasetItem.prototype.table = null;
site.cms.modules.base.js.JsDatasetItem.prototype.valueHolder = null;
site.cms.modules.base.js.JsDatasetItem.prototype.__class__ = site.cms.modules.base.js.JsDatasetItem;
site.cms.modules.base.js.JsSiteView = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsSiteView.__name__ = ["site","cms","modules","base","js","JsSiteView"];
site.cms.modules.base.js.JsSiteView.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsSiteView.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsSiteView.createSorter = function() {
	var j = new JQuery("#siteView");
	var m = new site.cms.modules.base.helper.MenuDef();
	try {
		m = haxe.Unserializer.run(j.val());
	}
	catch( $e0 ) {
		{
			var e = $e0;
			null;
		}
	}
	var s = "";
	{
		var _g = 0, _g1 = m.headings;
		while(_g < _g1.length) {
			var section = _g1[_g];
			++_g;
			if(section.isSeperator) {
				s += "<li class=\"sectionSeperator\">seperator <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></li>";
			}
			else {
				var name = section.name;
				s += "<li class=\"sectionHeading\"><p><span>" + section.name + "</span> <a href=\"#\" class=\"editItem\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></p>";
				s += "<ul class=\"connectedSortable\">";
				{
					var _g2 = 0, _g3 = m.items;
					while(_g2 < _g3.length) {
						var item = _g3[_g2];
						++_g2;
						if(item.heading == name) {
							s += "<li>";
							s += "<img src=\"./res/cms/";
							s += (function($this) {
								var $r;
								switch(item.type) {
								case site.cms.modules.base.helper.MenuItemType.DATASET:{
									$r = "site_list_list.png";
								}break;
								case site.cms.modules.base.helper.MenuItemType.PAGE:{
									$r = "site_list_page.png";
								}break;
								case site.cms.modules.base.helper.MenuItemType.NULL:{
									$r = "site_list_null.png";
								}break;
								default:{
									$r = null;
								}break;
								}
								return $r;
							}(this));
							s += "\" align=\"absmiddle\" /> <span class=\"listTreeIndent" + item.indent + "\" data=\"" + haxe.Serializer.run({ id : item.id, type : item.type}) + "\">";
							s += item.name + "</span> <a href=\"#\" class=\"editItemInner\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
						}
					}
				}
				s += "</ul>";
				s += "</li>";
			}
		}
	}
	j = new JQuery("#siteViewSection");
	j.html(s);
	j = new JQuery("#siteViewHidden");
	try {
		m = haxe.Unserializer.run(j.val());
	}
	catch( $e1 ) {
		{
			var e = $e1;
			null;
		}
	}
	s = "";
	{
		var _g = 0, _g1 = m.items;
		while(_g < _g1.length) {
			var item = _g1[_g];
			++_g;
			s += "<li>";
			s += "<img src=\"./res/cms/";
			s += (function($this) {
				var $r;
				switch(item.type) {
				case site.cms.modules.base.helper.MenuItemType.DATASET:{
					$r = "site_list_list.png";
				}break;
				case site.cms.modules.base.helper.MenuItemType.PAGE:{
					$r = "site_list_page.png";
				}break;
				case site.cms.modules.base.helper.MenuItemType.NULL:{
					$r = "site_list_null.png";
				}break;
				default:{
					$r = null;
				}break;
				}
				return $r;
			}(this));
			s += "\" align=\"absmiddle\" /> ";
			s += "<span data=\"" + haxe.Serializer.run({ id : item.id, type : item.type}) + "\">" + item.name + "</span><div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
		}
	}
	j = new JQuery("#siteViewHiddenSection");
	j.html(s);
}
site.cms.modules.base.js.JsSiteView.prototype.addNull = function(e) {
	var j = new JQuery("#addNullInput");
	if(!j.val()) {
		js.Lib.alert("Please enter a name.");
		j[0].focus();
	}
	else {
		var s = "<li><img src=\"./res/cms/site_list_null.png\" align=\"absmiddle\" /> <span class=\"listTreeIndent0\" data=\"" + haxe.Serializer.run({ id : 0, type : site.cms.modules.base.helper.MenuItemType.NULL}) + "\" >";
		s += j.val() + "</span> <a href=\"#\" class=\"editItemInner\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <div class=\"connectedSortableMover\"><a href=\"#\">-</a> <a href=\"#\">+</a></div></li>";
		var j1 = new JQuery("#siteViewHiddenSection");
		j1.html(s + j1.html());
		this.refreshBehaviour();
		this.flushSorter(null);
	}
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.addSection = function(e) {
	var j = new JQuery("#addSectionInput");
	if(!j.val()) {
		js.Lib.alert("Please enter a name.");
		j[0].focus();
	}
	else {
		var s = "<li class=\"sectionHeading\"><p><span>" + j.val() + "</span> <a href=\"#\" class=\"editItem\"><img src=\"./res/cms/pencil.png\" align=\"absmiddle\" /></a> <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></p><ul class=\"connectedSortable\"></ul></li>";
		var j1 = new JQuery("#siteViewSection");
		j1.html(s + j1.html());
		j1.val("");
		this.refreshBehaviour();
		this.flushSorter(null);
	}
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.addSeperator = function(e) {
	var s = "<li class=\"sectionSeperator\">seperator <a href=\"#\" class=\"deleteItem\"><img src=\"./res/cms/delete.png\" align=\"absmiddle\" /></a></li>";
	var j = new JQuery("#siteViewSection");
	j.html(s + j.html());
	this.refreshBehaviour();
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.editHeading = function(e) {
	var t = new JQuery(e.currentTarget).parent().parent().find("p > span");
	t.html(js.Lib.prompt("Name? Currently \"" + t.html() + "\"."));
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.editItem = function(e) {
	var t = new JQuery(e.currentTarget).parent().find("span");
	t.html(js.Lib.prompt("Name? Currently \"" + t.html() + "\"."));
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.flushSorter = function(e) {
	var dataPlace = new JQuery("#siteViewData");
	var m = new site.cms.modules.base.helper.MenuDef();
	var j = new JQuery("#siteViewSection > li");
	{
		var _g1 = 0, _g = j.length;
		while(_g1 < _g) {
			var n = _g1++;
			if(j[n].className == "sectionSeperator") {
				m.addSeperator();
			}
			else {
				var headingName = new JQuery("p > span",j[n]).html();
				m.addHeading(headingName);
				var items = new JQuery("li",j[n]);
				{
					var _g3 = 0, _g2 = items.length;
					while(_g3 < _g2) {
						var n2 = _g3++;
						var j2 = new JQuery("span",items[n2]);
						var itemName = j2.html();
						var tIndent = 0;
						if(j2.hasClass("listTreeIndent1")) tIndent = 1;
						if(j2.hasClass("listTreeIndent2")) tIndent = 2;
						if(j2.hasClass("listTreeIndent3")) tIndent = 3;
						if(j2.hasClass("listTreeIndent4")) tIndent = 4;
						var d = haxe.Unserializer.run(j2.attr("data"));
						var tId = d.id;
						var tType = d.type;
						m.addItem(tId,tType,itemName,headingName,tIndent);
					}
				}
			}
		}
	}
	dataPlace.val(haxe.Serializer.run(m));
}
site.cms.modules.base.js.JsSiteView.prototype.main = function() {
	var _t = this;
	new JQuery(js.Lib.document).ready(function() {
		site.cms.modules.base.js.JsSiteView.createSorter();
		_t.refreshBehaviour();
		new JQuery("#addSeperatorButton").click($closure(_t,"addSeperator"));
		new JQuery("#addSectionButton").click($closure(_t,"addSection"));
		new JQuery("#addNullButton").click($closure(_t,"addNull"));
		_t.flushSorter(null);
	});
}
site.cms.modules.base.js.JsSiteView.prototype.minus = function(e) {
	var t = new JQuery(e.currentTarget).parent().parent().find("span");
	var cC = 0;
	if(t.hasClass("listTreeIndent1")) cC = 1;
	if(t.hasClass("listTreeIndent2")) cC = 2;
	if(t.hasClass("listTreeIndent3")) cC = 3;
	if(t.hasClass("listTreeIndent4")) cC = 4;
	t.removeClass("listTreeIndent1");
	t.removeClass("listTreeIndent2");
	t.removeClass("listTreeIndent3");
	t.removeClass("listTreeIndent4");
	switch(cC) {
	case 2:{
		t.addClass("listTreeIndent1");
	}break;
	case 3:{
		t.addClass("listTreeIndent2");
	}break;
	case 4:{
		t.addClass("listTreeIndent3");
	}break;
	}
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.plus = function(e) {
	var t = new JQuery(e.currentTarget).parent().parent().find("span");
	var cC = 0;
	if(t.hasClass("listTreeIndent1")) cC = 1;
	if(t.hasClass("listTreeIndent2")) cC = 2;
	if(t.hasClass("listTreeIndent3")) cC = 3;
	if(t.hasClass("listTreeIndent4")) cC = 4;
	t.removeClass("listTreeIndent1");
	t.removeClass("listTreeIndent2");
	t.removeClass("listTreeIndent3");
	t.removeClass("listTreeIndent4");
	switch(cC) {
	case 0:{
		t.addClass("listTreeIndent1");
	}break;
	case 1:{
		t.addClass("listTreeIndent2");
	}break;
	case 2:{
		t.addClass("listTreeIndent3");
	}break;
	case 3:{
		t.addClass("listTreeIndent4");
	}break;
	case 4:{
		t.addClass("listTreeIndent4");
	}break;
	}
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.refreshBehaviour = function() {
	var j;
	j = new JQuery("#siteViewSection");
	j.sortable({ axis : "y", opacity : 0.8, update : $closure(this,"flushSorter")}).disableSelection();
	j = new JQuery(".connectedSortable");
	j.sortable({ connectWith : ".connectedSortable", axis : "y", opacity : 0.8, update : $closure(this,"flushSorter")}).disableSelection();
	new JQuery(".connectedSortableMover a:even").unbind("click",$closure(this,"minus"));
	new JQuery(".connectedSortableMover a:even").bind("click",null,$closure(this,"minus"));
	new JQuery(".connectedSortableMover a:odd").unbind("click",$closure(this,"plus"));
	new JQuery(".connectedSortableMover a:odd").bind("click",null,$closure(this,"plus"));
	new JQuery(".sectionSeperator a.deleteItem").unbind("click",$closure(this,"removeSeperator"));
	new JQuery(".sectionSeperator a.deleteItem").bind("click",null,$closure(this,"removeSeperator"));
	new JQuery(".sectionHeading a.deleteItem").unbind("click",$closure(this,"removeHeading"));
	new JQuery(".sectionHeading a.deleteItem").bind("click",null,$closure(this,"removeHeading"));
	new JQuery(".sectionHeading a.editItem").unbind("click",$closure(this,"editHeading"));
	new JQuery(".sectionHeading a.editItem").bind("click",null,$closure(this,"editHeading"));
	new JQuery("a.editItemInner").unbind("click",$closure(this,"editItem"));
	new JQuery("a.editItemInner").bind("click",null,$closure(this,"editItem"));
}
site.cms.modules.base.js.JsSiteView.prototype.removeHeading = function(e) {
	var t = new JQuery(e.currentTarget).parent().parent();
	var items = t.find("li");
	this.setIndent(items.find("span"),0);
	var s = "";
	{
		var _g1 = 0, _g = items.length;
		while(_g1 < _g) {
			var n = _g1++;
			s += "<li>" + items[n].innerHTML + "</li>";
		}
	}
	var j = new JQuery("#siteViewHiddenSection");
	j.html(s + j.html());
	t.remove();
	this.refreshBehaviour();
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.removeSeperator = function(e) {
	var t = new JQuery(e.currentTarget).parent();
	t.remove();
	this.flushSorter(null);
	e.preventDefault();
}
site.cms.modules.base.js.JsSiteView.prototype.setIndent = function(t,indent) {
	t.removeClass("listTreeIndent1");
	t.removeClass("listTreeIndent2");
	t.removeClass("listTreeIndent3");
	t.removeClass("listTreeIndent4");
	switch(indent) {
	case 1:{
		t.addClass("listTreeIndent1");
	}break;
	case 2:{
		t.addClass("listTreeIndent2");
	}break;
	case 3:{
		t.addClass("listTreeIndent3");
	}break;
	case 4:{
		t.addClass("listTreeIndent4");
	}break;
	}
	this.flushSorter(null);
}
site.cms.modules.base.js.JsSiteView.prototype.__class__ = site.cms.modules.base.js.JsSiteView;
haxe = {}
haxe.remoting = {}
haxe.remoting.AsyncConnection = function() { }
haxe.remoting.AsyncConnection.__name__ = ["haxe","remoting","AsyncConnection"];
haxe.remoting.AsyncConnection.prototype.call = null;
haxe.remoting.AsyncConnection.prototype.resolve = null;
haxe.remoting.AsyncConnection.prototype.setErrorHandler = null;
haxe.remoting.AsyncConnection.prototype.__class__ = haxe.remoting.AsyncConnection;
StringTools = function() { }
StringTools.__name__ = ["StringTools"];
StringTools.urlEncode = function(s) {
	return encodeURIComponent(s);
}
StringTools.urlDecode = function(s) {
	return decodeURIComponent(s.split("+").join(" "));
}
StringTools.htmlEscape = function(s) {
	return s.split("&").join("&amp;").split("<").join("&lt;").split(">").join("&gt;");
}
StringTools.htmlUnescape = function(s) {
	return s.split("&gt;").join(">").split("&lt;").join("<").split("&amp;").join("&");
}
StringTools.startsWith = function(s,start) {
	return (s.length >= start.length && s.substr(0,start.length) == start);
}
StringTools.endsWith = function(s,end) {
	var elen = end.length;
	var slen = s.length;
	return (slen >= elen && s.substr(slen - elen,elen) == end);
}
StringTools.isSpace = function(s,pos) {
	var c = s.charCodeAt(pos);
	return (c >= 9 && c <= 13) || c == 32;
}
StringTools.ltrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,r)) {
		r++;
	}
	if(r > 0) return s.substr(r,l - r);
	else return s;
}
StringTools.rtrim = function(s) {
	var l = s.length;
	var r = 0;
	while(r < l && StringTools.isSpace(s,l - r - 1)) {
		r++;
	}
	if(r > 0) {
		return s.substr(0,l - r);
	}
	else {
		return s;
	}
}
StringTools.trim = function(s) {
	return StringTools.ltrim(StringTools.rtrim(s));
}
StringTools.rpad = function(s,c,l) {
	var sl = s.length;
	var cl = c.length;
	while(sl < l) {
		if(l - sl < cl) {
			s += c.substr(0,l - sl);
			sl = l;
		}
		else {
			s += c;
			sl += cl;
		}
	}
	return s;
}
StringTools.lpad = function(s,c,l) {
	var ns = "";
	var sl = s.length;
	if(sl >= l) return s;
	var cl = c.length;
	while(sl < l) {
		if(l - sl < cl) {
			ns += c.substr(0,l - sl);
			sl = l;
		}
		else {
			ns += c;
			sl += cl;
		}
	}
	return ns + s;
}
StringTools.replace = function(s,sub,by) {
	return s.split(sub).join(by);
}
StringTools.hex = function(n,digits) {
	var neg = false;
	if(n < 0) {
		neg = true;
		n = -n;
	}
	var s = n.toString(16);
	s = s.toUpperCase();
	if(digits != null) while(s.length < digits) s = "0" + s;
	if(neg) s = "-" + s;
	return s;
}
StringTools.prototype.__class__ = StringTools;
Reflect = function() { }
Reflect.__name__ = ["Reflect"];
Reflect.hasField = function(o,field) {
	if(o.hasOwnProperty != null) return o.hasOwnProperty(field);
	var arr = Reflect.fields(o);
	{ var $it2 = arr.iterator();
	while( $it2.hasNext() ) { var t = $it2.next();
	if(t == field) return true;
	}}
	return false;
}
Reflect.field = function(o,field) {
	var v = null;
	try {
		v = o[field];
	}
	catch( $e3 ) {
		{
			var e = $e3;
			null;
		}
	}
	return v;
}
Reflect.setField = function(o,field,value) {
	o[field] = value;
}
Reflect.callMethod = function(o,func,args) {
	return func.apply(o,args);
}
Reflect.fields = function(o) {
	if(o == null) return new Array();
	var a = new Array();
	if(o.hasOwnProperty) {
		
					for(var i in o)
						if( o.hasOwnProperty(i) )
							a.push(i);
				;
	}
	else {
		var t;
		try {
			t = o.__proto__;
		}
		catch( $e4 ) {
			{
				var e = $e4;
				{
					t = null;
				}
			}
		}
		if(t != null) o.__proto__ = null;
		
					for(var i in o)
						if( i != "__proto__" )
							a.push(i);
				;
		if(t != null) o.__proto__ = t;
	}
	return a;
}
Reflect.isFunction = function(f) {
	return typeof(f) == "function" && f.__name__ == null;
}
Reflect.compare = function(a,b) {
	return ((a == b)?0:((((a) > (b))?1:-1)));
}
Reflect.compareMethods = function(f1,f2) {
	if(f1 == f2) return true;
	if(!Reflect.isFunction(f1) || !Reflect.isFunction(f2)) return false;
	return f1.scope == f2.scope && f1.method == f2.method && f1.method != null;
}
Reflect.isObject = function(v) {
	if(v == null) return false;
	var t = typeof(v);
	return (t == "string" || (t == "object" && !v.__enum__) || (t == "function" && v.__name__ != null));
}
Reflect.deleteField = function(o,f) {
	if(!Reflect.hasField(o,f)) return false;
	delete(o[f]);
	return true;
}
Reflect.copy = function(o) {
	var o2 = { }
	{
		var _g = 0, _g1 = Reflect.fields(o);
		while(_g < _g1.length) {
			var f = _g1[_g];
			++_g;
			o2[f] = Reflect.field(o,f);
		}
	}
	return o2;
}
Reflect.makeVarArgs = function(f) {
	return function() {
		var a = new Array();
		{
			var _g1 = 0, _g = arguments.length;
			while(_g1 < _g) {
				var i = _g1++;
				a.push(arguments[i]);
			}
		}
		return f(a);
	}
}
Reflect.prototype.__class__ = Reflect;
site.cms.modules.base.js.JsDataset = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsDataset.__name__ = ["site","cms","modules","base","js","JsDataset"];
site.cms.modules.base.js.JsDataset.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsDataset.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsDataset.prototype.hideFilterOptions = function() {
	if(js.Lib.document.getElementById("filter_normal") != null) js.Lib.document.getElementById("filter_normal").style.display = "none";
	if(js.Lib.document.getElementById("filter_assoc") != null) js.Lib.document.getElementById("filter_assoc").style.display = "none";
}
site.cms.modules.base.js.JsDataset.prototype.main = function() {
	this.hideFilterOptions();
	var ths = this;
	var filterBySelector = js.Lib.document.getElementById("options_filterBy");
	if(filterBySelector != null) {
		filterBySelector.onchange = function(e) {
			ths.showFilterOptions(filterBySelector.value);
		}
		if(filterBySelector.selectedIndex > 0) {
			var filterByAssocSelector = js.Lib.document.getElementById("options_filterByAssoc");
			if(filterByAssocSelector.childNodes.length > 1) {
				if(js.Lib.document.getElementById("filter_assoc") != null) js.Lib.document.getElementById("filter_assoc").style.display = "inline";
			}
			else {
				if(js.Lib.document.getElementById("filter_normal") != null) js.Lib.document.getElementById("filter_normal").style.display = "inline";
			}
			filterByAssocSelector.onchange = function(e) {
				haxe.Log.trace(filterByAssocSelector.value,{ fileName : "JsDataset.hx", lineNumber : 58, className : "site.cms.modules.base.js.JsDataset", methodName : "main"});
			}
		}
		var resetButton = js.Lib.document.getElementById("options_resetButton");
		resetButton.onclick = function(e) {
			js.Lib.document.getElementById("options_filterBy").selectedIndex = 0;
			js.Lib.document.getElementById("options_orderBy").selectedIndex = 0;
			js.Lib.document.getElementById("options_reset").value = "true";
			resetButton.form.submit();
		}
		var submitButton = js.Lib.document.getElementById("options_updateButton");
		submitButton.onclick = function(e) {
			js.Lib.document.getElementById("options_reset").value = "false";
			submitButton.form.submit();
		}
	}
}
site.cms.modules.base.js.JsDataset.prototype.onGetFilterInfo = function(response) {
	this.hideFilterOptions();
	switch(response.type) {
	case "association":case "bool":{
		js.Lib.document.getElementById("filter_assoc").style.display = "inline";
		var select = js.Lib.document.getElementById("options_filterByAssoc");
		var options = response.data;
		select.style.display = "block";
		select.innerHTML = "<option value=\"\" >- select -</option>";
		{ var $it5 = options.keys();
		while( $it5.hasNext() ) { var option = $it5.next();
		{
			select.innerHTML += "<option value=\"" + option + "\">" + options.get(option) + "</option>";
		}
		}}
	}break;
	default:{
		if(js.Lib.document.getElementById("filter_normal") != null) js.Lib.document.getElementById("filter_normal").style.display = "inline";
	}break;
	}
}
site.cms.modules.base.js.JsDataset.prototype.showFilterOptions = function(field) {
	if(field == "") {
		this.hideFilterOptions();
		return;
	}
	this.remoting.resolve("api").resolve("getFilterInfo").call([field],$closure(this,"onGetFilterInfo"));
}
site.cms.modules.base.js.JsDataset.prototype.__class__ = site.cms.modules.base.js.JsDataset;
haxe.Log = function() { }
haxe.Log.__name__ = ["haxe","Log"];
haxe.Log.trace = function(v,infos) {
	js.Boot.__trace(v,infos);
}
haxe.Log.clear = function() {
	js.Boot.__clear_trace();
}
haxe.Log.prototype.__class__ = haxe.Log;
MainJS = function() { }
MainJS.__name__ = ["MainJS"];
MainJS.app = null;
MainJS.main = function() {
	MainJS.app = new poko.js.JsApplication();
	MainJS.app.serverUrl = "http://localhost/poko/";
	js.Lib.window.onload = $closure(MainJS,"run");
}
MainJS.run = function(e) {
	MainJS.app.run();
}
MainJS.prototype.__class__ = MainJS;
StringBuf = function(p) { if( p === $_ ) return; {
	this.b = new Array();
}}
StringBuf.__name__ = ["StringBuf"];
StringBuf.prototype.add = function(x) {
	this.b[this.b.length] = x;
}
StringBuf.prototype.addChar = function(c) {
	this.b[this.b.length] = String.fromCharCode(c);
}
StringBuf.prototype.addSub = function(s,pos,len) {
	this.b[this.b.length] = s.substr(pos,len);
}
StringBuf.prototype.b = null;
StringBuf.prototype.toString = function() {
	return this.b.join("");
}
StringBuf.prototype.__class__ = StringBuf;
poko.js.JsApplication = function(p) { if( p === $_ ) return; {
	poko.js.JsApplication.instance = this;
	this.requests = new Hash();
	this.requestBuffer = new List();
	this.serverUrl = "";
	poko.js.JsApplication.setupLog();
	this.parseParams();
}}
poko.js.JsApplication.__name__ = ["poko","js","JsApplication"];
poko.js.JsApplication.instance = null;
poko.js.JsApplication.setupLog = function() {
	haxe.Log.trace = $closure(poko.js.JsApplication,"log");
}
poko.js.JsApplication.log = function(v,pos) {
	var console = Reflect.field(js.Lib.window,"console");
	console.log("%s:%s: %o \n",pos.fileName,pos.lineNumber,v);
}
poko.js.JsApplication.prototype.addRequest = function(req) {
	var r = this.setupRequest(req);
	if(r != null) this.requests.set(req,r);
	return r;
}
poko.js.JsApplication.prototype.params = null;
poko.js.JsApplication.prototype.parseParams = function() {
	if(this.params == null) this.params = new Hash();
	var parts = js.Lib.window.location.search.substr(1).split("&");
	{
		var _g = 0;
		while(_g < parts.length) {
			var part = parts[_g];
			++_g;
			var p = part.split("=");
			this.params.set(p[0],p[1]);
		}
	}
}
poko.js.JsApplication.prototype.requestBuffer = null;
poko.js.JsApplication.prototype.requests = null;
poko.js.JsApplication.prototype.resolveRequest = function(req) {
	return this.requests.get(req);
}
poko.js.JsApplication.prototype.run = function() {
	{ var $it6 = this.requests.iterator();
	while( $it6.hasNext() ) { var req = $it6.next();
	req.main();
	}}
}
poko.js.JsApplication.prototype.serverUrl = null;
poko.js.JsApplication.prototype.setupRequest = function(req) {
	var request = null;
	var c = Type.resolveClass(req);
	if(c != null) {
		request = Type.createInstance(c,[]);
		request.application = this;
		request.init();
	}
	return request;
}
poko.js.JsApplication.prototype.__class__ = poko.js.JsApplication;
haxe.io = {}
haxe.io.Bytes = function(length,b) { if( length === $_ ) return; {
	this.length = length;
	this.b = b;
}}
haxe.io.Bytes.__name__ = ["haxe","io","Bytes"];
haxe.io.Bytes.alloc = function(length) {
	var a = new Array();
	{
		var _g = 0;
		while(_g < length) {
			var i = _g++;
			a.push(0);
		}
	}
	return new haxe.io.Bytes(length,a);
}
haxe.io.Bytes.ofString = function(s) {
	var a = new Array();
	{
		var _g1 = 0, _g = s.length;
		while(_g1 < _g) {
			var i = _g1++;
			var c = s["cca"](i);
			if(c <= 127) a.push(c);
			else if(c <= 2047) {
				a.push(192 | (c >> 6));
				a.push(128 | (c & 63));
			}
			else if(c <= 65535) {
				a.push(224 | (c >> 12));
				a.push(128 | ((c >> 6) & 63));
				a.push(128 | (c & 63));
			}
			else {
				a.push(240 | (c >> 18));
				a.push(128 | ((c >> 12) & 63));
				a.push(128 | ((c >> 6) & 63));
				a.push(128 | (c & 63));
			}
		}
	}
	return new haxe.io.Bytes(a.length,a);
}
haxe.io.Bytes.ofData = function(b) {
	return new haxe.io.Bytes(b.length,b);
}
haxe.io.Bytes.prototype.b = null;
haxe.io.Bytes.prototype.blit = function(pos,src,srcpos,len) {
	if(pos < 0 || srcpos < 0 || len < 0 || pos + len > this.length || srcpos + len > src.length) throw haxe.io.Error.OutsideBounds;
	var b1 = this.b;
	var b2 = src.b;
	if(b1 == b2 && pos > srcpos) {
		var i = len;
		while(i > 0) {
			i--;
			b1[i + pos] = b2[i + srcpos];
		}
		return;
	}
	{
		var _g = 0;
		while(_g < len) {
			var i = _g++;
			b1[i + pos] = b2[i + srcpos];
		}
	}
}
haxe.io.Bytes.prototype.compare = function(other) {
	var b1 = this.b;
	var b2 = other.b;
	var len = ((this.length < other.length)?this.length:other.length);
	{
		var _g = 0;
		while(_g < len) {
			var i = _g++;
			if(b1[i] != b2[i]) return b1[i] - b2[i];
		}
	}
	return this.length - other.length;
}
haxe.io.Bytes.prototype.get = function(pos) {
	return this.b[pos];
}
haxe.io.Bytes.prototype.getData = function() {
	return this.b;
}
haxe.io.Bytes.prototype.length = null;
haxe.io.Bytes.prototype.readString = function(pos,len) {
	if(pos < 0 || len < 0 || pos + len > this.length) throw haxe.io.Error.OutsideBounds;
	var s = "";
	var b = this.b;
	var fcc = $closure(String,"fromCharCode");
	var i = pos;
	var max = pos + len;
	while(i < max) {
		var c = b[i++];
		if(c < 128) {
			if(c == 0) break;
			s += fcc(c);
		}
		else if(c < 224) s += fcc(((c & 63) << 6) | (b[i++] & 127));
		else if(c < 240) {
			var c2 = b[i++];
			s += fcc((((c & 31) << 12) | ((c2 & 127) << 6)) | (b[i++] & 127));
		}
		else {
			var c2 = b[i++];
			var c3 = b[i++];
			s += fcc(((((c & 15) << 18) | ((c2 & 127) << 12)) | ((c3 << 6) & 127)) | (b[i++] & 127));
		}
	}
	return s;
}
haxe.io.Bytes.prototype.set = function(pos,v) {
	this.b[pos] = (v & 255);
}
haxe.io.Bytes.prototype.sub = function(pos,len) {
	if(pos < 0 || len < 0 || pos + len > this.length) throw haxe.io.Error.OutsideBounds;
	return new haxe.io.Bytes(len,this.b.slice(pos,pos + len));
}
haxe.io.Bytes.prototype.toString = function() {
	return this.readString(0,this.length);
}
haxe.io.Bytes.prototype.__class__ = haxe.io.Bytes;
site.cms.ImportAll = function() { }
site.cms.ImportAll.__name__ = ["site","cms","ImportAll"];
site.cms.ImportAll.prototype.__class__ = site.cms.ImportAll;
IntIter = function(min,max) { if( min === $_ ) return; {
	this.min = min;
	this.max = max;
}}
IntIter.__name__ = ["IntIter"];
IntIter.prototype.hasNext = function() {
	return this.min < this.max;
}
IntIter.prototype.max = null;
IntIter.prototype.min = null;
IntIter.prototype.next = function() {
	return this.min++;
}
IntIter.prototype.__class__ = IntIter;
haxe.io.Error = { __ename__ : ["haxe","io","Error"], __constructs__ : ["Blocked","Overflow","OutsideBounds","Custom"] }
haxe.io.Error.Blocked = ["Blocked",0];
haxe.io.Error.Blocked.toString = $estr;
haxe.io.Error.Blocked.__enum__ = haxe.io.Error;
haxe.io.Error.Custom = function(e) { var $x = ["Custom",3,e]; $x.__enum__ = haxe.io.Error; $x.toString = $estr; return $x; }
haxe.io.Error.OutsideBounds = ["OutsideBounds",2];
haxe.io.Error.OutsideBounds.toString = $estr;
haxe.io.Error.OutsideBounds.__enum__ = haxe.io.Error;
haxe.io.Error.Overflow = ["Overflow",1];
haxe.io.Error.Overflow.toString = $estr;
haxe.io.Error.Overflow.__enum__ = haxe.io.Error;
haxe.remoting.HttpAsyncConnection = function(data,path) { if( data === $_ ) return; {
	this.__data = data;
	this.__path = path;
}}
haxe.remoting.HttpAsyncConnection.__name__ = ["haxe","remoting","HttpAsyncConnection"];
haxe.remoting.HttpAsyncConnection.urlConnect = function(url) {
	return new haxe.remoting.HttpAsyncConnection({ url : url, error : function(e) {
		throw e;
	}},[]);
}
haxe.remoting.HttpAsyncConnection.prototype.__data = null;
haxe.remoting.HttpAsyncConnection.prototype.__path = null;
haxe.remoting.HttpAsyncConnection.prototype.call = function(params,onResult) {
	var h = new haxe.Http(this.__data.url);
	var s = new haxe.Serializer();
	s.serialize(this.__path);
	s.serialize(params);
	h.setHeader("X-Haxe-Remoting","1");
	h.setParameter("__x",s.toString());
	var error = this.__data.error;
	h.onData = function(response) {
		var ok = true;
		var ret;
		try {
			if(response.substr(0,3) != "hxr") throw "Invalid response : '" + response + "'";
			var s1 = new haxe.Unserializer(response.substr(3));
			ret = s1.unserialize();
		}
		catch( $e7 ) {
			{
				var err = $e7;
				{
					ret = null;
					ok = false;
					error(err);
				}
			}
		}
		if(ok && onResult != null) onResult(ret);
	}
	h.onError = error;
	h.request(true);
}
haxe.remoting.HttpAsyncConnection.prototype.resolve = function(name) {
	var c = new haxe.remoting.HttpAsyncConnection(this.__data,this.__path.copy());
	c.__path.push(name);
	return c;
}
haxe.remoting.HttpAsyncConnection.prototype.setErrorHandler = function(h) {
	this.__data.error = h;
}
haxe.remoting.HttpAsyncConnection.prototype.__class__ = haxe.remoting.HttpAsyncConnection;
haxe.remoting.HttpAsyncConnection.__interfaces__ = [haxe.remoting.AsyncConnection];
Std = function() { }
Std.__name__ = ["Std"];
Std["is"] = function(v,t) {
	return js.Boot.__instanceof(v,t);
}
Std.string = function(s) {
	return js.Boot.__string_rec(s,"");
}
Std["int"] = function(x) {
	if(x < 0) return Math.ceil(x);
	return Math.floor(x);
}
Std.parseInt = function(x) {
	var v = parseInt(x);
	if(Math.isNaN(v)) return null;
	return v;
}
Std.parseFloat = function(x) {
	return parseFloat(x);
}
Std.random = function(x) {
	return Math.floor(Math.random() * x);
}
Std.prototype.__class__ = Std;
Type = function() { }
Type.__name__ = ["Type"];
Type.getClass = function(o) {
	if(o == null) return null;
	if(o.__enum__ != null) return null;
	return o.__class__;
}
Type.getEnum = function(o) {
	if(o == null) return null;
	return o.__enum__;
}
Type.getSuperClass = function(c) {
	return c.__super__;
}
Type.getClassName = function(c) {
	if(c == null) return null;
	var a = c.__name__;
	return a.join(".");
}
Type.getEnumName = function(e) {
	var a = e.__ename__;
	return a.join(".");
}
Type.resolveClass = function(name) {
	var cl;
	try {
		cl = eval(name);
	}
	catch( $e8 ) {
		{
			var e = $e8;
			{
				cl = null;
			}
		}
	}
	if(cl == null || cl.__name__ == null) return null;
	return cl;
}
Type.resolveEnum = function(name) {
	var e;
	try {
		e = eval(name);
	}
	catch( $e9 ) {
		{
			var err = $e9;
			{
				e = null;
			}
		}
	}
	if(e == null || e.__ename__ == null) return null;
	return e;
}
Type.createInstance = function(cl,args) {
	if(args.length <= 3) return new cl(args[0],args[1],args[2]);
	if(args.length > 8) throw "Too many arguments";
	return new cl(args[0],args[1],args[2],args[3],args[4],args[5],args[6],args[7]);
}
Type.createEmptyInstance = function(cl) {
	return new cl($_);
}
Type.createEnum = function(e,constr,params) {
	var f = Reflect.field(e,constr);
	if(f == null) throw "No such constructor " + constr;
	if(Reflect.isFunction(f)) {
		if(params == null) throw "Constructor " + constr + " need parameters";
		return f.apply(e,params);
	}
	if(params != null && params.length != 0) throw "Constructor " + constr + " does not need parameters";
	return f;
}
Type.createEnumIndex = function(e,index,params) {
	var c = Type.getEnumConstructs(e)[index];
	if(c == null) throw index + " is not a valid enum constructor index";
	return Type.createEnum(e,c,params);
}
Type.getInstanceFields = function(c) {
	var a = Reflect.fields(c.prototype);
	a.remove("__class__");
	return a;
}
Type.getClassFields = function(c) {
	var a = Reflect.fields(c);
	a.remove("__name__");
	a.remove("__interfaces__");
	a.remove("__super__");
	a.remove("prototype");
	return a;
}
Type.getEnumConstructs = function(e) {
	return e.__constructs__;
}
Type["typeof"] = function(v) {
	switch(typeof(v)) {
	case "boolean":{
		return ValueType.TBool;
	}break;
	case "string":{
		return ValueType.TClass(String);
	}break;
	case "number":{
		if(Math.ceil(v) == v % 2147483648.0) return ValueType.TInt;
		return ValueType.TFloat;
	}break;
	case "object":{
		if(v == null) return ValueType.TNull;
		var e = v.__enum__;
		if(e != null) return ValueType.TEnum(e);
		var c = v.__class__;
		if(c != null) return ValueType.TClass(c);
		return ValueType.TObject;
	}break;
	case "function":{
		if(v.__name__ != null) return ValueType.TObject;
		return ValueType.TFunction;
	}break;
	case "undefined":{
		return ValueType.TNull;
	}break;
	default:{
		return ValueType.TUnknown;
	}break;
	}
}
Type.enumEq = function(a,b) {
	if(a == b) return true;
	try {
		if(a[0] != b[0]) return false;
		{
			var _g1 = 2, _g = a.length;
			while(_g1 < _g) {
				var i = _g1++;
				if(!Type.enumEq(a[i],b[i])) return false;
			}
		}
		var e = a.__enum__;
		if(e != b.__enum__ || e == null) return false;
	}
	catch( $e10 ) {
		{
			var e = $e10;
			{
				return false;
			}
		}
	}
	return true;
}
Type.enumConstructor = function(e) {
	return e[0];
}
Type.enumParameters = function(e) {
	return e.slice(2);
}
Type.enumIndex = function(e) {
	return e[1];
}
Type.prototype.__class__ = Type;
haxe.Unserializer = function(buf) { if( buf === $_ ) return; {
	this.buf = buf;
	this.length = buf.length;
	this.pos = 0;
	this.scache = new Array();
	this.cache = new Array();
	this.setResolver(haxe.Unserializer.DEFAULT_RESOLVER);
}}
haxe.Unserializer.__name__ = ["haxe","Unserializer"];
haxe.Unserializer.initCodes = function() {
	var codes = new Array();
	{
		var _g1 = 0, _g = haxe.Unserializer.BASE64.length;
		while(_g1 < _g) {
			var i = _g1++;
			codes[haxe.Unserializer.BASE64.cca(i)] = i;
		}
	}
	return codes;
}
haxe.Unserializer.run = function(v) {
	return new haxe.Unserializer(v).unserialize();
}
haxe.Unserializer.prototype.buf = null;
haxe.Unserializer.prototype.cache = null;
haxe.Unserializer.prototype.get = function(p) {
	return this.buf.cca(p);
}
haxe.Unserializer.prototype.length = null;
haxe.Unserializer.prototype.pos = null;
haxe.Unserializer.prototype.readDigits = function() {
	var k = 0;
	var s = false;
	var fpos = this.pos;
	while(true) {
		var c = this.buf.cca(this.pos);
		if(Math.isNaN(c)) break;
		if(c == 45) {
			if(this.pos != fpos) break;
			s = true;
			this.pos++;
			continue;
		}
		c -= 48;
		if(c < 0 || c > 9) break;
		k = k * 10 + c;
		this.pos++;
	}
	if(s) k *= -1;
	return k;
}
haxe.Unserializer.prototype.resolver = null;
haxe.Unserializer.prototype.scache = null;
haxe.Unserializer.prototype.setResolver = function(r) {
	if(r == null) this.resolver = { resolveClass : function(_) {
		return null;
	}, resolveEnum : function(_) {
		return null;
	}}
	else this.resolver = r;
}
haxe.Unserializer.prototype.unserialize = function() {
	switch(this.buf.cca(this.pos++)) {
	case 110:{
		return null;
	}break;
	case 116:{
		return true;
	}break;
	case 102:{
		return false;
	}break;
	case 122:{
		return 0;
	}break;
	case 105:{
		return this.readDigits();
	}break;
	case 100:{
		var p1 = this.pos;
		while(true) {
			var c = this.buf.cca(this.pos);
			if((c >= 43 && c < 58) || c == 101 || c == 69) this.pos++;
			else break;
		}
		return Std.parseFloat(this.buf.substr(p1,this.pos - p1));
	}break;
	case 121:{
		var len = this.readDigits();
		if(this.buf.charAt(this.pos++) != ":" || this.length - this.pos < len) throw "Invalid string length";
		var s = this.buf.substr(this.pos,len);
		this.pos += len;
		s = StringTools.urlDecode(s);
		this.scache.push(s);
		return s;
	}break;
	case 107:{
		return Math.NaN;
	}break;
	case 109:{
		return Math.NEGATIVE_INFINITY;
	}break;
	case 112:{
		return Math.POSITIVE_INFINITY;
	}break;
	case 97:{
		var buf = this.buf;
		var a = new Array();
		this.cache.push(a);
		while(true) {
			var c = this.buf.cca(this.pos);
			if(c == 104) {
				this.pos++;
				break;
			}
			if(c == 117) {
				this.pos++;
				var n = this.readDigits();
				a[a.length + n - 1] = null;
			}
			else a.push(this.unserialize());
		}
		return a;
	}break;
	case 111:{
		var o = { }
		this.cache.push(o);
		this.unserializeObject(o);
		return o;
	}break;
	case 114:{
		var n = this.readDigits();
		if(n < 0 || n >= this.cache.length) throw "Invalid reference";
		return this.cache[n];
	}break;
	case 82:{
		var n = this.readDigits();
		if(n < 0 || n >= this.scache.length) throw "Invalid string reference";
		return this.scache[n];
	}break;
	case 120:{
		throw this.unserialize();
	}break;
	case 99:{
		var name = this.unserialize();
		var cl = this.resolver.resolveClass(name);
		if(cl == null) throw "Class not found " + name;
		var o = Type.createEmptyInstance(cl);
		this.cache.push(o);
		this.unserializeObject(o);
		return o;
	}break;
	case 119:{
		var name = this.unserialize();
		var edecl = this.resolver.resolveEnum(name);
		if(edecl == null) throw "Enum not found " + name;
		return this.unserializeEnum(edecl,this.unserialize());
	}break;
	case 106:{
		var name = this.unserialize();
		var edecl = this.resolver.resolveEnum(name);
		if(edecl == null) throw "Enum not found " + name;
		this.pos++;
		var index = this.readDigits();
		var tag = Type.getEnumConstructs(edecl)[index];
		if(tag == null) throw "Unknown enum index " + name + "@" + index;
		return this.unserializeEnum(edecl,tag);
	}break;
	case 108:{
		var l = new List();
		this.cache.push(l);
		var buf = this.buf;
		while(this.buf.cca(this.pos) != 104) l.add(this.unserialize());
		this.pos++;
		return l;
	}break;
	case 98:{
		var h = new Hash();
		this.cache.push(h);
		var buf = this.buf;
		while(this.buf.cca(this.pos) != 104) {
			var s = this.unserialize();
			h.set(s,this.unserialize());
		}
		this.pos++;
		return h;
	}break;
	case 113:{
		var h = new IntHash();
		this.cache.push(h);
		var buf = this.buf;
		var c = this.buf.cca(this.pos++);
		while(c == 58) {
			var i = this.readDigits();
			h.set(i,this.unserialize());
			c = this.buf.cca(this.pos++);
		}
		if(c != 104) throw "Invalid IntHash format";
		return h;
	}break;
	case 118:{
		var d = Date.fromString(this.buf.substr(this.pos,19));
		this.cache.push(d);
		this.pos += 19;
		return d;
	}break;
	case 115:{
		var len = this.readDigits();
		var buf = this.buf;
		if(buf.charAt(this.pos++) != ":" || this.length - this.pos < len) throw "Invalid bytes length";
		var codes = haxe.Unserializer.CODES;
		if(codes == null) {
			codes = haxe.Unserializer.initCodes();
			haxe.Unserializer.CODES = codes;
		}
		var i = this.pos;
		var rest = len & 3;
		var size = (len >> 2) * 3 + (((rest >= 2)?rest - 1:0));
		var max = i + (len - rest);
		var bytes = haxe.io.Bytes.alloc(size);
		var bpos = 0;
		while(i < max) {
			var c1 = codes[buf.cca(i++)];
			var c2 = codes[buf.cca(i++)];
			bytes.b[bpos++] = (((c1 << 2) | (c2 >> 4)) & 255);
			var c3 = codes[buf.cca(i++)];
			bytes.b[bpos++] = (((c2 << 4) | (c3 >> 2)) & 255);
			var c4 = codes[buf.cca(i++)];
			bytes.b[bpos++] = (((c3 << 6) | c4) & 255);
		}
		if(rest >= 2) {
			var c1 = codes[buf.cca(i++)];
			var c2 = codes[buf.cca(i++)];
			bytes.b[bpos++] = (((c1 << 2) | (c2 >> 4)) & 255);
			if(rest == 3) {
				var c3 = codes[buf.cca(i++)];
				bytes.b[bpos++] = (((c2 << 4) | (c3 >> 2)) & 255);
			}
		}
		this.pos += len;
		this.cache.push(bytes);
		return bytes;
	}break;
	default:{
		null;
	}break;
	}
	this.pos--;
	throw ("Invalid char " + this.buf.charAt(this.pos) + " at position " + this.pos);
}
haxe.Unserializer.prototype.unserializeEnum = function(edecl,tag) {
	var constr = Reflect.field(edecl,tag);
	if(constr == null) throw "Unknown enum tag " + Type.getEnumName(edecl) + "." + tag;
	if(this.buf.cca(this.pos++) != 58) throw "Invalid enum format";
	var nargs = this.readDigits();
	if(nargs == 0) {
		this.cache.push(constr);
		return constr;
	}
	var args = new Array();
	while(nargs > 0) {
		args.push(this.unserialize());
		nargs -= 1;
	}
	var e = constr.apply(edecl,args);
	this.cache.push(e);
	return e;
}
haxe.Unserializer.prototype.unserializeObject = function(o) {
	while(true) {
		if(this.pos >= this.length) throw "Invalid object";
		if(this.buf.cca(this.pos) == 103) break;
		var k = this.unserialize();
		if(!Std["is"](k,String)) throw "Invalid object key";
		var v = this.unserialize();
		o[k] = v;
	}
	this.pos++;
}
haxe.Unserializer.prototype.__class__ = haxe.Unserializer;
haxe.Serializer = function(p) { if( p === $_ ) return; {
	this.buf = new StringBuf();
	this.cache = new Array();
	this.useCache = haxe.Serializer.USE_CACHE;
	this.useEnumIndex = haxe.Serializer.USE_ENUM_INDEX;
	this.shash = new Hash();
	this.scount = 0;
}}
haxe.Serializer.__name__ = ["haxe","Serializer"];
haxe.Serializer.run = function(v) {
	var s = new haxe.Serializer();
	s.serialize(v);
	return s.toString();
}
haxe.Serializer.prototype.buf = null;
haxe.Serializer.prototype.cache = null;
haxe.Serializer.prototype.scount = null;
haxe.Serializer.prototype.serialize = function(v) {
	var $e = (Type["typeof"](v));
	switch( $e[1] ) {
	case 0:
	{
		this.buf.add("n");
	}break;
	case 1:
	{
		if(v == 0) {
			this.buf.add("z");
			return;
		}
		this.buf.add("i");
		this.buf.add(v);
	}break;
	case 2:
	{
		if(Math.isNaN(v)) this.buf.add("k");
		else if(!Math.isFinite(v)) this.buf.add((v < 0?"m":"p"));
		else {
			this.buf.add("d");
			this.buf.add(v);
		}
	}break;
	case 3:
	{
		this.buf.add((v?"t":"f"));
	}break;
	case 6:
	var c = $e[2];
	{
		if(c == String) {
			this.serializeString(v);
			return;
		}
		if(this.useCache && this.serializeRef(v)) return;
		switch(c) {
		case Array:{
			var ucount = 0;
			this.buf.add("a");
			var l = v["length"];
			{
				var _g = 0;
				while(_g < l) {
					var i = _g++;
					if(v[i] == null) ucount++;
					else {
						if(ucount > 0) {
							if(ucount == 1) this.buf.add("n");
							else {
								this.buf.add("u");
								this.buf.add(ucount);
							}
							ucount = 0;
						}
						this.serialize(v[i]);
					}
				}
			}
			if(ucount > 0) {
				if(ucount == 1) this.buf.add("n");
				else {
					this.buf.add("u");
					this.buf.add(ucount);
				}
			}
			this.buf.add("h");
		}break;
		case List:{
			this.buf.add("l");
			var v1 = v;
			{ var $it11 = v1.iterator();
			while( $it11.hasNext() ) { var i = $it11.next();
			this.serialize(i);
			}}
			this.buf.add("h");
		}break;
		case Date:{
			var d = v;
			this.buf.add("v");
			this.buf.add(d.toString());
		}break;
		case Hash:{
			this.buf.add("b");
			var v1 = v;
			{ var $it12 = v1.keys();
			while( $it12.hasNext() ) { var k = $it12.next();
			{
				this.serializeString(k);
				this.serialize(v1.get(k));
			}
			}}
			this.buf.add("h");
		}break;
		case IntHash:{
			this.buf.add("q");
			var v1 = v;
			{ var $it13 = v1.keys();
			while( $it13.hasNext() ) { var k = $it13.next();
			{
				this.buf.add(":");
				this.buf.add(k);
				this.serialize(v1.get(k));
			}
			}}
			this.buf.add("h");
		}break;
		case haxe.io.Bytes:{
			var v1 = v;
			var i = 0;
			var max = v1.length - 2;
			var chars = "";
			var b64 = haxe.Serializer.BASE64;
			while(i < max) {
				var b1 = v1.b[i++];
				var b2 = v1.b[i++];
				var b3 = v1.b[i++];
				chars += b64.charAt(b1 >> 2) + b64.charAt(((b1 << 4) | (b2 >> 4)) & 63) + b64.charAt(((b2 << 2) | (b3 >> 6)) & 63) + b64.charAt(b3 & 63);
			}
			if(i == max) {
				var b1 = v1.b[i++];
				var b2 = v1.b[i++];
				chars += b64.charAt(b1 >> 2) + b64.charAt(((b1 << 4) | (b2 >> 4)) & 63) + b64.charAt((b2 << 2) & 63);
			}
			else if(i == max + 1) {
				var b1 = v1.b[i++];
				chars += b64.charAt(b1 >> 2) + b64.charAt((b1 << 4) & 63);
			}
			this.buf.add("s");
			this.buf.add(chars.length);
			this.buf.add(":");
			this.buf.add(chars);
		}break;
		default:{
			this.cache.pop();
			this.buf.add("c");
			this.serializeString(Type.getClassName(c));
			this.cache.push(v);
			this.serializeFields(v);
		}break;
		}
	}break;
	case 4:
	{
		if(this.useCache && this.serializeRef(v)) return;
		this.buf.add("o");
		this.serializeFields(v);
	}break;
	case 7:
	var e = $e[2];
	{
		if(this.useCache && this.serializeRef(v)) return;
		this.cache.pop();
		this.buf.add((this.useEnumIndex?"j":"w"));
		this.serializeString(Type.getEnumName(e));
		if(this.useEnumIndex) {
			this.buf.add(":");
			this.buf.add(v[1]);
		}
		else this.serializeString(v[0]);
		this.buf.add(":");
		var l = v["length"];
		this.buf.add(l - 2);
		{
			var _g = 2;
			while(_g < l) {
				var i = _g++;
				this.serialize(v[i]);
			}
		}
		this.cache.push(v);
	}break;
	case 5:
	{
		throw "Cannot serialize function";
	}break;
	default:{
		throw "Cannot serialize " + Std.string(v);
	}break;
	}
}
haxe.Serializer.prototype.serializeException = function(e) {
	this.buf.add("x");
	this.serialize(e);
}
haxe.Serializer.prototype.serializeFields = function(v) {
	{
		var _g = 0, _g1 = Reflect.fields(v);
		while(_g < _g1.length) {
			var f = _g1[_g];
			++_g;
			this.serializeString(f);
			this.serialize(Reflect.field(v,f));
		}
	}
	this.buf.add("g");
}
haxe.Serializer.prototype.serializeRef = function(v) {
	var vt = typeof(v);
	{
		var _g1 = 0, _g = this.cache.length;
		while(_g1 < _g) {
			var i = _g1++;
			var ci = this.cache[i];
			if(typeof(ci) == vt && ci == v) {
				this.buf.add("r");
				this.buf.add(i);
				return true;
			}
		}
	}
	this.cache.push(v);
	return false;
}
haxe.Serializer.prototype.serializeString = function(s) {
	var x = this.shash.get(s);
	if(x != null) {
		this.buf.add("R");
		this.buf.add(x);
		return;
	}
	this.shash.set(s,this.scount++);
	this.buf.add("y");
	s = StringTools.urlEncode(s);
	this.buf.add(s.length);
	this.buf.add(":");
	this.buf.add(s);
}
haxe.Serializer.prototype.shash = null;
haxe.Serializer.prototype.toString = function() {
	return this.buf.b.join("");
}
haxe.Serializer.prototype.useCache = null;
haxe.Serializer.prototype.useEnumIndex = null;
haxe.Serializer.prototype.__class__ = haxe.Serializer;
List = function(p) { if( p === $_ ) return; {
	this.length = 0;
}}
List.__name__ = ["List"];
List.prototype.add = function(item) {
	var x = [item];
	if(this.h == null) this.h = x;
	else this.q[1] = x;
	this.q = x;
	this.length++;
}
List.prototype.clear = function() {
	this.h = null;
	this.q = null;
	this.length = 0;
}
List.prototype.filter = function(f) {
	var l2 = new List();
	var l = this.h;
	while(l != null) {
		var v = l[0];
		l = l[1];
		if(f(v)) l2.add(v);
	}
	return l2;
}
List.prototype.first = function() {
	return (this.h == null?null:this.h[0]);
}
List.prototype.h = null;
List.prototype.isEmpty = function() {
	return (this.h == null);
}
List.prototype.iterator = function() {
	return { h : this.h, hasNext : function() {
		return (this.h != null);
	}, next : function() {
		if(this.h == null) return null;
		var x = this.h[0];
		this.h = this.h[1];
		return x;
	}}
}
List.prototype.join = function(sep) {
	var s = new StringBuf();
	var first = true;
	var l = this.h;
	while(l != null) {
		if(first) first = false;
		else s.b[s.b.length] = sep;
		s.b[s.b.length] = l[0];
		l = l[1];
	}
	return s.b.join("");
}
List.prototype.last = function() {
	return (this.q == null?null:this.q[0]);
}
List.prototype.length = null;
List.prototype.map = function(f) {
	var b = new List();
	var l = this.h;
	while(l != null) {
		var v = l[0];
		l = l[1];
		b.add(f(v));
	}
	return b;
}
List.prototype.pop = function() {
	if(this.h == null) return null;
	var x = this.h[0];
	this.h = this.h[1];
	if(this.h == null) this.q = null;
	this.length--;
	return x;
}
List.prototype.push = function(item) {
	var x = [item,this.h];
	this.h = x;
	if(this.q == null) this.q = x;
	this.length++;
}
List.prototype.q = null;
List.prototype.remove = function(v) {
	var prev = null;
	var l = this.h;
	while(l != null) {
		if(l[0] == v) {
			if(prev == null) this.h = l[1];
			else prev[1] = l[1];
			if(this.q == l) this.q = prev;
			this.length--;
			return true;
		}
		prev = l;
		l = l[1];
	}
	return false;
}
List.prototype.toString = function() {
	var s = new StringBuf();
	var first = true;
	var l = this.h;
	s.b[s.b.length] = "{";
	while(l != null) {
		if(first) first = false;
		else s.b[s.b.length] = ", ";
		s.b[s.b.length] = Std.string(l[0]);
		l = l[1];
	}
	s.b[s.b.length] = "}";
	return s.b.join("");
}
List.prototype.__class__ = List;
haxe.Http = function(url) { if( url === $_ ) return; {
	this.url = url;
	this.headers = new Hash();
	this.params = new Hash();
	this.async = true;
}}
haxe.Http.__name__ = ["haxe","Http"];
haxe.Http.requestUrl = function(url) {
	var h = new haxe.Http(url);
	h.async = false;
	var r = null;
	h.onData = function(d) {
		r = d;
	}
	h.onError = function(e) {
		throw e;
	}
	h.request(false);
	return r;
}
haxe.Http.prototype.async = null;
haxe.Http.prototype.headers = null;
haxe.Http.prototype.onData = function(data) {
	null;
}
haxe.Http.prototype.onError = function(msg) {
	null;
}
haxe.Http.prototype.onStatus = function(status) {
	null;
}
haxe.Http.prototype.params = null;
haxe.Http.prototype.postData = null;
haxe.Http.prototype.request = function(post) {
	var me = this;
	var r = new js.XMLHttpRequest();
	var onreadystatechange = function() {
		if(r.readyState != 4) return;
		var s = (function($this) {
			var $r;
			try {
				$r = r.status;
			}
			catch( $e14 ) {
				{
					var e = $e14;
					$r = null;
				}
			}
			return $r;
		}(this));
		if(s == undefined) s = null;
		if(s != null) me.onStatus(s);
		if(s != null && s >= 200 && s < 400) me.onData(r.responseText);
		else switch(s) {
		case null:{
			me.onError("Failed to connect or resolve host");
		}break;
		case 12029:{
			me.onError("Failed to connect to host");
		}break;
		case 12007:{
			me.onError("Unknown host");
		}break;
		default:{
			me.onError("Http Error #" + r.status);
		}break;
		}
	}
	r.onreadystatechange = onreadystatechange;
	var uri = this.postData;
	if(uri != null) post = true;
	else { var $it15 = this.params.keys();
	while( $it15.hasNext() ) { var p = $it15.next();
	{
		if(uri == null) uri = "";
		else uri += "&";
		uri += StringTools.urlDecode(p) + "=" + StringTools.urlEncode(this.params.get(p));
	}
	}}
	try {
		if(post) r.open("POST",this.url,this.async);
		else if(uri != null) {
			var question = this.url.split("?").length <= 1;
			r.open("GET",this.url + ((question?"?":"&")) + uri,this.async);
			uri = null;
		}
		else r.open("GET",this.url,this.async);
	}
	catch( $e16 ) {
		{
			var e = $e16;
			{
				this.onError(e.toString());
				return;
			}
		}
	}
	if(this.headers.get("Content-Type") == null && post && this.postData == null) r.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	{ var $it17 = this.headers.keys();
	while( $it17.hasNext() ) { var h = $it17.next();
	r.setRequestHeader(h,this.headers.get(h));
	}}
	r.send(uri);
	if(!this.async) onreadystatechange();
}
haxe.Http.prototype.setHeader = function(header,value) {
	this.headers.set(header,value);
}
haxe.Http.prototype.setParameter = function(param,value) {
	this.params.set(param,value);
}
haxe.Http.prototype.setPostData = function(data) {
	this.postData = data;
}
haxe.Http.prototype.url = null;
haxe.Http.prototype.__class__ = haxe.Http;
site.cms.modules.base.js.JsKeyValueInput = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsKeyValueInput.__name__ = ["site","cms","modules","base","js","JsKeyValueInput"];
site.cms.modules.base.js.JsKeyValueInput.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsKeyValueInput.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsKeyValueInput.prototype.addKeyValueInput = function(id) {
	var set;
	{ var $it18 = this.keyValueSets.iterator();
	while( $it18.hasNext() ) { var set1 = $it18.next();
	{
		if(set1.id == id) set1.addRow();
	}
	}}
}
site.cms.modules.base.js.JsKeyValueInput.prototype.flushKeyValueInputs = function() {
	var set;
	{ var $it19 = this.keyValueSets.iterator();
	while( $it19.hasNext() ) { var set1 = $it19.next();
	{
		set1.flush();
	}
	}}
	return true;
}
site.cms.modules.base.js.JsKeyValueInput.prototype.keyValueSets = null;
site.cms.modules.base.js.JsKeyValueInput.prototype.main = function() {
	var set;
	{ var $it20 = this.keyValueSets.iterator();
	while( $it20.hasNext() ) { var set1 = $it20.next();
	{
		set1.setup();
	}
	}}
}
site.cms.modules.base.js.JsKeyValueInput.prototype.removeKeyValueInput = function(link) {
	new JQuery(link).parent().parent().parent().remove();
}
site.cms.modules.base.js.JsKeyValueInput.prototype.setupKeyValueInput = function(id,properties,minRows,maxRows) {
	if(maxRows == null) maxRows = 0;
	if(minRows == null) minRows = 0;
	if(this.keyValueSets == null) this.keyValueSets = new List();
	this.keyValueSets.add(new site.cms.modules.base.js.KeyValueSet(id,properties,this,minRows,maxRows));
}
site.cms.modules.base.js.JsKeyValueInput.prototype.__class__ = site.cms.modules.base.js.JsKeyValueInput;
site.cms.modules.base.js.KeyValueSet = function(id,properties,request,minRows,maxRows) { if( id === $_ ) return; {
	if(maxRows == null) maxRows = 0;
	if(minRows == null) minRows = 0;
	this.id = id;
	this.properties = properties;
	this.request = request;
	this.minRows = minRows;
	this.maxRows = maxRows;
	this.currentRows = 0;
}}
site.cms.modules.base.js.KeyValueSet.__name__ = ["site","cms","modules","base","js","KeyValueSet"];
site.cms.modules.base.js.KeyValueSet.prototype.addRow = function(keyValue,valueValue,removeable) {
	if(removeable == null) removeable = true;
	if(valueValue == null) valueValue = "";
	if(keyValue == null) keyValue = "";
	if(this.maxRows > 0 && this.currentRows == this.maxRows) {
		js.Lib.alert("Only " + this.maxRows + " allowed.");
		return false;
	}
	var keyElement = (this.properties.keyIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.keyHeight + "px; width:" + this.properties.keyWidth + "px;"},[keyValue]):JQuery.create("input",{ type : "text", value : keyValue, style : "width:" + this.properties.keyWidth + "px;"},[]));
	var valueElement = (this.properties.valueIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.valueHeight + "px; width:" + this.properties.valueWidth + "px;"},[valueValue]):JQuery.create("input",{ type : "text", value : valueValue, style : "width:" + this.properties.valueWidth + "px;"},[]));
	var d = { src : "./res/cms/delete.png", title : "remove"}
	d["class"] = "qTip";
	var removeElement = (removeable?JQuery.create("a",{ href : "#"},JQuery.create("img",d)):null);
	var _r = this.request;
	if(removeable) {
		removeElement.click(function(e) {
			_r.removeKeyValueInput(e.target);
		});
	}
	new JQuery("#" + this.id + "_keyValueTable tr:last").after(JQuery.create("tr",{ },[JQuery.create("td",{ valign : "top"},[keyElement]),JQuery.create("td",{ valign : "top"},[valueElement]),JQuery.create("td",{ valign : "top"},[removeElement])]));
	this.currentRows++;
	return true;
}
site.cms.modules.base.js.KeyValueSet.prototype.currentRows = null;
site.cms.modules.base.js.KeyValueSet.prototype.flush = function() {
	var data = [];
	new JQuery("#" + this.id + "_keyValueTable tr").each(function($int,html) {
		var items = new JQuery(html).find("td").children("input,textarea");
		if(items.length > 0) {
			data.push({ key : Reflect.field(items[0],"value"), value : Reflect.field(items[1],"value")});
		}
	});
	this.valueHolder.val(haxe.Serializer.run(data));
}
site.cms.modules.base.js.KeyValueSet.prototype.id = null;
site.cms.modules.base.js.KeyValueSet.prototype.maxRows = null;
site.cms.modules.base.js.KeyValueSet.prototype.minRows = null;
site.cms.modules.base.js.KeyValueSet.prototype.properties = null;
site.cms.modules.base.js.KeyValueSet.prototype.request = null;
site.cms.modules.base.js.KeyValueSet.prototype.setup = function() {
	this.valueHolder = new JQuery("#" + this.id);
	this.table = new JQuery("#" + this.id + "_keyValueTable");
	var val = this.valueHolder.val();
	var data = [];
	if(val != "") data = haxe.Unserializer.run(val);
	if(data.length != 0) {
		var remove = false;
		var c = 0;
		{
			var _g = 0;
			while(_g < data.length) {
				var item = data[_g];
				++_g;
				this.addRow(item.key,item.value,remove);
				c++;
				remove = (c >= this.minRows?true:false);
			}
		}
	}
	else {
		this.addRow("","",false);
	}
	if(data.length < this.minRows) {
		{
			var _g1 = 0, _g = this.minRows - data.length;
			while(_g1 < _g) {
				var i = _g1++;
				this.addRow("","",false);
			}
		}
	}
}
site.cms.modules.base.js.KeyValueSet.prototype.table = null;
site.cms.modules.base.js.KeyValueSet.prototype.valueHolder = null;
site.cms.modules.base.js.KeyValueSet.prototype.__class__ = site.cms.modules.base.js.KeyValueSet;
ValueType = { __ename__ : ["ValueType"], __constructs__ : ["TNull","TInt","TFloat","TBool","TObject","TFunction","TClass","TEnum","TUnknown"] }
ValueType.TBool = ["TBool",3];
ValueType.TBool.toString = $estr;
ValueType.TBool.__enum__ = ValueType;
ValueType.TClass = function(c) { var $x = ["TClass",6,c]; $x.__enum__ = ValueType; $x.toString = $estr; return $x; }
ValueType.TEnum = function(e) { var $x = ["TEnum",7,e]; $x.__enum__ = ValueType; $x.toString = $estr; return $x; }
ValueType.TFloat = ["TFloat",2];
ValueType.TFloat.toString = $estr;
ValueType.TFloat.__enum__ = ValueType;
ValueType.TFunction = ["TFunction",5];
ValueType.TFunction.toString = $estr;
ValueType.TFunction.__enum__ = ValueType;
ValueType.TInt = ["TInt",1];
ValueType.TInt.toString = $estr;
ValueType.TInt.__enum__ = ValueType;
ValueType.TNull = ["TNull",0];
ValueType.TNull.toString = $estr;
ValueType.TNull.__enum__ = ValueType;
ValueType.TObject = ["TObject",4];
ValueType.TObject.toString = $estr;
ValueType.TObject.__enum__ = ValueType;
ValueType.TUnknown = ["TUnknown",8];
ValueType.TUnknown.toString = $estr;
ValueType.TUnknown.__enum__ = ValueType;
site.cms.js = {}
site.cms.js.JsTest = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.js.JsTest.__name__ = ["site","cms","js","JsTest"];
site.cms.js.JsTest.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.js.JsTest.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.js.JsTest.prototype.calltest = function(v1,v2,v3) {
	haxe.Log.trace("testies XXXX",{ fileName : "JsTest.hx", lineNumber : 39, className : "site.cms.js.JsTest", methodName : "calltest"});
	haxe.Log.trace(v1,{ fileName : "JsTest.hx", lineNumber : 40, className : "site.cms.js.JsTest", methodName : "calltest"});
	haxe.Log.trace(v2,{ fileName : "JsTest.hx", lineNumber : 41, className : "site.cms.js.JsTest", methodName : "calltest"});
	haxe.Log.trace(v3,{ fileName : "JsTest.hx", lineNumber : 42, className : "site.cms.js.JsTest", methodName : "calltest"});
}
site.cms.js.JsTest.prototype.__class__ = site.cms.js.JsTest;
js = {}
js.Lib = function() { }
js.Lib.__name__ = ["js","Lib"];
js.Lib.isIE = null;
js.Lib.isOpera = null;
js.Lib.document = null;
js.Lib.window = null;
js.Lib.alert = function(v) {
	alert(js.Boot.__string_rec(v,""));
}
js.Lib.eval = function(code) {
	return eval(code);
}
js.Lib.setErrorHandler = function(f) {
	js.Lib.onerror = f;
}
js.Lib.prototype.__class__ = js.Lib;
js.Boot = function() { }
js.Boot.__name__ = ["js","Boot"];
js.Boot.__unhtml = function(s) {
	return s.split("&").join("&amp;").split("<").join("&lt;").split(">").join("&gt;");
}
js.Boot.__trace = function(v,i) {
	var msg = (i != null?i.fileName + ":" + i.lineNumber + ": ":"");
	msg += js.Boot.__unhtml(js.Boot.__string_rec(v,"")) + "<br/>";
	var d = document.getElementById("haxe:trace");
	if(d == null) alert("No haxe:trace element defined\n" + msg);
	else d.innerHTML += msg;
}
js.Boot.__clear_trace = function() {
	var d = document.getElementById("haxe:trace");
	if(d != null) d.innerHTML = "";
	else null;
}
js.Boot.__closure = function(o,f) {
	var m = o[f];
	if(m == null) return null;
	var f1 = function() {
		return m.apply(o,arguments);
	}
	f1.scope = o;
	f1.method = m;
	return f1;
}
js.Boot.__string_rec = function(o,s) {
	if(o == null) return "null";
	if(s.length >= 5) return "<...>";
	var t = typeof(o);
	if(t == "function" && (o.__name__ != null || o.__ename__ != null)) t = "object";
	switch(t) {
	case "object":{
		if(o instanceof Array) {
			if(o.__enum__ != null) {
				if(o.length == 2) return o[0];
				var str = o[0] + "(";
				s += "\t";
				{
					var _g1 = 2, _g = o.length;
					while(_g1 < _g) {
						var i = _g1++;
						if(i != 2) str += "," + js.Boot.__string_rec(o[i],s);
						else str += js.Boot.__string_rec(o[i],s);
					}
				}
				return str + ")";
			}
			var l = o.length;
			var i;
			var str = "[";
			s += "\t";
			{
				var _g = 0;
				while(_g < l) {
					var i1 = _g++;
					str += ((i1 > 0?",":"")) + js.Boot.__string_rec(o[i1],s);
				}
			}
			str += "]";
			return str;
		}
		var tostr;
		try {
			tostr = o.toString;
		}
		catch( $e21 ) {
			{
				var e = $e21;
				{
					return "???";
				}
			}
		}
		if(tostr != null && tostr != Object.toString) {
			var s2 = o.toString();
			if(s2 != "[object Object]") return s2;
		}
		var k = null;
		var str = "{\n";
		s += "\t";
		var hasp = (o.hasOwnProperty != null);
		for( var k in o ) { ;
		if(hasp && !o.hasOwnProperty(k)) continue;
		if(k == "prototype" || k == "__class__" || k == "__super__" || k == "__interfaces__") continue;
		if(str.length != 2) str += ", \n";
		str += s + k + " : " + js.Boot.__string_rec(o[k],s);
		}
		s = s.substring(1);
		str += "\n" + s + "}";
		return str;
	}break;
	case "function":{
		return "<function>";
	}break;
	case "string":{
		return o;
	}break;
	default:{
		return String(o);
	}break;
	}
}
js.Boot.__interfLoop = function(cc,cl) {
	if(cc == null) return false;
	if(cc == cl) return true;
	var intf = cc.__interfaces__;
	if(intf != null) {
		var _g1 = 0, _g = intf.length;
		while(_g1 < _g) {
			var i = _g1++;
			var i1 = intf[i];
			if(i1 == cl || js.Boot.__interfLoop(i1,cl)) return true;
		}
	}
	return js.Boot.__interfLoop(cc.__super__,cl);
}
js.Boot.__instanceof = function(o,cl) {
	try {
		if(o instanceof cl) {
			if(cl == Array) return (o.__enum__ == null);
			return true;
		}
		if(js.Boot.__interfLoop(o.__class__,cl)) return true;
	}
	catch( $e22 ) {
		{
			var e = $e22;
			{
				if(cl == null) return false;
			}
		}
	}
	switch(cl) {
	case Int:{
		return Math.ceil(o%2147483648.0) === o;
	}break;
	case Float:{
		return typeof(o) == "number";
	}break;
	case Bool:{
		return o === true || o === false;
	}break;
	case String:{
		return typeof(o) == "string";
	}break;
	case Dynamic:{
		return true;
	}break;
	default:{
		if(o == null) return false;
		return o.__enum__ == cl || (cl == Class && o.__name__ != null) || (cl == Enum && o.__ename__ != null);
	}break;
	}
}
js.Boot.__init = function() {
	js.Lib.isIE = (document.all != null && window.opera == null);
	js.Lib.isOpera = (window.opera != null);
	Array.prototype.copy = Array.prototype.slice;
	Array.prototype.insert = function(i,x) {
		this.splice(i,0,x);
	}
	Array.prototype.remove = (Array.prototype.indexOf?function(obj) {
		var idx = this.indexOf(obj);
		if(idx == -1) return false;
		this.splice(idx,1);
		return true;
	}:function(obj) {
		var i = 0;
		var l = this.length;
		while(i < l) {
			if(this[i] == obj) {
				this.splice(i,1);
				return true;
			}
			i++;
		}
		return false;
	});
	Array.prototype.iterator = function() {
		return { cur : 0, arr : this, hasNext : function() {
			return this.cur < this.arr.length;
		}, next : function() {
			return this.arr[this.cur++];
		}}
	}
	var cca = String.prototype.charCodeAt;
	String.prototype.cca = cca;
	String.prototype.charCodeAt = function(i) {
		var x = cca.call(this,i);
		if(isNaN(x)) return null;
		return x;
	}
	var oldsub = String.prototype.substr;
	String.prototype.substr = function(pos,len) {
		if(pos != null && pos != 0 && len != null && len < 0) return "";
		if(len == null) len = this.length;
		if(pos < 0) {
			pos = this.length + pos;
			if(pos < 0) pos = 0;
		}
		else if(len < 0) {
			len = this.length + len - pos;
		}
		return oldsub.apply(this,[pos,len]);
	}
	$closure = js.Boot.__closure;
}
js.Boot.prototype.__class__ = js.Boot;
IntHash = function(p) { if( p === $_ ) return; {
	this.h = {}
	if(this.h.__proto__ != null) {
		this.h.__proto__ = null;
		delete(this.h.__proto__);
	}
	else null;
}}
IntHash.__name__ = ["IntHash"];
IntHash.prototype.exists = function(key) {
	return this.h[key] != null;
}
IntHash.prototype.get = function(key) {
	return this.h[key];
}
IntHash.prototype.h = null;
IntHash.prototype.iterator = function() {
	return { ref : this.h, it : this.keys(), hasNext : function() {
		return this.it.hasNext();
	}, next : function() {
		var i = this.it.next();
		return this.ref[i];
	}}
}
IntHash.prototype.keys = function() {
	var a = new Array();
	
			for( x in this.h )
				a.push(x);
		;
	return a.iterator();
}
IntHash.prototype.remove = function(key) {
	if(this.h[key] == null) return false;
	delete(this.h[key]);
	return true;
}
IntHash.prototype.set = function(key,value) {
	this.h[key] = value;
}
IntHash.prototype.toString = function() {
	var s = new StringBuf();
	s.b[s.b.length] = "{";
	var it = this.keys();
	{ var $it23 = it;
	while( $it23.hasNext() ) { var i = $it23.next();
	{
		s.b[s.b.length] = i;
		s.b[s.b.length] = " => ";
		s.b[s.b.length] = Std.string(this.get(i));
		if(it.hasNext()) s.b[s.b.length] = ", ";
	}
	}}
	s.b[s.b.length] = "}";
	return s.b.join("");
}
IntHash.prototype.__class__ = IntHash;
site.cms.modules.base.js.JsDefinitionElement = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsDefinitionElement.__name__ = ["site","cms","modules","base","js","JsDefinitionElement"];
site.cms.modules.base.js.JsDefinitionElement.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsDefinitionElement.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsDefinitionElement.prototype.assocSelectBox1 = null;
site.cms.modules.base.js.JsDefinitionElement.prototype.assocSelectBox2 = null;
site.cms.modules.base.js.JsDefinitionElement.prototype.assocSelectBox3 = null;
site.cms.modules.base.js.JsDefinitionElement.prototype.hideAllElements = function() {
	new JQuery("select[id^='form_def_'],input[id^='form_def_']").parent().parent().css("display","none");
	new JQuery("fieldset[id^='form_def_']").css("display","none");
}
site.cms.modules.base.js.JsDefinitionElement.prototype.main = function() {
	this.types = ["text","number","bool","image","richtext","date","association","multilink","keyvalue","read-only","order","link","hidden"];
	var types = this.types;
	var ths = this;
	var typeSelector = js.Lib.document.getElementById("form_type");
	this.hideAllElements();
	if(typeSelector.value != "") ths.showElements(typeSelector.value);
	typeSelector.onchange = function(e) {
		ths.hideAllElements();
		ths.showElements(typeSelector.value);
	}
}
site.cms.modules.base.js.JsDefinitionElement.prototype.onAssocDataLoaded = function(response) {
	this.assocSelectBox2.innerHTML = "";
	this.assocSelectBox3.innerHTML = "";
	{
		var _g = 0;
		while(_g < response.length) {
			var item = response[_g];
			++_g;
			this.assocSelectBox2.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
			this.assocSelectBox3.innerHTML += "<option value=\"" + item + "\">" + item + "</option>";
		}
	}
	this.assocSelectBox3.selectedIndex = 1;
	new JQuery("button").attr("disabled",false);
}
site.cms.modules.base.js.JsDefinitionElement.prototype.onChangeSelectbox = function(selectBox) {
	switch(selectBox.name) {
	case "form_def_association_table":{
		this.assocSelectBox1 = js.Lib.document.getElementById("form_def_association_table");
		this.assocSelectBox2 = js.Lib.document.getElementById("form_def_association_field");
		this.assocSelectBox3 = js.Lib.document.getElementById("form_def_association_fieldLabel");
	}break;
	case "form_def_multilink_table":{
		this.assocSelectBox1 = js.Lib.document.getElementById("form_def_multilink_table");
		this.assocSelectBox2 = js.Lib.document.getElementById("form_def_multilink_field");
		this.assocSelectBox3 = js.Lib.document.getElementById("form_def_multilink_fieldLabel");
	}break;
	case "form_def_multilink_link":{
		this.assocSelectBox1 = js.Lib.document.getElementById("form_def_multilink_link");
		this.assocSelectBox2 = js.Lib.document.getElementById("form_def_multilink_linkField1");
		this.assocSelectBox3 = js.Lib.document.getElementById("form_def_multilink_linkField2");
	}break;
	}
	{
		var _g1 = 0, _g = this.assocSelectBox2.options.length;
		while(_g1 < _g) {
			var i = _g1++;
			this.assocSelectBox2.remove(0);
		}
	}
	{
		var _g1 = 0, _g = this.assocSelectBox3.options.length;
		while(_g1 < _g) {
			var i = _g1++;
			this.assocSelectBox3.remove(0);
		}
	}
	this.assocSelectBox2.innerHTML += "<option>-- loading --</option>";
	this.assocSelectBox3.innerHTML += "<option>-- loading --</option>";
	new JQuery("button").attr("disabled",true);
	this.remoting.resolve("api").resolve("getListData").call([this.assocSelectBox1.value],$closure(this,"onAssocDataLoaded"));
}
site.cms.modules.base.js.JsDefinitionElement.prototype.showElements = function(field) {
	if(js.Lib.isIE) {
		new JQuery("[id^='form_def_" + field + "']").parent().parent().css("display","block");
	}
	else {
		new JQuery("[id^='form_def_" + field + "']").parent().parent().css("display","table-row");
	}
	new JQuery("fieldset[id^='form_def_" + field + "']").css("display","block");
}
site.cms.modules.base.js.JsDefinitionElement.prototype.types = null;
site.cms.modules.base.js.JsDefinitionElement.prototype.__class__ = site.cms.modules.base.js.JsDefinitionElement;
site.cms.modules.media = {}
site.cms.modules.media.js = {}
site.cms.modules.media.js.JsGallery = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.media.js.JsGallery.__name__ = ["site","cms","modules","media","js","JsGallery"];
site.cms.modules.media.js.JsGallery.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.media.js.JsGallery.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.media.js.JsGallery.prototype.cancelUploads = function() {
	new JQuery("#uploadify").uploadifyClearQueue();
	this.updateContent();
}
site.cms.modules.media.js.JsGallery.prototype["delete"] = function(el,file) {
	el.parentNode.removeChild(el);
	this.remoting.resolve("api").resolve("deleteItem").call([file]);
}
site.cms.modules.media.js.JsGallery.prototype.gallery = null;
site.cms.modules.media.js.JsGallery.prototype.getPreview = function(item) {
	var ext = item.substr(item.lastIndexOf(".") + 1);
	return (function($this) {
		var $r;
		switch(ext.toUpperCase()) {
		case "JPG":case "GIF":case "PNG":{
			$r = JQuery.create("img",{ src : "?request=services.Image&preset=thumb&src=../media/galleries/" + $this.gallery + "/" + item});
		}break;
		default:{
			$r = null;
		}break;
		}
		return $r;
	}(this));
}
site.cms.modules.media.js.JsGallery.prototype.main = function() {
	this.gallery = this.application.params.get("name");
	new JQuery("#uploadify").uploadify({ uploader : "res/cms/media/uploadify.swf", script : "res/cms/media/uploadify.php", cancelImg : "res/cms/media/cancel.png", auto : true, folder : "./res/media/galleries/" + this.gallery, multi : true, fileExt : "*.jpg;*.gif;*.png", onAllComplete : $closure(this,"updateContent")});
	this.updateContent();
}
site.cms.modules.media.js.JsGallery.prototype.onContent = function(content) {
	var container = new JQuery("#imageContent");
	container.html("");
	{ var $it24 = content.iterator();
	while( $it24.hasNext() ) { var $t1 = $it24.next();
	{
		var item = [$t1];
		var el = this.getPreview(item[0]);
		if(el != null) {
			var atts = { }
			atts.imageName = item[0];
			atts["class"] = "galleryItem";
			var div = [JQuery.create("div",atts,[])];
			atts = { }
			atts["class"] = "gallerItemDelete";
			atts.src = "res/cms/delete.png";
			var ths = [this];
			var del = JQuery.create("a",{ href : "#"},[JQuery.create("img",atts)]);
			del.click(function(ths,item,div) {
				return function(e) {
					ths[0]["delete"](div[0][0],item[0]);
				}
			}(ths,item,div));
			div[0].append(el);
			div[0].append(del);
			container.append(div[0]);
		}
	}
	}}
}
site.cms.modules.media.js.JsGallery.prototype.updateContent = function() {
	this.remoting.resolve("api").resolve("getContent").call([],$closure(this,"onContent"));
}
site.cms.modules.media.js.JsGallery.prototype.__class__ = site.cms.modules.media.js.JsGallery;
poko.js.JsBinding = function(p) { if( p === $_ ) return; {
	null;
}}
poko.js.JsBinding.__name__ = ["poko","js","JsBinding"];
poko.js.JsBinding.prototype.jsRequest = null;
poko.js.JsBinding.prototype.__class__ = poko.js.JsBinding;
Hash = function(p) { if( p === $_ ) return; {
	this.h = {}
	if(this.h.__proto__ != null) {
		this.h.__proto__ = null;
		delete(this.h.__proto__);
	}
	else null;
}}
Hash.__name__ = ["Hash"];
Hash.prototype.exists = function(key) {
	try {
		key = "$" + key;
		return this.hasOwnProperty.call(this.h,key);
	}
	catch( $e25 ) {
		{
			var e = $e25;
			{
				
				for(var i in this.h)
					if( i == key ) return true;
			;
				return false;
			}
		}
	}
}
Hash.prototype.get = function(key) {
	return this.h["$" + key];
}
Hash.prototype.h = null;
Hash.prototype.iterator = function() {
	return { ref : this.h, it : this.keys(), hasNext : function() {
		return this.it.hasNext();
	}, next : function() {
		var i = this.it.next();
		return this.ref["$" + i];
	}}
}
Hash.prototype.keys = function() {
	var a = new Array();
	
			for(var i in this.h)
				a.push(i.substr(1));
		;
	return a.iterator();
}
Hash.prototype.remove = function(key) {
	if(!this.exists(key)) return false;
	delete(this.h["$" + key]);
	return true;
}
Hash.prototype.set = function(key,value) {
	this.h["$" + key] = value;
}
Hash.prototype.toString = function() {
	var s = new StringBuf();
	s.b[s.b.length] = "{";
	var it = this.keys();
	{ var $it26 = it;
	while( $it26.hasNext() ) { var i = $it26.next();
	{
		s.b[s.b.length] = i;
		s.b[s.b.length] = " => ";
		s.b[s.b.length] = Std.string(this.get(i));
		if(it.hasNext()) s.b[s.b.length] = ", ";
	}
	}}
	s.b[s.b.length] = "}";
	return s.b.join("");
}
Hash.prototype.__class__ = Hash;
site.cms.modules.base.js.JsFileUpload = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsFileUpload.__name__ = ["site","cms","modules","base","js","JsFileUpload"];
site.cms.modules.base.js.JsFileUpload.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsFileUpload.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsFileUpload.prototype.deleteFile = function(file,display) {
	var _t = this;
	var d = new JQuery("#" + display);
	d.fadeTo(0,.2);
	var img = d.find("a:last img")[0];
	img.src = "./res/cms/add.png";
	d.find("a:last")[0].onclick = function(e) {
		e.preventDefault();
		_t.undeleteFile(file,display);
	}
	site.cms.modules.base.js.JsFileUpload.filesToDelete.set(file,display.substr(18));
	this.update();
	return false;
}
site.cms.modules.base.js.JsFileUpload.prototype.main = function() {
	null;
}
site.cms.modules.base.js.JsFileUpload.prototype.onResponse = function(data) {
	if(data.success) {
		new JQuery("#" + data.display).html("<p>deleted</p>");
	}
	else {
		new JQuery("#" + data.display).html("<p>ERROR: " + data.error + "</p>");
	}
}
site.cms.modules.base.js.JsFileUpload.prototype.undeleteFile = function(file,display) {
	var _t = this;
	var d = new JQuery("#" + display);
	d.fadeTo(0,1);
	var img = d.find("a:last img")[0];
	img.src = "./res/cms/delete.png";
	d.find("a:last")[0].onclick = function(e) {
		e.preventDefault();
		_t.deleteFile(file,display);
	}
	site.cms.modules.base.js.JsFileUpload.filesToDelete.remove(file);
	this.update();
	return false;
}
site.cms.modules.base.js.JsFileUpload.prototype.update = function() {
	var h;
	h = new JQuery("#form1__filesToDelete");
	if(h.length == 0) {
		var f = new JQuery("#form1___submit");
		var e = JQuery.create("input",{ type : "hidden", name : "form1__filesToDelete", id : "form1__filesToDelete"});
		f.before(e);
		h = new JQuery("#form1__filesToDelete");
	}
	h.val(haxe.Serializer.run(site.cms.modules.base.js.JsFileUpload.filesToDelete));
}
site.cms.modules.base.js.JsFileUpload.prototype.__class__ = site.cms.modules.base.js.JsFileUpload;
site.cms.js.JsCommon = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.js.JsCommon.__name__ = ["site","cms","js","JsCommon"];
site.cms.js.JsCommon.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.js.JsCommon.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.js.JsCommon.prototype.__class__ = site.cms.js.JsCommon;
site.cms.modules.base.js.JsDefinition = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsDefinition.__name__ = ["site","cms","modules","base","js","JsDefinition"];
site.cms.modules.base.js.JsDefinition.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsDefinition.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsDefinition.prototype.main = function() {
	null;
}
site.cms.modules.base.js.JsDefinition.prototype.onResponse = function(data) {
	var el = js.Lib.document.getElementById("checkboxToggle_" + data.type + "_" + data.index);
	el.innerHTML = (data.value?"&#x2714;":"&#x02610;");
}
site.cms.modules.base.js.JsDefinition.prototype.toggleCheckbox = function(field,index,type) {
	this.remoting.resolve("api").resolve("toggleCheckbox").call([field,index,type],$closure(this,"onResponse"));
}
site.cms.modules.base.js.JsDefinition.prototype.__class__ = site.cms.modules.base.js.JsDefinition;
$Main = function() { }
$Main.__name__ = ["@Main"];
$Main.prototype.__class__ = $Main;
$_ = {}
js.Boot.__res = {}
js.Boot.__init();
{
	var JQuery = window.jQuery;
}
{
	Date.now = function() {
		return new Date();
	}
	Date.fromTime = function(t) {
		var d = new Date();
		d["setTime"](t);
		return d;
	}
	Date.fromString = function(s) {
		switch(s.length) {
		case 8:{
			var k = s.split(":");
			var d = new Date();
			d["setTime"](0);
			d["setUTCHours"](k[0]);
			d["setUTCMinutes"](k[1]);
			d["setUTCSeconds"](k[2]);
			return d;
		}break;
		case 10:{
			var k = s.split("-");
			return new Date(k[0],k[1] - 1,k[2],0,0,0);
		}break;
		case 19:{
			var k = s.split(" ");
			var y = k[0].split("-");
			var t = k[1].split(":");
			return new Date(y[0],y[1] - 1,y[2],t[0],t[1],t[2]);
		}break;
		default:{
			throw "Invalid date format : " + s;
		}break;
		}
	}
	Date.prototype["toString"] = function() {
		var date = this;
		var m = date.getMonth() + 1;
		var d = date.getDate();
		var h = date.getHours();
		var mi = date.getMinutes();
		var s = date.getSeconds();
		return date.getFullYear() + "-" + ((m < 10?"0" + m:"" + m)) + "-" + ((d < 10?"0" + d:"" + d)) + " " + ((h < 10?"0" + h:"" + h)) + ":" + ((mi < 10?"0" + mi:"" + mi)) + ":" + ((s < 10?"0" + s:"" + s));
	}
	Date.prototype.__class__ = Date;
	Date.__name__ = ["Date"];
}
{
	String.prototype.__class__ = String;
	String.__name__ = ["String"];
	Array.prototype.__class__ = Array;
	Array.__name__ = ["Array"];
	Int = { __name__ : ["Int"]}
	Dynamic = { __name__ : ["Dynamic"]}
	Float = Number;
	Float.__name__ = ["Float"];
	Bool = { __ename__ : ["Bool"]}
	Class = { __name__ : ["Class"]}
	Enum = { }
	Void = { __ename__ : ["Void"]}
}
{
	Math.NaN = Number["NaN"];
	Math.NEGATIVE_INFINITY = Number["NEGATIVE_INFINITY"];
	Math.POSITIVE_INFINITY = Number["POSITIVE_INFINITY"];
	Math.isFinite = function(i) {
		return isFinite(i);
	}
	Math.isNaN = function(i) {
		return isNaN(i);
	}
	Math.__name__ = ["Math"];
}
{
	js.Lib.document = document;
	js.Lib.window = window;
	onerror = function(msg,url,line) {
		var f = js.Lib.onerror;
		if( f == null )
			return false;
		return f(msg,[url+":"+line]);
	}
}
{
	js["XMLHttpRequest"] = (window.XMLHttpRequest?XMLHttpRequest:(window.ActiveXObject?function() {
		try {
			return new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch( $e27 ) {
			{
				var e = $e27;
				{
					try {
						return new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch( $e28 ) {
						{
							var e1 = $e28;
							{
								throw "Unable to create XMLHttpRequest object.";
							}
						}
					}
				}
			}
		}
	}:(function($this) {
		var $r;
		throw "Unable to create XMLHttpRequest object.";
		return $r;
	}(this))));
}
haxe.Unserializer.DEFAULT_RESOLVER = Type;
haxe.Unserializer.BASE64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789%:";
haxe.Unserializer.CODES = null;
haxe.Serializer.USE_CACHE = false;
haxe.Serializer.USE_ENUM_INDEX = false;
haxe.Serializer.BASE64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789%:";
js.Lib.onerror = null;
site.cms.modules.base.js.JsFileUpload.filesToDelete = new Hash();
$Main.init = MainJS.main();
