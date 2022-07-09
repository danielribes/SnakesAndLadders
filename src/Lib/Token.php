<?php
/**
 * This class moves the player token
 */

namespace SnakesAndLadders\Lib;

final class Token
{
    private $position;
    private $oldposition;

    public function __construct()
    {
        $this->position = 1;
    }


    /**
     * Get token position
     *
     * @return void
     */
    public function getPosition()
    {
        return $this->position;
    }


    /**
     * Set token position
     *
     * @param [type] $newposition
     * @return void
     */
    public function setPosition($newposition)
    {
        $this->oldposition = $this->position;
        $this->position = $newposition;
    }


    /**
     * Get old token position
     *
     * @return void
     */
    public function getOldPosition()
    {
        return $this->oldposition;
    }

}