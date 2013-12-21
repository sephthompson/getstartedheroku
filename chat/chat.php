<?php

function HttpDecode($String)
{
	$String = str_replace('&','&',$String);
	$String = str_replace('\\','',$String);
	$String = str_replace("\'",'`',$String);
	$String = str_replace("'",'`',$String);
	$String = str_replace('<','&lt;',$String);
	$String = str_replace('>','&gt;',$String);
	return $String;
}
function HttpEncode($String)
{
	$String = str_replace('&','&',$String);
	$String = str_replace('`',"'",$String);
	$String = str_replace('&lt;','<',$String);
	$String = str_replace('&gt;','>',$String);
	return $String;
}
function NameReplacements($Name)
{
	$Name = str_replace('[[ampersand]]','&',$Name);
	return $Name;
}
function MsgReplacements($Msg)
{
	$Msg = str_replace('[[ampersand]]','&',$Msg);
	$Msg = str_replace(':)','<img src="images/happy.png" />',$Msg);
	$Msg = str_replace(':(','<img src="images/sad.png" />',$Msg);
	$Msg = str_replace('xD','<img src="images/biglaugh.png" />',$Msg);
	$Msg = str_replace('XD','<img src="images/biglaugh.png" />',$Msg);
	return $Msg;
}
if ($_POST['Action'] != NULL)
{
	$Action = $_POST['Action'];
	if ($Action == 'GetChat')
	{
		$DocSize = (int)$_POST['DocSize'];
		$FilePath = 'chat.txt';
		if (file_exists($FilePath))
		{
			if (filesize($FilePath) != $DocSize)
			{
				echo filesize($FilePath).'|';
				include($FilePath);
			}
		}
		else
		{
			$hFile = fopen($FilePath,'w');
			fwrite($hFile,'');
			fclose($hFile);
		}
	}
	else if ($Action == 'AddChat' && $_POST['Name'] != NULL && $_POST['Msg'] != NULL)
	{
		$FilePath = 'chat.txt';
		$Name = $_POST['Name'];
		$Msg = $_POST['Msg'];
		$Name = HttpDecode($Name);
		$Msg = HttpDecode($Msg);
		$Name = NameReplacements($Name);
		$Msg = MsgReplacements($Msg);
		$Time = '<table><tr><td>'.date('n/j').'</td></tr><tr><td>'.date('G:i:s').'</td></tr></table>';
		
		$fData = '';
		
		if ($Msg == '/clear')
		{
			$fData = '<div><span class="system"><b>[<span class="name">System</span>]: </b>Chat cleared.</span></div>';
		}
		else
		{
			$Min = 50;
			$Max = 250;
			
			$Line = '<div><span class="message"><span class="time">'.$Time.'</span> <b><span class="name">'.$Name.'</span>: </b>'.$Msg.'</span></div>';
			
			if (file_exists($FilePath)) {
				if (filesize($FilePath) > 0)
				{
					$hFile = fopen($FilePath,'r');
					$fData = fread($hFile,filesize($FilePath));
					fclose($hFile);
				}
			}
			
			$fData = $Line.'
'.$fData;
			
			if (substr_count($fData,'<div>') > $Max)
			{
				$Count = 0;
				for($i = 0;$i < strlen($fData);$i++)
				{
					if (substr($fData,$i,6) == '</div>')
					{
						$Count++;
					}
					if ($Count == $Min)
					{
						$fData = substr($fData,0,$i + 6);
						$i = strlen($fData) + 1;
					}
				}
			}
		}
		$hFile = fopen($FilePath,'w');
		fwrite($hFile, $fData);
		fclose($hFile);
	}
}

?>