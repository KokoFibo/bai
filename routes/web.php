<?php

use App\Models\Lock;
use App\Livewire\Test;
use App\Livewire\Terwr;
use App\Models\Payroll;
use App\Livewire\DataLog;
use App\Livewire\Laporan;
use App\Livewire\Profile;
use App\Livewire\Rubahid;
use App\Livewire\UserLog;
use App\Livewire\Gajibpjs;
use App\Livewire\Moveback;
use App\Livewire\Movedata;
use App\Models\Department;
use App\Livewire\Applicant;
use App\Livewire\Developer;
use App\Livewire\Headcount;
use App\Livewire\Hitungthr;
use App\Livewire\Jabatanwr;
use App\Livewire\MissingId;
use App\Livewire\Payrollwr;
use App\Livewire\Prindexwr;
use App\Livewire\Rubahidwr;
use App\Livewire\Timeoffwr;
use App\Livewire\AddCompany;
use App\Livewire\BankReport;
use App\Livewire\CreateUser;
use App\Livewire\Jobgradewr;
use App\Livewire\Karyawanwr;
use App\Livewire\Tambahanwr;
use App\Livewire\UserMobile;
use App\Livewire\Adddocument;
use App\Livewire\AddPresensi;
use App\Livewire\AddTambahan;
use App\Livewire\ChangeField;
use App\Livewire\Informasiwr;
use App\Livewire\IuranLocker;
use App\Livewire\Newpresensi;
use App\Livewire\Requesterwr;
use App\Livewire\AddPlacement;
use App\Livewire\DataResigned;
use App\Livewire\DeleteNoscan;
use App\Livewire\Departmentwr;
use App\Livewire\Harikhususwr;
use App\Livewire\Infokaryawan;
use App\Livewire\UserNotFound;
use Google\Service\Forms\Info;
use App\Livewire\AbsensiKosong;
use App\Livewire\Cutirequestwr;
use App\Livewire\DataApplicant;
use App\Livewire\Excelpresensi;
use App\Livewire\Informationwr;
use App\Livewire\Editpresensiwr;
use App\Livewire\UpdateTambahan;
use App\Livewire\UserRegulation;
use App\Http\Controllers\Testaja;
use App\Livewire\Cekkenaikangaji;
use App\Livewire\ChangeFieldData;
use App\Livewire\Changeprofilewr;
use App\Livewire\Karyawanindexwr;
use App\Livewire\Liburnasionalwr;
use App\Livewire\Placementreport;
use App\Livewire\UpdatedPresensi;
use App\Livewire\UserInformation;
use App\Livewire\Changeuserrolewr;
use App\Livewire\Datatidaklengkap;
use App\Livewire\Deletepresensiwr;
use App\Livewire\Hitungthrlebaran;
use App\Livewire\Importkaryawanwr;
use App\Livewire\MovePresensiData;
use App\Livewire\Presensidetailwr;
use App\Livewire\Removepresensiwr;
use App\Livewire\SalaryAdjustment;
use App\Livewire\Timeoffapprovewr;
use App\Livewire\Updatekaryawanwr;
use App\Livewire\ApplicantDiterima;
use App\Livewire\KaryawanReinstate;
use App\Livewire\Karyawansettingwr;
use App\Livewire\Timeoutrequsterwr;
use App\Livewire\Yfpresensiindexwr;
use App\Livewire\DeveloperDashboard;
use App\Livewire\Checkabsensitanpaid;
use App\Livewire\PerbulanKurangBayar;
use App\Livewire\PermohonanPersonnel;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TerControler;
use App\Http\Controllers\PphController;
use App\Livewire\TanpaEmergencyContact;
use App\Livewire\Gantipassworddeveloper;
use App\Http\Controllers\ExcelController;
use App\Http\Controllers\KviewController;
use App\Livewire\Deleteduplicatepresensi;
use App\Http\Controllers\LogoutController;
use App\Http\Controllers\ReportController;
use App\Livewire\Removepresensiduplikatwr;
use App\Http\Controllers\LoggingController;
use App\Livewire\Yfdeletetanggalpresensiwr;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\ApplicantController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\YfpresensiController;
use App\Http\Controllers\SalaryAdjustController;
use App\Http\Controllers\ExcelUploaderController;
use App\Http\Controllers\KaryawanExcelController;

// Middleware
Auth::routes([
    'register' => false, // Register Routes...
    'verify' => false, // Email Verification Routes...
]);

