FWORK 0.5 Release
------------------

There are two parts to the release:

** FWORK **

fwork is a small f(rame)work for hxphp websites found in src/fwork. The framework is basically used to serve web requests - eg. "site.com?request=MyRequest" will look for a classfile named 'site.MyRequest' (site is appended to all requests - will be variable later). The request has events called on it - notably main() which should be the entry point for a page request.
see blog.touchmypixel.com for details.

INSTALLATION 

-install haxe 2.03 (haxe.org) 
-edit src/Main.hx  
	change the db.connect(xxx) to connect to your MySql Database. 
	

	
** FWORK CMS **

a flexible CMS build over fwork
The cms requires that you setup a MySQL database for it to access. It is a small but flexible cms aimed at allowing rapid CRUD of most common DB setups. This is useful for flash sites and the like, which often need ver specific data arrangemets. You can use haxe native db connections and spod also by bypassing the built in db object. - we just like it for its simplicity, and its similar to our previous workflow :P

INSTALLATION

-install haxe
-install templo via typeing "haxelib install templo" at the command prompt
- execure the fworkcms.sql on your database. 
- go to yoursite.com/cms/ - this will redirect you to "?request=cms.Index"
- login using:
	u: admin
	p: pass
- view the help section for details about how the cms works. 


COMPILING

the "Make.bat" file can be run on windows - run similar commands can use used on mac & linux.

The make file should compile the hxphp, haxe js and then the .mtt tempates. There is a small neko app used to compile the .mtt templates. It recurses a folder, finds all of the .mtt's and compiles them to a folder. I'm not sure if this can be achieved with templo2 natively? maybe...

USAGE

see blog.touchmypixel.com for more help. 

peace out!  
-tonypee





