<?php

namespace Tests\Feature\Controller\Admin\Ajax;

use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Modules\Admin\app\Models\Admin;
use Modules\Admin\database\factories\AdminFactory;
use Modules\Admin\database\factories\JobTitleFactory;
use Modules\FreeDay\app\Models\FreeDay;
use Tests\TestCase;

class AjaxControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_day_counter()
    {
        $customDate = Carbon::create(2023, 01, 01);
        Carbon::setTestNow($customDate);

        JobTitleFactory::new()
            ->count(1)
            ->create();

        AdminFactory::new()
            ->count(1)
            ->create();

        $admin = Admin::first();
        $this->actingAs($admin, 'admin');
        $this->post(route('admin.ajax.calc.day.work'), [
            'days' => 10,
        ])

            ->assertJson(function (AssertableJson $json) {
                $json->where('final_date', '2023-01-12')
                    ->where('total_work_days', 11)
                    ->where('total_free_days', 0)
                    ->etc();
            })
            ->assertOk();
    }

    public function test_day_counter_by_free_Days()
    {
        $customDate = Carbon::create(2023, 01, 01);
        Carbon::setTestNow($customDate);

        Admin::factory(1)->create();
        $admin = Admin::first();

        FreeDay::query()->create([
            'title' => 'Holiday',
            'free_at' => '2023-01-02',
        ]);

        FreeDay::query()->create([
            'title' => 'Holiday',
            'free_at' => '2023-01-05',
        ]);

        $this->actingAs($admin, 'admin');
        $this->post(route('admin.ajax.calc.day.work'), [
            'days' => 10,
        ])
            ->assertJson(function (AssertableJson $json) {
                $json->has('success')
                    ->where('final_date', '2023-01-14')
                    ->where('total_work_days', 11)
                    ->where('total_free_days', 2)
                    ->etc();
            })
            ->assertOk();
    }
}
