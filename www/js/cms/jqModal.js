/*
 * jqModal - Minimalist Modaling with jQuery
 *   (http://dev.iceburg.net/jquery/jqModal/)
 *
 * Copyright (c) 2007,2008 Brice Burgess <bhb@iceburg.net>
 * Dual licensed under the MIT and GPL licenses:
 *   http://www.opensource.org/licenses/mit-license.php
 *   http://www.gnu.org/licenses/gpl.html
 * 
 * $Version: 03/01/2009 +r14
 */
(function($) {
$.fn.jqm=function(o){
var p={
overlay: 50,
overlayClass: 'jqmOverlay',
closeClass: 'jqmClose',
trigger: '.jqModal',
ajax: F,
ajaxText: '',
target: F,
modal: F,
toTop: F,
onShow: F,
onHide: F,
onLoad: F
};
return this.each(function(){if(this._jqm)return H[this._jqm].c=$.extend({},H[this._jqm].c,o);s++;this._jqm=s;
H[s]={c:$.extend(p,$.jqm.params,o),a:F,w:$(this).addClass('jqmID'+s),s:s};
if(p.trigger)$(this).jqmAddTrigger(p.trigger);
});};

$.fn.jqmAddClose=function(e){return hs(this,e,'jqmHide');};
$.fn.jqmAddTrigger=function(e){return hs(this,e,'jqmShow');};
$.fn.jqmShow=function(t){return this.each(function(){t=t||window.event;$.jqm.open(this._jqm,t);});};
$.fn.jqmHide=function(t){return this.each(function(){t=t||window.event;$.jqm.close(this._jqm,t)});};

$.jqm = {
hash:{},
open:function(s,t){var h=H[s],c=h.c,cc='.'+c.closeClass,z=(parseInt(h.w.css('z-index'))),z=(z>0)?z:3000,o=$('<div></div>').css({height:'100%',width:'100%',position:'fixed',left:0,top:0,'z-index':z-1,opacity:c.overlay/100});if(h.a)return F;h.t=t;h.a=true;h.w.css('z-index',z);
 if(c.modal) {if(!A[0])L('bind');A.push(s);}
 else if(c.overlay > 0)h.w.jqmAddClose(o);
 else o=F;

 h.o=(o)?o.addClass(c.overlayClass).prependTo('body'):F;
 if(ie6){$('html,body').css({height:'100%',width:'100%'});if(o){o=o.css({position:'absolute'})[0];for(var y in {Top:1,Left:1})o.style.setExpression(y.toLowerCase(),"(_=(document.documentElement.scroll"+y+" || document.body.scroll"+y+"))+'px'");}}

 if(c.ajax) {var r=c.target||h.w,u=c.ajax,r=(typeof r == 'string')?$(r,h.w):$(r),u=(u.substr(0,1) == '@')?$(t).attr(u.substring(1)):u;
  r.html(c.ajaxText).load(u,function(){if(c.onLoad)c.onLoad.call(this,h);if(cc)h.w.jqmAddClose($(cc,h.w));e(h);});}
 else if(cc)h.w.jqmAddClose($(cc,h.w));

 if(c.toTop&&h.o)h.w.before('<span id="jqmP'+h.w[0]._jqm+'"></span>').insertAfter(h.o);	
 (c.onShow)?c.onShow(h):h.w.show();e(h);return F;
},
close:function(s){var h=H[s];if(!h.a)return F;h.a=F;
 if(A[0]){A.pop();if(!A[0])L('unbind');}
 if(h.c.toTop&&h.o)$('#jqmP'+h.w[0]._jqm).after(h.w).remove();
 if(h.c.onHide)h.c.onHide(h);else{h.w.hide();if(h.o)h.o.remove();} return F;
},
params:{}};
var s=0,H=$.jqm.hash,A=[],ie6=$.browser.msie&&($.browser.version == "6.0"),F=false,
i=$('<iframe src="javascript:false;document.write(\'\');" class="jqm"></iframe>').css({opacity:0}),
e=function(h){if(ie6)if(h.o)h.o.html('<p style="width:100%;height:100%"/>').prepend(i);else if(!$('iframe.jqm',h.w)[0])h.w.prepend(i); f(h);},
f=function(h){try{$(':input:visible',h.w)[0].focus();}catch(_){}},
L=function(t){$()[t]("keypress",m)[t]("keydown",m)[t]("mousedown",m);},
m=function(e){var h=H[A[A.length-1]],r=(!$(e.target).parents('.jqmID'+h.s)[0]);if(r)f(h);return !r;},
hs=function(w,t,c){return w.each(function(){var s=this._jqm;$(t).each(function() {
 if(!this[c]){this[c]=[];$(this).click(function(){for(var i in {jqmShow:1,jqmHide:1})for(var s in this[i])if(H[this[i][s]])H[this[i][s]].w[i](this);return F;});}this[c].push(s);});});};
})(jQuery);






