<?php

declare(strict_types=1);

namespace App\Factory;

use App\Cli\DTO\DeviceDTO;
use App\Entity\Device;

final class DeviceFactory
{
    public function createNew(): Device
    {
        return new Device();
    }

    public function createFromDeviceDTO(DeviceDTO $deviceDTO): Device
    {
        $device = $this->createNew();

        $device->setName($deviceDTO->getName());
        $device->setCodename($deviceDTO->getName());
        $device->setVendor($deviceDTO->getVendor());
        $device->setVersion($deviceDTO->getVersion());

        return $device;
    }
}
