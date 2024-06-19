<?php

namespace KumaDev\HideSystem\Event;

use pocketmine\player\Player;
use pocketmine\Server;

class HideManager {

    private array $hiddenNameTags = [];
    private array $hiddenPlayers = [];
    private array $nameTagHiddenFor = [];
    private array $playerHiddenFor = [];

    public function hideNameTags(Player $player): void {
        $this->hiddenNameTags[$player->getName()] = [];
        $this->nameTagHiddenFor[$player->getName()] = true;
        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            if ($p !== $player) {
                $this->hiddenNameTags[$player->getName()][$p->getName()] = $p->getNameTag();
                $p->setNameTag("");
            }
        }
    }

    public function showNameTags(Player $player): void {
        if (isset($this->hiddenNameTags[$player->getName()])) {
            foreach (Server::getInstance()->getOnlinePlayers() as $p) {
                if (isset($this->hiddenNameTags[$player->getName()][$p->getName()])) {
                    $p->setNameTag($this->hiddenNameTags[$player->getName()][$p->getName()]);
                }
            }
            unset($this->hiddenNameTags[$player->getName()]);
            unset($this->nameTagHiddenFor[$player->getName()]);
        }
    }

    public function isNameTagHidden(Player $player): bool {
        return isset($this->hiddenNameTags[$player->getName()]);
    }

    public function hidePlayers(Player $player): void {
        $this->hiddenPlayers[$player->getName()] = [];
        $this->playerHiddenFor[$player->getName()] = true;
        foreach (Server::getInstance()->getOnlinePlayers() as $p) {
            if ($p !== $player) {
                $player->hidePlayer($p);
                $this->hiddenPlayers[$player->getName()][] = $p->getName();
            }
        }
    }

    public function showPlayers(Player $player): void {
        if (isset($this->hiddenPlayers[$player->getName()])) {
            foreach (Server::getInstance()->getOnlinePlayers() as $p) {
                if (in_array($p->getName(), $this->hiddenPlayers[$player->getName()])) {
                    $player->showPlayer($p);
                }
            }
            unset($this->hiddenPlayers[$player->getName()]);
            unset($this->playerHiddenFor[$player->getName()]);
        }
    }

    public function isPlayerHidden(Player $player): bool {
        return isset($this->hiddenPlayers[$player->getName()]);
    }

    public function onPlayerJoin(Player $player): void {
        foreach ($this->nameTagHiddenFor as $playerName => $status) {
            if ($status) {
                $playerToHide = Server::getInstance()->getPlayerExact($playerName);
                if ($playerToHide !== null) {
                    $this->hiddenNameTags[$playerToHide->getName()][$player->getName()] = $player->getNameTag();
                    $player->setNameTag("");
                }
            }
        }

        foreach ($this->playerHiddenFor as $playerName => $status) {
            if ($status) {
                $playerToHide = Server::getInstance()->getPlayerExact($playerName);
                if ($playerToHide !== null) {
                    $playerToHide->hidePlayer($player);
                }
            }
        }
    }
}