//Guest
Route::middleware(['guest'])->group(function () {
    Route::get('/applicant', Applicant::class);
});

// Route::get('/applicant', function () {
//     return view('applicant.login');
// });
// Route::get('/applicant/registration', function () {
//     return view('applicant.registration');
// });
// Route::post('/applicant/register', [ApplicantController::class, 'register']);

// buka route ini untuk kerja applicant


Route::get('generate', function () {
    \Illuminate\Support\Facades\Artisan::call('storage:link');
    echo 'ok';
});

Route::middleware(['auth'])->group(function () {
    // Route::post('logout', LogoutController::class)->name('logout1');


    Route::middleware(['User'])->group(function () {
        Route::get('locale/{locale}', function ($locale) {
            Session::put('locale', $locale);
            return redirect()->back();
        });
        // DASHBOARD
        // Route::get('/dashboard', [App\Http\Controllers\HomeController::class, 'index'])->name('dashboard');
        Route::get('/', [App\Http\Controllers\HomeController::class, 'index']);
        Route::get('/userinfo', function () {
            return view('user_information');
        });
        // ini yg diblok
        Route::get('/userslipgaji', function () {
            return view('user_slipgaji');
        });

        Route::get('/adddocument', Adddocument::class);
        Route::get('/usermobile', UserMobile::class);
        Route::get('/profile', Profile::class);
        Route::get('/userinformation', UserInformation::class);
        Route::get('/userregulation', UserRegulation::class);
        Route::get('/timeoff', Timeoffwr::class);

        // Junior Admin
        Route::middleware(['JuniorAdmin'])->group(function () {
            Route::get('/dataapplicant', DataApplicant::class);


            Route::middleware(['Admin'])->group(function () {
                Route::get('/download-zip/{folder}', [ApplicantController::class, 'download'])->name('download.zip');

                // Route::get('/download-pdf/{folder}', [ApplicantController::class, 'downloadPdf'])->name('download.pdf');
                // Route::get('/download-pdf/{folder}', [ApplicantController::class, 'downloadPdf'])->name('download.pdf');


                Route::get('/download-merged-pdf/{folder}', [ApplicantController::class, 'mergeFilesToPdf'])->name('download.merged.pdf');

                //Dashboard
                Route::get('/dashboard', [DashboardController::class, 'index']);
                Route::get('/mobile', [DashboardController::class, 'mobile']);
                // KARYAWAN
                Route::get('/karyawancreate', Karyawanwr::class)->name('karyawancreate');
                Route::get('/karyawanupdate/{id}', Updatekaryawanwr::class)->name('karyawanupdate');
                // Route::get('/karyawanupdate', Updatekaryawanwr::class)->name('karyawanupdate');
                Route::get('/karyawanindex', Karyawanindexwr::class)->name('karyawanindex');
                // Route::resource('karyawan', KaryawanController::class);
                // Route::get('/karyawan', [KaryawanController::class, 'index'])->name('karyawan.index');
                // Route::get('/karyawan/hapus/{$id}', [KaryawanController::class,'hapus']);
                Route::delete('/karyawan/{id}/destroy', [KaryawanController::class, 'destroy'])->name('karyawan.destroy');
                Route::post('/cari', [KaryawanController::class, 'cari'])->name('karyawan.cari');
                Route::get('/resettable', [KaryawanController::class, 'resetTable'])->name('karyawan.resettable');
                Route::get('/informasi', Informasiwr::class);
                Route::get('/informationwr', Informationwr::class);
                Route::get('/tambahan', Tambahanwr::class);
                Route::get('/addtambahan', AddTambahan::class);
                Route::get('/updatetambahan/{id}', UpdateTambahan::class);
                Route::get('/iuranlocker', IuranLocker::class);
                Route::get('/karyawanreinstate/{id}', KaryawanReinstate::class)->name('karyawanreinstate');
                Route::get('/kview', [KviewController::class, 'index']);
                Route::get('/infokaryawan', Infokaryawan::class);
                Route::get('/datatidaklengkap', Datatidaklengkap::class);



                // YF PRESENSI
                Route::get('/yfupload', function () {
                    $lock = Lock::find(1);
                    $is_uploadable = !$lock->upload;
                    // dd('$is_uploadable', $is_uploadable);
                    return view('yfpresensi.upload', [
                        'is_uploadable' => $is_uploadable
                    ]);
                });

                Route::get('/yfindex', [YfpresensiController::class, 'index']);
                // route bawah ini untuk percabangan
                // Route::post('/yfstore', [YfpresensiController::class, 'check_store']);
                Route::post('/yfstore', [YfpresensiController::class, 'store']);
                Route::get('/yfdeletepresensi', [YfpresensiController::class, 'deletepresensi']);
                Route::get('/yfpresensiindexwr', Yfpresensiindexwr::class);
                Route::get('/presensidetailwr', Presensidetailwr::class);
                Route::get('/newpresensi', Newpresensi::class);


                // Presensi Summary Excel
                Route::get('/presensisummaryindex', [ReportController::class, 'presensi_summary_index']);
                Route::post('/createexcelpresensisummary', [ReportController::class, 'createExcelPresensiSummary']);


                // USER SETTING

                Route::get('/changeprofilewr', Changeprofilewr::class)->name('changeprofile');
                Route::get('/karyawansettingwr', Karyawansettingwr::class)->name('karyawansettingwr');
                Route::get('/payrollindex', Prindexwr::class);
                Route::get('/salaryadjustment', SalaryAdjustment::class);
                Route::get('/liburnasional', Liburnasionalwr::class);
                Route::get('/tanpaemergensicontact', TanpaEmergencyContact::class);

                Route::get('timeoff-approve', Timeoffapprovewr::class);

                Route::get('/bulk-upload-salary-adjust', function () {
                    $lock = Lock::find(1);
                    $is_uploadable = !$lock->upload;
                    // dd('$is_uploadable', $is_uploadable);
                    return view('upload-form-salary-adjust', [
                        'is_uploadable' => $is_uploadable
                    ]);
                });


                //Khusus Senior Admin
                Route::middleware(['SeniorAdmin'])->group(function () {
                    Route::get('/payroll', Payrollwr::class);
                    Route::get('getexcel', [TerControler::class, 'index']);
                    Route::post('upload/ter', [TerControler::class, 'upload']);
                    Route::get('ter', Terwr::class);
                    Route::get('gajibpjs', Gajibpjs::class);
                    Route::get('permohonan-personnel', PermohonanPersonnel::class);
                    Route::get('/addrequester', Requesterwr::class);
                    Route::get('/addtimeoutrequester', Timeoutrequsterwr::class);
                    Route::get('/headcount', Headcount::class);
                    // Route::get('/movedata', Movedata::class);
                    Route::get('/hitungthr', Hitungthr::class);
                    Route::get('/hitungthrlebaran', Hitungthrlebaran::class);
                    Route::get('/data-log', DataLog::class)->name('datalog');
                    Route::post('/bulk-upload', [SalaryAdjustController::class, 'import']);
                    // Khusus untuk STI Senior admin boleh download
                    Route::get('/template-gaji-form', [ExcelController::class, 'template_gaji']);
                    Route::get('/template-gaji-form-placement', [ExcelController::class, 'template_gaji_placement']);
                    Route::get('/cek-kenaikan-gaji', Cekkenaikangaji::class);






                    // KHUSUS Super Admin
                    Route::middleware(['SuperAdmin'])->group(function () {
                        Route::get('/yfdeletetanggalpresensiwr', Yfdeletetanggalpresensiwr::class);
                        Route::get('/changeuserrolewr', Changeuserrolewr::class);
                        // PAYROLL
                        Route::get('/reportindex', [ReportController::class, 'index']);
                        Route::post('/createexcel', [ReportController::class, 'createExcel']);
                        Route::get('/bankreport', BankReport::class);
                        Route::get('/multiple-excel-form', [ExcelController::class, 'downloadKaryawanZip']);

                        Route::get('/test-view', function () {
                            $karyawans = \App\Models\Karyawan::limit(5)->get();
                            return view('karyawan_excel_form_view', ['karyawans' => $karyawans, 'header_text' => 'ini header text nya']);
                        });
                        Route::get('/test-export', [ExcelController::class, 'testExport']);







                        // Route::get('/karyawan/excel', [KaryawanExcelController::class, 'index']);
                        // Route::post('/karyawan/createexcel', [KaryawanExcelController::class, 'createExcel']);


                        // KHUSUS DEVELOPER
                        Route::middleware(['Developer'])->group(function () {
                            Route::post('/karyawanimport', [KaryawanController::class, 'import'])->name('karyawan.import');
                            Route::get('/importKaryawanExcel', [KaryawanController::class, 'importKaryawanExcel']);
                            Route::get('/karyawanviewimport', function () {
                                return view('karyawan.importview');
                            });
                            Route::get('/erasedatakarayawan', [KaryawanController::class, 'erase'])->name('karyawan.erase');
                            Route::get('/deletenoscan', [YfpresensiController::class, 'deleteNoScan']);
                            Route::get('/deletejamkerja', [YfpresensiController::class, 'deleteJamKerja']);
                            Route::get('/generateusers', [YfpresensiController::class, 'generateUsers']);
                            Route::get('/testto', [YfpresensiController::class, 'testto']);
                            // Route::get('/rubahid', Rubahidwr::class);
                            Route::get('/editpresensi', Editpresensiwr::class);
                            Route::get('/removepresensi', Removepresensiwr::class);
                            Route::get('/removepresensiduplikat', Removepresensiduplikatwr::class);
                            Route::get('/exceluploader', [ExcelUploaderController::class, 'index']);
                            Route::post('/xlstore', [ExcelUploaderController::class, 'store']);
                            Route::get('/UserLog', UserLog::class);

                            Route::get('/MissingId', MissingId::class);
                            Route::get('/UpdatedPresensi', UpdatedPresensi::class);
                            Route::get('/absensikosong', AbsensiKosong::class);
                            Route::get('/dataresigned', DataResigned::class);
                            Route::get('/addpresensi', AddPresensi::class);
                            Route::get('/usernotfound', UserNotFound::class);
                            Route::get('/movepresensidata', MovePresensiData::class);
                            Route::get('/moveback', Moveback::class);
                            Route::get('/addcompany', AddCompany::class);
                            Route::get('/addplacement', AddPlacement::class);
                            Route::get('/yfuploaddelete', function () {
                                return view('yfpresensi.upload-delete');
                            });
                            Route::get('/yfuploadcompare', function () {
                                return view('yfpresensi.upload-compare');
                            });
                            Route::post('/yfdeletebypabrik', [YfpresensiController::class, 'deleteByPabrik']);
                            Route::post('/yfcompare', [YfpresensiController::class, 'compare']);
                            Route::get('/deletenoscan', DeleteNoscan::class);
                            Route::get('/developer-dashboard', DeveloperDashboard::class);
                            Route::get('/jabatan', Jabatanwr::class);
                            Route::get('/changefield', ChangeField::class)->name('changefield');
                            Route::get('applicantditerima', ApplicantDiterima::class)->name('applicantditerima');
                            Route::get('/department', Departmentwr::class);
                            Route::get('/rubahid', Rubahid::class);
                            Route::get('/GantiPasswordDeveloper', Gantipassworddeveloper::class);
                            Route::get('/movedata', Movedata::class);
                            Route::get('/deleteduplicatepresensi', Deleteduplicatepresensi::class);
                            Route::get('/cekabsensitanpaid', Checkabsensitanpaid::class);
                            Route::get('/createuser', CreateUser::class);
                            Route::get('/laporan', Laporan::class);
                            Route::get('/harikhusus', Harikhususwr::class);
                            Route::get('/jobgrade', Jobgradewr::class);
                            Route::get('/cekperbulan', PerbulanKurangBayar::class);










                            // TEST
                            Route::get('/test', Test::class)->name('test');
                            Route::get('/testaja', [Testaja::class, 'index']);
                            Route::get('/testok', function () {
                                return view('test');
                            });
                        });
                    });
                });
            });
        });
        Route::get('permohonan-personnel', PermohonanPersonnel::class)->middleware('Requester');
        Route::get('timeoff-approve', Timeoffapprovewr::class)->middleware('Requester');



        // PRESENSI
        // Route::get('/presensidelete', Deletepresensiwr::class)->name('presensidelete');
        // Route::get('/presensiupload', function() {
        //     return view('content.presensi.import');
        // });
        // Route::post('/presensi-update/{user_id}', [PresensiController::class, 'update_presensi'])->name('presensi.updatedata');
        // Route::delete('/presensi-delete/{user_id}/{date}', [PresensiController::class, 'delete_presensi'])->name('presensi.deletedata');
        // Route::resource('/presensi', PresensiController::class);

        // Route::get('/presensinormalize', [PresensiController::class, 'normalize'])->name('karyawan.normalize');
    });
});
// end of middleware auth
