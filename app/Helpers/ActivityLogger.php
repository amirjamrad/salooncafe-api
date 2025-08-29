<?php

namespace App\Helpers;

use App\Model\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogger
{
    public static function log(Request $request, $action, $entityType, $entityId, $description, $changes = null)
    {
        $admin = $request->user();
        if(!$admin){
            return Response::errorResponse(403,__('api.user_not_permission'));
        }

        return ActivityLog::create([
            'user_id' => $admin->id,
            'user_fullname' => $admin->full_name,
            'entity_type' => $entityType,
            'entity_id' => $entityId,
            'action' => $action,
            'description' => $description,
            'changes' => $changes,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'created_at' => now(),
        ]);
    }
}
