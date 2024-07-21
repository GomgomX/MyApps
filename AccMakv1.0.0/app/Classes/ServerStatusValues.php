<?php
namespace App\Classes;

class ServerStatusValues {
	private $statusTimeout = 1;
	private $serverStatus = [];
	private $statusInfo;
	private $file;

	public function __construct($serverConfig = [], $path = false) {
		if($path && strtolower(basename(getcwd()) == "public"))
		{
			$this->file = $path;
			foreach(explode("*", str_replace(" ", "", $serverConfig['statusTimeout'])) as $status_var) {
				if($status_var > 0) {
					$this->statusTimeout = $this->statusTimeout * $status_var;
				}
			}

			$this->statusTimeout = $this->statusTimeout/1000;
			if($this->isFile()) {
				$this->serverStatus = parse_ini_file($this->file);
			}

			if(!isset($this->serverStatus['serverStatus_lastCheck'])) {
				$this->serverStatus['serverStatus_lastCheck'] = "0";
			}

			if($this->serverStatus['serverStatus_lastCheck']+$this->statusTimeout < time()) {
				$this->serverStatus['serverStatus_checkInterval'] = $this->statusTimeout+3;
				$this->serverStatus['serverStatus_lastCheck'] = time();
				$this->statusInfo = new ServerStatus("127.0.0.1", $serverConfig['statusPort'], 1);
				if($this->statusInfo->isOnline()) {
					$this->setOnline();
				}
				else {
					$this->setOffline();
				}
				$this->writeFile();
			}
		}
	}

	public function isFile() {
		return is_file($this->file);
	}

	public function setOnline() {
		$this->serverStatus['serverStatus_online'] = 1;
		$this->serverStatus['serverStatus_players'] = $this->statusInfo->getPlayersCount();
		$this->serverStatus['serverStatus_playersMax'] = $this->statusInfo->getPlayersMaxCount();
		$h = floor($this->statusInfo->getUptime()/3600);
		$m = floor(($this->statusInfo->getUptime()-$h*3600)/60);
		$this->serverStatus['serverStatus_uptime'] = $h.'h '.$m.'m';
		$this->serverStatus['serverStatus_monsters'] = $this->statusInfo->getMonsters();
	}

	public function setOffline() {
		$this->serverStatus['serverStatus_online'] = 0;
		$this->serverStatus['serverStatus_players'] = 0;
		$this->serverStatus['serverStatus_playersMax'] = 0;
	}

	public function writeFile() {
		$file = fopen($this->file, "w");
		$file_data = '';
		foreach($this->serverStatus as $param => $data)
		{
			$safe_data = str_replace('"', '', $data);
			$safe_data = str_replace("\n", '', $safe_data);
			$safe_data = str_replace("\r", '', $safe_data);
			$file_data .= $param.' = "'.$safe_data.'"'."\n";
		}
		rewind($file);
		fwrite($file, $file_data);
		fclose($file);
	}

	public function getStatus() {
		return $this->serverStatus;
	}
}