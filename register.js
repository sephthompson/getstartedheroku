
function registerResponse(response)
{
	try {
		var data = eval("("+response+")");
		if (data.packet) {
			var output = document.getElementById("output");
			if (data.packet == "REGISTER_SUCCEEDED") {
				output.innerHTML = '<font color="green">Registration successful.</font><br />You can continue by logging in.';
			}
			else if (data.packet == "REGISTER_FAILED") {
				output.innerHTML = '<font color="red">There was an unexpected error when registering.<br />Please re-enter the information and try again.</font>';
			}
			else if (data.packet == "PASSWORD_MISMATCH") {
				output.innerHTML = '<font color="red">The passwords you entered do not match.</font>';
			}
			else if (data.packet == "ALREADY_LOGGED_IN") {
				output.innerHTML = '<font color="red">You are already logged in.</font>';
			}
			else if (data.packet == "USERNAME_TAKEN") {
				output.innerHTML = '<font color="red">The username you entered is in use.</font>';
			}
			else if (data.packet == "USERNAME_INVALID") {
				output.innerHTML = '<font color="red">The username you entered does not meet the requirements.</font>';
			}
			else if (data.packet == "PASSWORD_INVALID") {
				output.innerHTML = '<font color="red">The password you entered does not meet the requirements.</font>';
			}
			else if (data.packet == "EMAIL_INVALID") {
				output.innerHTML = '<font color="red">The email you entered is not valid.</font>';
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

function registerSubmit() {
	var user = encodeURIComponent(document.getElementById("regUser").value);
	var pass1 = encodeURIComponent(document.getElementById("regPass1").value);
	var pass2 = encodeURIComponent(document.getElementById("regPass2").value);
	var email = encodeURIComponent(document.getElementById("regEmail").value);
	
	ajaxRequest(API_PATH+"register.php", "action=register"
		+"&user="+user
		+"&pass1="+pass1
		+"&pass2="+pass2
		+"&email="+email
		, registerResponse);
}
