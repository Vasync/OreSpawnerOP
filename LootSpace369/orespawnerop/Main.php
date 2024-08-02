<?php

declare(strict_type=1);

namespace LootSpace369\orespawnerop;

use pocketmine\plugin\PluginBasse;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\Config;

class Main extends PluginBase {

  public static ?Config $data;
  
  protected function onEnable(): void {
    self::$data = new Config($this->getDataFolder() .'data.yml', Config::YAML);
    
    $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    
    $this->getLogger()->notice('Ore Spawner OP on enable!');
  }
  
  public function onCommand(CommandSender $player, Command $command, string $label, array $args): bool {
      if ($command->getName() === "orespawner") {
          if($player instanceof Player) {
            
              $type = ["coal", "lapis", "iron", "gold", "diamond", "emerald", "redstone"];
            
              if (!isset($args[0]) or !in_array($args[0], $type)) { $player->sendMessage(TF::RED .'Please, input ore spawner type!'); return false; }

              if (in_array($args[0], $type)) {
                  if (!isset($args[1])) { $this->giveOSP($player, $args[0]); return true; }
                  $this->giveOSP($player, $args[0], (string)$args[1]);
                  
                  return true;
              }
          }
      }
    return false;
  }

  public function giveOSP(Player $player, string $type, $name = 'ore spawner') {

      $item = StringToItemParser::getInstance()->parse($type.'_block')->setCustomName($name);
      $item->getNamedTag()->setString('osp', $type.'_ore');

      $player->addItem($item);
  }
}
