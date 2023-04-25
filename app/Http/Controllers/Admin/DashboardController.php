<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\Staff;
use App\Models\User;
use App\Models\SchoolSession;

use App\Models\SchoolTermSession;
use App\Helpers\DBHelpers;
use App\Helpers\ResponseHelper;

class DashboardController extends Controller
{
    //

    public function dashboard()
    {
        $total_student = DBHelpers::count(User::class, ['type' => 'student']);
        $total_staff = DBHelpers::count(User::class, ['type' => 'staff']);
        $school_session = DBHelpers::query_filter_first(SchoolSession::class, [
            'is_active' => 1,
        ]);
        $school_term = DBHelpers::query_filter_first(SchoolTermSession::class, [
            'is_active' => 1,
        ]);

        $res_data = [
            'total_students' => $total_student,
            'total_staff' => $total_staff,
            'school_session' => $school_session,
            'school_term' => $school_term,
        ];

        return ResponseHelper::success_response(
            'Admin Dashboard fetched successful',
            $res_data
        );
    }
}
