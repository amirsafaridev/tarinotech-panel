<?php

namespace App\Service\Json;

class SampleTransformer
{
    private ?array $similarSites = [];

    private ?array $favoriteSites = [];

    public function setSimilarSites($similarSites)
    {
        $this->similarSites = $similarSites;
    }

    public function setFavoriteSites($favoriteSites)
    {
        $this->favoriteSites = $favoriteSites;
    }

    public function toJson(): string
    {
        $data = [
            'similar_sites' => $this->similarSites,
            'favorite_sites' => $this->favoriteSites,
        ];

        return json_encode($data);
    }

    public static function fromJson($json): SampleTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setSimilarSites($data['similar_sites']);
        $config->setFavoriteSites($data['favorite_sites']);

        return $config;
    }
}
