<?php

namespace Modules\Project\database\factories;

use App\Service\Json\WebProject\DomainTransformer;
use App\Service\Json\WebProject\HostTransformer;
use App\Service\Json\WebProject\LanguageTransformer;
use App\Service\Json\WebProject\SampleTransformer;
use Crypt;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Enums\WebDomain;
use Modules\Project\app\Enums\WebHostLocation;
use Modules\Project\app\Models\ProjectWeb;

/**
 * @extends Factory
 */
class ProjectWebFactory extends Factory
{
    protected $model = ProjectWeb::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'package_id' => rand(1, 3),
            'pages' => $this->faker->numberBetween(1, 10),
            'domains' => $this->makeDomain(),
            'host' => $this->makeHost(),
            'language' => $this->makeLanguage(),
            'sample' => $this->sample(),
        //    'working_days' => $this->faker->numberBetween(30, 360),
        ];
    }

    private function makeHost(): array
    {
        $host = resolve(HostTransformer::class);

        $haveHost = $this->faker->boolean;
        if ($haveHost) {
            $host->setHaveHost(true);
            $host->setHostProvider($this->faker->company);
            $host->setHostUsername($this->faker->userName);
            $host->setHostPassword(Crypt::encrypt('1234'));
        } else {
            $host->setHaveHost(false);
        }
        $host->setHostLocation($this->faker->randomElement([WebHostLocation::IR, WebHostLocation::OUTSIDE]));
        $host->setHostMostVisit($this->faker->boolean);

        return $host->toArray();
    }

    private function makeLanguage(): array
    {
        $language = resolve(LanguageTransformer::class);
        $language->setLanguages($this->faker->randomElements(['EN', 'FA', 'AR']));
        $language->setPrimaryLanguage('FA');

        return $language->toArray();
    }

    private function makeDomain(): array
    {
        $domain = resolve(DomainTransformer::class);

        $haveHost = $this->faker->boolean;
        if ($haveHost) {
            $domain->setHaveDomain(true);
            $domain->setDomainProviderWebsite($this->faker->company);
            $domain->setDomainUsername($this->faker->userName);
            $domain->setDomainPassword(Crypt::encrypt('1234'));
        } else {
            $domain->setHaveDomain(false);
        }
        $domain->setDomainPrimary($this->faker->domainName);
        $domain->setDomainsRequired($this->faker->randomElements(WebDomain::getKeys()));
        $domain->setOtherDomain($this->faker->domainName);

        return $domain->toArray();
    }

    private function sample(): array
    {

        $fakeDomains = [];
        for ($i = 1; $i < 20; $i++) {
            $fakeDomains[] = $this->faker->domainName;
        }

        $sample = resolve(SampleTransformer::class);
        $sample->setSimilarSites($this->faker->randomElements($fakeDomains));
        $sample->setFavoriteSites($this->faker->randomElements($fakeDomains));

        return $sample->toArray();
    }
}
