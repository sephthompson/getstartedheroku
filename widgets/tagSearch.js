
function createTagSearch(searchBarID, guessBarID, guessTimeMs, guessLimit, selectCallback)
{
	var input = document.getElementById(searchBarID);
	input.mGuessOutput = document.getElementById(guessBarID);
	input.mGuessTimeMs = guessTimeMs;
	input.mGuessTimeout = false;
	input.mGuessLimit = guessLimit;
	input.mBlurGuesses = false;
	input.mSelectCallback = selectCallback;
	
	input.mOnChange = function() {
		if (input.mGuessTimeout)
			clearTimeout(input.mGuessTimeout);
		
		input.mGuessTimeout = setTimeout(function() {
			input.mRequestTagSearch();
		}, input.mGuessTimeMs);
	}
	input.mBlur = function() {
		this.mGuessOutput.innerHTML = '';
		this.mGuessOutput.style.display = "none";
	}
	
	input.mGuessResponse = function(response) {
		try {
			var data = eval("("+response+")");
			if (data.packet) {
				if (data.packet == "SEARCH_SUCCEEDED") {
					input.mGuessOutput.innerHTML = data.body;
					input.mGuessOutput.style.display = "inline-block";
					input.mBlurGuesses = false;
				}
				else if (data.packet == "NO_RESULTS") {
					input.mGuessOutput.innerHTML = '<font color="grey"><var>No results.</var></font>';
					input.mGuessOutput.style.display = "inline-block";
					input.mBlurGuesses = false;
				}
				else if (data.packet == "SEARCH_FAILED") {
					input.mGuessOutput.innerHTML = '<font color="red"><var>Search failed.</var></font>';
					input.mGuessOutput.style.display = "inline-block";
					input.mBlurGuesses = false;
				}
			}
			defaultPacketBehavior(data);
		}
		catch(error) {
			alert("An internal server error occurred. Server returned: "+response);
		}
	}
	
	input.mRequestTagSearch = function() {
		var text = this.value;
		if (text.length == 0) {
			this.mGuessOutput.innerHTML = '';
			this.mGuessOutput.style.display = "none";
		}
		else {
			ajaxRequest(API_PATH+"searchTags.php",
				"action=tagSearchGuess&limit="+this.mGuessLimit+"&text="+text+"&callback="+this.mSelectCallback, this.mGuessResponse);
		}
	}
	
	input.addEventListener("keydown", input.mOnChange);
	input.addEventListener("keypress", input.mOnChange);
	//input.addEventListener("blur", input.mBlur);
	
	input.mGuessOutput.addEventListener("mouseover", function() {
		input.mBlurGuesses = false;
	});
	input.mGuessOutput.addEventListener("mouseout", function() {
		input.mBlurGuesses = true;
	});
	document.addEventListener("mousedown", function() {
		if (input.mBlurGuesses) {
			input.mBlur();
		}
	});
}
