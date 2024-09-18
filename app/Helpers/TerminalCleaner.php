<?php

// Using this until Laravel Zero supports Laravel Prompts v0.2.0 which includes clear() function

namespace App\Helpers;

class TerminalCleaner
{
    /**
     * Clear the terminal.
     */
    public static function clear(): bool
    {
        if (PHP_SAPI === 'cli') {
            // For command line interface
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // For Windows
                system('cls');
            } else {
                // For Unix-like systems
                system('clear');
            }

            // Fill the previous newline count
            echo PHP_EOL.PHP_EOL;

            return true;
        }

        // For non-CLI environments, you might want to implement
        // a different behavior or return false
        return false;
    }
}
