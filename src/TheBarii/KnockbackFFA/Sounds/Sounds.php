<?php

declare(strict_types=1);

namespace TheBarii\KnockbackFFA\Sounds;

use pocketmine\Player;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\Server;

class Sounds{


    public static function levelupSound(Player $player)
    {
        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "random.levelup";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);
    }

    public static function tickSound(Player $player){

        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "note.hat";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);

    }


    public static function cancelSound(Player $player){

        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "note.bass";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);
    }

    public static function deathSound(Player $player){

        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "conduit.deactivate";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);
    }

    public static function spawnSound(Player $player){

        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "note.pling";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);
    }

    public static function cantHit(Player $player){

        if (is_null($player)) return;
        $sound1 = new PlaySoundPacket();
        $sound1->soundName = "item.shield.block";
        $sound1->x = $player->getX();
        $sound1->y = $player->getY();
        $sound1->z = $player->getZ();
        $sound1->volume = 10;
        $sound1->pitch = 1;
        $player->dataPacket($sound1);

    }


}