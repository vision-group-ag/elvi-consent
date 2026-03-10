<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\ConsentDecisionOutput;
use App\Dto\ConsentDecisionRequest;
use App\Entity\Customer;
use App\Service\ConsentService;
use DateTimeImmutable;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @implements ProcessorInterface<ConsentDecisionRequest, ConsentDecisionOutput>
 */
abstract readonly class AbstractConsentDecisionProcessor implements ProcessorInterface
{
    public function __construct(
        protected ConsentService $consentService,
        protected RequestStack $requestStack,
    ) {
    }

    abstract protected function record(
        string $externalIdentifier,
        string $brand,
        array $rawData,
        ?string $salesChannel,
        ?DateTimeImmutable $decidedAt,
        ?string $ipAddress,
        ?string $userAgent,
    ): Customer;

    #[\Override]
    final public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ConsentDecisionOutput
    {
        assert($data instanceof ConsentDecisionRequest);

        $request = $this->requestStack->getCurrentRequest();

        $brand = substr($uriVariables['salesChannel'], 0, -3);

        $customer = $this->record(
            externalIdentifier: $data->externalIdentifier,
            brand: $brand,
            rawData: $request->toArray(),
            salesChannel: $data->salesChannel,
            decidedAt: $data->decidedAt,
            ipAddress: $request->getClientIp(),
            userAgent: $request->headers->get('User-Agent'),
        );

        return new ConsentDecisionOutput($customer->getConsentStatus()->value);
    }
}
