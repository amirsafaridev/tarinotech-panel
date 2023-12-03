<?php

namespace Modules\Project\database\factories;

use App\Service\Json\SeoProject\HostTransformer;
use Illuminate\Database\Eloquent\Factories\Factory;
use Modules\Project\app\Enums\ProjectDesignBy;
use Modules\Project\app\Enums\SeoAgreementDuration;
use Modules\Project\app\Enums\SeoHostLocation;
use Modules\Project\app\Models\ProjectSeo;

/**
 * @extends Factory
 */
class ProjectSeoFactory extends Factory
{
    protected $model = ProjectSeo::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'field_activity' => 'زمینه کاری',
            'host' => $this->makeHost(),
            'agreement_duration' => SeoAgreementDuration::getRandomValue(),
            'designed_by' => ProjectDesignBy::getRandomValue(),
            'amount_content' => rand(20, 100),
            'keywords_count' => rand(1, 10),
            'due_date_payments' => rand(1, 31),
            'keywords' => $this->makeKeywords(),
            'price_monthly' => $this->faker->randomElement([100000, 300000, 5000000]),
        ];
    }

    private function makeKeywords(): string
    {
        $keywords = [
            'کلمه کلیدی 1',
            'کلمه کلیدی 2',
            'کلمه کلیدی 3',
            'کلمه کلیدی 4',
        ];

        return implode(PHP_EOL, $keywords);
    }

    private function makeHost(): array
    {
        $host = resolve(HostTransformer::class);

        $hostLocation = SeoHostLocation::getRandomKey();
        if ($hostLocation === SeoHostLocation::OUT_COMPANY) {
            $host->setHostProvider($this->faker->company);
        }
        $host->setHostLocation($hostLocation);

        return $host->toArray();
    }
}
