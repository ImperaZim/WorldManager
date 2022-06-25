<?php

namespace WorldManager;

use WorldManager\{
 Commands\WorldManager,
 Eventos\ServerListener, 
 Eventos\WorldManagerEvents, 
};

use pocketmine\{
 Server, 
 utils\Config,
 player\Player, 
 plugin\PluginBase, 
 utils\TextFormat as C, 
};

class Loader extends PluginBase {
 
 public static $instance = null;
 
 public static function getInstance() : Loader {
		return self::$instance;
	}
 
 public function onEnable() : void {
  self::$instance = $this; 
  $this->registerEvents();
  $this->registerCommands();
  $this->getWorldEvents()->reload();
  $this->getConfig()->get("command.help");
  $this->getLogger()->info("§aPlugin WorldManager ativado!");
 } 
 
 public function onDisable() : void {
  $this->getLogger()->info("§cPlugin WorldManager desativado");
 } 
 
 public function getWorldEvents() {
  return new WorldManagerEvents();
 }
 
 public function registerCommands() {
  $map = $this->getServer()->getCommandMap();
		$map->register("worldmanager", new WorldManager());   
 }
 
 public function registerEvents() {
  $manager = $this->getServer()->getPluginManager();
  $manager->registerEvents(new ServerListener(), $this);
 }  
 
 public function getProcessedTags($player, $world, $message) {
  $prefix = $this->getConfig()->get("plugin.prefix");;
  $tags = ["{world}", "{prefix}"];
  $processed = [$world, $prefix];
  $message = str_replace($tags, $processed, $message);
  return $message;
 }
 
}
