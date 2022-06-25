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
 Player\Player, 
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
 
 public function generateChunk($chunkX, $chunkZ)
 {
     $this->random->setSeed(0xdeadbeef ^ $chunkX << 8 ^ $chunkZ ^ $this->level->getSeed());
     $noise = Generator::getFastNoise3D($this->noiseBase, 16, 128, 16, 4, 8, 4, $chunkX * 16, 0, $chunkZ * 16);
     $chunk = $this->level->getChunk($chunkX, $chunkZ);
     for ($x = 0; $x < 16; ++$x) {
         for ($z = 0; $z < 16; ++$z) {
             $biome = Biome::getBiome(Biome::HELL);
             $chunk->setBiomeId($x, $z, $biome->getId());
             $color = [0, 0, 0];
             $bColor = $biome->getColor();
             $color[0] += ($bColor >> 16) ** 2;
             $color[1] += ($bColor >> 8 & 0xff) ** 2;
             $color[2] += ($bColor & 0xff) ** 2;
             $chunk->setBiomeColor($x, $z, $color[0], $color[1], $color[2]);
             for ($y = 0; $y < 128; ++$y) {
                 if ($y === 0 or $y === 127) {
                     $chunk->setBlockId($x, $y, $z, Block::BEDROCK);
                     continue;
                 }
                 $noiseValue = abs($this->emptyHeight - $y) / $this->emptyHeight * $this->emptyAmplitude - $noise[$x][$z][$y];
                 $noiseValue -= 1 - $this->density;
                 if ($noiseValue > 0) {
                     $chunk->setBlockId($x, $y, $z, Block::NETHERRACK);
                 } elseif ($y <= $this->waterHeight) {
                     $chunk->setBlockId($x, $y, $z, Block::STILL_LAVA);
                 }
             }
         }
     }
     foreach ($this->generationPopulators as $populator) {
         $populator->populate($this->level, $chunkX, $chunkZ, $this->random);
     }
 } 
 
}
