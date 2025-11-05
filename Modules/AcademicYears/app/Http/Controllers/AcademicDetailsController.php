<?php
// Modules/Academic/app/Http/Controllers/AcademicDetailController.php

namespace Modules\AcademicYears\App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Modules\Users\User\App\Models\User;
use Illuminate\Support\Facades\DB;
use Modules\AcademicYears\App\Models\AcademicDetail;
use Modules\AcademicYears\App\Models\AcademicYear;

class AcademicDetailsController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        try {
            return apiResponse(
                true,
                'Academic details endpoint.',
                null
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to process request.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function assignUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'academic_year_id' => 'required|exists:academic_years,id'
            ]);

            $result = DB::transaction(function () use ($validated) {
                // Check if user exists - without loading roles
                $user = User::findOrFail($validated['user_id']);

                // Check if academic year exists
                $academicYear = AcademicYear::findOrFail($validated['academic_year_id']);

                // Check if assignment already exists
                $existingAssignment = AcademicDetail::where('user_id', $validated['user_id'])
                    ->where('academic_id', $validated['academic_year_id'])
                    ->first();

                if ($existingAssignment) {
                    throw new \Exception('User is already assigned to this academic year.');
                }

                // Create the assignment
                $academicDetail = AcademicDetail::create([
                    'user_id' => $validated['user_id'],
                    'academic_id' => $validated['academic_year_id']
                ]);

                return [
                    'academic_detail' => $academicDetail,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email
                    ],
                    'academic_year' => $academicYear
                ];
            });

            return apiResponse(
                true,
                'User assigned to academic year successfully.',
                $result,
                201
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to assign user to academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function removeUser(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'academic_year_id' => 'required|exists:academic_years,id'
            ]);

            $result = DB::transaction(function () use ($validated) {
                $academicDetail = AcademicDetail::where('user_id', $validated['user_id'])
                    ->where('academic_id', $validated['academic_year_id'])
                    ->firstOrFail();

                return $academicDetail->delete();
            });

            if ($result) {
                return apiResponse(
                    true,
                    'User removed from academic year successfully.'
                );
            }

            return apiResponse(
                false,
                'Failed to remove user from academic year.',
                null,
                500
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to remove user from academic year.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getUserAcademicYears(int $userId): JsonResponse
    {
        try {
            // Load user without role relationships
            $user = User::with(['academicYears' => function ($query) {
                $query->orderBy('start_date', 'desc');
            }])->findOrFail($userId);

            // Transform the data to avoid role-related issues
            $academicYears = $user->academicYears->map(function ($academicYear) {
                return [
                    'id' => $academicYear->id,
                    'year_name' => $academicYear->year_name,
                    'start_date' => $academicYear->start_date,
                    'end_date' => $academicYear->end_date,
                    'is_current' => $academicYear->is_current,
                    'status' => $academicYear->status,
                    'description' => $academicYear->description
                ];
            });

            return apiResponse(
                true,
                'User academic years retrieved successfully.',
                $academicYears
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to fetch user academic years.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }

    public function getAcademicYearUsers(int $academicYearId): JsonResponse
    {
        try {
            // Load academic year with users but avoid role relationships
            $academicYear = AcademicYear::with(['users' => function ($query) {
                $query->select('id', 'name', 'email'); // Only select basic fields
            }])->findOrFail($academicYearId);

            // Transform users to avoid role-related issues
            $users = $academicYear->users->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email
                ];
            });

            return apiResponse(
                true,
                'Academic year users retrieved successfully.',
                $users
            );
        } catch (\Exception $e) {
            return apiResponse(
                false,
                'Failed to fetch academic year users.',
                null,
                500,
                [$e->getMessage()]
            );
        }
    }
}
