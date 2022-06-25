<?php

namespace WorldManager\Eventos;

use WorldManager\{
 Loader, 
 Eventos\WorldManagerEvents\WorldChatEvent,
 Eventos\WorldManagerEvents\WorldListEvent,
 Eventos\WorldManagerEvents\WorldLockEvent,
 Eventos\WorldManagerEvents\WorldUnlockEvent,
 Eventos\WorldManagerEvents\WorldReloadEvent,
 Eventos\WorldManagerEvents\WorldCombatEvent,
};

class WorldManagerEvents {
 
 public function __construct() { }
 
 public function reload() {
  return new WorldReloadEvent();
 }
 
 public function list($player) {
  return new WorldListEvent($player);
 }
 
 public function chat($player, $world, $bool) {
  return new WorldChatEvent($player, $world, $bool);
 }
 
 public function lock($player, $world) {
  return new WorldLockEvent($player, $world);
 }
 
 public function unlock($player, $world) {
  return new WorldUnlockEvent($player, $world);
 }
 
 public function combat($player, $world, $bool) {
  return new WorldCombatEvent($player, $world, $bool);
 }
 
}