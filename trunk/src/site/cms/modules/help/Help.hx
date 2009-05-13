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

package site.cms.modules.help;
import poko.TemploObject;
import site.cms.templates.CmsTemplate;

class Help extends CmsTemplate
{

	override public function main()
	{
		var topics = new Hash();
		var developerTopics = new Hash();
		
		// admin, manager
		if (application.user.isAdmin()) {
			topics.set("manager_home", "Introduction");
			topics.set("manager_about_users", "About Users");
		}else if (!application.user.isAdmin() && !application.user.isSuper()) {
			topics.set("user_home", "Introduction");
		}
		
		// normal users, but not super users (developers)
		topics.set("user_pages", "About Pages");
		topics.set("user_data", "About Data");
		
		// super user, developer
		if (application.user.isSuper())
		{
			developerTopics.set("developer_home", "Introduction");
			developerTopics.set("developer_basicConcepts", "Basic Concepts");
			developerTopics.set("developer_gettingStarted", "Getting started");
			developerTopics.set("developer_about_pages", "About Pages");
			developerTopics.set("developer_about_datasets", "About Datasets");
			developerTopics.set("developer_about_users", "About Users");
			developerTopics.set("developer_fieldDefinitions", "Field Definitions");
		}
		
		
		// Create the left Nav
		leftNavigation.addSection("General");
		leftNavigation.addSection("Developer");
		
		for (key in topics.keys())
			leftNavigation.addLink("General", topics.get(key), "cms.modules.help.Help&topic="+key);
		
		for (key in developerTopics.keys())
			leftNavigation.addLink("Developer", developerTopics.get(key), "cms.modules.help.Help&topic="+key);
		
		var topic = application.params.get("topic");
		if (topic == null) {
			if (application.user.isSuper()) {
				topic = "developer_home";
			}else if (application.user.isAdmin()) {
				topic = "manager_home";
			}else {
				topic = "user_home";
			}
		}
		setContentOutput("<div class=\"helpWrapper\">"+TemploObject.parse("site/cms/modules/help/blocks/"+topic+".mtt")+"</div>");
	}
}