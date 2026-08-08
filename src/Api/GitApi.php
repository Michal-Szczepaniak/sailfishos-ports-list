<?php

declare(strict_types=1);

namespace App\Api;

use App\Api\DTO\ServicesDTO;
use App\Cli\DTO\DeviceDTO;
use Symfony\Component\HttpFoundation\Response;

final class GitApi
{
    public function getFile(string $url, string $branch, string $file): string
    {
        $curl = curl_init();

        if (!str_ends_with($url, '.git')) return '';
        $url = substr($url, 0, -4);

        curl_setopt_array($curl, [
            CURLOPT_URL => sprintf("%s/raw/%s/%s", $url, $branch, $file),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_POSTFIELDS => "",
        ]);

        $response = curl_exec($curl);
        $err = curl_error($curl);
        $code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($err || $code !== Response::HTTP_OK) {
            return '';
        } else {
            return $response;
        }
    }
}
