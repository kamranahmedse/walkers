<?php

namespace KamranAhmed\Walkers\Console;

use KamranAhmed\Walkers\Console\Interfaces\ConsoleInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SymfonyConsole
 *
 * @package KamranAhmed\Walkers\Console
 */
class SymfonyConsole implements ConsoleInterface
{
    /** @var SymfonyStyle */
    protected $io;

    /**
     * SymfonyConsole constructor.
     *
     * @param \Symfony\Component\Console\Style\SymfonyStyle $io
     */
    public function __construct(SymfonyStyle $io)
    {
        $this->io = $io;
    }

    /**
     * Asks a choice question and returns one of the valid responses
     *
     * @param string $question
     * @param array  $options
     *
     * @return string
     */
    public function askChoice(string $question, array $options)
    {
        return $this->io->choice($question, $options);
    }

    /**
     * Asks a textual question and gets the response
     *
     * @param string $question
     *
     * @return string
     */
    public function askQuestion(string $question)
    {
        return $this->io->ask($question);
    }

    /**
     * Prints the section heading
     *
     * @param string $heading
     *
     * @return void
     */
    public function showSection(string $heading)
    {
        $this->io->section($heading);
    }

    /**
     * Prints the warning text
     *
     * @param string $message
     *
     * @return void
     */
    public function printDanger(string $message)
    {
        $this->io->block($message, null, 'fg=white;bg=red', ' ', true);
    }

    /**
     * Prints the information text
     *
     * @param string $message
     *
     * @return void
     */
    public function printInfo(string $message)
    {
        $this->io->block($message, null, 'fg=yellow;bg=blue;', ' ', 1);
    }

    /**
     * Prints the success message
     *
     * @param string $message
     *
     * @return void
     */
    public function printSuccess(string $message)
    {
        $this->io->success($message);
    }

    /**
     * Prints the normal text
     *
     * @param string $message
     *
     * @return void
     */
    public function print(string $message)
    {
        $this->io->text($message);
    }

    /**
     * Prints the heading
     *
     * @param string $title
     *
     * @return void
     */
    public function printTitle(string $title)
    {
        $this->io->title($title);
    }

    /**
     * Prints the line break
     *
     * @param int $lineCount
     *
     * @return void
     */
    public function breakLine($lineCount = 1)
    {
        $this->io->newLine($lineCount);
    }

    /**
     * Prints the table
     *
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    public function printTable(array $headers, array $rows)
    {
        $this->io->table($headers, $rows);
    }
}