//thickbox replacement
var closeModal = function(hash)
{
	var $modalWindow = $(hash.w);
	//$('#jqmContent').attr('src', 'blank.html');
	$modalWindow.hide();
	hash.o.remove();
};

var openInFrame = function(hash)
{
	var $trigger = $(hash.t);
	var $modalWindow = $(hash.w);
	var $modalContainer = $('iframe', $modalWindow);
	var myUrl = $trigger.attr('href');
	var myTitle = $trigger.attr('title');
	var newWidth = 0, newHeight = 0, newLeft = 0, newTop = 0;
	$modalContainer.html('').attr('src', myUrl);
	$('#jqmTitleText').text(myTitle);
	myUrl = (myUrl.lastIndexOf("#") > -1) ? myUrl.slice(0, myUrl.lastIndexOf("#")) : myUrl;
	var queryString = (myUrl.indexOf("?")> -1) ? myUrl.substr(myUrl.indexOf("?") + 1) : null;
	if (queryString != null && typeof queryString != 'undefined')
	{
		var queryVarsArray = queryString.split("&");
		for (var i = 0; i <queryVarsArray.length; i++)
		{
			if (unescape(queryVarsArray[i].split("=")[0]) == 'width')
			{
				var newWidth = queryVarsArray[i].split("=")[1];
			}
			if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'height')
			{
				var newHeight = queryVarsArray[i].split("=")[1];
			}
			if (escape(unescape(queryVarsArray[i].split("=")[0])) == 'jqmRefresh')
			{
				hash.refreshAfterClose = queryVarsArray[i].split("=")[1]
			} else
			{
				hash.refreshAfterClose = true;
			}
		}
		// let's run through all possible values: 90%, nothing or a value in pixel
		if (newHeight != 0)
		{
			if (newHeight.indexOf('%')> -1)
			{
				newHeight = Math.floor(parseInt($(window).height()) * (parseInt(newHeight) / 100));
			}
			var newTop = Math.floor(parseInt($(window).height() - newHeight) / 2);
		}
		else
		{
			newHeight = $modalWindow.height();
		}
		if (newWidth != 0)
		{
			if (newWidth.indexOf('%')> -1)
			{
				newWidth = Math.floor(parseInt($(window).width() / 100) * parseInt(newWidth));
			}
			var newLeft = Math.floor(parseInt($(window).width() / 2) - parseInt(newWidth) / 2);
		}
		else
		{
			newWidth = $modalWindow.width();
		}
		// do the animation so that the windows stays on center of screen despite resizing
		$modalWindow.css({
			width: newWidth,
			height: parseInt(newHeight),
			opacity: 1,
			top: newTop,
			left: newLeft
		}).jqmShow().animate({
			width: newWidth,
			height: parseInt(newHeight),
			top: newTop,
			left: newLeft,
			marginLeft: 0,
			opacity: 1
		}, 0);
		$modalContainer.css({
			height: parseInt($modalContainer.height())-30
		});
	}
	else
	{
		// don't do animations
		$modalWindow.jqmShow();
	}
}