<?php

namespace App\Service\Json;

class LanguageTransformer
{
    private ?string $primaryLanguage = null;

    private ?array $languages = [];

    public function setPrimaryLanguage(string $primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;
    }

    public function setLanguages(array $languages)
    {
        $this->languages = $languages;
    }

    public static function fromJson($json): LanguageTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setPrimaryLanguage($data['primary_language']);
        $config->setLanguages($data['languages']);

        return $config;
    }

    public function toArray(): array
    {
        return [
            'primary_language' => $this->primaryLanguage,
            'languages' => $this->languages,
        ];

    }
}
