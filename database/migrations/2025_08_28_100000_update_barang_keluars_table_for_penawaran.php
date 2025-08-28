<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            // Add foreign key to penawaran table
            $table->foreignId('penawaran_id')->nullable()->after('payment')->constrained()->nullOnDelete();
            
            // Add additional columns
            $table->string('alamat_penerima')->nullable()->after('penerima');
            $table->string('telepon_penerima')->nullable()->after('alamat_penerima');
            $table->decimal('total_harga', 12, 2)->default(0)->after('telepon_penerima');
            $table->text('catatan')->nullable()->after('total_harga');
            $table->enum('status', ['draft', 'finalized'])->default('draft')->after('catatan');
            $table->foreignId('user_id')->nullable()->after('status')->constrained()->nullOnDelete();
            $table->timestamp('finalized_at')->nullable()->after('user_id');
            
            // Rename nomor_transaksi to kode_barang_keluar
            $table->renameColumn('nomor_transaksi', 'kode_barang_keluar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('barang_keluars', function (Blueprint $table) {
            // Rename kode_barang_keluar back to nomor_transaksi
            $table->renameColumn('kode_barang_keluar', 'nomor_transaksi');
            
            // Drop the new columns
            $table->dropForeign(['penawaran_id']);
            $table->dropForeign(['user_id']);
            $table->dropColumn([
                'penawaran_id',
                'alamat_penerima',
                'telepon_penerima',
                'total_harga',
                'catatan',
                'status',
                'user_id',
                'finalized_at'
            ]);
        });
    }
};
