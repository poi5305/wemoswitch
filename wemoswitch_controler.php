<?php
include("wemoswitch_model.php");	

$ip = "10.0.1.98";
$switch = new WemoSwitch($ip);
//$switch->state();
//$switch->start();
//$switch->stop();

// For command
if(@$argc > 1)
{
	for($i=1; $i<$argc; $i++)
	{
		$t = explode("=", $argv[$i]);
		$_GET[ $t[0] ] = $t[1];
	}
}

$cmd = $_GET["cmd"];
//ps -eO etimes| grep 'wemoswitch' |head -n1 | awk '{print $2}'
switch($cmd)
{
	case "start":
		$switch->start();
		break;
	case "stop":
		$switch->stop();
		break;
	case "state":
		$total_time = 0;
		$runtime = trim( shell_exec("ps -eO etimes| grep 'delay_stop_impl' |head -n1 | awk '{print $2}'") );
		if($runtime != 0)
			$total_time = trim( shell_exec("ps -eO etimes| grep 'delay_stop_impl' |head -n1 | awk '{print $9}' | cut -d'=' -f2") );
		$remain_time = $total_time - $runtime;
		
		$r = array();
		$r["state"] = $switch->state();
		$r["remain_time"] = $remain_time;
		echo json_encode($r);
		break;
	case "delay_stop":
		$switch->start();
		$pid = trim( shell_exec("ps -eO etimes| grep 'delay_stop_impl' |head -n1 | awk '{print $1}'") );
		shell_exec("kill $pid >/dev/null 2>/dev/null");
		$time = $_GET["time"];
		$cmd_line = "php ".__FILE__." cmd=delay_stop_impl time=$time > /dev/null &";
		exec($cmd_line);
		break;
	case "delay_stop_impl":
		$time = $_GET["time"];
		sleep($time);
		$switch->stop();
		break;
}



?>