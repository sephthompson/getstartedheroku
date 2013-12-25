
<?php

class Template
{
	public $file = NULL;
	
	public function __construct($file)
	{
		$this->file = $file;
	}
	public function toString()
	{
		ob_start();
		include($this->file);
		$text = ob_get_clean();
		return $text;
	}
	public function render()
	{
		include($this->file);
	}
};

?>