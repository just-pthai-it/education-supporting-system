<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Account;
use App\Models\FixedSchedule;
use App\Models\OtherDepartment;
use Database\Factories\UserFactory;
use Database\Factories\AccountFactory;
use App\Http\Middleware\VerifyJWTToken;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class FixedScheduleTest extends TestCase
{
    /**
     * A basic feature test example.
     * @return void
     */
    public function test_create ()
    {
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->post('/api/fixed-schedules/create',
                                ['id_schedule' => 4,
                                 'new_date'    => '2022-08-08',
                                 'new_shift'   => '2',
                                 'new_id_room' => 'PHTT',
                                 'reason'      => 'abcxyz']);

        $response->assertStatus(201);
    }

    public function test_create_soft ()
    {
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->post('/api/fixed-schedules/create?type=soft',
                                ['id_schedule' => 4,
                                 'intend_time' => 'Tháng 5',
                                 'reason'      => 'abcxyz']);

        $response->assertStatus(201);
    }

    public function test_create_hard ()
    {
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->post('/api/fixed-schedules/create?type=hard',
                                ['id_schedule' => 4,
                                 'new_date'    => '2022-08-08',
                                 'new_shift'   => '2',
                                 'new_id_room' => 'PHTT',
                                 'reason'      => 'abcxyz']);

        $response->assertStatus(201);
    }

    public function test_update_accept_normal ()
    {
        $idFixedSchedule = 1;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 1,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => 2,
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => null,
                                                       'reason'      => 'abcxyz',
                                                       'reason_deny' => null,
                                                       'status'      => 0,
                                                       'accepted_at' => null,
                                                       'set_room_at' => null,]);
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=accept",
                                 ['accepted_at' => '2022-03-21 02:39:58',]);

        $response->assertStatus(200);
    }

    public function test_update_accept_intend_time ()
    {
        $idFixedSchedule = 2;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 2,
                                                       'new_date'    => null,
                                                       'new_shift'   => null,
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => 'intend_time',
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 0,
                                                       'accepted_at' => null,
                                                       'set_room_at' => null,]);
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=accept",
                                 ['accepted_at' => '2022-03-21 02:39:58',]);

        $response->assertStatus(200);
    }

    public function test_update_accept_straight ()
    {
        $idFixedSchedule = 3;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 3,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => '2',
                                                       'new_id_room' => '705-A2',
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => null,
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 0,
                                                       'accepted_at' => null,
                                                       'set_room_at' => null,]);
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=accept",
                                 ['accepted_at' => '2022-03-21 02:39:58',]);

        $response->assertStatus(200);
    }

    public function test_update_set_room ()
    {
        $idFixedSchedule = 4;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 4,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => '2',
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => 'intend_time',
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 1,
                                                       'accepted_at' => '2022-03-09 09:43:45',
                                                       'set_room_at' => null,]);
        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=set_room",
                                 ['set_room_at' => '2022-03-21 02:39:58',
                                  'new_id_room' => 'PHTT',]);

        $response->assertStatus(200);
    }

    public function test_update_deny_department ()
    {
        $idFixedSchedule = 5;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 5,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => '2',
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => null,
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 0,
                                                       'accepted_at' => '2022-03-09 09:43:45',
                                                       'set_room_at' => null,]);

        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=deny",
                                 ['reason_deny' => 'deny']);

        $response->assertStatus(200);
    }

    public function test_update_deny_other_department ()
    {
        $idFixedSchedule = 6;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 6,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => '2',
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => null,
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 1,
                                                       'accepted_at' => null,
                                                       'set_room_at' => null,]);

        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=deny",
                                 ['reason_deny' => 'deny']);

        $response->assertStatus(200);
    }

    public function test_update_cancel ()
    {
        $idFixedSchedule = 7;
        FixedSchedule::find($idFixedSchedule)->update(['id_schedule' => 7,
                                                       'new_date'    => '2021-09-09',
                                                       'new_shift'   => '2',
                                                       'new_id_room' => null,
                                                       'old_date'    => '2022-09-09',
                                                       'old_shift'   => '2',
                                                       'old_id_room' => 'PHTT',
                                                       'intend_time' => null,
                                                       'reason'      => 'reason',
                                                       'reason_deny' => null,
                                                       'status'      => 1,
                                                       'accepted_at' => null,
                                                       'set_room_at' => null,]);

        $this->withoutMiddleware(VerifyJWTToken::class);
        $response = $this->patch("/api/fixed-schedules/update/{$idFixedSchedule}?type=cancel");

        $response->assertStatus(200);
    }
}
