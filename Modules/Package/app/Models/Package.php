<?php

namespace Modules\Package\app\Models;

use App\Foundation\ValueObjects\PackagePriceResult;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Modules\Log\app\Enums\LogNames;
use Modules\Project\app\Models\ProjectType;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class Package extends Model
{
    use HasFactory;
    use LogsActivity;

    protected $fillable = [
        'title',
        'type_id',
        'contract_attachment',
        'contract_text',
        'minimum_price_percent',
        'seo_keywords_count',
        'seo_agreement_duration',
        'seo_amount_content',
        'main_unit',

    ];

    public function prices(): HasMany
    {
        return $this->hasMany(PackagePrice::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProjectType::class, 'type_id');
    }

    public function finalPrice(): HasOne
    {
        return $this->hasOne(PackagePrice::class)->orderByDesc('id');
    }

    public function contractHistories(): HasMany
    {
        return $this->hasMany(PackageContractHistory::class)
            ->limit(10)
            ->latest();
    }

    public function getPriceForDate($date): PackagePriceResult
    {
        $date = Carbon::parse($date);

        $priceRecord = $this->prices()
            ->where('start_at', '<=', $date)
            ->where('end_at', '>=', $date)
            ->first();

        if (! $priceRecord) {
            $priceRecord = $this->prices()
                ->whereNull('end_at')
                ->orderBy('start_at', 'desc')
                ->first();
        }

        $percentPrice = 0;
        if ($priceRecord) {
            $price = $priceRecord->price;
            if ($this->minimum_price_percent > 0) {
                $discountAmount = $price * ($this->minimum_price_percent / 100);
                $percentPrice = $price - round($discountAmount);
            } else {
                $percentPrice = $price;
            }
        }

        $packagePriceResult = new PackagePriceResult();

        return $packagePriceResult
            ->setPrice($priceRecord)
            ->setMinimumPrice($percentPrice)
            ->setPercentPrice($this->minimum_price_percent);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->useLogName(LogNames::PACKAGE)
            ->logAll();
    }
}
