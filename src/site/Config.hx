/**
 * ...
 * @author Tony Polinelli
 */

package site;


#if php


/*
 * If running the Make_examples.bat we will include all of the controllers for the exmple site 
 */
#if poko_examples 
import site.examples.ImportAll;
#end

import site.Test;
import site.cms.ImportAll;

class Config extends poko.system.Config
{
	public function new() 
	{
		super();
		
		database_host = "192.168.1.80";
		database_database = "poko";
		database_user = "root";
		database_password = "";
		
		sessionName = "poko_cms";
	}
	
}


#elseif js

import site.cms.ImportAll;


class Config extends poko.js.JsConfig
{
	public function new() 
	{
		super();
	}	
}

#end