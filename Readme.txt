FWORK 0.7 Release
------------------

There are two parts to the release:

** POKO FRAMEWORK **

Poko is a mini-framework for hxphp websites found in src/poko. The framework is basically used to serve web requests. For example with the request "site.com?request=MyRequest" will search for a classfile named 'site.MyRequest' ('site' is appended to all requests).
When a request is found (otherwise a 404 is called), events are called, notably main() will be called which should be the entry point for a page request.
see touchmypixel.com for details.

*Controllers
Every Request must extend the poko.controllers.Controller class. 
The HtmlController class is useful for requests which result in an XHTML page

*Views 
Controllers have a view property which defines how to display the request. Commonly the system searches for a file (.mtt or .php) in the same level 
of heirachy as the controller, and uses that as the template. You can explicitly set the view.template if you wish.
The content sent to the browser can be explicitly set via the view.setOutput or controller.setOutput
The views context (variables sent into the template system) is automatically populated with properties of the controller

*Make_Templates.n
This is a neko app which recurses the site folder to find .mtt or .php templates and copy (of compile) them to the www/tpl folder



INSTALLATION 

-install haxe 2.03 (haxe.org) 
-edit src/site/Config.hx  to setup databse




** POKO CMS **

a flexible CMS build over poko framework
The cms requires that you setup a MySQL database for it to access. It is a small but flexible cms aimed at allowing rapid CRUD of most common DB setups. This is useful for flash sites and the like, which often need very specific data arrangemets. You can use haxe native db connections and spod also by bypassing the built in db object. - we just like it for its simplicity, and its similar to our previous workflow :P

INSTALLATION

-install haxe
-install templo via typeing "haxelib install templo" at the command prompt
- execute the 'poko 0.5.sql' on your database. 
- set the database details in site/Config.hx
- compile
- go to http://*yoursite*/*poko folder*/www - you can put 'cms' after this to be direct staight to the cms
- login using:
	u: super
	p: pass
- view the help section for details about how the cms works. 

EXAMPLES

goto ?request=examples.Index to view examples

COMPILING

the "Make.bat" file can be run on windows - run similar commands can use used on mac & linux.

The make file should compile the hxphp, haxe js and then the .mtt tempates. There is a small neko app used to compile the .mtt templates. It recurses a folder, finds all of the .mtt's and compiles them to a folder. I'm not sure if this can be achieved with templo2 natively? maybe...

USAGE

see blog.touchmypixel.com for more help. 

peace out!  
-tonypee





