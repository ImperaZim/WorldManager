<?php

namespace WorldManager\Commands;

use WorldManager\Loader;

use pocketmine\ {
 player\Player,
 command\Command,
 command\CommandSender,
 command\PluginCommand,
 Utils\TextFormat as C,
};

class WorldManager extends Command {

 public function __construct() {
  parent::__construct("worldmanager", "§7World manager settings!");
  parent::setAliases(["wm", "WorldManager"]);
 }

 public function execute(CommandSender $player, string $commandLabel, array $args) : bool {

  $plugin = Loader::getInstance();
  $server = $plugin->getServer();
  $player = $server->getPlayerExact($player->getName());
  $op = $server->isOp($player->getName());
  $permission = $player->HasPermission("world.manager");

  if ($player instanceof Player) {
   $world = $player->getWorld()->getDisplayName();
   if ($permission == true) { } else {
    if ($op == true) { } else {
     $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.nopermission"))); 
     return true;
    }
   }
   if (isset($args[0])) {
    if ($args[0] == "lock") {
     $plugin->getWorldEvents()->lock($player, $world);
     return true;
    }
    if ($args[0] == "unlock") {
     $plugin->getWorldEvents()->unlock($player, $world);
     return true;
    }
    if ($args[0] == "combat") {
     if (isset($args[1])) {
      if ($args[1] == "on") {
       $bool = true;
      }
      if ($args[1] == "off") {
       $bool = false;
      }
      if ($args[1] == "on" || $args[1] == "off") {
       $plugin->getWorldEvents()->combat($player, $world, $bool);
      } else {
       $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.noexist")));
       $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.help")));
       return true;
      }
     } else {
      $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.combat.help")));
     }
     return true;
    }
    if ($args[0] == "chat") {
     if (isset($args[1])) {
      if ($args[1] == "unlock") {
       $bool = true;
      }
      if ($args[1] == "lock") {
       $bool = false;
      }
      if ($args[1] == "unlock" || $args[1] == "lock") {
       $plugin->getWorldEvents()->chat($player, $world, $bool);
      } else {
       $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.noexist")));
       $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.help")));
       return true;
      }
     } else {
      $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.chat.help")));
     }
     return true;
    }
    if($args[0] == "list"){
     $plugin->getWorldEvents()->list($player);
     return true;
    }
    $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.help")));
   } else {
    $player->sendMessage($plugin->getProcessedTags($player, $world, $plugin->getConfig()->get("command.help")));
   }
  } else {
   $server->getLogger()->warning("Comando indisponivel no console! Tente usar o comando no servidor");
  }

  return true;
 }

}
