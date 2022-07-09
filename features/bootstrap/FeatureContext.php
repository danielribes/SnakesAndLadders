<?php

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;
use PHPUnit\Framework\Assert;

use SnakesAndLadders\Lib\Game;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{
    private $game;
    private $player;
    private $diceresult;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given the game is started
     */
    public function theGameIsStarted()
    {
        $this->game = new Game();
        $this->player = $this->game->addPlayer();
    }

    /**
     * @When the token is placed on the board
     */
    public function theTokenIsPlacedOnTheBoard()
    {
        Assert::assertEquals('1', $this->player->getPosition());
    }

    /**
     * @Then the token is on square :arg1
     */
    public function theTokenIsOnSquare($arg1)
    {
        if(!isset($this->game))
        {
            $this->theGameIsStarted();
            $this->player->moveToken($arg1-1);  
        } 
        
        Assert::assertEquals($arg1, $this->player->getPosition());
    }


    /**
     * @When the token is moved :arg1 spaces
     */
    public function theTokenIsMovedSpaces($arg1)
    {
        $this->player->moveToken($arg1);
        $this->game->checkPlayerStatus($this->player);
    }

    /**
     * @When then it is moved :arg1 spaces
     */
    public function thenItIsMovedSpaces($arg1)
    {
        $this->player->moveToken($arg1);
    }

    /**
     * @Then the player has won the game
     */
    public function thePlayerHasWonTheGame()
    {
        Assert::assertTrue($this->player->getWin());
    }

    /**
     * @Then the player has not won the game
     */
    public function thePlayerHasNotWonTheGame()
    {
        Assert::assertFalse($this->player->getWin());
    }

    /**
     * @Then the result should be between :arg1-:arg2 inclusive
     */
    public function theResultShouldBeBetweenInclusive($arg1, $arg2)
    {
        $sides = range($arg1, $arg2);
        Assert::assertContains($this->diceresult, $sides);
    }

    /**
     * @Given the player rolls a :arg1
     */
    public function thePlayerRollsA($arg1)
    {
        if(!isset($this->game))
        {
            $this->theGameIsStarted();
        }

        if($arg1 == 'die')
        {
            $this->diceresult = $this->player->rollsADie();
        }

        if($arg1 != 'die')
        {
            $this->diceresult = $arg1;
        }
    }


    /**
     * @When they move their token
     */
    public function theyMoveTheirToken()
    {
        $this->player->moveToken($this->diceresult);    
    }


    /**
     * @Then the token should move :arg1 spaces
     */
    public function theTokenShouldMoveSpaces($arg1)
    {
        $old = $this->player->getOldPosition();
        $new = $this->player->getPosition();

        $rslt = $new-$old;

        Assert::assertEquals($arg1, $rslt);
    }
}
