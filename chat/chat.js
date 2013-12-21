var TIME_DELAY;

var Name = 'Guest';
var SysColor = '0077FF';
var DocSize = 0;

function ChatResponse(Body)
{
	if (Body.length > 0)
	{
		var pos = Body.indexOf('|');
		Obj = document.getElementById('chat_Content');
		Obj.innerHTML = Body.substring(pos+1);
		DocSize = Body.substring(0, pos);
	}
}
function SendResponse(Body)
{
}
function GetName()
{
	Name = document.getElementById('chat_NameText').value;
	Name = Name.replace(/&/g, '[[ampersand]]');
}
function ChatMessage()
{
	GetName();
	Msg = document.getElementById('chat_Message').value;
	Msg = Msg.replace(/&/g, '[[ampersand]]');
	document.getElementById('chat_Message').value = '';
	ajaxRequest('chat.php', 'Action=AddChat&Name=' + Name + '&Msg=' + Msg, SendResponse);
}
function ChatUpdate()
{
	ajaxRequest('chat.php', 'Action=GetChat&DocSize='+DocSize, ChatResponse);
	setTimeout(ChatUpdate, TIME_DELAY);
}
function InitChat(Delay)
{
	TIME_DELAY = Delay;
	ChatUpdate();
}