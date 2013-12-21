
function profileResponse(response) {
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			if (data.packet == "SUCCEEDED") {
				document.location.reload();
			}
			else if (data.packet == "FAILED") {
				alert("There was an error editing your profile.");
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}

var gBlurTagGuess = false;

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
				guessOutput.innerHTML = '<var>No results</var>';
				guessOutput.style.display = "inline-block";
				gBlurTagGuess = false;
				// guessOutput.innerHTML = '';
				// guessOutput.style.display = "none";
			}
			else if (data.packet == "SEARCH_FAILED") {
				guessOutput.innerHTML = '<var>Search failed</var>';
				guessOutput.style.display = "inline-block";
				gBlurTagGuess = false;
				// guessOutput.innerHTML = '';
				// guessOutput.style.display = "none";
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}


// Self

function toggleEditTitle() {
	toggleElement("editTitle");
}
function saveNewTitle() {
	var title = document.getElementById("editTitleText").value;
	ajaxRequest("./server/profile.php", "action=editTitle&title="+title, profileResponse);
	toggleElement("editTitle");
}

function toggleEditSkills() {
	toggleElement("editSkills");
}

// Visitor

function toggleEndorse() {
	toggleElement("endorse");
}
function postEndorse(uuid) {
	
}