<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config;  

class WorldUnlockEvent {
 
 public function __construct($user, $world) {
  $player = $user;
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $world = $player->getWorld()->getDisplayName();
  $config = new Config($data . "worlds.yml");
  $worlds = $config->getAll(); 
  
  if($worlds["$world"]["protected"] == false){
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.unlock.has")));        
   return true;
  }
  
  $config->setNested("$world.protected", false);
  $config->save(); 
  $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.unlock.sucess")));       
 }
 
}