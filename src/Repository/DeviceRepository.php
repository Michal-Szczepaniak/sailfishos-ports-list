<?php

namespace App\Repository;

use App\Cli\DTO\DeviceDTO;
use App\Entity\Device;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Sylius\Bundle\ResourceBundle\Doctrine\ORM\CreatePaginatorTrait;

class DeviceRepository extends ServiceEntityRepository
{
    use CreatePaginatorTrait;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Device::class);
    }

    public function findByDeviceDTO(DeviceDTO $device): ?Device
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.vendor = :vendor')
            ->andWhere('o.codename = :name')
            ->setParameter('vendor', $device->getVendor())
            ->setParameter('name', $device->getName())
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
}
