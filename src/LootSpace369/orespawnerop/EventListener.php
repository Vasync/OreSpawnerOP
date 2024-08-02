<?php

declare(strict_types=1);

namespace LootSpace369\orespawnerop;

use pocketmine\event\Listener;
use pocketmine\item\ItemBlock;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;

class EventListener implements Listener {

  public function onPlace(BlockPlaceEvent $ev) {
      $item = $ev->getItem();
    
      if ($item->getNamedTag()->getTag('osp') !== null) {
          $pos = $ev->getTransaction()->getBlocks();
          Main::setData($pos[0] .','. ($pos[1] + 1) .','. $pos[2] .','. $ev->getPlayer()->getWorld()->getFolderName() .','. $item->getNamedTag()->getString('osp'));
      }
  }
  
  public function onBreak(BlockBreakEvent $ev) {
      $block = $ev->getBlock();
      $pos = $block->getPosition();
      $ktra = $pos[0] .','. ($pos[1] + 1) .','. $pos[2] .','. $pos->getWorld()->getFolderName();
      if (Main::existsData($ktra)) {
          Main::removeData($ktra);
          foreach ($ev->getDrops() as $drop) {
              $drop->getNamedTag()->setString('osp', explode('_', $drop->getVanillaName())[0] .'_ore');
          }
      }
  }
}
