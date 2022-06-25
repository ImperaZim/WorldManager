<?php

namespace WorldManager\Eventos;

use WorldManager\Loader;
use pocketmine\utils\Config;

use pocketmine\ {
 event\Event,
 player\Player,
 event\Listener,
 event\block\BlockBreakEvent,
 event\block\BlockPlaceEvent,
 event\player\PlayerChatEvent,
 event\entity\EntityDamageEvent,
 event\entity\EntityDamageByEntityEvent,
};

class ServerListener implements Listener {

 public function __construct() {}

 public function BlockBreak(BlockBreakEvent $event) {
  $player = $event->getPlayer();
  $plugin = Loader::getInstance();
  $world = $player->getWorld()->getDisplayName();
  $config = new Config($plugin->getDataFolder() . "worlds.yml");
  $worlds = $config->getAll();

  if ($worlds[$world]["protected"] == true) {
   $event->cancel();
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.lock.break.message"))); 
   return true;
  }
 }

 public function BlockPlace(BlockPlaceEvent $event) {
  $player = $event->getPlayer();
  $plugin = Loader::getInstance();
  $world = $player->getWorld()->getDisplayName();
  $config = new Config($plugin->getDataFolder() . "worlds.yml");
  $worlds = $config->getAll();

  if ($worlds[$world]["protected"] == true) {
   $event->cancel();
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.lock.place.message"))); 
   return true;
  }
 }
 
 public function Chat(PlayerChatEvent $event) {
  $player = $event->getPlayer();
  $plugin = Loader::getInstance();
  $world = $player->getWorld()->getDisplayName();
  $config = new Config($plugin->getDataFolder() . "worlds.yml");
  $worlds = $config->getAll();

  if ($worlds[$world]["chat"] == false) {
   $event->cancel();
   $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.lock.message"))); 
   return true;
  }
 }

 public function EntityDamage(EntityDamageEvent $event) {
  $player = $event->getEntity();
  $plugin = Loader::getInstance();
  $world = $player->getWorld()->getDisplayName();
  $config = new Config($plugin->getDataFolder() . "worlds.yml");
  $worlds = $config->getAll();

  if ($player instanceof Player) {
   if ($worlds[$world]["combat"] == false) {
    $event->cancel();
    $player->sendTip($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.alert.message")));
    return true;
   }
  }
 }

}
