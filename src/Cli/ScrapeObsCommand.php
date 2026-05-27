<?php

namespace App\Cli;

use App\Api\DTO\EntryDTO;
use App\Api\ObsApi;
use App\Cli\DTO\DeviceDTO;
use App\Provider\DeviceProvider;
use App\Provider\GitMetadataProvider;
use App\Repository\DeviceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:scrape-obs',
    description: 'Scrape projects from OBS into database',
)]
class ScrapeObsCommand extends Command
{
    public function __construct(
        private readonly ObsApi $api,
        private readonly GitMetadataProvider $metadataProvider,
        private readonly DeviceProvider $deviceProvider,
        private readonly EntityManagerInterface $entityManager,
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $devices = [];
        $projects = $this->api->getProjects();
        foreach ($projects->getEntries() as $entry) {
            if (!str_starts_with($entry->getName(), ObsApi::NEMO_TESTING_PROJECT)) continue;

            $deviceDTO = $this->getDevice($entry);
            if ($deviceDTO === null) continue;


            $device = $this->deviceProvider->provide($deviceDTO);
            $device->setName($deviceDTO->getName());
            $device->setCodename($deviceDTO->getName());
            $device->setVendor($deviceDTO->getVendor());
            $device->setVersion($deviceDTO->getVersion());

            if (!$device->isBlacklisted()) {
                $metadata = $this->metadataProvider->provide($deviceDTO);

                if ($metadata !== null) {
                    $device->setAuthor($metadata->getAuthor());
                    $device->setAuthorEmail($metadata->getAuthorEmail());
                    $device->setBrokenList($metadata->getBroken());
                    $device->setFeatures($metadata->getFeatures());
                    $device->setName($metadata->getDevice() ?? $device->getCodename());
                }
            }

            $this->entityManager->persist($device);
            $this->entityManager->flush();
            $io->comment(sprintf('Updated device %s %s', $device->getVendor(), $device->getName()));
        }

        return Command::SUCCESS;
    }

    private function getDevice(EntryDTO $entry): ?DeviceDTO
    {
        $projectName = substr($entry->getName(), strlen(ObsApi::NEMO_TESTING_PROJECT) + 1);
        $nameParts = explode(':', $projectName);

        if (count($nameParts) !== 2) return null;
        [$vendor, $device] = $nameParts;

        $versions = $this->api->getProjects($entry->getName())->getEntries();
        $versions = array_filter($versions, fn (EntryDTO $entry) =>
            str_starts_with($entry->getName(), ObsApi::REPO_NAME_PREFIX) &&
            ctype_digit(implode('', explode('.', substr($entry->getName(), strlen(ObsApi::REPO_NAME_PREFIX)))))
        );
        if ([] === $versions) return null;

        usort($versions, $this->sortVersions(...));
        $version = $versions[0];

        return new DeviceDTO($device, $vendor, substr($version->getName(), strlen(ObsApi::REPO_NAME_PREFIX)));
    }

    private function sortVersions(EntryDTO $a, EntryDTO $b): int
    {
        if ($a->getName() == $b->getName()) {
            return 0;
        }

        $aParts = explode('.', substr($a->getName(), strlen('sailfishos_')));
        $bParts = explode('.', substr($b->getName(), strlen('sailfishos_')));
        if (count($aParts) < count($bParts)) {
            return -1;
        } else if (count($aParts) > count($bParts)) {
            return 1;
        }

        for ($i = 0; $i < count($aParts); $i++) {
            $aVal = intval($aParts[$i]);
            $bVal = intval($bParts[$i]);

            if ($aVal === $bVal) continue;

            return $bVal <=> $aVal;
        }

        return 0;
    }
}
