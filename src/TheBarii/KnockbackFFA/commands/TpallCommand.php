<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\commands;

use pocketmine\Player;
use pocketmine\command\PluginCommand;
use pocketmine\command\CommandSender;
use pocketmine\level\Position;
use TheBarii\KnockbackFFA\Main;


class TpallCommand extends PluginCommand{
	
	private $plugin;
	
	public function __construct(Main $plugin){
		parent::__construct("tpall", $plugin);
		$this->plugin=$plugin;
		$this->setDescription("§bTeleport all players on the server to you");
		$this->setPermission("kb.tpall");
	}
	public function execute(CommandSender $player, string $commandLabel, array $args){
		if(!$player->hasPermission("kb.tpall")){
			$player->sendMessage("§cYou cannot execute this command.");
			return;
		}
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->getName()!=$player->getName() and count($this->plugin->getServer()->getOnlinePlayers()) > 1){
				$online->teleport(new Position($player->getX(), $player->getY(), $player->getZ(), $player->getLevel()));
			}
		}
		$player->sendMessage("§aAll players have been teleported to you.");
		$message=$this->plugin->getStaffUtils()->sendStaffNoti("tpall");
		$message=str_replace("{name}", $player->getName(), $message);
		foreach($this->plugin->getServer()->getOnlinePlayers() as $online){
			if($online->hasPermission("staff.notif")){
				$online->sendMessage($message);
			}
		}
	}
}