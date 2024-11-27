<?php

declare(strict_types=1);

namespace App\Cli;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;

#[AsCommand('skeleton:demo:remove')]
class SkeletonDemoRemoveCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $rootDir = __DIR__ . '/../../';

        $this->removeDemoFiles($rootDir);
        $io->writeln("Removed demo files");

        file_put_contents($rootDir . 'README.md', "# %s\n\nProject setup to be documented here");
        $io->writeln('Reset contents of README.md');

        $io->success("Removed demo files successfully");

        return Command::SUCCESS;
    }

    private function removeDemoFiles(string $rootDir): void
    {
        $files = [
            'migrations/Version0000Demo.php',
            'src/Entity/DemoEntity.php',
            'src/Repository/DemoEntityRepository.php',
            'config/api-platform/DemoEntity.yaml',
            'config/doctrine/DemoEntity.orm.xml',
            'config/serialization/DemoEntity.yaml',
            'config/validator/DemoEntity.yaml',
            'tests/Codeception/Acceptance/Features/demo.feature',
            'tests/Codeception/Api/DemoCest.php',
            'tests/Spec/Entity/DemoEntitySpec.php',
        ];

        $fs = new Filesystem();
        foreach ($files as $file) {
            $fs->remove($rootDir . $file);
        }
    }
}
