<?php
require_once("PHPUnit/Util/Log/JUnit.php");

class TestRunner
{
	const TESTS_FOLDER = "tests";
	public $testSuites = array();
	public $assertions = 0;
	public $failed = 0;
	
    public function run()
    {
		$xml_result = $this->getXML();
		$simple = new SimpleXMLElement($xml_result);
		
		$xhtml = "";
		
	   foreach($simple->{'testsuite'}->testsuite as $testSuite)
	   {
	   		$xhtml .= "<h1>".(string)$testSuite['name']."</h1>";
	   		$xhtml .= $this->renderTestSuite($testSuite);
	   }
	   $class = "win";
	   if($this->failed > 0)
	   {
	   		$class = "epicfail";
	   }
	   $xhtml = "Total assertions: " . $this->assertions . "<br /> Total failed: ".$this->failed."<br /><div id='animatedgif' class='".$class."'></div>". $xhtml;
	   
	   return $xhtml;
    }

    public function getXML()
    {
    	$suite = new PHPUnit_Framework_TestSuite();
		$suite->setName('TestRunner');
		
		foreach($this->testSuites as $oneTest)
		{
			$suite->addTestSuite($oneTest);
		}

		$listener = new PHPUnit_Util_Log_JUnit;
		$testResult = new PHPUnit_Framework_TestResult();
		$testResult->addListener($listener);
		$result = $suite->run($testResult);
		$xml_result = $listener->getXML();
		return $xml_result;
    }
    
    public function renderTestSuite($testSuite)
    {
    	
    	$xhtml = "";
    	foreach($testSuite->testcase as $testcase)
	   {
	       $result = array();
	       // Don't froget to cast SimpleXMLElement to string!
	       $result['name'] = (string)$testcase['name'];
	       $xhtml .= "<div class='name'>".$result['name']. " (" .(string)$testcase['assertions'] . " assertions - ".(string)$testcase['time']."s)";
	      	$this->assertions += (string)$testcase['assertions'];
	      	
	       if(isset($testcase->{'failure'}))
	       {
				$this->failed = $this->failed + 1;
	       		$result['result'] = 'Fail';
	       		$xhtml .= "<a class='failAnchor' name='fail$this->failed'><div class='fail'>".$result['result']."</div>";
	       		$result['message'] = (string)$testcase->{'failure'};
				$xhtml .= "<div class='errorMessage'>".nl2br(htmlentities($result['message']))."</div></a>";
	       }
	       elseif(isset($testcase->{'error'}))
	       {
				$this->failed = $this->failed + 1;
	       		$result['result'] = 'Error';
	       		$xhtml .= "<a class='failAnchor' name='fail$this->failed'><div class='fail'>".$result['result']."</div>";
	       		$result['message'] = (string)$testcase->{'error'};
				$xhtml .= "<div class='errorMessage'>".nl2br($result['message'])."</div></a>";
	       }
	       else
	       {
	       		$result['result'] = 'Pass';
	            $xhtml .= "<div class='pass'>".$result['result']."</div>";
	            $result['message'] = '';
	       }
	       $xhtml .= "</div>";
	       $test_results[] = $result;     
	   }
	   
	   return $xhtml;
    }
    
    public function addTestSuite($test)
    {
    	$this->testSuites[] = $test;
    }
    
    public function getSuites($checked, $path = self::TESTS_FOLDER)
    {
    	$result = "";
    	$folders = glob($path);
		$result .= "<ul>\n";
		foreach($folders as $oneFolder)
		{
			if(is_dir($oneFolder))
			{
				$pathToTest = str_replace("./", "", $oneFolder);
				$testName = basename($oneFolder);
				$selectorSplit = explode("/", $pathToTest);
				$selector = "";
				foreach($selectorSplit as $word)
				{
					$selector .= " ".$word;
				}
				
				$hasTestSuite = (file_exists($oneFolder."/suite.php"));
				if($hasTestSuite)
				{
					$checkedBox = "";
					if(key_exists($pathToTest, $checked))
					{
						$checkedBox = "checked";
					}
					$result .= "<li class='test'><input class='checkbox".$selector."' type='checkbox' name='$pathToTest' ".$checkedBox.">".$testName."<a href='#' class='run'>Run</a></input>\n";
				}
				$files = glob($oneFolder."/*");
				foreach($files as $oneFile)
				{
					if(is_dir($oneFile))
					{
						$result .= $this->getSuites($checked, $oneFile);
					}
				}
			}
		}
		$result .= "</ul>\n";
		
		return $result;
    }
}
?>