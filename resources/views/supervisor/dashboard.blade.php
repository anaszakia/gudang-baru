@extends('layouts.app')

@section('title', 'Dashboard Supervisor')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-gray-600 mt-1">Dashboard Supervisor | {{ now()->format('d F Y') }}</p>
            </div>
            
            <div class="flex space-x-3">
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-chart-line"></i>
                    Laporan Kinerja
                </a>
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-users"></i>
                    Kelola Tim
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Total Sales -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Penjualan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">Rp {{ number_format($totalSales ?? 0, 0, ',', '.') }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600 font-medium">{{ $persenSales ?? '+0%' }}</span>
                        <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-shopping-cart text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Orders -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pesanan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalOrders ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600 font-medium">{{ $persenOrders ?? '+0%' }}</span>
                        <span class="text-sm text-gray-500 ml-2">dari bulan lalu</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-file-invoice text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Sales Team -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Tim Sales</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalSalesTeam ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-500">Anggota aktif</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-users text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Target Achievement -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pencapaian Target</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $targetAchievement ?? '0%' }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600 font-medium">Bulan ini</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-bullseye text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Performance Chart -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Performa Penjualan Bulanan</h3>
        <div class="w-full h-64 bg-gray-50 rounded-lg border border-gray-200 p-4 flex items-center justify-center">
            <!-- Placeholder for Chart -->
            <div class="text-center text-gray-500">
                <i class="fas fa-chart-bar text-4xl mb-3"></i>
                <p>Grafik Performa Penjualan akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
    
    <!-- Team Performance -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Top Performing Sales -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Top Sales</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Penjualan</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Target</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Performa</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($topSales ?? [] as $sales)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                        {{ strtoupper(substr($sales->name, 0, 1)) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $sales->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $sales->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($sales->total_sales, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Rp {{ number_format($sales->target, 0, ',', '.') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="mr-2 text-sm font-medium {{ $sales->performance >= 100 ? 'text-green-600' : 'text-orange-600' }}">
                                        {{ $sales->performance }}%
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($sales->performance, 100) }}%"></div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">Belum ada data sales</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Aktivitas Terbaru</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentActivities ?? [] as $activity)
                <div class="flex items-start">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-green-100 flex items-center justify-center text-green-600">
                        <i class="fas fa-{{ $activity->icon ?? 'check' }}"></i>
                    </div>
                    <div class="ml-4 flex-1">
                        <div class="text-sm font-medium text-gray-900">{{ $activity->title }}</div>
                        <div class="text-sm text-gray-500">{{ $activity->description }}</div>
                        <div class="text-xs text-gray-400 mt-1">{{ $activity->time }}</div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-clock text-2xl mb-2"></i>
                    <p>Belum ada aktivitas terbaru</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
