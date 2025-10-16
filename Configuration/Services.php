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

    // Alle Klassen in Classes/ automatisch laden
    $services->load('OliverThiele\\OtMarkdown\\', '../Classes/*');

    // Falls du gezielt einen Service öffentlich machen willst:
    $services->set(MarkdownService::class)->public();

    // ViewHelper kann privat bleiben, wird von Fluid automatisch aufgelöst
    $services->set(MarkdownViewHelper::class);
};
