<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\User;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ServiceController extends Controller
{
    /**
     * Display a listing of services.
     */
    public function index()
    {
        try {
            $services = Service::ordered()->get();
            
            // Calculate statistics
            $total = $services->count();
            $active = $services->where('status', 'active')->count();
            $inactive = $services->where('status', 'inactive')->count();
            $totalRevenue = $services->where('status', 'active')->sum('price');
            
            return view('Pages.Admin.services_management', compact('services', 'total', 'active', 'inactive', 'totalRevenue'));
        } catch (\Exception $e) {
            Log::error('Error loading services: ' . $e->getMessage());
            return view('Pages.Admin.services_management', [
                'services' => collect([]),
                'total' => 0,
                'active' => 0,
                'inactive' => 0,
                'totalRevenue' => 0
            ])->with('error', 'Unable to load services. Please check database connection.');
        }
    }
    
    /**
     * Show the form for creating a new service.
     */
    public function create()
    {
        return view('Component.Admin.add_service');
    }
    
    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name',
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'display_order' => 'nullable|integer',
            'department' => 'nullable|string|max:255'
        ]);
        
        try {
            // Handle image upload
            $imagePath = null;
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('services', 'public');
            }
            
            // Create slug from name
            $slug = Str::slug($request->name);
            
            $service = Service::create([
                'name' => $request->name,
                'slug' => $slug,
                'description' => $request->description,
                'icon' => $request->icon,
                'image' => $imagePath,
                'price' => $request->price,
                'duration' => $request->duration,
                'status' => $request->status,
                'display_order' => $request->display_order ?? 0,
                'department' => $request->department
            ]);
            
            Log::info('Service added successfully: ' . $service->name);
            
            // ✅ Send notification to all admins
            $this->sendServiceNotification(
                'Service Added',
                'Service "' . $service->name . '" has been added to the system',
                'service_added',
                $service,
                'fa-concierge-bell'
            );
            
            return redirect()->route('admin.services.index')
                ->with('success', 'Service "' . $service->name . '" added successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error adding service: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error adding service: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Show the form for editing the specified service.
     */
    public function edit($id)
    {
        try {
            $service = Service::findOrFail($id);
            return view('Layout.edit-service', compact('service'));
        } catch (\Exception $e) {
            Log::error('Service not found: ID ' . $id);
            return redirect()->route('admin.services.index')
                ->with('error', 'Service not found!');
        }
    }
    
    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:services,name,' . $id,
            'description' => 'required|string',
            'icon' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'price' => 'nullable|numeric|min:0',
            'duration' => 'nullable|integer|min:1',
            'status' => 'required|in:active,inactive',
            'display_order' => 'nullable|integer',
            'department' => 'nullable|string|max:255'
        ]);
        
        try {
            $service = Service::findOrFail($id);
            $oldName = $service->name;
            
            // Handle image upload
            if ($request->hasFile('image')) {
                // Delete old image
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                    Log::info('Deleted old service image: ' . $service->image);
                }
                $imagePath = $request->file('image')->store('services', 'public');
                $service->image = $imagePath;
            }
            
            // Update slug if name changed
            if ($service->name !== $request->name) {
                $service->slug = Str::slug($request->name);
            }
            
            $service->name = $request->name;
            $service->description = $request->description;
            $service->icon = $request->icon;
            $service->price = $request->price;
            $service->duration = $request->duration;
            $service->status = $request->status;
            $service->display_order = $request->display_order ?? 0;
            $service->department = $request->department;
            $service->save();
            
            Log::info('Service updated successfully: ' . $service->name);
            
            // ✅ Send notification to all admins
            $this->sendServiceNotification(
                'Service Updated',
                'Service "' . $service->name . '" has been updated',
                'service_updated',
                $service,
                'fa-edit',
                ['old_name' => $oldName]
            );
            
            return redirect()->route('admin.services.index')
                ->with('success', 'Service "' . $service->name . '" updated successfully!');
                
        } catch (\Exception $e) {
            Log::error('Error updating service: ' . $e->getMessage());
            
            return redirect()->back()
                ->with('error', 'Error updating service: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    /**
     * Remove the specified service from storage.
     */
    public function destroy($id)
    {
        try {
            $service = Service::findOrFail($id);
            $serviceName = $service->name;
            
            // Delete image if exists
            if ($service->image && Storage::disk('public')->exists($service->image)) {
                Storage::disk('public')->delete($service->image);
                Log::info('Deleted service image: ' . $service->image);
            }
            
            $service->delete();
            
            Log::info('Service deleted successfully: ' . $serviceName);
            
            // ✅ Send notification to all admins
            $this->sendServiceDeleteNotification($serviceName);
            
            return response()->json([
                'success' => true,
                'message' => 'Service "' . $serviceName . '" deleted successfully!'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting service: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Error deleting service: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Update service status
     */
    public function updateStatus($id, $status)
    {
        try {
            $service = Service::findOrFail($id);
            $oldStatus = $service->status;
            
            if (!in_array($status, ['active', 'inactive'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status value!'
                ], 400);
            }
            
            $service->status = $status;
            $service->save();
            
            // ✅ Send notification to all admins
            $this->sendServiceNotification(
                'Service Status Updated',
                'Service "' . $service->name . '" status changed from ' . ucfirst($oldStatus) . ' to ' . ucfirst($status),
                'service_updated',
                $service,
                'fa-toggle-on',
                ['old_status' => $oldStatus, 'new_status' => $status]
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Service status updated successfully!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating service status!'
            ], 500);
        }
    }
    
    /**
     * Get active services list (for API/frontend)
     */
    public function getActiveServices()
    {
        try {
            $services = Service::active()->ordered()->get();
            return response()->json([
                'success' => true,
                'data' => $services
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error fetching active services'
            ], 500);
        }
    }
    
    /**
     * Search services
     */
    public function search(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $services = Service::where('name', 'LIKE', "%{$query}%")
                ->orWhere('department', 'LIKE', "%{$query}%")
                ->orWhere('description', 'LIKE', "%{$query}%")
                ->ordered()
                ->get();
            
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $services
                ]);
            }
            
            return view('Pages.Admin.services_management', compact('services'));
        } catch (\Exception $e) {
            Log::error('Error searching services: ' . $e->getMessage());
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error searching services'
                ], 500);
            }
            return redirect()->back()->with('error', 'Error searching services');
        }
    }
    
    /**
     * Bulk delete services
     */
    public function bulkDelete(Request $request)
    {
        try {
            $ids = $request->get('ids', []);
            if (empty($ids)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No services selected for deletion'
                ], 400);
            }
            
            $services = Service::whereIn('id', $ids)->get();
            $deletedCount = 0;
            $deletedNames = [];
            
            foreach ($services as $service) {
                $deletedNames[] = $service->name;
                // Delete image if exists
                if ($service->image && Storage::disk('public')->exists($service->image)) {
                    Storage::disk('public')->delete($service->image);
                }
                $service->delete();
                $deletedCount++;
            }
            
            Log::info("Bulk deleted {$deletedCount} services");
            
            // ✅ Send notification to all admins
            $this->sendServiceBulkDeleteNotification($deletedNames, $deletedCount);
            
            return response()->json([
                'success' => true,
                'message' => "Successfully deleted {$deletedCount} service(s)!",
                'count' => $deletedCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error in bulk delete: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error deleting services: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * ✅ Send service notification to all admins
     */
    private function sendServiceNotification($title, $message, $type, $service, $icon, $extra = [])
    {
        try {
            $admins = User::where('role', 'admin')->get();

            if ($admins->count() === 0) {
                Log::warning('No admin users found to send notification');
                return;
            }

            $data = array_merge([
                'icon' => $icon,
                'service_id' => $service->id,
                'service_name' => $service->name,
                'service_price' => $service->price,
                'service_status' => $service->status,
                'url' => route('admin.services.edit', $service->id)
            ], $extra);

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => $title,
                    'message' => $message,
                    'type' => $type,
                    'data' => json_encode($data),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Service notification sent to ' . $admins->count() . ' admins: ' . $title);

        } catch (\Exception $e) {
            Log::error('Failed to create service notification: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Send service delete notification
     */
    private function sendServiceDeleteNotification($serviceName)
    {
        try {
            $admins = User::where('role', 'admin')->get();

            if ($admins->count() === 0) {
                Log::warning('No admin users found to send notification');
                return;
            }

            $data = [
                'icon' => 'fa-trash',
                'service_name' => $serviceName
            ];

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Service Deleted',
                    'message' => 'Service "' . $serviceName . '" has been removed from the system',
                    'type' => 'service_deleted',
                    'data' => json_encode($data),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Service delete notification sent to ' . $admins->count() . ' admins');

        } catch (\Exception $e) {
            Log::error('Failed to create service delete notification: ' . $e->getMessage());
        }
    }

    /**
     * ✅ Send service bulk delete notification
     */
    private function sendServiceBulkDeleteNotification($serviceNames, $count)
    {
        try {
            $admins = User::where('role', 'admin')->get();

            if ($admins->count() === 0) {
                Log::warning('No admin users found to send notification');
                return;
            }

            $namesList = implode(', ', array_slice($serviceNames, 0, 3));
            if (count($serviceNames) > 3) {
                $namesList .= ' and ' . (count($serviceNames) - 3) . ' more';
            }

            $data = [
                'icon' => 'fa-trash-alt',
                'service_names' => $serviceNames,
                'count' => $count
            ];

            foreach ($admins as $admin) {
                Notification::create([
                    'user_id' => $admin->id,
                    'title' => 'Services Bulk Deleted',
                    'message' => $count . ' service(s) have been removed from the system: ' . $namesList,
                    'type' => 'service_deleted',
                    'data' => json_encode($data),
                    'read_at' => null,
                    'created_at' => now()
                ]);
            }

            Log::info('Service bulk delete notification sent to ' . $admins->count() . ' admins');

        } catch (\Exception $e) {
            Log::error('Failed to create service bulk delete notification: ' . $e->getMessage());
        }
    }
}