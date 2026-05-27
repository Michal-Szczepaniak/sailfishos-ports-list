<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\DTO\DirectoryDTO;
use App\Api\DTO\ServicesDTO;
use App\Cli\DTO\DeviceDTO;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\SerializerInterface;

readonly final class ObsApi
{
    public const string NEMO_TESTING_PROJECT = 'nemo:testing:hw';

    public const string REPO_NAME_PREFIX = 'sailfishos_';

    public function __construct(private SerializerInterface $serializer, private string $basicAuth) {}

    public function getProjects(string $project = ''): DirectoryDTO
    {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => sprintf("https://build.sailfishos.org/published/%s", $project),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                sprintf("Authorization: Basic %s", $this->basicAuth),
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err || $code !== Response::HTTP_OK) {
            echo "cURL Error #:" . $err;
            return new DirectoryDTO();
        } else {
            return $this->serializer->deserialize($response, DirectoryDTO::class, 'xml');
        }
    }

    public function getServiceFile(DeviceDTO $device): ServicesDTO
    {
        $curl = curl_init();

        $url = sprintf(
            "https://build.sailfishos.org/source/%s:%s:%s/droid-config-%s/_service",
            self::NEMO_TESTING_PROJECT,
            $device->getVendor(),
            $device->getName(),
            $device->getName()
        );

        curl_setopt_array($curl, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
            CURLOPT_HTTPHEADER => [
                sprintf("Authorization: Basic %s", $this->basicAuth),
            ],
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err || $code !== Response::HTTP_OK) {
            return new ServicesDTO();
        } else {
            return $this->serializer->deserialize($response, ServicesDTO::class, 'xml');
        }
    }
}
