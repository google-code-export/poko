$estr = function() { return js.Boot.__string_rec(this,''); }
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
			if(Std["is"](field,poko.utils.JsBinding)) a[f] = this.application.resolveRequest(field.jsRequest);
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
site = {}
site.cms = {}
site.cms.modules = {}
site.cms.modules.base = {}
site.cms.modules.base.js = {}
site.cms.modules.base.js.JsDatasetItem = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.modules.base.js.JsDatasetItem.__name__ = ["site","cms","modules","base","js","JsDatasetItem"];
site.cms.modules.base.js.JsDatasetItem.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.modules.base.js.JsDatasetItem.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.modules.base.js.JsDatasetItem.prototype.addKeyValueInput = function(keyValue,valueValue,removeable) {
	if(removeable == null) removeable = true;
	if(valueValue == null) valueValue = "";
	if(keyValue == null) keyValue = "";
	var keyElement = (this.properties.keyIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.keyHeight + "px; width:" + this.properties.keyWidth + "px;"},[keyValue]):JQuery.create("input",{ type : "text", value : keyValue, style : "width:" + this.properties.keyWidth + "px;"},[]));
	var valueElement = (this.properties.valueIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.valueHeight + "px; width:" + this.properties.valueWidth + "px;"},[valueValue]):JQuery.create("input",{ type : "text", value : valueValue, style : "width:" + this.properties.valueWidth + "px;"},[]));
	var removeElement = (removeable?JQuery.create("a",{ href : "#", onclick : this.getRawCall("removeKeyValueInput(this)") + "; return(false);"},"remove"):null);
	new JQuery("#" + this.id + "_keyValueTable tr:last").after(JQuery.create("tr",{ },[JQuery.create("td",{ valign : "top"},[keyElement]),JQuery.create("td",{ valign : "top"},[valueElement]),JQuery.create("td",{ valign : "top"},[removeElement])]));
}
site.cms.modules.base.js.JsDatasetItem.prototype.flushKeyValueInputs = function() {
	var data = [];
	new JQuery("#" + this.id + "_keyValueTable tr").each(function($int,html) {
		var items = new JQuery(html).find("td").children("input,textarea");
		if(items.length > 0) {
			data.push({ key : Reflect.field(items[0],"value"), value : Reflect.field(items[1],"value")});
		}
	});
	this.valueHolder.val(haxe.Serializer.run(data));
	return (true);
}
site.cms.modules.base.js.JsDatasetItem.prototype.id = null;
site.cms.modules.base.js.JsDatasetItem.prototype.main = function() {
	null;
}
site.cms.modules.base.js.JsDatasetItem.prototype.properties = null;
site.cms.modules.base.js.JsDatasetItem.prototype.removeKeyValueInput = function(link) {
	new JQuery(link).parent().parent().remove();
}
site.cms.modules.base.js.JsDatasetItem.prototype.setupKeyValueInput = function(id,properties) {
	this.id = id;
	this.properties = properties;
	this.valueHolder = new JQuery("#" + id);
	this.table = new JQuery("#" + id + "_keyValueTable");
	var val = this.valueHolder.val();
	var data = [];
	if(val != "") data = haxe.Unserializer.run(val);
	if(data.length != 0) {
		var remove = false;
		{
			var _g = 0;
			while(_g < data.length) {
				var item = data[_g];
				++_g;
				this.addKeyValueInput(item.key,item.value,remove);
				remove = true;
			}
		}
	}
	else {
		this.addKeyValueInput("","",false);
	}
}
site.cms.modules.base.js.JsDatasetItem.prototype.table = null;
site.cms.modules.base.js.JsDatasetItem.prototype.valueHolder = null;
site.cms.modules.base.js.JsDatasetItem.prototype.__class__ = site.cms.modules.base.js.JsDatasetItem;
haxe = {}
haxe.io = {}
haxe.io.BytesBuffer = function(p) { if( p === $_ ) return; {
	this.b = new Array();
}}
haxe.io.BytesBuffer.__name__ = ["haxe","io","BytesBuffer"];
haxe.io.BytesBuffer.prototype.add = function(src) {
	var b1 = this.b;
	var b2 = src.b;
	{
		var _g1 = 0, _g = src.length;
		while(_g1 < _g) {
			var i = _g1++;
			this.b.push(b2[i]);
		}
	}
}
haxe.io.BytesBuffer.prototype.addByte = function($byte) {
	this.b.push($byte);
}
haxe.io.BytesBuffer.prototype.addBytes = function(src,pos,len) {
	if(pos < 0 || len < 0 || pos + len > src.length) throw haxe.io.Error.OutsideBounds;
	var b1 = this.b;
	var b2 = src.b;
	{
		var _g1 = pos, _g = pos + len;
		while(_g1 < _g) {
			var i = _g1++;
			this.b.push(b2[i]);
		}
	}
}
haxe.io.BytesBuffer.prototype.b = null;
haxe.io.BytesBuffer.prototype.getBytes = function() {
	var bytes = new haxe.io.Bytes(this.b.length,this.b);
	this.b = null;
	return bytes;
}
haxe.io.BytesBuffer.prototype.__class__ = haxe.io.BytesBuffer;
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
	{ var $it0 = arr.iterator();
	while( $it0.hasNext() ) { var t = $it0.next();
	if(t == field) return true;
	}}
	return false;
}
Reflect.field = function(o,field) {
	var v = null;
	try {
		v = o[field];
	}
	catch( $e1 ) {
		{
			var e = $e1;
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
		catch( $e2 ) {
			{
				var e = $e2;
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
		}
		var resetButton = js.Lib.document.getElementById("options_resetButton");
		resetButton.onclick = function(e) {
			js.Lib.document.getElementById("options_filterBy").selectedIndex = 0;
			js.Lib.document.getElementById("options_orderBy").selectedIndex = 0;
			resetButton.form.submit();
		}
	}
}
site.cms.modules.base.js.JsDataset.prototype.onGetFilterInfo = function(response) {
	this.hideFilterOptions();
	if(response.type == "association") {
		js.Lib.document.getElementById("filter_assoc").style.display = "inline";
		var select = js.Lib.document.getElementById("options_filterByAssoc");
		var options = response.data;
		select.style.display = "block";
		select.innerHTML = "<option value=\"\" >- select -</option>";
		{ var $it3 = options.keys();
		while( $it3.hasNext() ) { var option = $it3.next();
		select.innerHTML += "<option value=\"" + option + "\">" + options.get(option) + "</option>";
		}}
	}
	else {
		if(js.Lib.document.getElementById("filter_normal") != null) js.Lib.document.getElementById("filter_normal").style.display = "inline";
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
	{ var $it4 = this.requests.iterator();
	while( $it4.hasNext() ) { var req = $it4.next();
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
	this.b[pos] = v;
}
haxe.io.Bytes.prototype.sub = function(pos,len) {
	if(pos < 0 || len < 0 || pos + len > this.length) throw haxe.io.Error.OutsideBounds;
	return new haxe.io.Bytes(len,this.b.slice(pos,pos + len));
}
haxe.io.Bytes.prototype.toString = function() {
	return this.readString(0,this.length);
}
haxe.io.Bytes.prototype.__class__ = haxe.io.Bytes;
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
	var error = $closure(this.__data,"error");
	h.onData = function(response) {
		var ok = true;
		var ret;
		try {
			if(response.substr(0,3) != "hxr") throw "Invalid response : '" + response + "'";
			var s1 = new haxe.Unserializer(response.substr(3));
			ret = s1.unserialize();
		}
		catch( $e5 ) {
			{
				var err = $e5;
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
	catch( $e6 ) {
		{
			var e = $e6;
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
	catch( $e7 ) {
		{
			var err = $e7;
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
		var b = new haxe.io.BytesBuffer();
		var i = this.pos;
		var rest = len & 3;
		var max = i + (len - rest);
		while(i < max) {
			var c1 = codes[buf.cca(i++)];
			var c2 = codes[buf.cca(i++)];
			b.b.push((c1 << 2) | (c2 >> 4));
			var c3 = codes[buf.cca(i++)];
			b.b.push(((c2 << 4) | (c3 >> 2)) & 255);
			var c4 = codes[buf.cca(i++)];
			b.b.push(((c3 << 6) | c4) & 255);
		}
		if(rest >= 2) {
			var c1 = codes[buf.cca(i++)];
			var c2 = codes[buf.cca(i++)];
			b.b.push((c1 << 2) | (c2 >> 4));
			if(rest == 3) {
				var c3 = codes[buf.cca(i++)];
				b.b.push(((c2 << 4) | (c3 >> 2)) & 255);
			}
		}
		var bytes = b.getBytes();
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
			{ var $it8 = v1.iterator();
			while( $it8.hasNext() ) { var i = $it8.next();
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
			{ var $it9 = v1.keys();
			while( $it9.hasNext() ) { var k = $it9.next();
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
			{ var $it10 = v1.keys();
			while( $it10.hasNext() ) { var k = $it10.next();
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
		s.b[s.b.length] = l[0];
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
haxe.Http.request = function(url) {
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
		var s = function($this) {
			var $r;
			try {
				$r = r.status;
			}
			catch( $e11 ) {
				{
					var e = $e11;
					$r = null;
				}
			}
			return $r;
		}(this);
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
	else { var $it12 = this.params.keys();
	while( $it12.hasNext() ) { var p = $it12.next();
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
	catch( $e13 ) {
		{
			var e = $e13;
			{
				this.onError(e.toString());
				return;
			}
		}
	}
	if(this.headers.get("Content-Type") == null && post && this.postData == null) r.setRequestHeader("Content-Type","application/x-www-form-urlencoded");
	{ var $it14 = this.headers.keys();
	while( $it14.hasNext() ) { var h = $it14.next();
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
site.cms.modules.base.js.JsKeyValueInput.prototype.addKeyValueInput = function(keyValue,valueValue,removeable) {
	if(removeable == null) removeable = true;
	if(valueValue == null) valueValue = "";
	if(keyValue == null) keyValue = "";
	var keyElement = (this.properties.keyIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.keyHeight + "px; width:" + this.properties.keyWidth + "px;"},[keyValue]):JQuery.create("input",{ type : "text", value : keyValue, style : "width:" + this.properties.keyWidth + "px;"},[]));
	var valueElement = (this.properties.valueIsMultiline == "1"?JQuery.create("textarea",{ style : "height:" + this.properties.valueHeight + "px; width:" + this.properties.valueWidth + "px;"},[valueValue]):JQuery.create("input",{ type : "text", value : valueValue, style : "width:" + this.properties.valueWidth + "px;"},[]));
	var removeElement = (removeable?JQuery.create("a",{ href : "#", onclick : this.getRawCall("removeKeyValueInput(this)") + "; return(false);"},"remove"):null);
	new JQuery("#" + this.id + "_keyValueTable tr:last").after(JQuery.create("tr",{ },[JQuery.create("td",{ valign : "top"},[keyElement]),JQuery.create("td",{ valign : "top"},[valueElement]),JQuery.create("td",{ valign : "top"},[removeElement])]));
}
site.cms.modules.base.js.JsKeyValueInput.prototype.flushKeyValueInputs = function() {
	var data = [];
	new JQuery("#" + this.id + "_keyValueTable tr").each(function($int,html) {
		var items = new JQuery(html).find("td").children("input,textarea");
		if(items.length > 0) {
			data.push({ key : Reflect.field(items[0],"value"), value : Reflect.field(items[1],"value")});
		}
	});
	this.valueHolder.val(haxe.Serializer.run(data));
	return (true);
}
site.cms.modules.base.js.JsKeyValueInput.prototype.id = null;
site.cms.modules.base.js.JsKeyValueInput.prototype.main = function() {
	null;
}
site.cms.modules.base.js.JsKeyValueInput.prototype.properties = null;
site.cms.modules.base.js.JsKeyValueInput.prototype.removeKeyValueInput = function(link) {
	new JQuery(link).parent().parent().remove();
}
site.cms.modules.base.js.JsKeyValueInput.prototype.setupKeyValueInput = function(id,properties) {
	this.id = id;
	this.properties = properties;
	this.valueHolder = new JQuery("#" + id);
	this.table = new JQuery("#" + id + "_keyValueTable");
	var val = this.valueHolder.val();
	var data = [];
	if(val != "") data = haxe.Unserializer.run(val);
	if(data.length != 0) {
		var remove = false;
		{
			var _g = 0;
			while(_g < data.length) {
				var item = data[_g];
				++_g;
				this.addKeyValueInput(item.key,item.value,remove);
				remove = true;
			}
		}
	}
	else {
		this.addKeyValueInput("","",false);
	}
}
site.cms.modules.base.js.JsKeyValueInput.prototype.table = null;
site.cms.modules.base.js.JsKeyValueInput.prototype.valueHolder = null;
site.cms.modules.base.js.JsKeyValueInput.prototype.__class__ = site.cms.modules.base.js.JsKeyValueInput;
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
		catch( $e15 ) {
			{
				var e = $e15;
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
	catch( $e16 ) {
		{
			var e = $e16;
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
	{ var $it17 = it;
	while( $it17.hasNext() ) { var i = $it17.next();
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
poko.utils = {}
poko.utils.JsBinding = function(p) { if( p === $_ ) return; {
	null;
}}
poko.utils.JsBinding.__name__ = ["poko","utils","JsBinding"];
poko.utils.JsBinding.prototype.jsRequest = null;
poko.utils.JsBinding.prototype.__class__ = poko.utils.JsBinding;
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
site.cms.js.JsCommon = function(p) { if( p === $_ ) return; {
	poko.js.JsRequest.apply(this,[]);
}}
site.cms.js.JsCommon.__name__ = ["site","cms","js","JsCommon"];
site.cms.js.JsCommon.__super__ = poko.js.JsRequest;
for(var k in poko.js.JsRequest.prototype ) site.cms.js.JsCommon.prototype[k] = poko.js.JsRequest.prototype[k];
site.cms.js.JsCommon.prototype.__class__ = site.cms.js.JsCommon;
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
	catch( $e18 ) {
		{
			var e = $e18;
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
	{ var $it19 = it;
	while( $it19.hasNext() ) { var i = $it19.next();
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
		catch( $e20 ) {
			{
				var e = $e20;
				{
					try {
						return new ActiveXObject("Microsoft.XMLHTTP");
					}
					catch( $e21 ) {
						{
							var e1 = $e21;
							{
								throw "Unable to create XMLHttpRequest object.";
							}
						}
					}
				}
			}
		}
	}:function($this) {
		var $r;
		throw "Unable to create XMLHttpRequest object.";
		return $r;
	}(this)));
}
haxe.Unserializer.DEFAULT_RESOLVER = Type;
haxe.Unserializer.BASE64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789%:";
haxe.Unserializer.CODES = null;
haxe.Serializer.USE_CACHE = false;
haxe.Serializer.USE_ENUM_INDEX = false;
haxe.Serializer.BASE64 = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789%:";
js.Lib.onerror = null;
$Main.init = MainJS.main();
