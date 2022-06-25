<?php

namespace WorldManager\Eventos\WorldManagerEvents;

use WorldManager\Loader;
use pocketmine\utils\Config;  

class WorldListEvent {
 
 public function __construct($user) {
  $player = $user;
  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $data = $plugin->getDataFolder();
  $config = new Config($data . "worlds.yml");
  $world = $player->getWorld()->getDisplayName();  
  $worlds = $config->getAll(); 
  
  $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.list.message"))); 
  
  $worldsArray = scandir($server->getDataPath() . "worlds/");
  foreach ($worldsArray as $world) {
   if ($world === "." || $world === "..") { continue; }
   
   $name = $worlds[$world]["name"];
   $chat = $worlds[$world]["chat"];
   $combat = $worlds[$world]["combat"];
   $protected = $worlds[$world]["protected"];
   
   $chat = $chat == true ? "§atrue" : "§cfalse";
   $combat = $combat == true ? "§atrue" : "§cfalse";
   $protected = $protected == true ? "§atrue" : "§cfalse";
   
   $player->sendMessage("§b[{$name}]§7 Chat: {$chat} §b| §7Protected: {$protected} §b| §7Combat: {$combat}");
  }
  $player->sendMessage("§b ");
  
 }
 
} 