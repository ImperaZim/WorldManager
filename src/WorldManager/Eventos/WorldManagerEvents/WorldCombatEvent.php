<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config;   

class WorldCombatEvent {
 
 public function __construct($user, $world, $bool) {
  $player = $user;
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $config = new Config($data . "worlds.yml");
  $worlds = $config->getAll();
  
  if($bool == true){
   if($worlds["$world"]["combat"] == true){
    $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.enable.has")));   
    return true;
   }
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.enable.sucess")));   
  }
  
  if($bool == false){
   if($worlds["$world"]["combat"] == false){
    $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.disable.has")));   
    return true;
   }
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.disable.sucess"))); 
  }
  
  $config->setNested("$world.combat", $bool); 
  $config->save(); 
 }
 
} 