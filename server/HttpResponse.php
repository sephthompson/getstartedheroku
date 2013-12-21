<?php

class HttpResponse
{
	public $packet = array();
	public $flags = array();
	public $alerts = array();
	public $body = "";
	
	public function __construct($packet = "")
	{
		$this->packet = $packet;
	}
	public function toString()
	{
		return json_encode($this->toArray());
	}
	public function toArray()
	{
		$response = array(
			"packet" => $this->packet,
			"flags" => $this->flags,
			"alerts" => $this->alerts,
			"body" => $this->body,
		);
		return $response;
	}
	public function addFlag($flag)
	{
		array_push($this->flags, $flag);
	}
	public function addAlert($alert)
	{
		array_push($this->alerts, $alert);
	}
	public function setBody($body)
	{
		$this->body = $body;
	}
	public function appendBody($body)
	{
		$this->body .= $body;
	}
};

?>