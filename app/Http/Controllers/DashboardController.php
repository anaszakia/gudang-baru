<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\AuditLog;
use App\Models\Penawaran;
use App\Models\DetailPenawaran;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminGudangDashboard()
    {
        $user = auth()->user();
        
        // Data untuk admin-gudang dashboard
        $data = [
            'user' => $user,
            'totalProducts' => \App\Models\Produk::count(),
            'totalBarangMasuk' => \App\Models\BarangMasuk::count(),
            // 'persenBarangMasuk' => '+5.4%',
            'totalBarangKeluar' => \App\Models\BarangKeluar::count(),
            // 'persenBarangKeluar' => '+3.1%',
            'lowStockProducts' => \App\Models\Produk::where('stok', '<', 10)->count(),
            'totalTransactions' => \App\Models\BarangMasuk::count() + \App\Models\BarangKeluar::count(),
            'recentBarangMasuk' => \App\Models\BarangMasuk::latest()->take(5)->get(),
            'recentBarangKeluar' => \App\Models\BarangKeluar::latest()->take(5)->get(),
        ];
        
        return view('admin-gudang.dashboard', $data);
    }
    
    public function supervisorDashboard()
    {
        $user = auth()->user();
        
        // Data untuk supervisor dashboard
        $data = [
            'user' => $user,
            'totalSales' => 1250000000, // Placeholder data - replace with actual calculation
            'persenSales' => '+12.5%',
            'totalOrders' => 486,
            'persenOrders' => '+8.2%',
            'totalSalesTeam' => 8,
            'targetAchievement' => '78%',
            'topSales' => collect([]), // Replace with actual data
            'recentActivities' => collect([]), // Replace with actual data
        ];
        
        return view('supervisor.dashboard', $data);
    }
    
    public function salesDashboard()
    {
        $user = auth()->user();
        
        // Calculate total of approved penawarans by the sales
        $totalApproved = Penawaran::where('user_id', $user->id)
            ->where('status', 'approved')
            ->sum('total_harga');
            
        // Calculate total of pending penawarans
        $totalPending = Penawaran::where('user_id', $user->id)
            ->where('status', 'pending')
            ->sum('total_harga');
            
        // Count total penawarans
        $totalPenawarans = Penawaran::where('user_id', $user->id)->count();
        
        // Count approved penawarans
        $approvedPenawarans = Penawaran::where('user_id', $user->id)
            ->where('status', 'approved')
            ->count();
            
        // Count rejected penawarans
        $rejectedPenawarans = Penawaran::where('user_id', $user->id)
            ->where('status', 'rejected')
            ->count();
            
        // Get recent penawarans
        $recentPenawarans = Penawaran::where('user_id', $user->id)
            ->latest()
            ->take(5)
            ->get();
            
        // Calculate approval rate percentage
        $approvalRate = $totalPenawarans > 0 
            ? round(($approvedPenawarans / $totalPenawarans) * 100, 1) 
            : 0;
            
        // Get top products from this sales penawarans
        $topProducts = DetailPenawaran::select(
                'produks.nama',
                DB::raw('SUM(detail_penawarans.jumlah) as total_quantity'),
                DB::raw('SUM(detail_penawarans.subtotal) as total_value')
            )
            ->join('penawarans', 'detail_penawarans.penawaran_id', '=', 'penawarans.id')
            ->join('produks', 'detail_penawarans.produk_id', '=', 'produks.id')
            ->where('penawarans.user_id', $user->id)
            ->where('penawarans.status', 'approved')
            ->groupBy('produks.nama')
            ->orderBy('total_value', 'desc')
            ->take(5)
            ->get();
            
        // Get month-by-month penawaran statistics for charts
        $monthlyStats = $this->getSalesMonthlyStats($user->id);
        
        // Data untuk sales dashboard
        $data = [
            'user' => $user,
            'personalSales' => $totalApproved,
            'totalPending' => $totalPending,
            'totalPenawarans' => $totalPenawarans,
            'approvedPenawarans' => $approvedPenawarans,
            'rejectedPenawarans' => $rejectedPenawarans,
            'approvalRate' => $approvalRate . '%',
            'recentPenawarans' => $recentPenawarans,
            'topProducts' => $topProducts,
            'monthlyStats' => $monthlyStats
        ];
        
        return view('sales.dashboard', $data);
    }
    
    /**
     * Get monthly penawaran statistics for a specific sales user
     */
    private function getSalesMonthlyStats($userId)
    {
        $months = [];
        $approvedValues = [];
        $pendingValues = [];
        $rejectedValues = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            
            // Approved penawarans value for this month
            $approvedValue = Penawaran::where('user_id', $userId)
                ->where('status', 'approved')
                ->whereYear('tanggal_penawaran', $date->year)
                ->whereMonth('tanggal_penawaran', $date->month)
                ->sum('total_harga');
            $approvedValues[] = $approvedValue;
            
            // Pending penawarans value for this month
            $pendingValue = Penawaran::where('user_id', $userId)
                ->where('status', 'pending')
                ->whereYear('tanggal_penawaran', $date->year)
                ->whereMonth('tanggal_penawaran', $date->month)
                ->sum('total_harga');
            $pendingValues[] = $pendingValue;
            
            // Rejected penawarans value for this month
            $rejectedValue = Penawaran::where('user_id', $userId)
                ->where('status', 'rejected')
                ->whereYear('tanggal_penawaran', $date->year)
                ->whereMonth('tanggal_penawaran', $date->month)
                ->sum('total_harga');
            $rejectedValues[] = $rejectedValue;
        }
        
        return [
            'months' => $months,
            'approved' => $approvedValues,
            'pending' => $pendingValues,
            'rejected' => $rejectedValues
        ];
    }
    
    public function driverDashboard()
    {
        $user = auth()->user();
        
        // Count total deliveries assigned to this driver
        $totalPengiriman = \App\Models\Pengiriman::where('driver_id', $user->id)->count();
        
        // Count active deliveries (not completed)
        $aktivePengiriman = \App\Models\Pengiriman::where('driver_id', $user->id)
            ->where('status_pengiriman', '!=', 'selesai')
            ->count();
            
        // Count completed deliveries
        $selesaiPengiriman = \App\Models\Pengiriman::where('driver_id', $user->id)
            ->where('status_pengiriman', 'selesai')
            ->count();
            
        // Get active deliveries list
        $pengirimanAktifList = \App\Models\Pengiriman::where('driver_id', $user->id)
            ->with('barangKeluar')
            ->where('status_pengiriman', '!=', 'selesai')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        // Calculate completion rate
        $completionRate = $totalPengiriman > 0 
            ? round(($selesaiPengiriman / $totalPengiriman) * 100, 1) . '%' 
            : '0%';
        
        // Data untuk driver dashboard
        $data = [
            'user' => $user,
            'deliveriesToday' => \App\Models\Pengiriman::where('driver_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),
            'completedToday' => \App\Models\Pengiriman::where('driver_id', $user->id)
                ->where('status_pengiriman', 'selesai')
                ->whereDate('waktu_selesai', today())
                ->count(),
            'totalPengiriman' => $totalPengiriman,
            'aktivePengiriman' => $aktivePengiriman,
            'selesaiPengiriman' => $selesaiPengiriman,
            'completionRate' => $completionRate,
            'pengirimanAktifList' => $pengirimanAktifList,
        ];
        
        return view('driver.dashboard', $data);
    }

    public function adminDashboard()
    {
        // Data untuk admin dashboard
        $data = [
            'totalUsers' => User::whereIn('role', ['admin-gudang', 'driver', 'sales', 'supervisor'])->count(),
            'totalAdmins' => User::where('role', 'admin-super')->count(),
            'totalRegularUsers' => User::where('role', 'user')->count(),
            'todayRegistrations' => User::whereDate('created_at', today())->count(),
            'thisWeekRegistrations' => User::whereBetween('created_at', [
                Carbon::now()->startOfWeek(),
                Carbon::now()->endOfWeek()
            ])->count(),
            'thisMonthRegistrations' => User::whereMonth('created_at', Carbon::now()->month)
                ->whereYear('created_at', Carbon::now()->year)
                ->count(),
            'todayLogins' => AuditLog::where('action', 'Login')
                ->whereDate('created_at', today())
                ->count(),
            'totalAuditLogs' => AuditLog::count(),
            'recentUsers' => User::latest()->take(5)->get(),
            'recentActivity' => AuditLog::with('user')->latest()->take(10)->get(),
            'userGrowthData' => $this->getUserGrowthData(),
            'loginStats' => $this->getLoginStats(),
        ];
        
        return view('admin-super.dashboard', $data);
    }
    
    private function getUserGrowthData()
    {
        $months = [];
        $userCounts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $months[] = $date->format('M Y');
            $count = User::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            $userCounts[] = $count;
        }
        
        return [
            'months' => $months,
            'userCounts' => $userCounts
        ];
    }
    
    private function getLoginStats()
    {
        $days = [];
        $loginCounts = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $days[] = $date->format('M d');
            $count = AuditLog::where('action', 'Login')
                ->whereDate('created_at', $date)
                ->count();
            $loginCounts[] = $count;
        }
        
        return [
            'days' => $days,
            'loginCounts' => $loginCounts
        ];
    }
}
