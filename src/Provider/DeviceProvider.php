<?php

declare(strict_types=1);

namespace App\Provider;

use App\Cli\DTO\DeviceDTO;
use App\Entity\Device;
use App\Factory\DeviceFactory;
use App\Repository\DeviceRepository;

readonly final class DeviceProvider
{
    public function __construct(private DeviceFactory $factory, private DeviceRepository $repository)
    {}

    public function provide(DeviceDTO $deviceDTO): Device
    {
        $device = $this->repository->findByDeviceDTO($deviceDTO);
        if ($device === null) {
            $device = $this->factory->createFromDeviceDTO($deviceDTO);
        }

        return $device;
    }
}
