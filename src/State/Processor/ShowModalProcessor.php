<?php

declare(strict_types=1);

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Dto\ShowModalOutput;
use App\Dto\ShowModalRequest;
use App\Service\ConsentService;

/**
 * @implements ProcessorInterface<ShowModalRequest, ShowModalOutput>
 */
readonly class ShowModalProcessor implements ProcessorInterface
{
    public function __construct(
        private ConsentService $consentService,
    ) {
    }

    #[\Override]
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ShowModalOutput
    {
        assert($data instanceof ShowModalRequest);

        return new ShowModalOutput(showModal: $this->consentService->showConsentModal($data->externalIdentifier, $data->salesChannel));
    }
}
