<?php
/**
 * This class setup the game and have the rules
 */

namespace SnakesAndLadders\Lib;

use SnakesAndLadders\Lib\Player;
use SnakesAndLadders\Lib\Token;
use SnakesAndLadders\Lib\Dice;

final class Game 
{
    private $snakesandladders;

    public function __construct()
    {
        $snakes = [
            16 => [6, 'snake'],
            46 => [25, 'snake'],
            49 => [11, 'snake'],
            62 => [19, 'snake'],
            64 => [60, 'snake'],
            74 => [53, 'snake'],
            89 => [68, 'snake'],
            92 => [88, 'snake'],
            95 => [75, 'snake'],
            99 => [80, 'snake']
        ];

        $ladders = [
            2 => [38, 'ladder'],
            7 => [14, 'ladder'],
            8 => [31, 'ladder'],
            15 => [26, 'ladder'],
            21 => [42, 'ladder'],
            28 => [84, 'ladder'],
            36 => [44, 'ladder'],
            51 => [67, 'ladder'],
            71 => [91, 'ladder'],
            78 => [98, 'ladder'],
            87 => [94, 'ladder']
        ];

        $this->snakesandladders = $snakes + $ladders;
    }


    /**
     * Check if player Win 
     *
     * @return void
     */
    public function checkPlayerStatus(Player $player)
    {
        $player->setOutOfBounds(false);
        $position = $player->getPosition();

        if($position == 100)
        {
            $player->setWin();
        }
        
        if($position > 100)
        {
            $player->moveToSquare($player->getOldPosition());
            $player->setOutOfBounds(true);
        }
    }


    /**
     * Check square type of player position
     *
     * @param Player $player
     * @return void
     */
    public function checkPlayerPosition(Player $player)
    {
        $position = $player->getPosition();

        $square = ['type' => 'normal', 
                   'position' => $position
                  ];
        
        if(array_key_exists($position, $this->snakesandladders))
        {
            $player->moveToSquare($this->snakesandladders[$position][0]);
            $squaretype = $this->snakesandladders[$position][1];

            $square = ['type' => $squaretype, 
                       'position' => $this->snakesandladders[$position][0]
                      ];
        }

        return $square;
    }


    /**
     * Create a new Player
     *
     * @return void
     */
    public function addPlayer()
    {
        return new Player(new Token, new Dice);  
    }

}