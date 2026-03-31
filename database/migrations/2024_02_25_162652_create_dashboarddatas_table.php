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
        Schema::create('dashboarddatas', function (Blueprint $table) {
            $table->id();
            $table->integer('jumlah_total_karyawan')->nullable();
            $table->integer('jumlah_karyawan_pria')->nullable();
            $table->integer('jumlah_karyawan_wanita')->nullable();
            $table->integer('karyawan_baru_mtd')->nullable();
            $table->integer('karyawan_resigned_mtd')->nullable();
            $table->integer('karyawan_blacklist_mtd')->nullable();
            $table->integer('karyawan_aktif_mtd')->nullable();
            $table->integer('jumlah_karyawan_baru_hari_ini')->nullable();
            $table->integer('jumlah_karyawan_Resigned_hari_ini')->nullable();
            $table->integer('jumlah_karyawan_blacklist_hari_ini')->nullable();
            $table->integer('jumlah_ASB')->nullable();
            $table->integer('jumlah_DPA')->nullable();
            $table->integer('jumlah_YCME')->nullable();
            $table->integer('jumlah_YEV')->nullable();
            $table->integer('jumlah_YIG')->nullable();
            $table->integer('jumlah_YSM')->nullable();
            $table->integer('jumlah_YAM')->nullable();
            $table->integer('jumlah_GAMA')->nullable();
            $table->integer('jumlah_WAS')->nullable();
            $table->integer('jumlah_Pabrik_1')->nullable();
            $table->integer('jumlah_Pabrik_2')->nullable();
            $table->integer('jumlah_Kantor')->nullable();
            $table->integer('jumlah_placement')->nullable();
            $table->integer('jumlah_company')->nullable();
            $table->integer('department_BD')->nullable();
            $table->integer('department_Engineering')->nullable();
            $table->integer('department_EXIM')->nullable();
            $table->integer('department_Finance_Accounting')->nullable();
            $table->integer('department_GA')->nullable();
            $table->integer('department_Gudang')->nullable();
            $table->integer('department_HR')->nullable();
            $table->integer('department_Legal')->nullable();
            $table->integer('department_Procurement')->nullable();
            $table->integer('department_Produksi')->nullable();
            $table->integer('department_Quality_Control')->nullable();
            $table->integer('department_Board_of_Director')->nullable();
            $table->integer('jabatan_Admin')->nullable();
            $table->integer('jabatan_Asisten_Direktur')->nullable();
            $table->integer('jabatan_Asisten_Kepala')->nullable();
            $table->integer('jabatan_Asisten_Manager')->nullable();
            $table->integer('jabatan_Asisten_Pengawas')->nullable();
            $table->integer('jabatan_Asisten_Wakil_Presiden')->nullable();
            $table->integer('jabatan_Design_grafis')->nullable();
            $table->integer('jabatan_Director')->nullable();
            $table->integer('jabatan_Kepala')->nullable();
            $table->integer('jabatan_Manager')->nullable();
            $table->integer('jabatan_Pengawas')->nullable();
            $table->integer('jabatan_President')->nullable();
            $table->integer('jabatan_Senior_staff')->nullable();
            $table->integer('jabatan_Staff')->nullable();
            $table->integer('jabatan_Supervisor')->nullable();
            $table->integer('jabatan_Vice_President')->nullable();
            $table->integer('jabatan_Satpam')->nullable();
            $table->integer('jabatan_Koki')->nullable();
            $table->integer('jabatan_Dapur_Kantor')->nullable();
            $table->integer('jabatan_Dapur_Pabrik')->nullable();
            $table->integer('jabatan_QC_Aging')->nullable();
            $table->integer('jabatan_Driver')->nullable();
            $table->integer('countLatestHadir')->nullable();
            $table->integer('bd')->nullable();
            $table->integer('engineering')->nullable();
            $table->integer('exim')->nullable();
            $table->integer('finance_accounting')->nullable();
            $table->integer('ga')->nullable();
            $table->integer('gudang')->nullable();
            $table->integer('hr')->nullable();
            $table->integer('legal')->nullable();
            $table->integer('procurement')->nullable();
            $table->integer('produksi')->nullable();
            $table->integer('quality_control')->nullable();
            $table->integer('total_presensi_by_departemen')->nullable();
            $table->integer('shift_pagi')->nullable();
            $table->integer('shift_malam')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dashboarddatas');
    }
};
