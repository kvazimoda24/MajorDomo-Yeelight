<?php
if (preg_match("/(m=getdata|action=Yeelight)/", $params['SOURCE'])) return;
//========= метод set_mode (смена режима работы) ===================
$debug=true;
$debug2file=true;
include_once(DIR_MODULES.'Yeelight/Yeelight_library.php');
$Location = $this->getProperty('Location');
$id = $this->getProperty('id');
$mode = $this->getProperty('active_mode');
if ($mode == 0) $mode = 1;
elseif ($mode == 1) $mode = 5;
$classname="Yeelight";
$power = 'on';
$data = [
    "Location" => $Location,
    "id" => $id, 
];
$socketFactory = new Factory();
$bulbFactory = new BulbFactory($socketFactory);
$bulb = $bulbFactory->create($data);
$res = $bulb->setPower($power, 'smooth', 1000, $mode); // режим работы
if($debug) {
    DebMes("send commant power:" . json_encode($power),$classname);
    DebMes("response:" . json_encode($res,true),$classname);
}

if (array_key_exists('result', $res)) {
    $result = $res['result'][0];
    //переменная содержит ответ от лампочки
}
if (array_key_exists('error', $res)) {
    $result = $res['error']['message'].". Code ".$res['error']['code'];
	$model=$this->getProperty('model');
    DebMes("Ошибка включения/выключения  Yeelight устройства ".$Location.", модель: ".$model);
    //DebMes("Ошибка включения/выключения Yeelight: ".$result);
}