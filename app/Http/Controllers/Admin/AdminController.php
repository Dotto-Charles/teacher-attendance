<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Models\School;

class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard', [

            'users' => User::latest()->take(8)->get(),

            'stats' => [
                'total' => User::count(),
                'approved' => User::where('status','approved')->count(),
                'pending' => User::where('status','pending')->count(),
                'blocked' => User::where('status','blocked')->count(),

                'headteachers' =>
                    User::where('role','head_teacher')->count(),

                'wardofficers' =>
                    User::where('role','ward_officer')->count(),

                'districtofficers' =>
                    User::where('role','district_officer')->count(),
            ]
        ]);
    }

    public function users(Request $request)
    {
        $search = $request->search;

        $users = User::query()

            ->when($search, function($q) use ($search){

                $q->where('first_name','like',"%$search%")
                  ->orWhere('last_name','like',"%$search%")
                  ->orWhere('email','like',"%$search%");
            })

            ->latest()
            ->paginate(15);

        return view('admin.users.index', compact('users'));
    }

    public function pendingUsers()
    {
        $users = User::where('status','pending')
                    ->latest()
                    ->paginate(20);

        return view('admin.users.pending', compact('users'));
    }

    public function roles()
    {
        $users = User::latest()->paginate(20);

        return view('admin.users.roles', compact('users'));
    }

    public function approve(User $user)
    {
        $user->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        return back()->with('success', 'User approved');
    }

    public function block(User $user)
    {
        $user->update([
            'status' => 'blocked'
        ]);

        return back()->with('success', 'User blocked');
    }

    public function changeRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required'
        ]);

        $user->update([
            'role' => $request->role
        ]);

        return back()->with('success', 'Role updated');
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
        ]);

        $data = $request->only([
            'first_name',
            'last_name',
            'email'
        ]);

        if($request->password){
            $data['password'] =
                Hash::make($request->password);
        }

        $user->update($data);

        return back()->with('success','User updated');
    }

    public function destroy(User $user)
    {
        $user->delete();

        return back()->with('success','User deleted');
    }

    public function reports()
    {
        return view('admin.reports');
    }

    public function activity()
    {
        return view('admin.activity');
    }

    public function changePassword(Request $request, User $user)
{
    $request->validate([
        'password' => 'required|min:6|confirmed'
    ]);

    $user->update([
        'password' => Hash::make($request->password)
    ]);

    return back()->with(
        'success',
        'Password changed successfully'
    );
}

public function resetPassword(User $user)
{
    $newPassword = Str::random(8);

    $user->update([
        'password' => Hash::make($newPassword)
    ]);

    return back()->with(
        'success',
        'New password: '.$newPassword
    );
}

public function schools()
{
    $schools = School::latest()->paginate(10);

    return view('admin.schools.index', compact('schools'));
}

public function showSchool(School $school)
{
    return view('admin.schools.show', compact('school'));
}

public function updateSchool(Request $request, School $school)
{
    $school->update($request->all());

    return back()->with('success', 'School updated successfully');
}

public function deleteSchool(School $school)
{
    $school->delete();

    return back()->with('success', 'School deleted successfully');
}
    
}