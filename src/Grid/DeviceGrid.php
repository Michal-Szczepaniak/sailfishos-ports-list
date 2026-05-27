<?php

namespace App\Grid;

use App\Entity\Device;
use Sylius\Bundle\GridBundle\Builder\Action\CreateAction;
use Sylius\Bundle\GridBundle\Builder\Action\DeleteAction;
use Sylius\Bundle\GridBundle\Builder\Action\ShowAction;
use Sylius\Bundle\GridBundle\Builder\Action\UpdateAction;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\BulkActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\ItemActionGroup;
use Sylius\Bundle\GridBundle\Builder\ActionGroup\MainActionGroup;
use Sylius\Bundle\GridBundle\Builder\Field\StringField;
use Sylius\Bundle\GridBundle\Builder\Field\TwigField;
use Sylius\Bundle\GridBundle\Builder\GridBuilderInterface;
use Sylius\Bundle\GridBundle\Grid\AbstractGrid;
use Sylius\Component\Grid\Attribute\AsGrid;

#[AsGrid(
    resourceClass: Device::class,
    name: 'app_device',
)]
final class DeviceGrid extends AbstractGrid
{
    public function __invoke(GridBuilderInterface $gridBuilder): void
    {
        $gridBuilder
            ->setLimits([15])
            ->withFields(
                StringField::create('name')
                    ->setLabel('app.name')
                    ->setSortable(true),
                StringField::create('vendor')
                    ->setLabel('app.vendor')
                    ->setSortable(true),
                StringField::create('codename')
                    ->setLabel('app.codename')
                    ->setSortable(true),
                StringField::create('author')
                    ->setLabel('app.author')
                    ->setSortable(true),
                StringField::create('author_email')
                    ->setLabel('app.author_email')
                    ->setSortable(true),
                StringField::create('version')
                    ->setLabel('app.version')
                    ->setSortable(true),
                TwigField::create('broken_list', '/list_field.html.twig')
                    ->setLabel('app.broken_list'),
                TwigField::create('features', '/list_field.html.twig')
                    ->setLabel('app.features'),
            )
        ;
    }
}
