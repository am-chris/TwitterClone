<?php

namespace Tests\Feature\User;

use App\User;
use App\Models\User\Report;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function user_can_report_another_user()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.reports.store', $user2->id), [
                'type' => 'user',
                'reason' => 'spam',
                'description' => 'He bullied me',
            ]);

        $this->assertDatabaseHas('reports', [
            'reporter_id' => $user->id,
            'reported_id' => $user2->id,
        ]);
    }

    /** @test */
    public function user_cant_report_themselves()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->json('POST', route('users.reports.store', $user->id), [
                'type' => 'user',
                'reason' => 'spam',
                'description' => 'He bullied me',
            ]);

        $this->assertDatabaseMissing('reports', [
            'reporter_id' => $user->id,
            'reported_id' => $user->id,
        ]);
    }

    // The tests below are to check if the report settings in the
    // config/settings.php file work correctly.

    /** @test */
    public function user_cant_report_more_than_x_times_per_hour()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        for ($i = 1; $i <= config('settings.max_reports_per_hour') + 1; $i++) {
            $this->actingAs($user)
                ->json('POST', route('users.reports.store', $user2->id), [
                    'type' => 'user',
                    'reason' => 'spam',
                    'description' => 'He bullied me',
                ]);
        }

        $this->assertDatabaseMissing('reports', [
            'id' => config('settings.max_reports_per_hour') + 1,
        ]);
    }

    /** @test */
    public function user_cant_report_more_than_x_times_per_day()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();

        for ($i = 1; $i <= config('settings.max_reports_per_day') + 1; $i++) {
            $this->actingAs($user)
                ->json('POST', route('users.reports.store', $user2->id), [
                    'type' => 'user',
                    'reason' => 'spam',
                    'description' => 'He bullied me',
                ]);
        }

        $this->assertDatabaseMissing('reports', [
            'id' => config('settings.max_reports_per_day') + 1,
        ]);
    }
}
