<?php

declare(strict_types=1);

use OliverThiele\OtMarkdown\Service\MarkdownService;
use OliverThiele\OtMarkdown\ViewHelpers\MarkdownViewHelper;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->autowire(true)
        ->autoconfigure(true)
        ->private();

    $services->load('OliverThiele\\OtMarkdown\\', '../Classes/*');

    $services->set(MarkdownService::class)->public();

    $services->set(MarkdownViewHelper::class);
};
