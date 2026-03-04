<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\ConsentDecisionOutput;
use App\Dto\ConsentDecisionRequest;
use App\Enum\ConsentSource;
use App\Service\ConsentService;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * @implements ProcessorInterface<ConsentDecisionRequest, ConsentDecisionOutput>
 */
readonly class ConsentOptInProcessor implements ProcessorInterface
{
    public function __construct(
        private ConsentService $consentService,
        private RequestStack $requestStack,
    ) {
    }

    #[\Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ConsentDecisionOutput
    {
        assert($data instanceof ConsentDecisionRequest);

        $request = $this->requestStack->getCurrentRequest();

        $customer = $this->consentService->recordOptIn(
            externalIdentifier: $data->externalIdentifier,
            salesChannel: $uriVariables['salesChannel'],
            rawData: $request->toArray(),
            source: ConsentSource::LandingPage,
            decidedAt: $data->decidedAt,
            ipAddress: $request->getClientIp(),
            userAgent: $request->headers->get('User-Agent'),
        );

        return new ConsentDecisionOutput($customer->getConsentStatus()->value);
    }
}
