<?php

namespace Modules\Survey\app\Resources\Survey;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @typescript
 * interface Survey {
 *   id: number;
 *   title: string;
 *   description: string | null;
 *   requires_auth: boolean;
 *   is_active: boolean;
 *   start_date: string | null;
 *   end_date: string | null;
 *   access_token: string;
 *   questions_count?: number;
 *   has_participated?: boolean;
 *   created_at: string;
 * }
 */
class SurveyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'requires_auth' => $this->requires_auth,
            'is_active' => $this->is_active,
            'start_date' => $this->start_date?->format('Y-m-d H:i:s'),
            'end_date' => $this->end_date?->format('Y-m-d H:i:s'),
            'access_token' => $this->access_token,
            'questions_count' => $this->when(isset($this->questions_count), $this->questions_count),
            'has_participated' => $this->when(isset($this->has_participated), $this->has_participated),
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
        ];
    }
}
