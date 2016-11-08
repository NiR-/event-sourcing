<?php
/**
 * This file is part of the prooph/event-sourcing.
 * (c) 2014-2016 prooph software GmbH <contact@prooph.de>
 * (c) 2015-2016 Sascha-Oliver Prolic <saschaprolic@googlemail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace ProophTest\EventSourcing\Container\Snapshot;

use Interop\Container\ContainerInterface;
use Prooph\EventSourcing\Container\Snapshot\SnapshotStoreFactory;
use Prooph\EventSourcing\Snapshot\Adapter\Adapter;
use Prooph\EventSourcing\Snapshot\SnapshotStore;
use ProophTest\EventStore\TestCase;

final class SnapshotStoreFactoryTest extends TestCase
{
    /**
     * @test
     */
    public function it_creates_a_snapshot_store(): void
    {
        $snapshotAdapter = $this->prophesize(Adapter::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn([
            'prooph' => [
                'snapshot_store' => [
                    'default' => [
                        'adapter' => [
                            'type' => 'mock_adapter'
                        ]
                    ]
                ]
            ]
        ]);

        $container->get('mock_adapter')->willReturn($snapshotAdapter->reveal());

        $factory = new SnapshotStoreFactory();

        $snapshotStore = $factory($container->reveal());

        $this->assertInstanceOf(SnapshotStore::class, $snapshotStore);
    }

    /**
     * @test
     */
    public function it_creates_a_snapshot_store_via_callstatic(): void
    {
        $snapshotAdapter = $this->prophesize(Adapter::class);

        $container = $this->prophesize(ContainerInterface::class);

        $container->get('config')->willReturn([
            'prooph' => [
                'snapshot_store' => [
                    'another' => [
                        'adapter' => [
                            'type' => 'mock_adapter'
                        ]
                    ]
                ]
            ]
        ]);

        $container->get('mock_adapter')->willReturn($snapshotAdapter->reveal());

        $type = 'another';
        $snapshotStore = SnapshotStoreFactory::$type($container->reveal());

        $this->assertInstanceOf(SnapshotStore::class, $snapshotStore);
    }
}