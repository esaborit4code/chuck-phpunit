<html>
<head>
<script src='view/js/mootools.js' type='text/javascript'></script>
<script src='view/js/mootools-more.js' type='text/javascript'></script>

<script type="text/javascript">
var failShowed = 0;

document.addEvent("domready", function(){
	var form = $("suitesForm");

	var checkboxes = $$(".checkbox");
	checkboxes.each(function(checkbox){
		checkbox.addEvent("click", function(){
			var selector = checkbox.get("class");
			selector = "."+selector.replace("checkbox ", "");
			var checked = checkbox.get("checked");
			var children = $$(selector);
			children.each(function(child){
				child.set("checked", checked);
			});
		});
	});
	
	var checkAllButton = $("checkAll");
	checkAllButton.addEvent("click", function(e){
		e.preventDefault();
		checkAll();
	});

	function checkAll()
	{
		checkboxes.each(function(checkbox){
			checkbox.set("checked", "checked");
		});
	}
	
	var checkNoneButton = $("checkNone");
	checkNoneButton.addEvent("click", function(e){
		e.preventDefault();
		checkNone();
	});

	function checkNone()
	{
		checkboxes.each(function(checkbox){
			checkbox.set("checked", "");
		});
	}

	var runAllButton = $("runAll");
	runAllButton.addEvent("click", function(e){
		e.preventDefault();
		checkAll();
		sendForm();
	});

	var runSelectedButton = $("runSelected");
	runSelectedButton.addEvent("click", function(e){
		e.preventDefault();
		var noTestSelected = true;
		checkboxes.each(function(checkbox){
			var checked = checkbox.get("checked");
			if(checked)
			{
				noTestSelected = false;
			}
		});

		if(noTestSelected)
		{
			alert("No test selected");
			return;
		}
		sendForm();
	});

	var runButtons = $$(".run");
	runButtons.each(function(runButton){
		runButton.addEvent("click", function(e){
			e.preventDefault();
			checkNone();
			var parent = runButton.getParent();
			var checkbox = parent.getChildren(".checkbox");
			checkbox.set("checked", "checked");
			sendForm();
		});
	});

	function sendForm()
	{
		form.submit();
		$("navigationButtons").setStyle("display", "none");
		$("results").set("html", "<div id='testloader'><h1>Running tests...</h1></div>");
	}
	var navigationContainer = $("navigationButtons");

	var failNames = new Array();
	var failAnchors = $$("a.failAnchor");
	failAnchors.each(function(fail){
		var name = fail.get("name");
		failNames[failNames.length] = name;
	});

	var testFinished = ($$(".pass").length > 0);
	if(testFinished)
	{
		navigationContainer.setStyle("display","block");
	}
	
	var anyFail = (failNames.length > 0);
	if(anyFail)
	{
		navigationContainer.setStyle("background", "red");
	}

	loadButtons();
	
	function loadButtons()
	{
		var buttons = $$("#navigationButtons span");
		buttons.each(function(button){
			setNonClickable(button);
		});
		
		if(failShowed > 0)
		{
			var firstFailButton = $("firstFail");
			setClickable(firstFailButton);
			firstFailButton.addEvent("click", function(e){
				e.preventDefault();
				failShowed = 0;
				location.href="#"+failNames[failShowed];
				loadButtons();		
			});

			var previousFailButton = $("previousFail"); 
			setClickable(previousFailButton);
			previousFailButton.addEvent("click", function(e){
				e.preventDefault();
				failShowed--;
				location.href="#"+failNames[failShowed];		
				loadButtons();	
			});
		}

		if(failShowed < failNames.length)
		{
			var nextFailButton = $("nextFail"); 
			setClickable(nextFailButton);
			nextFailButton.addEvent("click", function(e){
				e.preventDefault();
				failShowed++;
				location.href="#"+failNames[failShowed];		
				loadButtons();	
			});
			nextFailButton.set("href", "#");

			var lastFailButton = $("lastFail");
			setClickable(lastFailButton);
			lastFailButton.addEvent("click", function(e){
				e.preventDefault();
				failShowed = failNames.length - 1;
				location.href="#"+failNames[failShowed];		
				loadButtons();	
			});
		}
	}

	function setNonClickable(button)
	{
		button.removeEvents("click");
		button.setStyle("cursor", "default");
		button.setStyle("font-weight", "normal");
	}

	function setClickable(button)
	{
		button.setStyle("cursor", "pointer");
		button.setStyle("font-weight", "bold");
	}

	var animatedGif = $("animatedgif");
	if($defined(animatedGif))
	{
		animatedGif.addEvent("click", function(){
			animatedGif.setStyle("display", "none");
		});
	}
	
});
</script>

