<?php

declare(strict_types=1);

namespace App\Provider;

use App\Api\GitApi;
use App\Api\ObsApi;
use App\Cli\DTO\DeviceDTO;
use App\Parser\GitMetadataParser;
use App\Provider\DTO\GitMetadataDTO;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class GitMetadataProvider
{
    private const string TAR_GIT_SERVICE = 'tar_git';

    private const string URL_PARAM = 'url';

    private const string BRANCH_PARAM = 'branch';

    public function __construct(
        private ObsApi $obsApi,
        private GitApi $gitApi,
        private GitMetadataParser $metadataParser,
    ) {}

    public function provide(DeviceDTO $device): ?GitMetadataDTO
    {
        $packages = $this->obsApi->getPackages($device);

        foreach ($packages->getEntries() as $entry) {
            if (!str_starts_with($entry->getName(), 'droid-config')) continue;

            $droidConfigPackage = ObsApi::DROID_CONFIG_PREFIX . substr($entry->getName(), 13);

            $services = $this->obsApi->getServiceFile($device, $droidConfigPackage);

            $url = null;
            $branch = null;
            foreach ($services->getServices() as $service) {
                if ($service->getName() !== self::TAR_GIT_SERVICE) continue;

                foreach ($service->getParams() as $param) {
                    match ($param->getName()) {
                        self::URL_PARAM => $url = $param->getValue(),
                        self::BRANCH_PARAM => $branch = $param->getValue(),
                        default => null,
                    };
                }
            }

            if ($url === null || $branch === null) return null;

            $metadataFile = $this->gitApi->getMetadataFile($url, $branch);
            try {
                return $this->metadataParser->parse($metadataFile);
            } catch (\Exception $e) {
                echo $e->getMessage();

                return null;
            }
        }

        return null;
    }
}
