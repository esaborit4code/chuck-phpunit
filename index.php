<?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/framework/TestRunner.php';

define("TESTING_MODE", true);


$testRunner = new TestRunner();

$resultsHTML = "";

$checkedTestFolders = $_POST;

$thereAreTestsToRun = (!empty($checkedTestFolders));
if($thereAreTestsToRun)
{
	$folderNames = array_keys($checkedTestFolders);
	$testRunner->addTestFolders($folderNames);
	
	$resultsHTML = $testRunner->run();
}


$suitesListHTML = $testRunner->getSuitesListHTML($checkedTestFolders);

require_once dirname(__FILE__).'/view/template.php';
?>
