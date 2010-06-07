
haxe -cp src -php www -main poko.Poko -lib templo -lib hscript

haxe -cp src -js www/js/main.js -main poko.Poko

neko makeTemplates.n -from src/site -to www/tpl