<?php
/**
 * Frontend to test the SankesAndLadders Game Lib
 * 
 */

require_once __DIR__.'/vendor/autoload.php';

use Symfony\Component\Console\Application;
use SnakesAndLadders\Game\GameCommand;

$cmd = new GameCommand();
$app = new Application('SnakesAndLadders','0.1');
$app->add($cmd);
$app->setDefaultCommand($cmd->getName());
$app->run();