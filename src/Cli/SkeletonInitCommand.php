<?php

declare(strict_types=1);

namespace App\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand('skeleton:init')]
class SkeletonInitCommand extends Command
{
    private const GIT_REPOSITORY_URL_PATTERN = '#^(git@.*\.git|https?://.*|/.*)$#';

    protected function configure()
    {
        $this->addArgument('git-repository-url', InputArgument::REQUIRED, 'The git repository url');
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $gitRepositoryUrl = $input->getArgument('git-repository-url');
        if (null === $gitRepositoryUrl) {
            $gitRepositoryUrl = $io->ask('What is the git repository URL?', null, function ($gitRepositoryUrl) {
                if (null === $gitRepositoryUrl) {
                    throw new \RuntimeException('The git repository URL cannot be empty');
                }
                if (!preg_match(self::GIT_REPOSITORY_URL_PATTERN, $gitRepositoryUrl)) {
                    throw new \RuntimeException(
                        sprintf(
                            "'%s' does not look like a valid git URL - should start with 'git@', 'http(s)://' or just '/' for local filesystem",
                            $gitRepositoryUrl
                        )
                    );
                }

                    return $gitRepositoryUrl;
            });
            $input->setArgument('git-repository-url', $gitRepositoryUrl);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $gitRepositoryUrl = $input->getArgument('git-repository-url');

        if (!preg_match(self::GIT_REPOSITORY_URL_PATTERN, $gitRepositoryUrl)) {
            $io->error(sprintf("'%s' does not look like a valid git URL - should start with 'git@', 'http(s)://' or just '/' for local filesystem", $gitRepositoryUrl));

            return Command::FAILURE;
        }

        $fs = new Filesystem();
        $fs->remove(__DIR__ . '/../../.git/');
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
        $io->writeln("Added '{$gitRepositoryUrl}' as git remote 'origin'");

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
