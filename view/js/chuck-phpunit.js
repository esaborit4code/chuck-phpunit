document.addEvent("domready", function() {
	var form = $("suitesForm");

	var checkboxes = $$(".checkbox");
	checkboxes.each(function(checkbox) {
		checkbox.addEvent("click", function() {
			var selector = checkbox.get("class");
			selector = "." + selector.replace("checkbox ", "");
			var checked = checkbox.get("checked");
			var children = $$(selector);
			children.each(function(child) {
				child.set("checked", checked);
			});
		});
	});

	var checkAllButton = $("checkAll");
	checkAllButton.addEvent("click", function(e) {
		e.preventDefault();
		checkAll();
	});

	function checkAll() {
		checkboxes.each(function(checkbox) {
			checkbox.set("checked", "checked");
		});
	}

	var checkNoneButton = $("checkNone");
	checkNoneButton.addEvent("click", function(e) {
		e.preventDefault();
		checkNone();
	});

	function checkNone() {
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
		runButton.addEvent("click", function(e) {
			e.preventDefault();
			checkNone();
			var parent = runButton.getParent();
			var checkbox = parent.getChildren(".checkbox");
			checkbox.set("checked", "checked");
			sendForm();
		});
	});

	function sendForm() {
		form.submit();
		$("navigationButtons").setStyle("display", "none");
		$("results").set("html",
				"<div id='testloader'><h1>Running tests...</h1></div>");
	}

	var animatedGif = $("animatedgif");
	if ($defined(animatedGif)) {
		animatedGif.addEvent("click", function() {
			animatedGif.setStyle("display", "none");
		});
	}
});