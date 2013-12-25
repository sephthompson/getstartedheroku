
function isUserClean(str) {
	for (var i = 0; i < str.length; i++) {
		var c = str.charAt(i);
		if (!(	(c >= 'A' && c <= 'Z') ||
			(c >= 'a' && c <= 'z') ||
			(c >= '0' && c <= '9') ||
			c == '_' ||
			c == '-' ||
			c == ' '
		)) {
			return false;
		}
	}
	return true;
}
function isPassClean(str) {
	for (var i = 0; i < str.length; i++) {
		var c = str.charCodeAt(i);
		if (!(	(c >= 32 && c <= 126)
		)) {
			return false;
		}
	}
	return true;
}

function welcomeResponse(response) {
	
	var loadingGif = document.getElementById("loginBarLoadingGif");
	if (loadingGif) loadingGif.style.display = "none";
	
	try {
		var data = eval("("+response+")");
		
		if (data.user) {
			var loginBar = document.getElementById("loginBar");
			var welcomeBar = document.getElementById("welcomeBar");
			if (loginBar) loginBar.style.display = "none";
			if (welcomeBar) welcomeBar.style.display = "inline-block";
			if (loadingGif) loadingGif.style.display = "inline-block";
			
			var welcomeBarPic = document.getElementById("welcomeBarPic");
			var welcomeBarMessage = document.getElementById("welcomeBarMessage");
			
			welcomeBarMessage.innerHTML = '<a href="?p=profile">'+data.user.username+'</a>';
		}
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}

function loginResponse(response) {
	var loadingGif = document.getElementById("loginBarLoadingGif");
	if (loadingGif) loadingGif.style.display = "none";
	
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			if (data.packet == "LOGIN_SUCCEEDED") {
				document.location.reload();
			}
			else if (data.packet == "ALREADY_LOGGED_IN") {
				alert("This user is already logged in to a session. For security reasons, that session has been terminated.");
				document.location.reload();
			}
			else if (data.packet == "USER_BAD_CHARS") {
				alert("Usernames can only contain A-Z, a-z, 0-9, underscores, dashes, and spaces.");
			}
			else if (data.packet == "PASS_BAD_CHARS") {
				alert("Passwords can contain all characters from ascii 32 to 126.");
			}
			else if (data.packet == "USERNAME_NOT_FOUND") {
				alert("The username you have entered was not found.");
			}
			else if (data.packet == "PASSWORD_INCORRECT") {
				alert("The password you have entered is incorrect.");
			}
			else if (data.packet == "DATABASE_UNAVAILABLE") {
				alert("The database server is unavailable.");
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}
function logoutResponse(response) {
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			if (data.packet == "LOGOUT_SUCCEEDED") {
				alert("Logout successful.");
				document.location.reload();
			}
			else if (packet == "ALREADY_LOGGED_OUT") {
				alert("This user is already logged in to a session. For security reasons, that session has been terminated.");
			}
			else if (packet == "USERNAME_NOT_FOUND") {
				alert("The username you have entered was not found.");
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}
function sessionLoginResponse(response) {
	var loadingGif = document.getElementById("loginBarLoadingGif");
	if (loadingGif) loadingGif.style.display = "none";
	
	try {
		var data = eval("("+response+")");
		
		if (data.packet) {
			if (data.packet == "SESSION_LOGIN_SUCCEEDED") {
				if (loadingGif) loadingGif.style.display = "inline-block";
				if (document.onlogin) document.onlogin();
				ajaxRequest(API_PATH+"userData.php", "action=getAccount", welcomeResponse);
			}
		}
		defaultPacketBehavior(data);
	}
	catch(error) {
		alert("An internal server error occurred. Server returned: "+response);
	}
}

function requestLogin() {
	var userField = document.getElementById("loginBarUser");
	var passField = document.getElementById("loginBarPass");
	if (userField && passField) {
		var user = userField.value;
		var pass = passField.value;
		var userClean = isUserClean(user);
		var passClean = isPassClean(pass);
		if (userClean && passClean) {
			var loadingGif = document.getElementById("loginBarLoadingGif");
			if (loadingGif) loadingGif.style.display = "inline-block";
			ajaxRequest(API_PATH+"login.php", "action=login&user="+user+"&pass="+pass, loginResponse);
		}
		else {
			if (!userClean) {
				alert("Usernames can only contain A-Z, a-z, 0-9, underscores, dashes, and spaces.");
			}
			if (!passClean) {
				alert("Passwords can contain all characters from ascii 32 to 126.");
			}
		}
	}
}
function requestLogout() {
	ajaxRequest(API_PATH+"login.php", "action=logout", logoutResponse);
}

function tryAutoLogin() {
	var loginBar = document.getElementById("loginBar");
	var welcomeBar = document.getElementById("welcomeBar");
	if (loginBar) loginBar.style.display = "inline-block";
	if (welcomeBar) welcomeBar.style.display = "none";
	
	if (readCookie("gsUUID") && readCookie("gsSessionID")) {
		if (loginBar) loginBar.style.display = "none";
		if (welcomeBar) welcomeBar.style.display = "inline-block";
		var loadingGif = document.getElementById("loginBarLoadingGif");
		if (loadingGif) loadingGif.style.display = "inline-block";
		ajaxRequest(API_PATH+"login.php", "action=sessionLogin", sessionLoginResponse);
	}
}

addLoadEvent(tryAutoLogin);
