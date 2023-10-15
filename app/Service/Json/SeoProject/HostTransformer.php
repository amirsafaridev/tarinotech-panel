<?php

namespace App\Service\Json\SeoProject;

use App\Enums\Database\Project\SeoHostLocation;
use InvalidArgumentException;

class HostTransformer
{
    private ?string $hostProvider = null;

    private ?string $hostLocation;

    public function setHostProvider(?string $hostProvider)
    {
        $this->hostProvider = $hostProvider;
    }

    public function setHostLocation(?string $hostLocation)
    {
        // Validate the host_location value
        if (in_array($hostLocation, [SeoHostLocation::IN_COMPANY, SeoHostLocation::OUT_COMPANY])) {
            $this->hostLocation = $hostLocation;
        } else {
            throw new InvalidArgumentException('Invalid value for hostLocation. It must be one of the class constants: HOST_LOCATION_IN_IRAN or HOST_LOCATION_OUT_IRAN.');
        }
    }

    public static function fromJson($json): HostTransformer
    {
        $data = json_decode($json, true);
        $config = new self();
        $config->setHostProvider($data['host_provider']);
        // Use enum-like constants for host_location
        $config->setHostLocation($data['host_location']);

        return $config;
    }

    public function toArray(): array
    {
        return [
            'host_provider' => $this->hostProvider,
            'host_location' => $this->hostLocation,
        ];
    }
}
