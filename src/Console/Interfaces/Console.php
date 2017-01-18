<?php

namespace KamranAhmed\Walkers\Console\Interfaces;

/**
 * Interface ConsoleInterface
 *
 * @package KamranAhmed\Walkers\Console\Interfaces
 */
interface Console
{
    /**
     * Asks a choice question and returns one of the valid responses
     *
     * @param string $question
     * @param array  $options
     *
     * @return mixed
     */
    public function askChoice(string $question, array $options);

    /**
     * Asks a textual question and gets the response
     *
     * @param string $question
     *
     * @return mixed
     */
    public function askQuestion(string $question);

    /**
     * Prints the section heading
     *
     * @param string $heading
     *
     * @return mixed
     */
    public function showSection(string $heading);

    /**
     * Prints the warning text
     *
     * @param string $message
     *
     * @return mixed
     */
    public function printDanger(string $message);

    /**
     * Prints the success text
     *
     * @param string $message
     *
     * @return mixed
     */
    public function printSuccess(string $message);

    /**
     * Prints the information text
     *
     * @param string $message
     *
     * @return mixed
     */
    public function printInfo(string $message);

    /**
     * Prints the normal text
     *
     * @param string $message
     *
     * @return mixed
     */
    public function printText(string $message);

    /**
     * Prints the heading
     *
     * @param string $title
     *
     * @return mixed
     */
    public function printTitle(string $title);

    /**
     * Adds a line break
     *
     * @param int $lineCount
     *
     * @return void
     */
    public function breakLine($lineCount = 1);

    /**
     * Prints the table
     *
     * @param array $headers
     * @param array $rows
     *
     * @return void
     */
    public function printTable(array $headers, array $rows);
}
