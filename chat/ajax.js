function ajaxRequest(url,params,callBack)
{
	var request;
	try {
		request = new XMLHttpRequest();
	}
	catch (e) {
		try {
			request = new ActiveXObject('Msxml2.XMLHTTP');
		}
		catch (e) {
			try {
				request = new ActiveXObject('Microsoft.XMLHTTP');
			}
			catch (e) {
				return false;
			}
		}
	}
	request.onreadystatechange = function() {
		if(request.readyState == 4 && request.status == 200) {
			if (typeof(callBack) != 'undefined') {
				callBack(request.responseText);
			}
		}
	}
	//if (url == ''){url = '';}
	//url = 'http://' + document.domain + '/' + url;

	request.open("POST",url,true);
	request.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	request.setRequestHeader("Content-length",params.length);
	request.setRequestHeader("Connection","close");
	request.send(params);
}