<html>
	<head>
		<script src='view/js/mootools.js' type='text/javascript'></script>
		<script src='view/js/mootools-more.js' type='text/javascript'></script>
		
		<script type="text/javascript" src="view/js/chuck-phpunit.js"></script>
		<link type="text/css" rel="stylesheet" href="view/css/chuck-phpunit.css" />
	</head>
	<body>
		<div id="content">
			<form name="suitesForm" id="suitesForm" action="" method="post">
				<div id="suitesButtons">
					<a href="#" id="checkAll">Check all</a>
					<a href="#" id="checkNone">Check none</a>
				</div>
				<div id="suitesListsContainer">
					<?=$suitesListHTML?>
				</div>
				<div id="formButtons">
					<a href="#" id="runAll">Run all</a>
					<a href="#"	id="runSelected">Run selected</a>
				</div>
			</form>
			<div id="results">
				<?=$resultsHTML?>
			</div>
		</div>
	</body>
</html>
