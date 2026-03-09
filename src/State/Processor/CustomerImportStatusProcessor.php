<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\CustomerImportStatusUpdateOutput;
use App\Dto\CustomerImportStatusUpdateRequest;
use App\Repository\CustomerRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * @implements ProcessorInterface<CustomerImportStatusUpdateRequest, CustomerImportStatusUpdateOutput>
 */
readonly class CustomerImportStatusProcessor implements ProcessorInterface
{
    public function __construct(
        private CustomerRepository $repository,
    ) {
    }

    #[\Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): CustomerImportStatusUpdateOutput
    {
        assert($data instanceof CustomerImportStatusUpdateRequest);

        if ($data->salesChannel) {
            $customer = $this->repository->findOneByExternalIdentifierAndSalesChannel($data->email, $data->salesChannel);
        } else {
            $customer = $this->repository->findOneByExternalIdentifier($data->email);
        }

        if ($customer === null) {
            throw new NotFoundHttpException('Customer not found');
        }

        $customer->recordDataImport($data->status, $data->importedAt);
        $this->repository->flush();

        return new CustomerImportStatusUpdateOutput(status: $data->status, message: null);
    }
}
