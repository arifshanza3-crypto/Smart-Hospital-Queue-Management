<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QueueReport;
use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class QueueReportController extends Controller
{
    public function index(Request $request)
    {
        $reports = QueueReport::query();
        
        // Filter by date range
        if ($request->has('from_date') && $request->from_date) {
            $reports->whereDate('date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $reports->whereDate('date', '<=', $request->to_date);
        }
        
        // Filter by date (single date - backward compatibility)
        if ($request->has('date') && $request->date && !$request->from_date) {
            $reports->whereDate('date', $request->date);
        }
        
        // Filter by department
        if ($request->has('department') && $request->department) {
            $reports->where('department', $request->department);
        }
        
        // Filter by status
        if ($request->has('status') && $request->status) {
            $reports->where('status', $request->status);
        }
        
        $reports = $reports->orderBy('created_at', 'desc')->paginate(15);
        
        // Statistics
        $totalPatients = QueueReport::count();
        $completedToday = QueueReport::whereDate('date', today())->where('status', 'completed')->count();
        $avgWaitingTime = QueueReport::where('status', 'completed')->avg('waiting_time') ?? 0;
        $avgServiceTime = QueueReport::where('status', 'completed')->avg('service_time') ?? 0;
        
        // Department wise statistics
        $departmentStats = QueueReport::select('department', DB::raw('count(*) as total'))
            ->whereNotNull('department')
            ->groupBy('department')
            ->get();
        
        // Daily statistics for chart (last 7 days)
        $dailyStats = QueueReport::select(DB::raw('DATE(date) as date'), DB::raw('count(*) as total'))
            ->where('date', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date', 'asc')
            ->get();
        
        $departments = QueueReport::distinct()->whereNotNull('department')->pluck('department');
        
        // Status wise statistics
        $statusStats = [
            'waiting' => QueueReport::where('status', 'waiting')->count(),
            'in_progress' => QueueReport::where('status', 'in_progress')->count(),
            'completed' => QueueReport::where('status', 'completed')->count(),
            'cancelled' => QueueReport::where('status', 'cancelled')->count(),
        ];
        
        return view('Pages.Admin.queue_reports', compact(
            'reports', 
            'totalPatients', 
            'completedToday',
            'avgWaitingTime',
            'avgServiceTime',
            'departmentStats',
            'dailyStats',
            'departments',
            'statusStats'
        ));
    }
    
    public function show($id)
    {
        $report = QueueReport::findOrFail($id);
        return view('Layout.show-queue-report', compact('report'));
    }
    
    public function export(Request $request)
    {
        $reports = QueueReport::query();
        
        // Apply same filters as in index
        if ($request->has('from_date') && $request->from_date) {
            $reports->whereDate('date', '>=', $request->from_date);
        }
        
        if ($request->has('to_date') && $request->to_date) {
            $reports->whereDate('date', '<=', $request->to_date);
        }
        
        if ($request->has('date') && $request->date && !$request->from_date) {
            $reports->whereDate('date', $request->date);
        }
        
        if ($request->has('department') && $request->department) {
            $reports->where('department', $request->department);
        }
        
        if ($request->has('status') && $request->status) {
            $reports->where('status', $request->status);
        }
        
        $reports = $reports->orderBy('created_at', 'desc')->get();
        
        // Generate filename with date range
        $filename = 'queue_report_';
        if ($request->from_date && $request->to_date) {
            $filename .= $request->from_date . '_to_' . $request->to_date;
        } elseif ($request->date) {
            $filename .= $request->date;
        } else {
            $filename .= date('Y-m-d');
        }
        $filename .= '.csv';
        
        // Create CSV
        $handle = fopen('php://temp', 'w');
        
        // Add headers
        fputcsv($handle, ['Token Number', 'Patient Name', 'Doctor', 'Department', 'Status', 'Waiting Time (min)', 'Service Time (min)', 'Date', 'Created At']);
        
        // Add data
        foreach ($reports as $report) {
            fputcsv($handle, [
                $report->token_number,
                $report->patient_name,
                $report->doctor_name,
                $report->department,
                ucfirst(str_replace('_', ' ', $report->status)),
                $report->waiting_time,
                $report->service_time,
                $report->date,
                $report->created_at->format('Y-m-d H:i:s')
            ]);
        }
        
        // Add summary at the end
        fputcsv($handle, []);
        fputcsv($handle, ['SUMMARY REPORT']);
        fputcsv($handle, ['Total Records', $reports->count()]);
        fputcsv($handle, ['Generated On', now()->format('Y-m-d H:i:s')]);
        
        rewind($handle);
        $csvContent = stream_get_contents($handle);
        fclose($handle);
        
        return response($csvContent)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->header('Cache-Control', 'max-age=0');
    }
    
    // Get report summary for dashboard
    public function summary()
    {
        $today = today();
        $weekAgo = now()->subDays(7);
        
        $summary = [
            'total_patients' => QueueReport::count(),
            'today_patients' => QueueReport::whereDate('date', $today)->count(),
            'week_patients' => QueueReport::where('date', '>=', $weekAgo)->count(),
            'avg_waiting_time' => round(QueueReport::where('status', 'completed')->avg('waiting_time') ?? 0),
            'avg_service_time' => round(QueueReport::where('status', 'completed')->avg('service_time') ?? 0),
            'pending_patients' => QueueReport::whereIn('status', ['waiting', 'in_progress'])->count(),
            'completed_today' => QueueReport::whereDate('date', $today)->where('status', 'completed')->count(),
        ];
        
        return response()->json($summary);
    }
}