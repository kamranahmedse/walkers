<?php

namespace KamranAhmed\Tests;

use KamranAhmed\Walkers\Console\SymfonyConsole;
use Mockery;
use PHPUnit_Framework_TestCase;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Class SymfonyConsoleTest
 *
 * @package KamranAhmed\Tests
 */
class SymfonyConsoleTest extends PHPUnit_Framework_TestCase
{
    public function testAskChoiceGetsUnmodifiedResponseFromSymfonyIO()
    {
        $question = [
            'question' => 'Does it cascade `askChoice` correctly and returns unmodified response?',
            'choices'  => [
                'a' => 'Yes, it does',
                'b' => 'No, it does not',
            ],
            'answer'   => 'b',
        ];

        $choice = $this->getConsole(['choice' => $question])->askChoice($question['question'], $question['choices']);

        $this->assertEquals($question['answer'], $choice);
    }

    /**
     * Prepares the symfony console component while mocking the underlying console
     *
     * @param $params
     *
     * @return \KamranAhmed\Walkers\Console\SymfonyConsole
     */
    public function getConsole($params)
    {
        $symfonyStyleMock = Mockery::mock(SymfonyStyle::class);

        $methodMapping = $this->getPrintMethodMapping();

        // Print method mocking, make sure that
        foreach ($methodMapping as $method => $mapping) {

            $proxiedTo = $mapping[0];
            $withArgs  = array_slice($mapping, 1);

            $symfonyStyleMock->shouldReceive($proxiedTo)
                             ->zeroOrMoreTimes()
                             ->withArgs($withArgs)
                             ->andReturn(null);
        }

        if (!empty($params['choice'])) {
            $symfonyStyleMock->shouldReceive('choice')
                             ->once()
                             ->withArgs([
                                 $params['choice']['question'],
                                 $params['choice']['choices'],
                             ])
                             ->andReturn($params['choice']['answer']);
        }

        if (!empty($params['question'])) {
            $symfonyStyleMock->shouldReceive('ask')
                             ->once()
                             ->withAnyArgs()
                             ->andReturn($params['question']['answer']);
        }


        return new SymfonyConsole($symfonyStyleMock);
    }

    /**
     * @return array
     */
    public function getPrintMethodMapping()
    {
        // Keys specify original method call
        // and values specify the expected proxy call to mock
        return [
            'showSection'  => ['section', Mockery::any()],
            'printDanger'  => ['block', Mockery::any(), null, 'fg=white;bg=red', ' ', true],
            'printInfo'    => ['block', Mockery::any(), null, 'fg=yellow;bg=blue;', ' ', 1],
            'printSuccess' => ['success', Mockery::any()],
            'print'        => ['text', Mockery::any()],
            'printTitle'   => ['title', Mockery::any()],
            'printTable'   => ['table', Mockery::any(), Mockery::any()],
            'breakLine'    => ['newLine', Mockery::any()],
        ];
    }

    public function testAskQuestionGetsUnmodifiedResponseFromSymfonyIO()
    {
        $question = [
            'question' => 'Does `askQuestion` return unmodified response from component?',
            'answer'   => 'Yes',
        ];

        $answer = $this->getConsole(['question' => $question])->askQuestion($question['question']);

        $this->assertEquals($question['answer'], $answer);
    }

    /**
     * @dataProvider printMethodsProvider
     *
     * @param       $method
     * @param array $args
     */
    public function testPrintMethodsAreCorrectlyProxied($method, ...$args)
    {
        $console = $this->getConsole([]);

        call_user_func_array([$console, $method], $args);
    }

    public function printMethodsProvider()
    {
        return [
            ['showSection', 'Some section'],
            ['printDanger', 'Dangerous text'],
            ['printInfo', 'Information to be printed'],
            ['printSuccess', 'Success message to be printed'],
            ['print', 'Something to be printed'],
            ['printTitle', 'Title to be printed'],
            ['printTable', [], []],
            ['breakLine', 1],
        ];
    }
}


