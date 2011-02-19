<?php
require_once("PHPUnit/Util/Log/JUnit.php");

class TestRunner
{
	const TESTS_FOLDER = "tests";
	
	public $testSuites = array();
	
	public $assertions	= 0;
	public $failed		= 0;

	public function run()
	{
		$resultXML = $this->getResultXML();

		$testSuitesHTML = "";

		foreach($resultXML->{'testsuite'}->testsuite as $testSuite)
		{
			$testSuitesHTML .= $this->getTestSuiteHTML($testSuite);
		}
		
		$resultHTML = $this->getResultHTML($testSuitesHTML);

		return $resultHTML;
	}
	
	private function getResultXML()
	{
		$testRunner = new PHPUnit_Framework_TestSuite();
		$testRunner->setName('TestRunner');

		foreach($this->testSuites as $testSuite)
		{
			$testRunner->addTestSuite($testSuite);
		}

		$listener = new PHPUnit_Util_Log_JUnit;
		
		$testResult = new PHPUnit_Framework_TestResult();
		$testResult->addListener($listener);
		
		$testRunner->run($testResult);
		
		$resultXMLString = $listener->getXML();
		
		$resultXML = simplexml_load_string($resultXMLString);
		
		return $resultXML;
	}
	
	private function getResultHTML($testSuitesHTML)
	{
		$thereAreFailedTests = ($this->failed > 0);
		$class = $thereAreFailedTests ? "epicfail" : "win";
		$animatedGifHTML = "<div id=\"animatedgif\" class=\"$class\"></div>";
		
		$totalAssertionsHTML = "<p class=\"totalAssertions\"><span class=\"totalLabel\">Total assertions:</span> <span class=\"totalValue\">" . $this->assertions . "</span></p>";
		
		$totalFailedHTML = "<p class=\"totalAssertions\"><span class=\"totalLabel\">Total failed:</span> <span class=\"totalValue\">" . $this->failed . "</span></p>";
		
		$resultHTML = $animatedGifHTML . $totalAssertionsHTML . $totalFailedHTML . $testSuitesHTML;
		
		return $resultHTML;
	}

	private function getTestSuiteHTML($testSuite)
	{
		$testCasesHTML = "";
		
		foreach($testSuite->testcase as $testCase)
		{
			$testCasesHTML .= $this->getTestCaseHTML($testCase);
		}
		
		$testSuiteLabel = "<h1 class=\"testSuiteLabel\">" . (string)$testSuite['name'] . "</h1>";
		
		$testSuiteHTML = $testSuiteLabel . $testCasesHTML;
		
		return $testSuiteHTML;
	}
	
	private function getTestCaseHTML($testCase)
	{
		$name		= (string)$testCase['name'];
		$assertions	= (string)$testCase['assertions'];
		$time		= (string)$testCase['time'];
		
		$isFailure	= (isset($testCase->{'failure'}));
		$isError	= (isset($testCase->{'error'}));
		
		$statusBoxCSS	= "pass";
		$statusBoxText	= "Pass";
		$messageBoxHTML	= "";
		
		if($isFailure)
		{
			$statusBoxCSS	= "fail";
			$statusBoxText	= "Fail";
			$message		= nl2br(htmlentities((string)$testCase->{'failure'}));
			
			$messageBoxHTML	= "<p class=\"messageBox\">$message</p>";
			
			$this->failed++;
		}
		else if ($isError)
		{
			$statusBoxCSS	= "fail";
			$statusBoxText	= "Error";
			$message		= nl2br(htmlentities((string)$testCase	->{'error'}));

			$messageBoxHTML	= "<p class=\"messageBox\">$message</p>";
			
			$this->failed++;
		}
		
		$this->assertions += $assertions;
		
		$nameHTML				= "<span class=\"testName\">$name</span>";
		$assertionsAndTimeHTML	= "<span class=\"assertionsAndTime\">($assertions assertions - $time)</span>";
		$statusBoxHTML			= "<span class=\"statusBox $statusBoxCSS\">$statusBoxText</span>";
		
		$testCaseHeader = "<p class=\"testCaseHeader\">" . $nameHTML . $assertionsAndTimeHTML . $statusBoxHTML . "</p>";
		
		
		$testCaseHTML = 
			"<div class=\"testCase\">" .
			$testCaseHeader . 
			$messageBoxHTML .
			"</div>";
		
		return $testCaseHTML;
	}
	
	public function addTestSuite($test)
	{
		$this->testSuites[] = $test;
	}
	
	public function addTestFolder($testFolder)
	{
	    require_once("$testFolder/suite.php");
	}
	
	public function addTestFolders($testFolders)
	{
		foreach($testFolders as $testFolderName)
		{
			$this->addTestFolder($testFolderName);
		}
	}

	public function getSuitesListHTML($checkedTests, $path = self::TESTS_FOLDER)
	{
		$listItemsHTML = "";
		
		$folders = glob($path);
		foreach($folders as $oneFolder)
		{
			if(is_dir($oneFolder))
			{
				$testName = basename($oneFolder);
				
				$isTestFolder = (file_exists("$oneFolder/suite.php"));
				if($isTestFolder)
				{
					$testIsChecked = (key_exists($oneFolder, $checkedTests));
					$checkedBox = $testIsChecked ? "checked" : "";
					
					$listItem = "<li class='test'><input type='checkbox' name='$oneFolder' $checkedBox>$testName<a href='#' class='run'>Run</a></input>\n";
					
					$listItemsHTML .= $listItem;
				}
				
				$files = glob("$oneFolder/*");
				foreach($files as $oneFile)
				{
					if(is_dir($oneFile))
					{
						$innerSuitesList = $this->getSuitesListHTML($checkedTests, $oneFile);
						
						$listItemsHTML .= $innerSuitesList;
					}
				}
			}
		}
		
		$suitesListHTML = "<ul class=\"suitesList\">$listItemsHTML</ul>\n";

		return $suitesListHTML;
	}
}
?>