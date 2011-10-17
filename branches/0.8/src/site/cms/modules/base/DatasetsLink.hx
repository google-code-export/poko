/*
 * Copyright (c) 2008, TouchMyPixel & contributors
 * Original author : Tony Polinelli <tonyp@touchmypixel.com> 
 * Contributers: Tarwin Stroh-Spijer 
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

package site.cms.modules.base;

import poko.Poko;
import poko.form.elements.Button;
import poko.form.elements.Input;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.form.FormElement;
import poko.controllers.HtmlController;
import site.cms.templates.CmsTemplate;
import site.cms.modules.base.Datasets;

class DatasetsLink extends DatasetBase
{
	private var tableName:String;
	private var form1:Form;
	
	public function new() 
	{
		super();
	}
	
	override public function main()
	{	
		tableName= app.params.get("tableName");
		
		var info = app.db.requestSingle("SELECT t.*, d.`name` as 'definitionName' FROM `_datalink` t, `_definitions` d WHERE t.definitionId=d.id AND t.`tableName`=\"" + tableName + "\"");
			
		// Setup form
		form1= new Form("form1");
		form1.addElement(new Input("label", "Label", info.label));
		form1.addElement(new Selectbox("definitionId", "Definition", null, info.definitionId, false, "- none -" ));
		form1.addElement(new Selectbox("indents", "Indents", null, info.indents, false, "- none -" ));
		form1.addElement(new Button("submit", "Update"));
		form1.populateElements();
		
		// process actions
		if (form1.isSubmitted()) process();
		
		// fill in form data
		var definitionsSelctor = form1.getElementTyped("definitionId", Selectbox);
		definitionsSelctor.data = app.db.request("SELECT `id` as 'key', `name` as 'value' FROM _definitions WHERE isPage='0'");
		
		var indentSelctor = form1.getElementTyped("indents", Selectbox);
		indentSelctor.addOption( { key:1, value:1 } );
		indentSelctor.addOption( { key:2, value:2 } );
		indentSelctor.addOption( { key:3, value:3 } );
		indentSelctor.addOption( { key:4, value:4 } );

		setupLeftNav();
	}
	
	private function process():Void
	{
		app.db.update("_datalink", form1.getData(), "`tableName`='" + tableName + "'");
		
		messages.addMessage("DataLink '" + tableName + "' has been updated");
		
		app.redirect("?request=cms.modules.base.Datasets&manage=true");
	}
	
}