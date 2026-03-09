<?php

declare(strict_types=1);

namespace App\Cli;

use App\Enum\ConsentStatus;
use App\Repository\CustomerRepository;
use Elvi\EventsBundle\Event\ExternalDataImport\ExternalDataImportRequested;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:request-customer-data-import',
    description: 'Dispatches an import request event for opted-in customers.',
)]
class RequestCustomerDataImportCommand extends Command
{
    public function __construct(
        private readonly CustomerRepository $repository,
        private readonly MessageBusInterface $commandBus,
    ) {
        parent::__construct();
    }
    #[\Override]
    protected function configure(): void
    {
        $this->addOption('externalIdentifier', null, InputOption::VALUE_OPTIONAL, 'External identifier of the customer to import data for')
            ->addOption('decidedAtFrom', null, InputOption::VALUE_OPTIONAL, 'Start date for decidedAt filter (Y-m-d format)')
            ->addOption('decidedAtTo', null, InputOption::VALUE_OPTIONAL, 'End date for decidedAt filter (Y-m-d format)')
            ->addOption('dry-run', null, InputOption::VALUE_NONE, 'If set, the command will only output the customers that would be processed without actually dispatching any events')
        ;
    }

    #[\Override]
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $externalIdentifier = $input->getOption('externalIdentifier');
        $decidedAtFromValue = $input->getOption('decidedAtFrom');
        $decidedAtToValue = $input->getOption('decidedAtTo');
        $decidedAtFrom = $decidedAtFromValue ? new \DateTimeImmutable($decidedAtFromValue) : null;
        $decidedAtTo = $decidedAtToValue ? new \DateTimeImmutable($decidedAtToValue) : null;
        $dryRun = $input->getOption('dry-run');


        if ($dryRun) {
            $output->writeln('Dry run mode enabled. No events will be dispatched.');
        } else {
            $output->writeln("Starting import");
        }

        if ($externalIdentifier) {
            $output->writeln(sprintf('Filtering customers with external identifier: %s', $externalIdentifier));
            $customer = $this->repository->findOneByExternalIdentifierAndStatus($externalIdentifier, ConsentStatus::OptedIn);
            $customers = $customer ? [$customer] : [];
        } elseif ($decidedAtFrom && $decidedAtTo) {
            $output->writeln('No external identifier provided. Processing all customers with filters - Decided At From: ' . $decidedAtFrom->format('Y-m-d') . ', Decided At To: ' . $decidedAtTo->format('Y-m-d'));
            $customers = $this->repository->findFromToDateRange($decidedAtFrom, $decidedAtTo, ConsentStatus::OptedIn);
        } else {
            $output->writeln('No filters provided. Finishing without processing any customers.');

            return Command::SUCCESS;
        }
        if (empty($customers)) {
            $output->writeln('No customers found with the provided filters. Finishing without processing any customers.');

            return Command::SUCCESS;
        }

        $table = new Table($output);
        $table->setHeaders(['External Identifier', 'Channel', 'Status', 'Decided At']);
        foreach ($customers as $customer) {
            $table->addRow([
                $customer->getExternalIdentifier(),
                $customer->getSalesChannel(),
                $customer->getConsentStatus()->value,
                $customer->getDecisionDate()?->format('Y-m-d H:i:s') ?? '-',
            ]);
        }
        $table->render();
        $output->writeln(sprintf('%d customer(s) found.', count($customers)));

        if ($dryRun) {
            return Command::SUCCESS;
        }

        $output->writeln('Dispatching import events...');
        foreach ($customers as $customer) {
            $this->commandBus->dispatch(new ExternalDataImportRequested(
                entity: 'customer',
                rule: 'all',
                salesChannel: null,
                customerContext: null,
                customerAccountId: null,
                customerEmail: $customer->getExternalIdentifier(),
                externalCustomerId: null,
                requestedByContext: 'elvi-consent',
            ));
        }
        $output->writeln('Done.');

        return Command::SUCCESS;
    }
}
