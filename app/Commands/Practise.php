<?php

declare(strict_types=1);

namespace App\Commands;

use App\Helpers\TerminalCleaner;
use LaravelZero\Framework\Commands\Command;

// use function Laravel\Prompts\clear;
use function Laravel\Prompts\confirm;
use function Laravel\Prompts\error;
use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\pause;
use function Laravel\Prompts\table;
use function Laravel\Prompts\text;

class Game
{
    public int $gameLength = 10;

    public int $totalAttempts = 0;

    public int $totalCorrect = 0;

    public int $totalIncorrect = 0;

    public bool $lastGuessCorrect = false;
}

class Practise extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:practise';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        TerminalCleaner::clear();

        $game = new Game;

        $password = password(
            label: 'What is the password that you want to practise?',
            required: true
        );

        $seePasswordPreference = confirm('Do you want to see the password again?');

        if ($seePasswordPreference) {
            info($password);
        }

        $game->gameLength = (int) text(
            label: 'Enter the number of times to practise your password (1-20):',
            default: (string) $game->gameLength,
            validate: fn (string $value) => match (true) {
                ! is_numeric($value) || $value != (int) $value => 'Please enter a whole number.',
                $value < 1 || $value > 20 => 'The game length must be between 1 and 20.',
                default => null
            }
        );

        pause('Press ENTER to start practising…');

        TerminalCleaner::clear();

        info('––––');

        while ($game->totalAttempts < $game->gameLength) {

            if ($game->totalAttempts > 0) {
                info('––––');
                if ($game->lastGuessCorrect) {
                    info('Correct! – '.$game->totalCorrect.'/'.$game->totalAttempts);
                } else {
                    error('Incorrect! – '.$game->totalCorrect.'/'.$game->totalAttempts);
                }
            }

            $attempt = password(
                label: 'Attempt '.$game->totalAttempts + 1,
                required: true,
            );

            if ($attempt === $password) {
                $game->totalCorrect++;
                $game->lastGuessCorrect = true;
            } else {
                $game->totalIncorrect++;
                $game->lastGuessCorrect = false;
            }

            $game->totalAttempts++;
            info('––––');

            TerminalCleaner::clear();

        }

        table(
            // headers: ['Metric', 'Value'],
            rows: [
                ['Total Attempts', $game->totalAttempts],
                ['Correct Answers', $game->totalCorrect],
                ['Incorrect Answers', $game->totalIncorrect],
            ]
        );

    }
}
