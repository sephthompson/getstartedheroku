
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

// Self

function tagSelected(uuid, name) {
	var input = document.getElementById("tagSearchBar");
	input.mBlur();
	input.value = "";
	alert("Add tag: "+name+" uuid["+uuid+"]");
}

function toggleEditTitle() {
	toggleElement("editTitle");
}
function saveNewTitle() {
	var title = encodeURIComponent(document.getElementById("editTitleText").value);
	ajaxRequest(API_PATH+"profile.php", "action=editTitle&title="+title, profileResponse);
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
	var text = document.getElementById("endorseText").value;
	ajaxRequest(API_PATH+"profile.php", "action=postEndorsement&subject="+uuid+"&text="+text, profileResponse);
}