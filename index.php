<?php
ini_set("display_errors", 1);
ini_set("error_reporting", E_ALL);

require_once 'PHPUnit/Framework.php';
require_once dirname(__FILE__).'/framework/TestRunner.php';

$testRunner = new TestRunner();

$checked = $_POST;

$suites = $testRunner->getSuites($checked);
$results = "";

$thereAreTestsToRun = (!empty($checked));
if($thereAreTestsToRun)
{
	foreach($checked as $key => $value)
	{
	    require_once("./".$key."/suite.php");
	}
	
	$results = $testRunner->run();
}

require_once dirname(__FILE__).'/view/template.php';

?>
