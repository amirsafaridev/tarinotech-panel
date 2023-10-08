<?php

namespace App\Service\Json;

class LanguageTransformer
{
    private ?string $primaryLanguage = null;

    private ?array $languages = [];

    public function setPrimaryLanguage($primaryLanguage)
    {
        $this->primaryLanguage = $primaryLanguage;
    }

    public function setLanguages($languages)
    {
        $this->languages = $languages;
    }

    public function toJson(): string
    {
        $data = [
            'primary_language' => $this->primaryLanguage,
            'languages' => $this->languages,
        ];

        return json_encode($data);
    }

    public static function fromJson($json): LanguageTransformer
    {
        $data = json_decode($json, true);

        $config = new self();

        $config->setPrimaryLanguage($data['primary_language']);
        $config->setLanguages($data['languages']);

        return $config;
    }
}
