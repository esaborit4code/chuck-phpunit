document.addEvent("domready", function() {
	var form = $("suitesForm");

	var checkboxes = $$(".suitesList input[type=checkbox]");
	checkboxes.each(function(checkbox) {
		checkbox.addEvent("click", function(event) {
			checkChildren(event.target);			
		});
	});
	
	var checkChildren = function(parentCheckbox){
		var checked = parentCheckbox.get("checked");
		
		var parentList = parentCheckbox.getParent();
		var childrenCheckboxes = parentList.getElements("input[type=checkbox]");
		
		childrenCheckboxes.each(function(child) {
			child.set("checked", checked);
		});
	}

	var checkAllButton = $("checkAll");
	checkAllButton.addEvent("click", function(e) {
		e.preventDefault();
		checkAll();
	});

	var checkAll = function() {
		checkboxes.each(function(checkbox) {
			checkbox.set("checked", "checked");
		});
	}

	var checkNoneButton = $("checkNone");
	checkNoneButton.addEvent("click", function(e) {
		e.preventDefault();
		checkNone();
	});

	var checkNone = function() {
		checkboxes.each(function(checkbox) {
			checkbox.set("checked", "");
		});
	}

	var runAllButton = $("runAll");
	runAllButton.addEvent("click", function(e) {
		e.preventDefault();
		checkAll();
		sendForm();
	});

	var runSelectedButton = $("runSelected");
	runSelectedButton.addEvent("click", function(e) {
		e.preventDefault();
		var noTestSelected = true;
		checkboxes.each(function(checkbox) {
			var checked = checkbox.get("checked");
			if (checked) {
				noTestSelected = false;
			}
		});

		if (noTestSelected) {
			alert("No test selected");
			return;
		}
		sendForm();
	});

	var runButtons = $$(".run");
	runButtons.each(function(runButton) {
		runButton.addEvent("click", function(event) {
			event.preventDefault();
			
			runButtonTest(event.target);
		});
	});
	
	var runButtonTest = function(button){
		checkNone();
		
		var parent = button.getParent();
		var checkbox = parent.getChildren("input[type=checkbox]");
		checkbox.set("checked", "checked");
		
		sendForm();
	}

	var sendForm = function() {
		$("results").set("html", "<div id='testloader'><h1>Running tests...</h1></div>");
		form.submit();
	}

	var animatedGif = $("animatedgif");
	if ($defined(animatedGif)) {
		animatedGif.addEvent("click", function() {
			animatedGif.setStyle("display", "none");
		});
	}
});