<style type="text/css">

html * {
	padding: 			0;
	margin:				0;
	text-indent: 		0;
	border:				0;
}

body {
	background: 		#39424d;
	font-size:			76%;
	font-family: 		Arial, Verdana;
	color:				#eee;
}

#content {
	width:800px;
	height: 100%;
	margin: 30px 0 40px 400px;
}

#animatedgif
{
	cursor: crosshair;
	position: absolute;
	top: 25px;
	right: 5px;
	position: fixed;
	float: right;
	background-repeat: no-repeat;
	background-position: center center;
}

.epicfail
{
	background-image: url("view/img/fail.gif");
	width: 220px;
	height: 250px;
	border: 2px solid red;
	background-color: black;
}
.win
{
	border: 2px solid green;
	background-image: url("view/img/win.gif");
	width: 220px;
	height: 250px;
	background-position: -60px 0px;
}

#content #results {
	width: 100%;
	margin-top: 30px;
}

#content #results .name {
	width:800px;
	float:left;
	background: #333;
	padding: 5px;
	font-size: 14px;
	position: relative;
	border-bottom: 1px solid #666;
}

#content #results .pass, #content #results .fail {
	width:15%;
	padding: 5px;
	text-align: center;
	position: absolute;
	right:0;
	top:0;
}

.pass {
	background: green;
}

.fail {
	background: red;
}

.errorMessage {
	width: 770px;
	background: #333;
	float:left;
	clear:both;
	padding: 10px 5px 5px 20px;
	font-size: .8em;
}

#content h1 {
	font-size: 1.4em;
}

#suites
{
	position: fixed;
	width: 300px;
	background: #49525D;
	border: solid 3px #29323D;;
	border-left: 0;
	padding: 20px;
	float: left;
}

#suitesList
{
	height: 600px;
	overflow: auto;
}

#suites li
{
	position: relative;
	margin-left: 20px;
	list-style: none;
}

#suites input
{
	margin-right: 7px;
}

#suites li a
{
	margin-left: 7px;
	color: #e5c53c;
}

#suitesButtons
{
	margin-bottom: 20px;
}

#formButtons
{
	margin-top: 20px;
}

#suitesButtons a, #formButtons a
{
	text-decoration: none;
	padding: 5px;
	background: #69727D;
	border-top: solid 2px #89929D;
	border-left: solid 2px #89929D;
	color: #8FB732;
	margin-right: 15px;
	font-size: 15px;
}

#navigationButtons
{
	display: none;
	margin-top: 20px;
	background: green;
	text-align: center;
}
#navigationButtons span
{
	font-size: 20px;
	margin-right: 10px;
}

#navigationButtons span .noclickable
{
	cursor: default;
	font-weight: normal;
}

#navigationButtons span .clickable
{
	cursor: pointer;
	font-weight: bold;
}

#testloader
{
	position:absolute;
    top:50%;
    left: 50%;
    background-image: url("view/img/testloader.gif");
    background-position: bottom;
    background-repeat: none;
    padding-bottom: 30px;

}

</style>

</head>
<body>
	<div id="suites">
		<div id="suitesButtons">
			<a href="#" id="checkAll">Check all</a><a href="#" id="checkNone">Check none</a>
		</div>
		<form name="suitesForm" id="suitesForm" action="" method="post">
		<div id="suitesList">
		<? echo $suites?>
		</div>
		</form>
		<div id="formButtons">
			<a href="#" id="runAll">Run all</a><a href="#" id="runSelected">Run selected</a>
		</div>
		<div id="navigationButtons">
		<span id="firstFail">&lt;&lt;</span><span id="previousFail">&lt;</span><span id="nextFail">&gt;</span><span id="lastFail">&gt;&gt;</span>
		</div>
	</div>
	<div id="content">
		<div id="results">
			<? echo $results?>
		</div>
	</div>
</body>
</html>