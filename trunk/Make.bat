haxe -cp src -php www  -main Main -lib templo

haxe -cp src -js www/js/main.js -main MainJS

neko MakeTemplates.n -from src -to www\tpl -removePath src