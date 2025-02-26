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
          $poss = $ev->getTransaction()->getBlocks();
          foreach ($poss as $pos)
          Main::setData($pos[0] .','. ((int)$pos[1] + 1) .','. $pos[2] .','. $ev->getPlayer()->getWorld()->getFolderName() .','. $item->getNamedTag()->getString('osp') .','. $item->getNamedTag()->getString('customname'));
      }
  }
  
  public function onBreak(BlockBreakEvent $ev) {
      $block = $ev->getBlock();
      $pos = $block->getPosition();
      $ktra = $pos->getX() .','. ((int)$pos->getY() + 1) .','. $pos->getZ() .','. $pos->getWorld()->getFolderName();
      if (Main::existsData($ktra)) {
          $name = Main::removeData($ktra);
          foreach ($ev->getDrops() as $drop) {
              $drop->getNamedTag()->setString('osp', strtolower(explode(' ', $drop->getVanillaName())[0]) .'_ore');
              $drop->setCustomName($name);
          }
      }
  }
}
