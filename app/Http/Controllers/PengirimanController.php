<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\AuditLog;
use App\Models\Pengiriman;
use App\Models\BarangKeluar;
use App\Exports\PengirimanExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class PengirimanController extends Controller
{
    /**
     * Display a listing of the pengiriman for admin.
     */
    public function index()
    {
        $pengirimanList = Pengiriman::with(['barangKeluar', 'driver'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('pengiriman.index', compact('pengirimanList'));
    }

    /**
     * Show the form for creating a new pengiriman.
     */
    public function create(BarangKeluar $barangKeluar)
    {
        // Check if barang keluar is already finalized
        if ($barangKeluar->status != 'finalized') {
            return redirect()->route('admin-super.barang-keluar.index')
                ->with('error', 'Barang keluar harus difinalisasi terlebih dahulu.');
        }
        
        // Temporary solution - get all users with driver role without condition
        $availableDrivers = User::where('role', 'driver')->get();
        
        // Debugging information to help troubleshoot
        if ($availableDrivers->isEmpty()) {
            // Add at least one driver for testing if none exists
            $testUser = User::find(1); // Get existing user for reference
            if ($testUser) {
                // Create a test driver if no drivers exist
                User::create([
                    'name' => 'Driver Test',
                    'email' => 'driver@test.com',
                    'password' => bcrypt('password'),
                    'role' => 'driver'
                ]);
                $availableDrivers = User::where('role', 'driver')->get();
            }
        }
            
        return view('pengiriman.create', compact('barangKeluar', 'availableDrivers'));
    }

    /**
     * Store a newly created pengiriman in storage.
     */
    public function store(Request $request, BarangKeluar $barangKeluar)
    {
        $request->validate([
            'metode_pengiriman' => 'required|in:ambil_sendiri,diantar_driver',
            'driver_id' => 'required_if:metode_pengiriman,diantar_driver|nullable|exists:users,id',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        $pengirimanData = [
            'barang_keluar_id' => $barangKeluar->id,
            'metode_pengiriman' => $request->metode_pengiriman,
            'catatan' => $request->catatan,
            'status_pengiriman' => 'belum_dikirim',
        ];
        
        // Jika metode pengiriman oleh driver, maka set driver_id
        if ($request->metode_pengiriman == 'diantar_driver') {
            $pengirimanData['driver_id'] = $request->driver_id;
        }
        
        $pengiriman = Pengiriman::create($pengirimanData);
        
        // Log activity
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role,
            'action' => 'store',
            'controller' => 'PengirimanController',
            'route' => 'pengiriman.store',
            'method' => 'POST',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => ['detail' => 'Membuat pengiriman baru', 'pengiriman_id' => $pengiriman->id],
            'performed_at' => now()
        ]);
            
        $redirectRoute = Auth::user()->role === 'admin-super' 
            ? 'admin-super.pengiriman.show' 
            : 'admin-gudang.pengiriman.show';
            
        return redirect()->route($redirectRoute, $pengiriman->id)
            ->with('success', 'Pengiriman berhasil dibuat.');
    }

    /**
     * Display the specified pengiriman.
     */
    public function show(Pengiriman $pengiriman)
    {
        $pengiriman->load(['barangKeluar', 'driver']);
        return view('pengiriman.show', compact('pengiriman'));
    }

    /**
     * Update the status pengiriman.
     */
    public function updateStatus(Request $request, Pengiriman $pengiriman)
    {
        $request->validate([
            'status_pengiriman' => 'required|in:dalam_perjalanan,istirahat,selesai',
        ]);
        
        $oldStatus = $pengiriman->status_pengiriman;
        $pengiriman->status_pengiriman = $request->status_pengiriman;
        
        // Set waktu mulai jika pertama kali dalam perjalanan
        if ($request->status_pengiriman == 'dalam_perjalanan' && $oldStatus == 'belum_dikirim') {
            $pengiriman->waktu_mulai = Carbon::now();
        }
        
        // Set waktu selesai jika status berubah menjadi selesai
        if ($request->status_pengiriman == 'selesai') {
            $pengiriman->waktu_selesai = Carbon::now();
        }
        
        $pengiriman->save();
        
        // Log activity
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role,
            'action' => 'update',
            'controller' => 'PengirimanController',
            'route' => 'pengiriman.update-status',
            'method' => 'POST',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => [
                'status_lama' => $oldStatus, 
                'status_baru' => $request->status_pengiriman,
                'pengiriman_id' => $pengiriman->id
            ],
            'performed_at' => now()
        ]);
        
        return redirect()->back()
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
    
    /**
     * Display driver's assigned deliveries
     */
    public function driverDeliveries()
    {
        $pengirimanList = Pengiriman::where('driver_id', Auth::id())
            ->with('barangKeluar')
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('driver.deliveries', compact('pengirimanList'));
    }
    
    /**
     * Show the driver delivery detail
     */
    public function driverDeliveryDetail(Pengiriman $pengiriman)
    {
        // Check if the current driver is assigned to this delivery
        if ($pengiriman->driver_id != Auth::id()) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        $pengiriman->load(['barangKeluar.detailBarangKeluars.produk']);
        return view('driver.delivery-detail', compact('pengiriman'));
    }
    
    /**
     * Update delivery status by driver
     */
    public function driverUpdateStatus(Request $request, Pengiriman $pengiriman)
    {
        // Check if the current driver is assigned to this delivery
        if ($pengiriman->driver_id != Auth::id()) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        $request->validate([
            'status_pengiriman' => 'required|in:dalam_perjalanan,istirahat,selesai',
            'catatan' => 'nullable|string|max:500',
        ]);
        
        $oldStatus = $pengiriman->status_pengiriman;
        $pengiriman->status_pengiriman = $request->status_pengiriman;
        
        // Update catatan jika ada
        if ($request->filled('catatan')) {
            $pengiriman->catatan = $request->catatan;
        }
        
        // Set waktu mulai jika pertama kali dalam perjalanan
        if ($request->status_pengiriman == 'dalam_perjalanan' && $oldStatus == 'belum_dikirim') {
            $pengiriman->waktu_mulai = Carbon::now();
        }
        
        // Set waktu selesai jika status berubah menjadi selesai
        if ($request->status_pengiriman == 'selesai') {
            $pengiriman->waktu_selesai = Carbon::now();
        }
        
        $pengiriman->save();
        
        // Log activity
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role,
            'action' => 'update',
            'controller' => 'PengirimanController',
            'route' => 'driver.deliveries.update-status',
            'method' => 'POST',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => [
                'status_lama' => $oldStatus, 
                'status_baru' => $request->status_pengiriman,
                'pengiriman_id' => $pengiriman->id
            ],
            'performed_at' => now()
        ]);
        
        return redirect()->route('driver.deliveries.detail', $pengiriman->id)
            ->with('success', 'Status pengiriman berhasil diperbarui.');
    }
    
    /**
     * Export pengiriman to Excel
     */
    public function exportExcel(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'status_filter' => 'nullable|in:belum_dikirim,dalam_perjalanan,istirahat,selesai',
        ]);
        
        // Log activity
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role,
            'action' => 'export',
            'controller' => 'PengirimanController',
            'route' => 'pengiriman.export-excel',
            'method' => 'POST',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => [
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'status_filter' => $request->status_filter
            ],
            'performed_at' => now()
        ]);
        
        $fileName = 'pengiriman_' . date('Y-m-d_His') . '.xlsx';
        
        return Excel::download(new PengirimanExport($request), $fileName);
    }
    
    /**
     * Print shipping label
     */
    public function printLabel(Pengiriman $pengiriman)
    {
        $pengiriman->load(['barangKeluar', 'driver']);
        return view('pengiriman.print-label', compact('pengiriman'));
    }
    
    /**
     * Track delivery
     */
    public function track(Pengiriman $pengiriman)
    {
        $pengiriman->load(['barangKeluar', 'driver']);
        return view('pengiriman.track', compact('pengiriman'));
    }
    
    /**
     * Show accept delivery form for driver
     */
    public function driverAcceptDelivery(Pengiriman $pengiriman)
    {
        // Check if the current driver is assigned to this delivery
        if ($pengiriman->driver_id != Auth::id()) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        // Check if the delivery status is still "belum_dikirim"
        if ($pengiriman->status_pengiriman != 'belum_dikirim') {
            return redirect()->route('driver.deliveries')
                ->with('error', 'Pengiriman ini sudah diproses.');
        }
        
        $pengiriman->load(['barangKeluar']);
        return view('driver.accept-delivery', compact('pengiriman'));
    }
    
    /**
     * Process the driver's acceptance of delivery
     */
    public function driverAcceptDeliveryConfirm(Request $request, Pengiriman $pengiriman)
    {
        // Check if the current driver is assigned to this delivery
        if ($pengiriman->driver_id != Auth::id()) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        // Check if the delivery status is still "belum_dikirim"
        if ($pengiriman->status_pengiriman != 'belum_dikirim') {
            return redirect()->route('driver.deliveries')
                ->with('error', 'Pengiriman ini sudah diproses.');
        }
        
        $request->validate([
            'catatan' => 'nullable|string|max:500',
        ]);
        
        // Update pengiriman status
        $pengiriman->status_pengiriman = 'dalam_perjalanan';
        $pengiriman->waktu_mulai = Carbon::now();
        
        // Update catatan jika ada
        if ($request->filled('catatan')) {
            $pengiriman->catatan = $request->catatan;
        }
        
        $pengiriman->save();
        
        // Log activity
        AuditLog::create([
            'user_id' => Auth::id(),
            'user_email' => Auth::user()->email,
            'user_role' => Auth::user()->role,
            'action' => 'update',
            'controller' => 'PengirimanController',
            'route' => 'driver.deliveries.accept-confirm',
            'method' => 'POST',
            'url' => request()->fullUrl(),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status_code' => 200,
            'request_data' => [
                'status_lama' => 'belum_dikirim', 
                'status_baru' => 'dalam_perjalanan',
                'pengiriman_id' => $pengiriman->id
            ],
            'performed_at' => now()
        ]);
        
        return redirect()->route('driver.deliveries.detail', $pengiriman->id)
            ->with('success', 'Pengiriman berhasil diterima dan status diubah menjadi Dalam Perjalanan.');
    }
    
    /**
     * Print surat jalan for driver
     */
    public function driverPrintSuratJalan(Pengiriman $pengiriman)
    {
        // Check if the current driver is assigned to this delivery
        if ($pengiriman->driver_id != Auth::id()) {
            return redirect()->route('driver.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke pengiriman ini.');
        }
        
        $pengiriman->load(['barangKeluar.detailBarangKeluars.produk', 'driver']);
        return view('driver.surat-jalan', compact('pengiriman'));
    }
}
