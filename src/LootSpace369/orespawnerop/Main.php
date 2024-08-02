<?php

declare(strict_type=1);

namespace LootSpace369\orespawnerop;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\utils\TextFormat as TF;
use pocketmine\item\StringToItemParser;
use pocketmine\utils\Config;

class Main extends PluginBase {

  public static ?Config $data;
  
  protected function onEnable(): void {   
      self::$data = new Config($this->getDataFolder() .'data.yml', Config::YAML, ['osp' => []]);
      $this->getServer()->getPluginManager()->registerEvents(new EventListener(), $this);
    
      $this->getLogger()->notice('Ore Spawner OP on enable!');

      $this->getScheduler()->scheduleRepeatingTask(new UpdateOspTask(), 20);
  }

  public static function getData(): array {
      return self::$data->get('osp');
  }

  public static function setData(string $data): void {
      self::$data->set('osp', array_merge($data, self::getData()));
  }

  public static function removeData(string $data): void {
      foreach (self::getData() as $osp) {
          $exosp = explode(',', $osp);
        
          if ($data == $exosp[0] .','. ((int)$exosp[1] + 1) .','. $exosp[2] .','. $exosp[3]) {
              unset(self::$data['osp'][$osp]);
            
              break;
          }
      }
  }

  public static function existsData(string $data): bool {
      foreach (self::getData() as $osp) {
          $exosp = explode(',', $osp);
          if ($data == $exosp[0] .','. ((int)$exosp[1] + 1) .','. $exosp[2] .','. $exosp[3]) {
              return true;
          }
      }
      return false;
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

      $player->getInventory()->addItem($item);
  }
}
