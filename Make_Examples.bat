haxe -cp src -cp src_examples -cp ../../touchmypixel/haxe/src -php www -main poko.Poko -D poko_examples -lib templo -lib hscript -D debug

haxe -cp src -cp src_examples -js www/js/main.js -main poko.Poko -D poko_examples

neko MakeTemplates.n -from src/site -to www/tpl

neko MakeTemplates.n -from src_examples/site -to www\tpl