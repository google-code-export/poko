wymeditor_filebrowser = function(wym, wdw) {
	
  // the URL to the Django filebrowser, depends on your URLconf
  var fb_url = '?request=cms.modules.media.MediaSelector&from=wym';
  
  var dlg = jQuery(wdw.document.body);

  if (dlg.hasClass('wym_dialog_image')) {
    // this is an image dialog
    dlg.find('.wym_src').css('width', '200px').attr('id', 'filebrowser')
      .after('<a id="fb_link" title="Filebrowser" href="#">Media Browser</a>');	
    dlg.find('fieldset')
      .append('<a id="link_filebrowser"><img id="image_filebrowser" /></a>' +
              '<br /><span id="help_filebrowser"></span>');
    dlg.find('#fb_link')
      .click(function() {
        fb_window = wdw.open(fb_url, 'filebrowser', 'height=450,width=700,resizable=no,scrollbars=yes');
        fb_window.focus();
        return false;
      });
  }
}

/* @name dialog
* @description Opens a dialog box
*/
/*WYMeditor.editor.prototype.dialog = function( dialogType, bodyHtml ) {

  var sBodyHtml = "";
  var h = WYMeditor.Helper;
  var wym = this;
  //var doc = SBAjaxWrapper.ajaxDialog; // This points to DOMElement use as jQuery UI Dialog box
  var doc = this.document.body;
  console.log(doc);
  var selected = wym.selected();
  var sStamp = wym.uniqueStamp();

  switch( dialogType ) {

	 case WYMeditor.DIALOG_LINK:
	 sBodyHtml = this._options.dialogLinkHtml;
	 break;

	 case WYMeditor.DIALOG_IMAGE:
	 sBodyHtml = this._options.dialogImageHtml;
	 break;

	 case WYMeditor.DIALOG_TABLE:
	 sBodyHtml = this._options.dialogTableHtml;
	 break;

	 case WYMeditor.DIALOG_PASTE:
	 sBodyHtml = this._options.dialogPasteHtml;
	 break;

	 case WYMeditor.PREVIEW:
	 sBodyHtml = this._options.dialogPreviewHtml;
	 break;

	 default:
	 sBodyHtml = bodyHtml;
  }

  //construct the dialog
  var dialogHtml = h.replaceAll(this._options.dialogHtml, WYMeditor.DIALOG_BODY, sBodyHtml);
  dialogHtml = jQuery(this.replaceStrings(dialogHtml));

  switch( dialogType ) {

	 case WYMeditor.DIALOG_LINK:
	 okFunction = function() {
		var sUrl = jQuery(wym._options.hrefSelector, doc).val();
		if(sUrl.length > 0) {
		   wym._exec(WYMeditor.CREATE_LINK, sStamp);
		   jQuery("a[@href=" + sStamp + "]", wym._doc.body)
			  .attr(WYMeditor.HREF, sUrl)
			  .attr(WYMeditor.TITLE, jQuery(wym._options.titleSelector, doc).val());
		}
	 }
	 break;

	 case WYMeditor.DIALOG_IMAGE:
	 okFunction = function() {
		var sUrl = jQuery(wym._options.srcSelector, SBAjaxWrapper.ajaxDialog).val();
		if(sUrl.length > 0) {
		   wym._exec(WYMeditor.INSERT_IMAGE, sStamp);
		   jQuery("img[@src=" + sStamp + "]", wym._doc.body)
			  .attr(WYMeditor.SRC, sUrl)
			  .attr(WYMeditor.TITLE, jQuery(wym._options.titleSelector, doc).val())
			  .attr(WYMeditor.ALT, jQuery(wym._options.altSelector, doc).val());
		}
	 }
	 break;

	 case WYMeditor.DIALOG_TABLE:
	 okFunction = function() {

		var iRows = jQuery(wym._options.rowsSelector, doc).val();
		var iCols = jQuery(wym._options.colsSelector, doc).val();

		if(iRows > 0 && iCols > 0) {

		   var table = wym._doc.createElement(WYMeditor.TABLE);
		   var newRow = null;
		   var newCol = null;

		   var sCaption = jQuery(wym._options.captionSelector, doc).val();

		   //we create the caption
		   var newCaption = table.createCaption();
		   newCaption.innerHTML = sCaption;

		   //we create the rows and cells
		   for(x=0; x<iRows; x++) {
			  newRow = table.insertRow(x);
			  for(y=0; y<iCols; y++) {newRow.insertCell(y);}
		   }

		   //set the summary attr
		   jQuery(table).attr('summary', jQuery(wym._options.summarySelector, doc).val());

		   //append the table after the selected container
		   var node = jQuery(wym.findUp(wym.container(), WYMeditor.MAIN_CONTAINERS)).get(0);
		   if(!node || !node.parentNode) jQuery(wym._doc.body).append(table);
		   else jQuery(node).after(table);
		}
	 }
	 break;

	 case WYMeditor.DIALOG_PASTE:
	 okFunction = function() {
		var sText = jQuery(wym._options.textSelector, doc).val();
		wym.paste(sText);
	 }
	 break;

	 default:
	 okFunction = function() {}
	 break;
  }

  option = jQuery.extend(SBAjaxWrapper.option.dialogOption, {
	 buttons:      {
		'Ok':   function() {
		   okFunction();
		   doc.dialog('close');
		}
	 }
  } );

  doc.html(dialogHtml).dialog(option);

  // init dialog

  switch(dialogType) {

	 case WYMeditor.DIALOG_LINK:
	 //ensure that we select the link to populate the fields
	 if(selected && selected.tagName && selected.tagName.toLowerCase != WYMeditor.A)
		selected = jQuery(selected).parentsOrSelf(WYMeditor.A);

	 //fix MSIE selection if link image has been clicked
	 if(!selected && wym._selected_image)
		selected = jQuery(wym._selected_image).parentsOrSelf(WYMeditor.A);
	 break;

  }

  //pre-init functions
  if(jQuery.isFunction(wym._options.preInitDialog))
	 wym._options.preInitDialog(wym, doc);

  //auto populate fields if selected container (e.g. A)
  if(selected) {
	 jQuery(wym._options.hrefSelector, doc).val(jQuery(selected).attr(WYMeditor.HREF));
	 jQuery(wym._options.srcSelector, doc).val(jQuery(selected).attr(WYMeditor.SRC));
	 jQuery(wym._options.titleSelector, doc).val(jQuery(selected).attr(WYMeditor.TITLE));
	 jQuery(wym._options.altSelector, doc).val(jQuery(selected).attr(WYMeditor.ALT));
  }

  //auto populate image fields if selected image
  if(wym._selected_image) {
	 jQuery(wym._options.dialogImageSelector + " " + wym._options.srcSelector, doc)
		.val(jQuery(wym._selected_image).attr(WYMeditor.SRC));
	 jQuery(wym._options.dialogImageSelector + " " + wym._options.titleSelector, doc)
		.val(jQuery(wym._selected_image).attr(WYMeditor.TITLE));
	 jQuery(wym._options.dialogImageSelector + " " + wym._options.altSelector, doc)
		.val(jQuery(wym._selected_image).attr(WYMeditor.ALT));
  }

  jQuery(wym._options.dialogPreviewSelector + " " + wym._options.previewSelector, doc).html(wym.xhtml());

  //pre-init functions
  if(jQuery.isFunction(wym._options.postInitDialog))
	 wym._options.postInitDialog(wym, doc);
};*/