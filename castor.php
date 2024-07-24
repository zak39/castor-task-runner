<?php

use Castor\Attribute\AsTask;

use function Castor\io;
use function Castor\capture;
use function Castor\input;
use function Castor\log;
use function Castor\notify;
use function Castor\parallel;
use function Castor\run;

#[AsTask(description: 'Welcome to Castor!')]
function hello(): void
{
    $currentUser = capture('whoami');

    io()->title(sprintf('Hello %s!', $currentUser));
}

#[AsTask(description: 'Test IO')]
function testIO(): void {
    io()->title('Title');
    io()->section('Section');
    io()->text('Text');
    io()->comment('Comment');
    io()->success('Success');
    io()->error('Error');
    io()->warning('Warning');
    io()->note('Note');
    io()->table(
        [ 'Header 1', 'Header 2' ],
        [
            ['Row 1', 'Row 2']
        ]
    );

    // bar de progression
    io()->progressStart(100);
    for ($i = 0; $i < 100; $i++) {
        usleep(10000);
        io()->progressAdvance();
    }

    io()->progressFinish();
}

#[AsTask(description: 'Test Input function')]
function testInput(string $name, int $age): void
{
    $name = input()->getArgument('name');
    $age = input()->getArgument('age');

    io()->text(sprintf('Hello %s, you are %s years old.', $name, $age));
}

#[AsTask(description: 'Test Command execution')]
function testCommand(): void {
    run('ls -la');
}

#[AsTask(description: 'Test Command execution')]
function testCommand2(): void {
    $result = run('ls -la');

    if ($result->isSuccessful()) {
        io()->success('Command executed successfully');
    } else {
        io()->success('Command failed');
    }
}

#[AsTask(description: 'Test Command execution')]
function testCommand3(): void {
    run('ls -la', path: '/tmp');
}

#[AsTask(description: 'Test parallel execution')]
function testParallel(): void
{
    parallel(
        fn() => run('sleep 10 && echo "First command"'),
        fn() => run('sleep 4 && echo "Second command"'),
        fn() => run('sleep 8 && echo "Third command"'),
        fn() => run('sleep 2 && echo "Four command"'),
        fn() => run('sleep 5 && echo "Five command"'),
    );
}

#[AsTask(description: 'Test Log')]
function testLog(): void
{
    log('This is a info message', 'info');
    log('This is a notice message', 'notice');
    log('This is a warning message', 'warning');
    log('This is a error message', 'error');
}

#[AsTask(description: 'Test Notification')]
function testNotification(): void
{
    // for Linux and macOS only.
    // not available for Windows.
    notify('This is a info message');
}
