<?php
/**
 * Console Command to SnakesAndLadders Game
 * 
 */

namespace SnakesAndLadders\Game;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Question\ConfirmationQuestion;

use SnakesAndLadders\Lib\Game;

class GameCommand extends Command
{
    /**
     * configure command
     */
    protected function configure()
    {
        $this->setName('SnakesAndLadders Game')
             ->setDescription('SnakesAndLadders game frontend to play and test the SnakesAndLadders Library from Voxel Kata')
             ->setHelp("Usage: see README.md\n")
             ->addOption(
                'bysteps',
                null,
                InputOption::VALUE_NONE,
                'Play step by step throwing the dice'
             );
    }


    /**
     * Execute command
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    { 
        $game = new Game();
        $player = $game->addPlayer();
        $playerposition = $player->getPosition();
        $output->writeln("Player at square: $playerposition");

        if($input->getOption('bysteps'))
        {
            $helper = $this->getHelper('question');
            $question = new ConfirmationQuestion('Roll Dice ? [y/n] ', false, '/^(y|j)/i');
        }

        while(!$player->getWin())
        {            
            $squares = $player->rollsADie();
            $output->writeln(PHP_EOL."Dice show: $squares");
            $player->moveToken($squares);
            $game->checkPlayerStatus($player);
            if($player->checkOutOfBounds())
            {
                $output->writeln("Player can't move");
            } else {
                $output->writeln("Player move token $squares squares");
            }
            $position = $player->getPosition();
            $output->writeln("Player at square: $position");
            $square = $game->checkPlayerPosition($player);
            if($square['type'] != 'normal')
            {
                $message = 'Player at '.$square['type'].' square, moved to new position '.$square['position'];
                $output->writeln($message);
            }

            if($input->getOption('bysteps') && (!$player->getWin()))
            {
                if (!$helper->ask($input, $output, $question)) 
                {
                    exit;
                }
            }
        }
        
        if($player->getWin())
        {
            $output->writeln("Player WIN!!!!");
        }

        return 0;       
    }

}