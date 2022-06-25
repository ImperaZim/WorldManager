<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config;

class WorldReloadEvent {

 public function __construct() {
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $config = new Config($data . "worlds.yml");
  $worlds = $config->getAll();

  $worldsArray = scandir($server->getDataPath() . "worlds/");
  foreach ($worldsArray as $world) {
   if ($world === "." || $world === "..") {
    continue;
   }
   if (!isset($worlds[$world])) {
    $config = new Config($data . "worlds.yml", Config::YAML, [
     "$world" => [
      "name" => "$world",
      "chat" => true,
      "combat" => true,
      "protected" => false,
     ]
    ]);
    $config->save();
    $server->getLogger()->notice("{$world} adicionado na pasta");
   }
  }
 }

}