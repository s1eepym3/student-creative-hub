<?php

namespace App\Services;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

class AuditLogService
{
    /**
     * Centralized function to log activities in the system.
     *
     * @param string $action
     * @param string $description
     * @param int|null $userId
     * @return AuditLog
     */
    public function log(string $action, string $description, ?int $userId = null): AuditLog
    {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'ip_address' => request()->ip(),
        ]);
    }
}
