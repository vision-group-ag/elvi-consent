<?php

declare(strict_types=1);

use PhpCsFixer\Fixer\ArrayNotation\ArraySyntaxFixer;
use Symplify\EasyCodingStandard\Config\ECSConfig;
use Symplify\EasyCodingStandard\ValueObject\Option;
use Symplify\EasyCodingStandard\ValueObject\Set\SetList;

return static function (ECSConfig $ecsConfig): void {
    $parameters = $ecsConfig->parameters();
    $parameters->set(Option::PATHS, [__DIR__ . '/src', __DIR__ . '/tests/Codeception']);
    $parameters->set(Option::SKIP, [__DIR__ . '/tests/Codeception/_support/_generated']);
    $parameters->set(Option::PARALLEL, true);

    $ecsConfig->services()->set(ArraySyntaxFixer::class)->call('configure', [['syntax' => 'short']]);
    $ecsConfig->import(SetList::STRICT);
    $ecsConfig->import(SetList::CLEAN_CODE);
    $ecsConfig->import(SetList::PSR_12);
};
