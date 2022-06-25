<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config;   

class WorldChatEvent {
 
 public function __construct($user, $world, $bool) {
  $player = $user;
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $config = new Config($data . "worlds.yml");
  $worlds = $config->getAll();
  
  if($bool == true){
   if($worlds["$world"]["chat"] == true){
    $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.unlock.has")));
    return true;
   }
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.unlock.sucess")));
  }
  
  if($bool == false){
   if($worlds["$world"]["chat"] == false){
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.lock.has")));
    return true;
   }
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.lock.sucess")));
  }
  
  $config->setNested("$world.chat", $bool); 
  $config->save(); 
 }
 
}  
