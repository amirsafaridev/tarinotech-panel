<?php

namespace Modules\Factor\app\Traits;

use App\Domain\Jobs\FactorItemCreateJob;
use App\Domain\Jobs\FactorItemUpdateJob;
use App\Foundation\ValueObjects\Requests\FactorItemValues;
use Exception;
use Illuminate\Http\Request;
use Modules\Factor\app\Models\Factor;
use Modules\Factor\app\Models\FactorItem;

trait WithAttributeChange
{
    /**
     * @throws Exception
     */
    protected function syncAttributes(Request $request, Factor $factor): void
    {
        $updatedItemIds = [];
        foreach ($request->input('item') as $item) {
            $factorItemValues = $this->setItemValues($factor, $item);
            $action = $item['action'];
            if ($action === 'store') {
                resolve(FactorItemCreateJob::class)
                    ->handle($factorItemValues);
            } else {
                $updatedItemIds[] = $item['id'];
                resolve(FactorItemUpdateJob::class)
                    ->handle($factorItemValues, $item['id']);
            }
        }

        $currentItemIds = $factor->items->pluck('id')->toArray();
        $deletedItemIds = array_diff($currentItemIds, $updatedItemIds);
        if (count($deletedItemIds)) {
            FactorItem::query()
                ->whereIn('id', $deletedItemIds)
                ->delete();
        }
    }

    private function setItemValues(Factor $factor, array $item): FactorItemValues
    {
        return resolve(FactorItemValues::class)
            ->setFactorId($factor->id)
            ->setTitle($item['title'])
            ->setTransactionCategoryId($item['transaction_category_id'])
            ->setPrice($item['price'])
            ->setDiscount($item['discount']);
    }
}
