<?php

namespace antbag\JoinCount;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\Server;

class PlayerJoinListener implements Listener {

  private $dataFile;

  public function __construct(string $dataFilePath) {
    $this->dataFile = $dataFilePath;
  }

  public function onPlayerJoin(PlayerJoinEvent $event) {
    $player = $event->getPlayer();
    $playerName = $player->getName();
    $data = $this->loadData(); 
    if (!isset($data[$playerName])) {
      $data[$playerName] = true; 
      $this->saveData($data); 
      $totalPlayers = count($data);
      Server::getInstance()->broadcastMessage("§8Welcome,§c $playerName! §8You are the #§8 $totalPlayers §8player to join our server!");
    }
  }

  private function loadData(): array {
    $data = file_get_contents($this->dataFile);
    return json_decode($data, true) ?? [];
    if (!file_exists($this->dataFile)) {
      return [];
    }
  }

  private function saveData(array $data) {
    $encodedData = json_encode($data);
    file_put_contents($this->dataFile, $encodedData);
  }
}