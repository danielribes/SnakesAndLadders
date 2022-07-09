<?php
/**
 * This class has the player state
 */
 
namespace SnakesAndLadders\Lib;

use SnakesAndLadders\Lib\Token;
use SnakesAndLadders\Lib\Dice;

final class Player
{
    private $token;
    private $dice;
    private $hasWin;
    private $outOfBounds;

    public function __construct(Token $token, Dice $dice)
    {
        $this->token = $token;
        $this->dice = $dice; 
        $this->hasWin = false;
        $this->outOfBounds = false;
    }


    /**
     * Set the state to know if the player can move to the last square or not
     *
     * @param [type] $booleanstatus
     * @return void
     */
    public function setOutOfBounds($booleanstatus)
    {
        $this->outOfBounds = $booleanstatus; 
    }


    /**
     * Return the state to know if the player can move to the last square or not
     *
     * @return void
     */
    public function checkOutOfBounds()
    {
        return $this->outOfBounds;
    }


    /**
     * Move Token to dice value
     *
     * @param [type] $positions
     * @return void
     */
    public function moveToken($positions)
    {
        $newposition = $this->token->getPosition() + $positions;
        $this->token->setPosition($newposition);

        return $newposition;
    }

    
    /**
     * Set player as Winner
     *
     * @return void
     */
    public function setWin()
    {
        $this->hasWin = true;
    }

    
    /**
     * Get Winner status
     *
     * @return void
     */
    public function getWin()
    {
        return $this->hasWin;
    }


    /**
     * Move Token to specific square
     *
     * @param [type] $square
     * @return void
     */
    public function moveToSquare($square)
    {
        $this->token->setPosition($square);
    }

    
    /**
     * Get old player position
     *
     * @return void
     */
    public function getOldPosition()
    {
        return $this->token->getOldPosition();
    }


    /**
     * Player Rolls dices
     *
     * @return void
     */
    public function rollsADie()
    {
        return $this->dice->roll();
    }


    /**
     * Get player token position
     *
     * @return void
     */
    public function getPosition()
    {
        return $this->token->getPosition();
    }

}