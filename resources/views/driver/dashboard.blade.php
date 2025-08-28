@extends('layouts.app')

@section('title', 'Dashboard Driver')

@section('content')
<div class="space-y-6">
    <!-- Welcome Banner -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
        <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Selamat Datang, {{ auth()->user()->name }}</h1>
                <p class="text-gray-600 mt-1">Dashboard Driver | {{ now()->format('d F Y') }}</p>
            </div>
            
            <div class="flex space-x-3">
                <a href="#" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-clipboard-list"></i>
                    Jadwal Pengiriman
                </a>
                <a href="#" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors flex items-center gap-2">
                    <i class="fas fa-truck"></i>
                    Update Status
                </a>
            </div>
        </div>
    </div>
    
    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <!-- Deliveries Today -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Pengiriman Hari Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $deliveriesToday ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-blue-600 font-medium">{{ $completedToday ?? 0 }}</span>
                        <span class="text-sm text-gray-500 ml-2">selesai</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-truck text-blue-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Total Deliveries -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Pengiriman</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalDeliveries ?? 0 }}</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-green-600 font-medium">{{ $completionRate ?? '0%' }}</span>
                        <span class="text-sm text-gray-500 ml-2">tingkat penyelesaian</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-box text-green-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Distance -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Jarak</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $totalDistance ?? 0 }} km</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-gray-500">Bulan ini</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-route text-purple-600"></i>
                </div>
            </div>
        </div>
        
        <!-- Fuel Consumption -->
        <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Konsumsi BBM</p>
                    <p class="text-2xl font-bold text-gray-900 mt-1">{{ $fuelConsumption ?? 0 }} L</p>
                    <div class="flex items-center mt-2">
                        <span class="text-sm text-orange-600 font-medium">{{ $fuelEfficiency ?? '0 km/L' }}</span>
                        <span class="text-sm text-gray-500 ml-2">efisiensi</span>
                    </div>
                </div>
                <div class="w-12 h-12 bg-orange-100 rounded-full flex items-center justify-center">
                    <i class="fas fa-gas-pump text-orange-600"></i>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Map View -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
        <h3 class="text-lg font-semibold text-gray-900 mb-4">Peta Rute Pengiriman</h3>
        <div class="w-full h-80 bg-gray-50 rounded-lg border border-gray-200 p-4 flex items-center justify-center">
            <!-- Placeholder for Map -->
            <div class="text-center text-gray-500">
                <i class="fas fa-map-marked-alt text-4xl mb-3"></i>
                <p>Peta rute pengiriman akan ditampilkan di sini</p>
            </div>
        </div>
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Today's Delivery Schedule -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Jadwal Pengiriman Hari Ini</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No. Order</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelanggan & Lokasi</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Waktu</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($todayDeliveries ?? [] as $delivery)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">#{{ $delivery->order_number }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">{{ $delivery->customer_name }}</div>
                                <div class="text-sm text-gray-500">{{ $delivery->address }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $delivery->scheduled_time }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'in_transit' => 'bg-blue-100 text-blue-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                    ];
                                    $statusClass = $statusClasses[$delivery->status] ?? 'bg-gray-100 text-gray-800';
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusClass }}">
                                    {{ ucfirst(str_replace('_', ' ', $delivery->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="#" class="text-blue-600 hover:text-blue-900 mr-3">Detail</a>
                                <a href="#" class="text-green-600 hover:text-green-900">Update</a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">Tidak ada pengiriman hari ini</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Delivery History -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Riwayat Pengiriman Terakhir</h3>
            </div>
            <div class="p-6 space-y-4">
                @forelse($recentDeliveries ?? [] as $history)
                <div class="flex items-start space-x-3 p-3 hover:bg-gray-50 rounded-lg transition-colors">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center
                        {{ $history->status === 'delivered' ? 'bg-green-100' : 'bg-blue-100' }}">
                        <i class="fas {{ $history->status === 'delivered' ? 'fa-check' : 'fa-truck' }} 
                            {{ $history->status === 'delivered' ? 'text-green-600' : 'text-blue-600' }}"></i>
                    </div>
                    <div class="flex-1">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $history->customer_name }}</p>
                                <p class="text-xs text-gray-500">Order #{{ $history->order_number }}</p>
                            </div>
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                {{ $history->status === 'delivered' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                {{ ucfirst($history->status) }}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600 mt-1">{{ $history->address }}</p>
                        <div class="flex justify-between items-center mt-1">
                            <p class="text-xs text-gray-500">{{ $history->completed_at }}</p>
                            <p class="text-xs font-medium text-gray-600">{{ $history->distance }} km</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center text-gray-500 py-4">
                    <i class="fas fa-history text-2xl mb-2"></i>
                    <p>Belum ada riwayat pengiriman</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
