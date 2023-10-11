<?php

namespace App\Service\Json;

use InvalidArgumentException;

class SeoHostTransformer
{
    private ?string $hostAgreementAt = null;

    private ?string $hostProvider = null;

    const HOST_LOCATION_IN_IRAN = 'in_iran';

    const HOST_LOCATION_OUT_IRAN = 'out_iran';

    private string $hostStatus = self::HOST_LOCATION_IN_IRAN;

    public function setHostAgreementAt($hostAgreementAt)
    {
        $this->hostAgreementAt = $hostAgreementAt;
    }

    public function setHostProvider($hostProvider)
    {
        $this->hostProvider = $hostProvider;
    }

    public function setHostStatus($hostStatus)
    {
        // Validate the host_location value
        if ($hostStatus === self::HOST_LOCATION_IN_IRAN || $hostStatus === self::HOST_LOCATION_OUT_IRAN) {
            $this->hostStatus = $hostStatus;
        } else {
            throw new InvalidArgumentException('Invalid value for host status. It must be one of the class constants: HOST_LOCATION_IN_IRAN or HOST_LOCATION_OUT_IRAN.');
        }
    }

    public static function fromJson($json): SeoHostTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setHostAgreementAt($data['host_agreement_at']);
        $config->setHostProvider($data['host_provider']);
        $config->setHostStatus($data['host_status']);

        return $config;
    }

    public function toJson(): string
    {
        $data = [
            'host_agreement_at' => $this->hostAgreementAt,
            'host_provider' => $this->hostProvider,
            'host_status' => $this->hostStatus,
        ];

        return json_encode($data);
    }
}
