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
use pocketmine\inventory\ArmorInventory;
use pocketmine\inventory\ArmorInventoryEventProcessor;
use pocketmine\item\Armor;
use pocketmine\event\player\PlayerChatEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerPreLoginEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\player\PlayerCommandPreprocessEvent;
use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\entity\EntityDeathEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityRegainHealthEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityLevelChangeEvent;
use TheBarii\KnockbackFFA\Main;
use TheBarii\KnockbackFFA\CPlayer;
use pocketmine\item\enchantment\Enchantment;

class PlayerListener implements Listener{
    public function __construct(Main $plugin){
        $this->plugin=$plugin;
    }


    /**
     * @priority HIGHEST
     */
    function onCreation(PlayerCreationEvent $event){
        $event->setPlayerClass(CPlayer::class);
    }
    /**
     * @priority HIGHEST
     */
    public function onJoin(PlayerJoinEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setJoinMessage("§r§d+§r§a $n");
        $p->sendMessage("§4§l─────────────────────────────\n§r§k§5l§dl §r§fWelcome to §bKnockback FFA!\n§7Blocks reset every twenty seconds after you place them.\n§r§71 §r§4kill §7will give you one extra §r§6arrow §r§7and an §r§bender pearl.\n§l§4─────────────────────────────\n§r§l§6Teaming is not allowed!");
        $this->setItems($p);




    }

    /**
     * @priority LOW
     */

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

        }else{

            $e->setFormat("§r§5[§r§6 $n §d] §7§r$msg");

        }
    }
    /**
     * @priority HIGHEST
     */
    public function onCraft(CraftItemEvent $e){
        $e->setCancelled();
    }

    public function onQuit(PlayerQuitEvent $e){

        $p = $e->getPlayer();
        $n = $p->getName();
        $e->setQuitMessage("§r§c-§r§c $n");

    }


    /**
     * @priority HIGHEST
     */
    public function onDeath(PlayerDeathEvent $e){
    $p = $e->getPlayer();
    $pn = $p->getName();
    $this->setItems($p);
        if ($p instanceof Player) {
            $cause = $p->getLastDamageCause();
            if ($cause instanceof EntityDamageByEntityEvent) {
                $damager = $cause->getDamager();
                if ($damager instanceof Player) {
                    $this->setItems($p);
                    $finalhealth=round($damager->getHealth(), 1);
                    $dn = $damager->getName();
                    $damager->getInventory()->addItem(Item::get(368, 0, 1));
                    $damager->getInventory()->addItem(Item::get(262, 0, 1));
                    $messages=["quickied", "railed", "clapped", "killed", "smashed", "OwOed", "UwUed", "sent to the heavens"];
                    $dm="§e$pn §7was ".$messages[array_rand($messages)]." by §c$dn §7[".$finalhealth." HP]";
                    $e->setDeathMessage($dm);
                }
            }
        }
    }

    public function onRespawn(PlayerRespawnEvent $e){
        $p = $e->getPlayer();
        $this->setItems($p);

    }

    /**
     * @priority LOWEST
     */
    public function onExhaust(PlayerExhaustEvent $event){
        $event->setCancelled();
    }

    /**
     * @priority LOWEST
     */
    public function onDamage(EntityDamageEvent $ev){
        if($ev->getCause() === EntityDamageEvent::CAUSE_FALL){
            $ev->setCancelled();

        }

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
        $helm = Item::get(298);
        $chest = Item::get(299);
        $pant = Item::get(300);
        $boot = Item::get(301);

        //enchants
        $sharpness = Enchantment::getEnchantment(9);

        $prot = Enchantment::getEnchantment(0);

        $kb = Enchantment::getEnchantment(12);

        $eff = Enchantment::getEnchantment(15);

        $punch = Enchantment::getEnchantment(20);

        //enchant items

        $stick->addEnchantment(new EnchantmentInstance($kb, 1));
        $sword->addEnchantment(new EnchantmentInstance($sharpness, 1));
        $bow->addEnchantment(new EnchantmentInstance($punch, 1));
        $pickaxe->addEnchantment(new EnchantmentInstance($eff, 3));
        $helm->addEnchantment(new EnchantmentInstance($prot, 1));
        $chest->addEnchantment(new EnchantmentInstance($prot, 1));
        $boot->addEnchantment(new EnchantmentInstance($prot, 1));
        $pant->addEnchantment(new EnchantmentInstance($prot, 1));

        //set sum shit
        $p->extinguish();
        $p->setScale(1);
        $p->setGamemode(2);
        $p->getInventory()->setSize(36);
        $p->getInventory()->clearAll();
        $p->getArmorInventory()->clearAll();

        //set items
        $p->getInventory()->setItem(0, $sword);
        $p->getInventory()->setItem(1, $stick);
        $p->getInventory()->setItem(3, $bow);
        $p->getInventory()->setItem(2, $enderpearl);
        $p->getInventory()->setItem(4, $stone);
        $p->getInventory()->setItem(7, $arrow);
        $p->getInventory()->setItem(5, $pickaxe);




        //set armor inv
        $p->getArmorInventory()->setHelmet($helm);
        $p->getArmorInventory()->setChestplate($chest);
        $p->getArmorInventory()->setLeggings($pant);
        $p->getArmorInventory()->setBoots($boot);

    }

}