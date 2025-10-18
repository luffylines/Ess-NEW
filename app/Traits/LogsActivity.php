<?php

namespace App\Traits;

use App\Models\ActivityLog;
use App\Services\IpAddressService;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{

    /**
     * Log an activity
     */
    public function logActivity($actionType, $description, $properties = [])
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action_type' => $actionType,
            'description' => $description,
            'ip_address' => IpAddressService::getRealIpAddress(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
        ]);
    }

    /**
     * Log login activity
     */
    public function logLogin($method = 'email')
    {
        $this->logActivity('login', 'Logged In to the system', [
            'login_method' => $method,
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log logout activity
     */
    public function logLogout()
    {
        $this->logActivity('logout', 'Logged out from the system', [
            'timestamp' => now()->toISOString(),
        ]);
    }

    /**
     * Log view activity
     */
    public function logView($resource, $resourceId = null)
    {
        $description = "Viewed {$resource}";
        if ($resourceId) {
            $description .= " (ID: {$resourceId})";
        }

        $this->logActivity('view', $description, [
            'resource' => $resource,
            'resource_id' => $resourceId,
        ]);
    }

    /**
     * Log create activity
     */
    public function logCreate($resource, $resourceId, $additionalData = [])
    {
        $this->logActivity('create', "Created new {$resource} (ID: {$resourceId})", array_merge([
            'resource' => $resource,
            'resource_id' => $resourceId,
        ], $additionalData));
    }

    /**
     * Log update activity
     */
    public function logUpdate($resource, $resourceId, $additionalData = [])
    {
        $this->logActivity('update', "Updated {$resource} (ID: {$resourceId})", array_merge([
            'resource' => $resource,
            'resource_id' => $resourceId,
        ], $additionalData));
    }

    /**
     * Log delete activity
     */
    public function logDelete($resource, $resourceId, $additionalData = [])
    {
        $this->logActivity('delete', "Deleted {$resource} (ID: {$resourceId})", array_merge([
            'resource' => $resource,
            'resource_id' => $resourceId,
        ], $additionalData));
    }

    /**
     * Log export activity
     */
    public function logExport($format, $resource, $recordCount = null)
    {
        $description = "Exported {$resource} to {$format}";
        if ($recordCount) {
            $description .= " ({$recordCount} records)";
        }

        $this->logActivity('export', $description, [
            'format' => $format,
            'resource' => $resource,
            'record_count' => $recordCount,
        ]);
    }
}