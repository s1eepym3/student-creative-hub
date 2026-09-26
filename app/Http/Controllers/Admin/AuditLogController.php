<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $query = AuditLog::with('user')->latest();

        if ($request->filled('q')) {
            $keyword = $request->q;
            $query->where(function ($q) use ($keyword) {
                $q->where('action', 'like', "%{$keyword}%")
                  ->orWhere('description', 'like', "%{$keyword}%")
                  ->orWhere('ip_address', 'like', "%{$keyword}%")
                  ->orWhereHas('user', fn($u) => $u->where('name', 'like', "%{$keyword}%"));
            });
        }

        $logs = $query->paginate(20)->withQueryString();

        return view('admin.audit_logs.index', compact('logs'));
    }
}
