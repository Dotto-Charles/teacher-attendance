<?php

namespace App\Http\Controllers\District;

use App\Http\Controllers\Controller;
use App\Models\School;
use App\Models\Transfer;
use App\Models\User;
use App\Models\Ward;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DistrictAssignmentController extends Controller
{
    private function councilId(): int
    {
        return Auth::user()->council_id;
    }

    // ─────────────────────────────────────────────────────────────────
    // INDEX — main page
    // ─────────────────────────────────────────────────────────────────
    public function index(Request $request)
    {
        $officer   = Auth::user();
        $councilId = $this->councilId();

        $tab = $request->get('tab', 'overview');

        // ── Wards & Schools for dropdowns ─────────────────────────────
        $wards = Ward::where('council_id', $councilId)->orderBy('name')->get();

        $schools = School::whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->where('is_active', 1)->orderBy('name')->get();

        // ── Current Ward Officers ──────────────────────────────────────
        $wardOfficers = User::with(['ward'])
            ->where('role', 'ward_officer')
            ->whereHas('ward', fn($q) => $q->where('council_id', $councilId))
            ->orderBy('first_name')->get();

        // ── Current Head Teachers ──────────────────────────────────────
        $headTeachers = User::with(['school.ward'])
            ->where('role', 'head_teacher')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->orderBy('first_name')->get();

        // ── Wards WITHOUT officer ──────────────────────────────────────
        $assignedWardIds = $wardOfficers->pluck('ward_id')->filter()->unique();
        $unassignedWards = $wards->whereNotIn('id', $assignedWardIds);

        // ── Schools WITHOUT head teacher ───────────────────────────────
        $assignedSchoolIds = $headTeachers->pluck('school_id')->filter()->unique();
        $unassignedSchools = $schools->whereNotIn('id', $assignedSchoolIds);

        // ── Eligible teachers (approved, role=teacher) ─────────────────
        $eligibleTeachers = User::with(['school.ward'])
            ->where('status', 'approved')
            ->whereIn('role', ['teacher', 'ward_officer', 'head_teacher'])
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))
            ->orderBy('first_name')->get();

        // ── Pending transfers ──────────────────────────────────────────
        $pendingTransfers = Transfer::with(['user.school', 'fromSchool.ward', 'toSchool.ward', 'requester'])
            ->where('status', 'pending')
            ->whereHas('user.school.ward', fn($q) => $q->where('council_id', $councilId))
            ->latest()->get();

        // ── Transfer history ───────────────────────────────────────────
        $transferHistory = Transfer::with(['user', 'fromSchool.ward', 'toSchool.ward', 'requester'])
            ->whereIn('status', ['approved', 'rejected'])
            ->whereHas('user', fn($q) =>
                $q->whereHas('school.ward', fn($q2) => $q2->where('council_id', $councilId))
            )
            ->latest()->limit(50)->get();

        // ── Summary stats ──────────────────────────────────────────────
        $stats = [
            'ward_officers'       => $wardOfficers->count(),
            'head_teachers'       => $headTeachers->count(),
            'unassigned_wards'    => $unassignedWards->count(),
            'unassigned_schools'  => $unassignedSchools->count(),
            'pending_transfers'   => $pendingTransfers->count(),
            'total_transfers'     => Transfer::whereHas('user', fn($q) =>
                $q->whereHas('school.ward', fn($q2) => $q2->where('council_id', $councilId))
            )->count(),
        ];

        $pendingTeachers = User::where('role', 'teacher')->where('status', 'pending')
            ->whereHas('school.ward', fn($q) => $q->where('council_id', $councilId))->count();

        return view('district.assignments.index', compact(
            'officer', 'wards', 'schools', 'tab',
            'wardOfficers', 'headTeachers',
            'unassignedWards', 'unassignedSchools',
            'eligibleTeachers',
            'pendingTransfers', 'transferHistory',
            'stats', 'pendingTeachers',
        ));
    }

    // ─────────────────────────────────────────────────────────────────
    // ASSIGN WARD OFFICER
    // Logic: kama kata ina WO tayari → WO huyo anakuwa teacher
    //        katika shule yoyote ya kata hiyo (au anaendelea na shule yake)
    // ─────────────────────────────────────────────────────────────────
    public function assignWardOfficer(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ward_id' => 'required|exists:wards,id',
        ]);

        $councilId = $this->councilId();
        $ward      = Ward::findOrFail($request->ward_id);
        abort_unless($ward->council_id === $councilId, 403);

        $newOfficer = User::findOrFail($request->user_id);
        // Must be from same council
        abort_unless(
            $newOfficer->school?->ward?->council_id === $councilId ||
            $newOfficer->ward?->council_id === $councilId,
            403
        );

        DB::transaction(function () use ($request, $ward, $newOfficer) {
            // ── Demote existing WO of this ward (if any) ──────────────
            $existingOfficer = User::where('role', 'ward_officer')
                ->where('ward_id', $ward->id)
                ->first();

            if ($existingOfficer && $existingOfficer->id !== $newOfficer->id) {
                // Find a school in this ward to assign them to
                $fallbackSchool = School::where('ward_id', $ward->id)
                    ->where('is_active', 1)->first();

                $existingOfficer->update([
                    'role'      => 'teacher',
                    'ward_id'   => null,
                    'school_id' => $existingOfficer->school_id ?? $fallbackSchool?->id,
                ]);
            }

            // ── Also demote if new officer was HT somewhere ────────────
            if ($newOfficer->role === 'head_teacher') {
                // Demote HT role in their school first
                $this->demoteHeadTeacher($newOfficer);
            }

            // ── Promote new officer ────────────────────────────────────
            $newOfficer->update([
                'role'      => 'ward_officer',
                'ward_id'   => $ward->id,
                'school_id' => null,
            ]);
        });

        return back()->with('success', "{$newOfficer->first_name} {$newOfficer->last_name} amepewa nafasi ya Ward Officer wa kata ya {$ward->name}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // ASSIGN HEAD TEACHER
    // Logic: kama shule ina HT tayari → HT huyo anakuwa teacher wa shule hiyo
    // ─────────────────────────────────────────────────────────────────
    public function assignHeadTeacher(Request $request)
    {
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'school_id' => 'required|exists:schools,id',
        ]);

        $councilId = $this->councilId();
        $school    = School::findOrFail($request->school_id);
        abort_unless($school->ward->council_id === $councilId, 403);

        $newHT = User::findOrFail($request->user_id);

        DB::transaction(function () use ($school, $newHT) {
            // ── Demote existing HT of this school (if any) ────────────
            $existingHT = User::where('role', 'head_teacher')
                ->where('school_id', $school->id)
                ->first();

            if ($existingHT && $existingHT->id !== $newHT->id) {
                $existingHT->update([
                    'role'    => 'teacher',
                    'ward_id' => null,
                ]);
            }

            // ── If new HT was a WO, demote WO role ────────────────────
            if ($newHT->role === 'ward_officer') {
                $newHT->update(['ward_id' => null]);
            }

            // ── Promote new HT ─────────────────────────────────────────
            $newHT->update([
                'role'      => 'head_teacher',
                'school_id' => $school->id,
                'ward_id'   => null,
            ]);
        });

        return back()->with('success', "{$newHT->first_name} {$newHT->last_name} amepewa nafasi ya Mwalimu Mkuu wa {$school->name}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REQUEST TRANSFER (teacher / WO / HT)
    // ─────────────────────────────────────────────────────────────────
    public function requestTransfer(Request $request)
    {
        $request->validate([
            'user_id'       => 'required|exists:users,id',
            'to_school_id'  => 'required|exists:schools,id',
            'reason'        => 'nullable|string|max:500',
        ]);

        $councilId   = $this->councilId();
        $officer     = Auth::user();
        $user        = User::with('school')->findOrFail($request->user_id);
        $toSchool    = School::findOrFail($request->to_school_id);

        abort_unless($toSchool->ward->council_id === $councilId, 403);

        // Check no pending transfer for this user
        $existing = Transfer::where('user_id', $user->id)
            ->where('status', 'pending')->first();

        if ($existing) {
            return back()->with('error', "Mwalimu huyu tayari ana ombi la uhamisho linalosubiri idhini.");
        }

        Transfer::create([
            'user_id'      => $user->id,
            'from_school_id' => $user->school_id,
            'to_school_id'  => $toSchool->id,
            'requested_by'  => $officer->id,
            'status'        => 'pending',
            'reason'        => $request->reason,
        ]);

        return back()->with('success', "Ombi la uhamisho wa {$user->first_name} {$user->last_name} limewasilishwa. Linasubiri idhini.");
    }

    // ─────────────────────────────────────────────────────────────────
    // APPROVE TRANSFER
    // ─────────────────────────────────────────────────────────────────
    public function approveTransfer(Transfer $transfer)
    {
        $councilId = $this->councilId();
        abort_unless($transfer->toSchool->ward->council_id === $councilId, 403);
        abort_unless($transfer->status === 'pending', 422);

        DB::transaction(function () use ($transfer) {
            $user     = $transfer->user;
            $toSchool = $transfer->toSchool;

            // ── If user is HT, demote before transfer ─────────────────
            if ($user->role === 'head_teacher') {
                $this->demoteHeadTeacher($user);
            }

            // ── If user is WO, demote before transfer ──────────────────
            if ($user->role === 'ward_officer') {
                $user->update(['role' => 'teacher', 'ward_id' => null]);
            }

            // ── Move to new school ─────────────────────────────────────
            $user->update(['school_id' => $toSchool->id]);

            $transfer->update(['status' => 'approved']);
        });

        return back()->with('success', "Uhamisho wa {$transfer->user->first_name} {$transfer->user->last_name} umeidhinishwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REJECT TRANSFER
    // ─────────────────────────────────────────────────────────────────
    public function rejectTransfer(Request $request, Transfer $transfer)
    {
        $councilId = $this->councilId();
        abort_unless($transfer->toSchool->ward->council_id === $councilId, 403);
        abort_unless($transfer->status === 'pending', 422);

        $transfer->update([
            'status' => 'rejected',
            'reason' => $request->rejection_reason
                ? $transfer->reason . ' | Sababu ya kukataa: ' . $request->rejection_reason
                : $transfer->reason,
        ]);

        return back()->with('error', "Ombi la uhamisho la {$transfer->user->first_name} {$transfer->user->last_name} limekataliwa.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REMOVE WARD OFFICER (demote to teacher)
    // ─────────────────────────────────────────────────────────────────
    public function removeWardOfficer(User $user)
    {
        $councilId = $this->councilId();
        abort_unless($user->ward?->council_id === $councilId, 403);
        abort_unless($user->role === 'ward_officer', 422);

        $wardName = $user->ward->name ?? '—';
        $fallbackSchool = School::where('ward_id', $user->ward_id)
            ->where('is_active', 1)->first();

        $user->update([
            'role'      => 'teacher',
            'ward_id'   => null,
            'school_id' => $user->school_id ?? $fallbackSchool?->id,
        ]);

        return back()->with('success', "{$user->first_name} {$user->last_name} ameondolewa nafasi ya Ward Officer wa kata ya {$wardName}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // REMOVE HEAD TEACHER (demote to teacher)
    // ─────────────────────────────────────────────────────────────────
    public function removeHeadTeacher(User $user)
    {
        $councilId = $this->councilId();
        abort_unless($user->school?->ward?->council_id === $councilId, 403);
        abort_unless($user->role === 'head_teacher', 422);

        $schoolName = $user->school->name ?? '—';
        $this->demoteHeadTeacher($user);

        return back()->with('success', "{$user->first_name} {$user->last_name} ameondolewa nafasi ya Mwalimu Mkuu wa {$schoolName}.");
    }

    // ─────────────────────────────────────────────────────────────────
    // PRIVATE: demote head teacher
    // ─────────────────────────────────────────────────────────────────
    private function demoteHeadTeacher(User $user): void
    {
        $user->update([
            'role'    => 'teacher',
            'ward_id' => null,
            // Keep school_id — they remain teacher at same school
        ]);
    }
}