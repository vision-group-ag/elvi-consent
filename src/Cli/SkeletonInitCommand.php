<?php

declare(strict_types=1);

namespace App\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand('skeleton:init')]
class SkeletonInitCommand extends Command
{
    protected function configure()
    {
        $this->addArgument('git-repository-url', InputArgument::REQUIRED, 'The git repository url');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $gitRepositoryUrl = $input->getArgument('git-repository-url');
        $repositoryUrlPattern = '#^(git@.*\.git|https?://.*)$#';

        if (!preg_match($repositoryUrlPattern, $gitRepositoryUrl)) {
            $io->error(sprintf("'%s' does not look like a valid git URL - should start with 'git@' or 'http(s)://'", $gitRepositoryUrl));

            return Command::FAILURE;
        }

        rmdir(__DIR__ . '/.git/');
        $io->writeln('Removed .git directory');

        $shellOutput = [];
        exec('git init', $shellOutput, $exitCode);
        if (0 !== $exitCode) {
            $io->error("Failed to initialize a new git repository:\n\n" . implode("\n", $shellOutput));

            return Command::FAILURE;
        }
        $io->writeln('Initialized a new git repository');

        $shellOutput = [];
        exec('git remote add origin ' . escapeshellarg($gitRepositoryUrl), $shellOutput, $exitCode);
        if (0 !== $exitCode) {
            $io->error("Failed to add '{$gitRepositoryUrl}' as git remote:\n\n" . implode("\n", $shellOutput));

            return Command::FAILURE;
        }
        $io->writeln("Added '{$gitRepositoryUrl}' as git remote");

        $shellOutput = [];
        exec('x=$(cat .gitignore); echo "$x" | grep -v \'composer.lock\' > .gitignore ', $shellOutput, $exitCode);
        if (0 !== $exitCode) {
            $io->error("Failed to remove composer.lock from .gitignore:\n\n" . implode("\n", $shellOutput));

            return Command::FAILURE;
        }
        $io->writeln("Removed composer.lock from .gitignore");

        $io->success("Skeleton initialization completed successfully");

        return Command::SUCCESS;
    }
}
