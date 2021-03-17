<?php

declare(strict_types=1);

namespace TheBarii\Listeners;

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

class PlayerListener extends Listener{

    public function __construct(KnockbackFFA $plugin){
        $this->plugin=$plugin;
    }

    public function onJoin(PlayerJoinEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setJoinMessage("§r§d+§r§a $n");
        $p->sendMessage("§4§l─────────────────────────────\n§r§k§5l§dl §r§fWelcome to §bKnockback FFA!\n§7Blocks reset every twenty seconds after you place them.\n§r§71 §r§4kill §7will give you one extra §r§6arrow §r§7and an §r§bender pearl.\n§l§4─────────────────────────────\n§r§l§6Teaming is not allowed!");
        $this->setItems($p);

    }

    public function onChat(PlayerChatEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $msg = $e->getMessage();
        if(!$n == "TheBarii") {
            $e->setFormat("§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "TheBarii"){
            $e->setFormat("§4Owner §r§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "Argued168"){

            $e->setFormat("§4Administrator §r§5[§r§6 $n §d] §7§r$msg");
        }elseif($n == "Mo8rty22"){

            $e->setFormat("§4Owner §r§5[§r§6 $n §d] §7§r$msg");

        }
    }

    public function onCraft(CraftItemEvent $e){
        $e->setCancelled();
    }

    public function onQuit(PlayerQuitEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setQuitMessage("§r§c-§r§c $n");

    }

    public function disableCommands(){
        $map=$this->getServer()->getCommandMap();
        $map->unregister($map->getCommand("kill"));
        $map->unregister($map->getCommand("me"));
        $map->unregister($map->getCommand("op"));
        $map->unregister($map->getCommand("deop"));
        $map->unregister($map->getCommand("enchant"));
        $map->unregister($map->getCommand("effect"));
        $map->unregister($map->getCommand("defaultgamemode"));
        $map->unregister($map->getCommand("difficulty"));
        $map->unregister($map->getCommand("spawnpoint"));
        $map->unregister($map->getCommand("setworldspawn"));
        $map->unregister($map->getCommand("title"));
        $map->unregister($map->getCommand("seed"));
        $map->unregister($map->getCommand("particle"));
        $map->unregister($map->getCommand("gamemode"));
        $map->unregister($map->getCommand("tell"));
        $map->unregister($map->getCommand("say"));
        $map->unregister($map->getCommand("reload"));
        $map->unregister($map->getCommand("ban"));
        $map->unregister($map->getCommand("kick"));
        $map->unregister($map->getCommand("ban-ip"));
        $map->unregister($map->getCommand("summon"));
        $map->unregister($map->getCommand("weather"));
        $map->unregister($map->getCommand("pardon"));
        $map->unregister($map->getCommand("pardon-ip"));
    }

    public function onDeath(PlayerDeathEvent $e){
    $p = $e->getPlayer();
    $pn = strtolower($p->getName());
        if ($p instanceof Player) {
            $cause = $p->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    $this->setItems($p);
                    $this->setItems($damager);
                    $finalhealth=round($damager->getHealth(), 1);
                    $dn = $damager->getName();
                    $damager->getInventory()->addItem(Item::get(368, 0, 1));
                    $damager->getInventory()->addItem(Item::get(262, 0, 1));
                    $messages=["quickied", "railed", "clapped", "killed", "smashed", "OwOed", "UwUed", "sent to the heavens"];
                    $dm="§e $pn §7was ".$messages[array_rand($messages)]." by §c $dn §7[".$finalhealth." HP]";
                    $e->setDeathMessage($dm);
                }
            }
        }
    }

    /**
     * @priority LOWEST
     */
    public function onExhaust(PlayerExhaustEvent $event){
        $event->setCancelled();
    }


    public function setItems(Player $p){

        //get items
        $sword = Item::get(268, 0, 1);
        $stick = Item::get(280, 0, 1);
        $bow = Item::get(261, 0, 1);
        $stone = Item::get(24, 0, 64);
        $enderpearl = Item::get(368, 0, 1);
        $arrow = Item::get(262, 0, 1);
        $pickaxe = Item::get(270, 0, 1);

        //set sum shit
        $p->extinguish();
        $p->setScale(1);
        $p->setGamemode(2);
        $p->getInventory()->setSize(36);
        $p->getInventory()->clearAll();
        $p->getArmorInventory()->clearAll();

        //set items
        $p->getInventory()->setItem($sword, 0);
        $p->getInventory()->setItem($stick, 1);
        $p->getInventory()->setItem($bow, 3);
        $p->getInventory()->setItem($enderpearl, 2);
        $p->getInventory()->setItem($stone, 4);
        $p->getInventory()->setItem($arrow, 7);
        $p->getInventory()->setItem($pickaxe, 5);

        //enchants
        $sharpness = Enchantment::get(9);
        $sharpness->setLevel(1);

        $prot = Enchantment::get(0);
        $prot->setLevel(2);

        $kb = Enchantment::get(12);
        $kb->setLevel(1);

        $eff = Enchantment::get(15);
        $eff->setLevel(3);

        $punch = Enchantment::get(20);
        $punch->setLevel(1);

        //enchant items

        $stick->addEnchantment($kb);
        $sword->addEnchantment($sharpness);
        $bow->addEnchantment($punch);
        $pickaxe->addEnchantment($eff);


        //set armor inv
        $p->getInventory()->setHelmet(Item::get(298)->addEnchantment($prot));
        $p->getInventory()->setChestplate(Item::get(299)->addEnchantment($prot));
        $p->getInventory()->setLeggings(Item::get(300)->addEnchantment($prot));
        $p->getInventory()->setBoots(Item::get(301)->addEnchantment($prot));

    }

}