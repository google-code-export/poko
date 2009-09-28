
haxe -cp src -cp src_examples -php www -main Main -lib templo

haxe -cp src -cp src_examples -js www/js/main.js -main MainJS

neko MakeTemplates.n -from src -to www\tpl -removePath src

neko MakeTemplates.n -from src_examples -to www\tpl -removePath src_examples