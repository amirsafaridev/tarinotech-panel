<?php

namespace App\Service\Json\WebProject;

use InvalidArgumentException;

class HostTransformer
{
    private bool $haveHost = false;

    private ?string $hostProvider = null;

    private ?string $hostUsername = null;

    private ?string $hostPassword = null;

    private ?string $hostLocation;

    private bool $hostMostVisit = false;

    const HOST_LOCATION_IN_IRAN = 'IN_IRAN';

    const HOST_LOCATION_OUT_IRAN = 'OUT_IRAN';

    public function setHaveHost(bool $haveHost)
    {
        $this->haveHost = $haveHost;
    }

    public function setHostProvider(?string $hostProvider)
    {
        $this->hostProvider = $hostProvider;
    }

    public function setHostUsername(?string $hostUsername)
    {
        $this->hostUsername = $hostUsername;
    }

    public function setHostPassword(?string $hostPassword)
    {
        $this->hostPassword = $hostPassword;
    }

    public function setHostLocation(?string $hostLocation)
    {
        // Validate the host_location value
        if ($hostLocation === self::HOST_LOCATION_IN_IRAN || $hostLocation === self::HOST_LOCATION_OUT_IRAN) {
            $this->hostLocation = $hostLocation;
        } else {
            throw new InvalidArgumentException('Invalid value for hostLocation. It must be one of the class constants: HOST_LOCATION_IN_IRAN or HOST_LOCATION_OUT_IRAN.');
        }
    }

    public function setHostMostVisit(bool $hostMostVisit)
    {
        $this->hostMostVisit = $hostMostVisit;
    }

    public static function fromJson($json): HostTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setHaveHost($data['have_host']);
        $config->setHostProvider($data['host_provider']);
        $config->setHostUsername($data['host_username']);
        $config->setHostPassword($data['host_password']);

        // Use enum-like constants for host_location
        $config->setHostLocation($data['host_location']);
        $config->setHostMostVisit($data['host_most_visit']);

        return $config;
    }

    public function toArray(): array
    {
        return [
            'have_host' => $this->haveHost,
            'host_provider' => $this->hostProvider,
            'host_username' => $this->hostUsername,
            'host_password' => $this->hostPassword,
            'host_location' => $this->hostLocation,
            'host_most_visit' => $this->hostMostVisit,
        ];

    }
}
