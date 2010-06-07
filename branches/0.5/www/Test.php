<?

include ("PhpInit.php");

$res = mysql_query("SELECT * FROM _definitions");

echo "<pre>";

$page = Pages::getPageByName("Test Page");

print_r($page->data);

?>