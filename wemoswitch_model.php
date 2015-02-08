<?php
	
include("WeMo-PHP-Toolkit/models/Device.php");
include("WeMo-PHP-Toolkit/models/Outlet.php");

//ignore_user_abort
class WemoSwitch
{
	var $print_log = false;
	var $outlet;
	
	public function WemoSwitch($ip)
	{
		//echo "WemoSwitch constructure\n";
		$this->outlet = new \wemo\models\Outlet($ip);
	}
	public function start()
	{
		$this->log("start\n");
		$this->outlet->setIsOn(true);
	}
	public function stop()
	{
		$this->log("stop\n");
		$this->outlet->setIsOn(false);
	}
	public function state()
	{
		$this->log("state\n");
		return $this->outlet->getIsOn();
		
	}
	private function log($msg)
	{
		if(!$this->print_log)
			return;
		echo $msg;
	}
	
};
?>