function createCookie(name, value, days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i<ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}
function eraseCookie(name) {
	createCookie(name, "", -1);
}

function urlParams(url)
{
	if (!url) url = window.location.href;
	
	var a = document.createElement('a');
	a.href = url;
	var pairs = a.search.substring(1).split('&');
	
	var params = [];
	
	if(pairs[0].length > 1)
	{
		for(var i = 0; i < pairs.length; i++)
		{
			var pair = pairs[i].split("=");
			var key = unescape(pair[0]);
			var val = unescape(pair[1]);
 			params[key] = val;
		}
	}
	
	return params;
}

function addLoadEvent(func) {
	if (typeof window.onload != "function") {
		window.onload = func;
	}
	else {
		var prev = window.onload;
		window.onload = function() {
			if (prev)
				prev();
			func();
		}
	}
}
function addLoginEvent(func) {
	if (typeof document.onlogin != "function") {
		document.onlogin = func;
	}
	else {
		var prev = document.onlogin;
		document.onlogin = function() {
			if (prev)
				prev();
			func();
		}
	}
}
function bin2hex(s) {
	var i, o = "", n;
	s += "";
	for (i = 0; i < s.length; i++) {
		n = s.charCodeAt(i).toString(16);
		o += n.length < 2 ? "0" + n : n;
	}
	return o;
}

function defaultPacketBehavior(data) {
	if (data.flags) {
		for (var i = 0;i < data.flags.length;i++) {
			// Nothing
		}
	}
	if (data.alerts) {
		for (var i = 0;i < data.alerts.length;i++) {
			alert(data.alerts[i]);
		}
	}
	if (data.body) {
		// Nothing
	}
	if (data.packet) {
		if (data.packet == "SESSION_INVALID") {
			// alert("Your session is invalid, so you have been logged out. Please login again.");
			// document.location.reload();
		}
		if (data.packet == "DATABASE_UNAVAILABLE") {
			alert("Unable to access the database.");
			// document.location.reload();
		}
	}
}

function alignColumns(left, center, right)
{
	var wleft = left.offsetWidth;
	var wright = right.offsetWidth;
	left.style.width = left.style.maxWidth = left.style.minWidth = wleft + "px";
	right.style.width = right.style.maxWidth = right.style.minWidth = wright + "px";
	center.style.paddingLeft = wleft + "px";
	center.style.paddingRight = wright + "px";
}

function toggleElement(id)
{
	var obj = document.getElementById(id);
	if (obj) {
		if (obj.style.display == "none")
			obj.style.display = "inline-block";
		else
			obj.style.display = "none";
	}
}
