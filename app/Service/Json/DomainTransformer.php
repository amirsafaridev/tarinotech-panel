<?php

namespace App\Service\Json;

use Crypt;

class DomainTransformer
{
    private bool $haveDomain = false;

    private ?string $domainProviderWebsite = null;

    private ?string $domainUsername = null;

    private ?string $domainPassword = null;

    private ?string $otherDomain = null;

    private string $domainPrimary;

    private array $domainsRequired;

    public function setHaveDomain(bool $haveDomain): void
    {
        $this->haveDomain = $haveDomain;
    }

    public function setDomainProviderWebsite(string $domainProviderWebsite): void
    {
        $this->domainProviderWebsite = $domainProviderWebsite;
    }

    public function setDomainUsername(?string $domainUsername): void
    {
        $this->domainUsername = $domainUsername;
    }

    public function setDomainPassword(?string $domainPassword): void
    {
        $this->domainPassword = Crypt::encrypt($domainPassword);
    }

    public function setOtherDomain(?string $otherDomain): void
    {
        $this->otherDomain = $otherDomain;
    }

    public function setDomainPrimary(string $domainPrimary): void
    {
        $this->domainPrimary = $domainPrimary;
    }

    public function setDomainsRequired(array $domainsRequired): void
    {
        $this->domainsRequired = $domainsRequired;
    }

    public static function fromJson($json): DomainTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setHaveDomain($data['have_domain']);
        $config->setDomainProviderWebsite($data['domain_provider_website']);
        $config->setDomainUsername($data['domain_username']);
        $config->setDomainPassword($data['domain_password']);
        $config->setOtherDomain(Crypt::decryptString($data['other_domain']));
        $config->setDomainPrimary($data['domain_primary']);
        $config->setDomainsRequired($data['domains_required']);

        return $config;
    }

    public function toJson(): string
    {
        $data = [
            'have_domain' => $this->haveDomain,
            'domain_provider_website' => $this->domainProviderWebsite,
            'domain_username' => $this->domainUsername,
            'domain_password' => Crypt::decryptString($this->domainPassword),
            'other_domain' => $this->otherDomain,
            'domain_primary' => $this->domainPrimary,
            'domains_required' => $this->domainsRequired,
        ];

        return json_encode($data);
    }
}
