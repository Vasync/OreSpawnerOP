<?php

declare(strict_types = 1);

namespace LootSpace369\orespawnerop;

use pocketmine\world\Position;
use pocketmine\scheduler\Task;
use pocketmine\world\World;
use pocketmine\Server;

class UpdateOspTask extends Task {
  
  public function __construct() {}

  public function onRun(): void {
      foreach (Main::getData() as $pos) {
          $ex = explode($pos);
        
          Server::getInstance()->getWorldManager()->getWorldByName($ex[3])->setBlockAt($ex[0], $ex[1], $ex[2], \pocketmine\item\StringToItemParser::getInstance()->parse($ex[4]));
      }
  }
}