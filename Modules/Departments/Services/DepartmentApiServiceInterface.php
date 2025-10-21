<?php

namespace Modules\Departments\Services;

interface DepartmentApiServiceInterface
{
    // Basic CRUD Operations
    public function getAllDepartments(?string $type = null): array;
    public function getDepartmentById(int $id): array;
    public function createDepartment(array $data): array;
    public function updateDepartment(int $id, array $data): array;
    public function deleteDepartment(int $id): bool;

    // High School Specific Methods
    public function getDepartmentGradeLevelInfo(int $id, string $grade): array;
    public function getTeacherAssignments(int $id): array;
    public function getClassSchedule(int $id, string $grade, string $section): array;
    public function getAvailableSubstituteTeachers(int $id): array;
    public function getParentNotifications(int $id, string $grade): array;

    // Event Management
    public function getDepartmentEvents(int $id, ?string $type = null, ?string $grade = null): array;
    public function createDepartmentEvent(int $id, array $data): array;

    // Announcement Management
    public function getDepartmentAnnouncements(int $id, ?string $category = null, ?string $audience = null, ?string $grade = null): array;
    public function createDepartmentAnnouncement(int $id, array $data): array;

    // Schedule Management
    public function getDepartmentSchedules(int $id, array $filters = []): array;
    public function createDepartmentSchedule(int $id, array $data): array;

    // Statistics and Reports
    public function getDepartmentStatistics(int $id): array;
    public function getTeacherWorkload(int $id): array;
    public function getResourceUtilization(int $id): array;
    public function getAcademicCalendar(int $id, string $startDate, string $endDate): array;
    public function getPerformanceMetrics(int $id, string $startDate, string $endDate): array;

    // Academic Performance and Attendance
    public function getAcademicPerformance(int $id, array $filters): array;
    public function getAttendanceReport(int $id, array $filters): array;
    public function generateDepartmentReport(int $id, array $filters): array;

    // Resource Management
    public function getDepartmentResources(int $id): array;
    public function assignDepartmentResource(int $id, array $data): array;

    // Academic Information
    public function getSubjectsBySemester(int $id): array;
}
