<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config; 

class WorldLockEvent {
 
 public function __construct($user, $world) {
  $this->execute($user, $world);
 } 
 
 public function execute($player, $world) {
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $world = $player->getWorld()->getDisplayName();  
  $config = new Config($data . "worlds.yml");
  $worlds = $config->getAll(); 
  
  if($worlds[$world]["protected"] == true){
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.lock.has")));       
   return true;
  }
  
  $config->setNested("$world.protected", true);
  $config->save();
  $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.lock.sucess")));       
 }
 
}