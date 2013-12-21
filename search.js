var SERVER_PATH = "./server/";

function searchResponse(response) {
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			if (data.packet == "SEARCH_SUCCEEDED") {
				var output = document.getElementById("results");
				output.innerHTML = data.body;
			}
			else if (data.packet == "SEARCH_FAILED") {
				alert("There was an error executing your search.");
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}
function tagSearchGuessResponse(response) {
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			var guessOutput = document.getElementById("tagSearchBarGuess");
			if (data.packet == "SEARCH_SUCCEEDED") {
				guessOutput.innerHTML = data.body;
				guessOutput.style.display = "inline-block";
				gBlurTagGuess = false;
			}
			else if (data.packet == "NO_RESULTS") {
				guessOutput.innerHTML = '';
				guessOutput.style.display = "none";
			}
			else if (data.packet == "SEARCH_FAILED") {
				guessOutput.innerHTML = '';
				guessOutput.style.display = "none";
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}

function requestSearch() {
	ajaxRequest(SERVER_PATH+"searchProjects.php", "action=search&tags=none"+"&location=none", searchResponse);
}

function requestTagSearchGuess() {
	var input = document.getElementById("tagSearchBar");
	var guessOutput = document.getElementById("tagSearchBarGuess");
	var val = input.value;
	if (val.length == 0) {
		guessOutput.innerHTML = '';
		guessOutput.style.display = "none";
	}
	else {
		ajaxRequest(SERVER_PATH+"searchTags.php", "action=tagSearchGuess&callback=addTag&limit=5&text="+val, tagSearchGuessResponse);
	}
}

var gTagHover = document.getElementById("tagHover");
var gDraggingTagId = null;
var gDeleteTag = false;
gTagHover.style.position = "absolute";
gTagHover.style.display = "none";

function staticWidth(o) {
	var width = o.offsetWidth;
	o.style.width = o.style.maxWidth = o.style.minWidth = width + "px";
	return o.offsetWidth;
}

document.addEventListener("mousemove", function(e) {
	var mouseX = 0;
	var mouseY = 0;
	
	if (document.all) {
		mouseX = e.clientX + document.body.scrollLeft;
		mouseY = e.clientY + document.body.scrollTop;
	}
	else {
		mouseX = e.pageX;
		mouseY = e.pageY;
	}
	
	gTagHover.style.left = (mouseX + 4) + "px";
	gTagHover.style.top = (mouseY - gTagHover.offsetHeight / 2) + "px";
	
	return true;
});
document.addEventListener("mousedown", function(e) {
	if (gDraggingTagId) {
		if (gDeleteTag) {
			removeTag(gDraggingTagId);
		}
		gDraggingTagId = false;
		gTagHover.innerHTML = '';
		gTagHover.style.display = "none";
	}
	return true;
});

function dragStart(o) {
	gDraggingTagId = o.id;
	gTagHover.innerHTML = o.outerHTML;
	gTagHover.style.display = "inline-block";
}
function tagDeleteOver() {
	gDeleteTag = true;
}
function tagDeleteOut() {
	gDeleteTag = false;
}

var gTagSet = [];
function refreshTagSet() {
	var tagSet = document.getElementById("tagSet");
	var str = "";
	for (var i = 0; i < gTagSet.length; i++) {
		var tag = gTagSet[i];
		str += '<span id="tag_'+tag[0]+'" onClick="dragStart(this);">'+tag[1]+'</span>';
	}
	tagSet.innerHTML = str;
}
function addTag(uuid, title) {
	var input = document.getElementById("tagSearchBar");
	var guessOutput = document.getElementById("tagSearchBarGuess");
	gTagSet.push([uuid, title]);
	input.value = "";
	guessOutput.innerHTML = '';
	guessOutput.style.display = "none";
	gBlurTagGuess = false;
	refreshTagSet();
	requestSearch();
}
function removeTag(tag_id) {
	for (var i=0; i<gTagSet.length; i++) {
		var tag = gTagSet[i];
		var id = "tag_"+tag[0];
		if (tag_id == id) {
			gTagSet.splice(i, 1);
			i--;
		}
	}
	refreshTagSet();
}

var gTimeout;
var gBlurTagGuess = false;
document.getElementById("tagSearchBarGuess").onmouseenter = function() {
	gBlurTagGuess = false;
}
document.getElementById("tagSearchBarGuess").onmouseleave = function() {
	gBlurTagGuess = true;
}
document.addEventListener("mousedown", function() {
	if (gBlurTagGuess) {
		var guessOutput = document.getElementById("tagSearchBarGuess");
		guessOutput.innerHTML = '';
		guessOutput.style.display = "none";
	}
});
function tagSearchChange() {
	clearTimeout(gTimeout);
	gTimeout = setTimeout(requestTagSearchGuess, 500);
}
function tagSearchBlur() {
	if (gBlurTagGuess) {
		var guessOutput = document.getElementById("tagSearchBarGuess");
		guessOutput.innerHTML = '';
		guessOutput.style.display = "none";
	}
}
