

haxe -cp src -cp src_examples -php www -main poko.Poko -lib templo -D poko_examples

haxe -cp src -cp src_examples -js www/js/main.js -main poko.Poko -D poko_examples

neko makeTemplates.n -from src/site -to www/tpl

neko MakeTemplates.n -from src_examples/site -to www\tpl