<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Listeners;

use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\Server;

use pocketmine\item\Item;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\event\player\PlayerCreationEvent;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use TheBarii\KnockbackFFA\Main;
use TheBarii\KnockbackFFA\Tasks\BlockReset;

class BlockListener implements Listener{
    public function __construct(Main $plugin){
        $this->plugin=$plugin;
    }
    public function onPlace(BlockPlaceEvent $e){
        $block = $e->getBlock();
        $x = $block->getX();
        $y = $block->getY();
        $z = $block->getZ();
        $this->plugin->getScheduler()->scheduleDelayedTask(new BlockReset($block, $x, $y, $z), 350);
    }
   public function onBreak(BlockBreakEvent $e){

        $blockID = $e->getBlock()->getID();
        if($blockID == 168 or $blockID == 209 or $blockID == 181 or $blockID == 182 or $blockID == 44) {
               $e->setCancelled();
        }
    }
}