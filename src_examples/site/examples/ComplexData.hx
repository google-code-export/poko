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

package site.examples;
import poko.form.elements.Button;
import poko.form.elements.Selectbox;
import poko.form.Form;
import poko.Request;
import site.cms.common.PageData;
import site.examples.components.Navigation;
import site.examples.templates.DefaultTemplate;

class ComplexData extends DefaultTemplate
{
	public var form1:Form;
	public var showProject:Bool;
	public var projectId:String;
	public var projectData:Dynamic;
	public var projectServices:List<Dynamic>;
	public var projectImages:List<Dynamic>;
	
	
	override public function main()
	{
		form1= new Form("form1");
		form1.addElement(new Selectbox("project", "Project"));
		form1.setSubmitButton(form1.addElement(new Button("submit", "Submit")));
		form1.populateElements();
		
		var options = application.db.request("SELECT `id` as 'value', `name` as 'key' FROM example_projects");
		
		for(option in options)
			form1.getElementTyped("project", Selectbox).addOption(option);
		
			
		showProject = false;
		
		if (form1.isSubmitted())
		{
			projectId = form1.getData().project;
			
			if (projectId != "") 
				viewProject();
		}
	}
	
	private function viewProject():Void
	{
		showProject = true;
		
		projectData = application.db.requestSingle("SELECT * FROM `example_projects` p, `example_categories` c WHERE c.`id`=p.`category` AND p.`id`=\"" + projectId + "\"");
		
		projectServices = application.db.request("SELECT * FROM `example_services` s, `example_projects_services` link WHERE link.`projectId`=" + projectId + " AND s.`id`=link.`serviceId`");
		
		projectImages = application.db.request("SELECT * FROM `example_images` WHERE `link_to`='example_projects' AND `link_value`=\""+projectId+"\"");
	}
}