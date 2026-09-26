<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\UserService;
use App\Services\AuditLogService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(
        protected UserService $userService,
        protected AuditLogService $auditLogService
    ) {}

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $status = $request->input('status', 'all');

        $query = User::with('mahasiswa')->where('role', 'mahasiswa')->latest();

        if ($status === 'pending') {
            $query->where('status', 'inactive');
        } elseif ($status === 'active') {
            $query->where('status', 'active');
        } elseif ($status === 'suspended') {
            $query->where('status', 'suspended');
        }

        $users = $query->paginate(20)->withQueryString();

        return view('admin.users.index', compact('users', 'status'));
    }

    /**
     * Approve a pending mahasiswa user.
     */
    public function approve(User $user)
    {
        if ($user->status !== 'inactive') {
            return back()->with('error', 'Hanya akun dengan status pending yang dapat disetujui.');
        }

        $user->update(['status' => 'active']);

        $this->auditLogService->log(
            'USER_APPROVED',
            "Admin menyetujui akun mahasiswa: {$user->name} (Email: {$user->email})"
        );

        return back()->with('success', "Akun {$user->name} berhasil disetujui.");
    }

    /**
     * Suspend an active user.
     */
    public function suspend(User $user)
    {
        if ($user->status !== 'active') {
            return back()->with('error', 'Hanya akun aktif yang dapat disuspensi.');
        }

        $user->update(['status' => 'suspended']);

        $this->auditLogService->log(
            'USER_SUSPENDED',
            "Admin mensuspensi akun mahasiswa: {$user->name} (Email: {$user->email})"
        );

        return back()->with('success', "Akun {$user->name} berhasil disuspensi.");
    }

    /**
     * Activate a suspended user.
     */
    public function activate(User $user)
    {
        if ($user->status !== 'suspended') {
            return back()->with('error', 'Hanya akun disuspensi yang dapat diaktifkan kembali.');
        }

        $user->update(['status' => 'active']);

        $this->auditLogService->log(
            'USER_ACTIVATED',
            "Admin mengaktifkan kembali akun mahasiswa: {$user->name} (Email: {$user->email})"
        );

        return back()->with('success', "Akun {$user->name} berhasil diaktifkan kembali.");
    }

    /**
     * Soft delete a user and clean all files.
     */
    public function destroy(User $user)
    {
        // 1. Clean files physically
        $this->userService->cleanFiles($user);

        // 2. Soft delete mahasiswa profile
        if ($user->mahasiswa) {
            $user->mahasiswa->delete();
        }

        // 3. Soft delete user record
        $userName = $user->name;
        $userEmail = $user->email;
        $user->delete();

        // 4. Log
        $this->auditLogService->log(
            'USER_DELETED',
            "Admin menghapus (soft-delete) akun mahasiswa: {$userName} (Email: {$userEmail})"
        );

        return redirect()->route('admin.users.index')->with('success', "Akun {$userName} berhasil dihapus.");
    }
}
