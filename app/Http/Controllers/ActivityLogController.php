<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use App\Services\IpAddressService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Barryvdh\DomPDF\Facade\Pdf;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $query = ActivityLog::with('user')->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('action_type')) {
            $query->byActionType($request->action_type);
        }

        if ($request->filled('user_id')) {
            $query->byUser($request->user_id);
        }

        if ($request->filled('date_from') && $request->filled('date_to')) {
            $query->byDateRange($request->date_from, $request->date_to);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('description', 'like', "%{$search}%")
                  ->orWhere('ip_address', 'like', "%{$search}%")
                  ->orWhereHas('user', function($userQuery) use ($search) {
                      $userQuery->where('name', 'like', "%{$search}%")
                               ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        // For DataTables AJAX
        if ($request->ajax()) {
            $logs = $query->paginate($request->length ?? 10);
            
            return response()->json([
                'draw' => intval($request->draw),
                'recordsTotal' => ActivityLog::count(),
                'recordsFiltered' => $logs->total(),
                'data' => $logs->items()
            ]);
        }

        $logs = $query->paginate(10);
        $users = User::orderBy('name')->get();
        $actionTypes = ActivityLog::distinct()->pluck('action_type');

        return view('admin.activity-logs.index', compact('logs', 'users', 'actionTypes'));
    }

    public function show(ActivityLog $activityLog)
    {
        return view('admin.activity-logs.show', compact('activityLog'));
    }

    public function exportPdf(Request $request, $id = null)
    {
        if ($id) {
            // Export single log
            $log = ActivityLog::with('user')->findOrFail($id);
            $logs = collect([$log]);
            $title = 'Activity Log - ' . $log->formatted_date;
        } else {
            // Export all logs with filters
            $query = ActivityLog::with('user')->orderBy('created_at', 'desc');
            
            if ($request->filled('action_type')) {
                $query->byActionType($request->action_type);
            }
            if ($request->filled('user_id')) {
                $query->byUser($request->user_id);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            $logs = $query->get();
            $title = 'Activity Logs Report - ' . now()->format('d M Y');
        }

        $pdf = Pdf::loadView('admin.activity-logs.pdf', compact('logs', 'title'));
        $filename = 'activity_logs_' . now()->format('Y_m_d_H_i_s') . '.pdf';
        
        return $pdf->download($filename);
    }

    public function exportCsv(Request $request, $id = null)
    {
        if ($id) {
            // Export single log
            $log = ActivityLog::with('user')->findOrFail($id);
            $logs = collect([$log]);
            $filename = 'activity_log_' . $id . '_' . now()->format('Y_m_d') . '.csv';
        } else {
            // Export all logs with filters
            $query = ActivityLog::with('user')->orderBy('created_at', 'desc');
            
            if ($request->filled('action_type')) {
                $query->byActionType($request->action_type);
            }
            if ($request->filled('user_id')) {
                $query->byUser($request->user_id);
            }
            if ($request->filled('date_from') && $request->filled('date_to')) {
                $query->byDateRange($request->date_from, $request->date_to);
            }
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('description', 'like', "%{$search}%")
                      ->orWhere('ip_address', 'like', "%{$search}%")
                      ->orWhereHas('user', function($userQuery) use ($search) {
                          $userQuery->where('name', 'like', "%{$search}%")
                                   ->orWhere('email', 'like', "%{$search}%");
                      });
                });
            }
            
            $logs = $query->get();
            $filename = 'activity_logs_' . now()->format('Y_m_d_H_i_s') . '.csv';
        }

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($logs) {
            $file = fopen('php://output', 'w');
            
            // Add CSV headers
            fputcsv($file, [
                'ID',
                'User Name',
                'User Email',
                'Action Type',
                'Description',
                'IP Address',
                'Date & Time',
                'Time Elapsed'
            ]);

            // Add data rows
            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->user?->name ?? 'Unknown',
                    $log->user?->email ?? 'Unknown',
                    ucfirst($log->action_type),
                    $log->description,
                    $log->ip_address,
                    $log->formatted_date,
                    $log->time_elapsed
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }



    // Helper method to log activities
    public static function log($actionType, $description, $userId = null, $properties = [])
    {
        ActivityLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action_type' => $actionType,
            'description' => $description,
            'ip_address'  => IpAddressService::getRealIpAddress(),
            'user_agent' => request()->userAgent(),
            'properties' => $properties,
        ]);
    }
}
