<?php

namespace TheBarii\KnockbackFFA\commands;

use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\server\DataPacketReceiveEvent;
use pocketmine\network\mcpe\protocol\NetworkStackLatencyPacket;
use pocketmine\Player;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\plugin\Plugin;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use TheBarii\KnockbackFFA\Main;

class PingCommand extends PluginCommand implements Listener{

    /** @var Plugin - (to make IntellIJ shut up) */
	private $plugin;
	private $networkSessions = [];
	
	public function __construct(Main $plugin){
		parent::__construct("ping", $plugin);
		$this->plugin=$plugin;
		$this->setAliases(["ms"]);
		Server::getInstance()->getPluginManager()->registerEvents($this, $plugin);
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!isset($args[0]) and $sender instanceof Player){
		    $session = $this->networkSessions[$sender->getName()] ?? null;
			if($session !== null){
                $sender->sendMessage("§aYour ping is ".$session['latency']."ms.");
            } else {
			    $sender->sendMessage(TextFormat::RED . 'Something went wrong while getting your ping.');
            }
			return;
		}
		if(isset($args[0]) and ($target=$this->plugin->getServer()->getPlayer($args[0]))===null){
			$sender->sendMessage("§cPlayer not found.");
			return;
		}
		$target=$this->plugin->getServer()->getPlayer($args[0]);
		if($target instanceof Player){
		    $session = $this->networkSessions[$target->getName()] ?? null;
		    if($session !== null){
                $sender->sendMessage("§a".$target->getName()."'s ping is ".$session['latency']."ms.");
            } else {
                $sender->sendMessage(TextFormat::RED . 'Something went wrong while getting the ping of ' . $target->getName());
            }
		}
	}

	public function onJoin(PlayerJoinEvent $event) : void{
	    $packet = new NetworkStackLatencyPacket();
	    $packet->timestamp = mt_rand(10, 1000000) * 1000;
	    $packet->needResponse = true;
	    $this->networkSessions[$event->getPlayer()->getName()] = ['timestamp' => $packet->timestamp, 'lastSend' => microtime(true), 'hasResponded' => false, 'latency' => -1];
	    $event->getPlayer()->dataPacket($packet);
        $player = $event->getPlayer();
        $task = new ClosureTask(function(int $currentTick) use($player, &$task) : void{
            $session = $this->networkSessions[$player->getName()] ?? null;
            if($session === null){
                $this->plugin->getScheduler()->cancelTask($task->getTaskId());
            } else {
                $player->updatePing((int)($session['latency']));
                if(microtime(true) - $session['lastSend'] >= 5 && $session['hasResponded']){
                    $packet = new NetworkStackLatencyPacket();
                    $packet->timestamp = mt_rand(10, 1000000) * 1000;
                    $packet->needResponse = true;
                    $this->networkSessions[$player->getName()]['timestamp'] = $packet->timestamp;
                    $this->networkSessions[$player->getName()]['lastSend'] = microtime(true);
                    $this->networkSessions[$player->getName()]['hasResponded'] = false;
                    $player->dataPacket($packet);
                }
            }
        });
	    $this->plugin->getScheduler()->scheduleRepeatingTask($task, 1);
    }

    public function onLeave(PlayerQuitEvent $event) : void{
	    unset($this->networkSessions[$event->getPlayer()->getName()]);
    }

    public function onReceivePacket(DataPacketReceiveEvent $event) : void{
	    $packet = $event->getPacket();
	    $player = $event->getPlayer();
	    if($packet instanceof NetworkStackLatencyPacket && isset($this->networkSessions[$player->getName()]) && $packet->timestamp === $this->networkSessions[$player->getName()]['timestamp']){
            $this->networkSessions[$player->getName()]['hasResponded'] = true;
            $latency = round((microtime(true) - $this->networkSessions[$player->getName()]['lastSend']) * 1000);
            $this->networkSessions[$player->getName()]['latency'] = $latency;
        }
    }

}