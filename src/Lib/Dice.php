<?php
/**
 * A little Dice class
 */


namespace SnakesAndLadders\Lib;

final class Dice 
{

    /**
     * Roll dice!
     *
     * @return void
     */
    public function roll()
    {
        return random_int(1,6);
    }

}