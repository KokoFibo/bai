<?php

use Carbon\Carbon;
use App\Models\Ter;
use App\Models\Lock;
use App\Models\User;
use App\Models\Company;
use App\Models\Jabatan;
use App\Models\Payroll;
use App\Models\Timeoff;
use App\Models\Karyawan;
use App\Models\Tambahan;
use App\Models\Placement;
use App\Models\Requester;
use App\Models\Department;
use Illuminate\Support\Str;
use App\Models\Applicantdata;
use App\Models\Applicantfile;
use App\Models\Dashboarddata;
use App\Models\Harikhusus;
use App\Models\Liburnasional;
use App\Models\Yfrekappresensi;
use App\Models\Timeoffrequester;
use App\Models\Personnelrequestform;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

function khusus_checkFirstInLate($check_in, $shift, $tgl, $placement_id)

{
    // rubah angka ini utk bulan puasa
    $test = $placement_id;
    if (is_puasa($tgl)) {
        $jam_mulai_pagi = '07:33';
        $strtime_pagi = '07:33:00';
        $jam_mulai_sore = '19:33';
        $strtime_sore = '19:33:00';
        $jam_mulai_sore_sabtu = '16:03';
        $strtime_sore_sabtu = '16:03:00';
    } else {
        $jam_mulai_pagi = '08:03';
        $strtime_pagi = '08:03:00';
        $jam_mulai_sore = '20:03';
        $strtime_sore = '20:03:00';
        $jam_mulai_sore_sabtu = '17:03';
        $strtime_sore_sabtu = '17:03:00';
    }

    $perJam = 60;
    $late = null;

    if ($check_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($check_in)->betweenIncluded('05:30', $jam_mulai_pagi)) {
                $late = null;
            } else {
                $t1 = strtotime($strtime_pagi);
                $t2 = strtotime($check_in);
                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $perJam);
                if ($late <= 5 && $late > 3.5) {
                    if (khusus_is_friday($tgl)) {
                        $late = 3.5;
                    } else {
                        $late = 4;
                    }
                } elseif ($late > 5) {
                    if (khusus_is_friday($tgl)) {
                        $late = $late - 1.5;
                    } else {
                        $late = $late - 1;
                    }
                }
            }
        } else {
            if (khusus_is_saturday($tgl)) {
                if (Carbon::parse($check_in)->betweenIncluded('14:00', $jam_mulai_sore_sabtu)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_sore_sabtu);
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            } else {
                if (Carbon::parse($check_in)->betweenIncluded('16:00', $jam_mulai_sore)) {
                    $late = null;
                } else {
                    $t1 = strtotime($strtime_sore);
                    $t2 = strtotime($check_in);

                    $diff = gmdate('H:i:s', $t2 - $t1);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                }
            }
        }
    }
    return $late;
}

function khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id)
{
    if (is_puasa($tgl)) {
        $jam_secondOut_pagi = '16:29';
        $strtime_secondOut_pagi = '16:30:00';
        $jam_secondOut_pagi_sabtu = '14:29';
        $strtime_secondOut_pagi_sabtu = '15:00:00';

        $jam_secondOut_sore = '04:59';
        $strtime_secondOut_sore = '05:00:00';
        $jam_secondOut_sore_sabtu = '22:59';
        $strtime_secondOut_sore_sabtu = '23:00:00';
    } else {
        $jam_secondOut_pagi = '16:59';
        $strtime_secondOut_pagi = '17:00:00';
        $jam_secondOut_pagi_sabtu = '14:59';
        $strtime_secondOut_pagi_sabtu = '15:00:00';

        $jam_secondOut_sore = '04:59';
        $strtime_secondOut_sore = '05:00:00';
        $jam_secondOut_sore_sabtu = '23:59';
        $strtime_secondOut_sore_sabtu = '23:59:00';
    }
    $perJam = 60;
    $late = null;

    if ($second_out != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (khusus_is_saturday($tgl)) {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', $jam_secondOut_pagi_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_pagi_sabtu);
                    $t2 = strtotime($second_out);
                    //kkk
                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('12:00', $jam_secondOut_pagi)) {
                    $t1 = strtotime($strtime_secondOut_pagi);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else if (Carbon::parse($second_out)->betweenIncluded('09:00', '11:59')) {
                    $t1 = strtotime('12:00:00');
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam + 4);
                } else {
                    $late = null;
                }
            }
        } else {
            if (khusus_is_saturday($tgl)) {
                // if (Carbon::parse($second_out)->betweenIncluded('19:00', '23:59') ) {
                if (Carbon::parse($second_out)->betweenIncluded('19:00', $jam_secondOut_sore_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_sore_sabtu);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);
                } else {
                    $late = null;
                }
            } else {
                if (Carbon::parse($second_out)->betweenIncluded('00:00', $jam_secondOut_sore)) {
                    $t1 = strtotime($strtime_secondOut_sore);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam);

                    // ook
                } elseif (Carbon::parse($second_out)->betweenIncluded('19:00', $jam_secondOut_sore_sabtu)) {
                    $t1 = strtotime($strtime_secondOut_pagi_sabtu);
                    $t2 = strtotime($second_out);

                    $diff = gmdate('H:i:s', $t1 - $t2);
                    $late = ceil(hoursToMinutes($diff) / $perJam) + 4;
                } else {
                    $late = null;
                }
            }
        }
    }
    return $late;
}

function khusus_checkOvertimeInLate($overtime_in, $shift, $tgl)
{
    $persetengahJam = 30;
    $late = null;
    if ($overtime_in != null) {
        if ($shift == 'Pagi') {
            // Shift Pagi
            if (Carbon::parse($overtime_in)->betweenIncluded('12:00', '18:33')) {
                $late = null;
            } else {
                $t1 = strtotime('18:33:00');
                $t2 = strtotime($overtime_in);

                $diff = gmdate('H:i:s', $t2 - $t1);
                $late = ceil(hoursToMinutes($diff) / $persetengahJam);
            }
        }
    }
    return $late;
}

function khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id)
{
    //ok
    $perJam = 60;
    $late = null;

    if (is_puasa($tgl)) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else {

                    if (Carbon::parse($first_out)->betweenIncluded('01:00', '02:30')) {
                        $t1 = strtotime('02:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            if ($first_out != null) {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (Carbon::parse($first_out)->betweenIncluded('08:00', '11:29')) {
                        $t1 = strtotime('11:30:00');
                        $t2 = strtotime($first_out);

                        $diff = gmdate('H:i:s', $t1 - $t2);
                        $late = ceil(hoursToMinutes($diff) / $perJam);
                    } else {
                        $late = null;
                    }
                } else { // shift malam
                    if (khusus_is_saturday($tgl)) {
                        if (Carbon::parse($first_out)->betweenIncluded('17:01', '20:29')) {
                            $t1 = strtotime('20:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    } else {
                        if (Carbon::parse($first_out)->betweenIncluded('20:00', '23:29')) {
                            $t1 = strtotime('23:30:00');
                            $t2 = strtotime($first_out);

                            $diff = gmdate('H:i:s', $t1 - $t2);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        } else {
                            $late = null;
                        }
                    }
                }
            }
        }
    }
    return $late;
}

function khusus_checkSecondInLate($second_in, $shift, $firstOut, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    $late = null;


    if (is_puasa($tgl)) {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (khusus_is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('13:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                        // if ($shift == 'Pagi') {
                        //     if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('13:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // } else {

                        //     if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                        //         $late = null;
                        //     } else {
                        //         $t1 = strtotime('01:03:00');
                        //         $t2 = strtotime($second_in);

                        //         $diff = gmdate('H:i:s', $t2 - $t1);
                        //         $late = ceil(hoursToMinutes($diff) / $perJam);
                        //     }
                        // }
                    }
                } else { // shift malam
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('01:00', '02:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('03:00', '03:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if ($groupIstirahat == 1) {
                            if (Carbon::parse($second_in)->betweenIncluded('01:00', '03:29')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('03:33:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } elseif ($groupIstirahat == 2) {
                            if (Carbon::parse($second_in)->betweenIncluded('03:00', '03:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('04:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            $late = null;
                        }
                    } else {
                        if (Carbon::parse($second_in)->betweenIncluded('02:30', '04:03')) {
                            $late = null;
                        } else {
                            $t1 = strtotime('04:03:00');
                            $t2 = strtotime($second_in);

                            $diff = gmdate('H:i:s', $t2 - $t1);
                            $late = ceil(hoursToMinutes($diff) / $perJam);
                        }
                    }
                }
            }
        }
    } else {
        if (is_jabatan_khusus($jabatan) == 1) {
            $late = null;
        } else {
            // jangan remark ini kalau ada error
            // $groupIstirahat;

            if ($second_in != null) {
                if ($shift == 'Pagi') {
                    if ($firstOut != null) {
                        if (Carbon::parse($firstOut)->betweenIncluded('08:00', '11:59')) {
                            $groupIstirahat = 1;
                        } elseif (Carbon::parse($firstOut)->betweenIncluded('12:00', '12:59')) {
                            $groupIstirahat = 2;
                        } else {
                            $groupIstirahat = 0;
                        }

                        // Shift Pagi ggg
                        if (khusus_is_friday($tgl)) {
                            if (Carbon::parse($second_in)->betweenIncluded('11:30', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);
                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('08:00', '12:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('12:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('11:00', '13:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('13:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        }
                    } else {
                        //jika first out null
                        if ($shift == 'Pagi') {
                            if (Carbon::parse($second_in)->betweenIncluded('08:00', '13:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('13:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        } else {
                            if (khusus_is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                } else { //shift Malam
                    if (khusus_is_saturday($tgl)) { //ini ya 
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('17:00', '20:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('21:00', '22:00')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }
                            if ($groupIstirahat == 1) {
                                if (Carbon::parse($second_in)->betweenIncluded('17:00', '21:33')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('21:33:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } elseif ($groupIstirahat == 2) {
                                if (Carbon::parse($second_in)->betweenIncluded('21:00', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                $late = null;
                            }
                        } else {
                            //jika first out null


                            if (Carbon::parse($second_in)->betweenIncluded('20:30', '22:03')) {
                                $late = null;
                            } else {
                                $t1 = strtotime('22:03:00');
                                $t2 = strtotime($second_in);

                                $diff = gmdate('H:i:s', $t2 - $t1);
                                $late = ceil(hoursToMinutes($diff) / $perJam);
                            }
                        }
                    } else {
                        if ($firstOut != null) {
                            if (Carbon::parse($firstOut)->betweenIncluded('20:00', '23:59')) {
                                $groupIstirahat = 1;
                            } elseif (Carbon::parse($firstOut)->betweenIncluded('00:00', '00:59')) {
                                $groupIstirahat = 2;
                            } else {
                                $groupIstirahat = 0;
                            }

                            // Shift Pagi ggg
                            if (khusus_is_friday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);
                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if ($groupIstirahat == 1) {
                                    if (Carbon::parse($second_in)->betweenIncluded('20:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '00:33')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('00:33:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } elseif ($groupIstirahat == 2) {
                                    if (Carbon::parse($second_in)->betweenIncluded('23:00', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                        $late = null;
                                    } else {
                                        $t1 = strtotime('01:03:00');
                                        $t2 = strtotime($second_in);

                                        $diff = gmdate('H:i:s', $t2 - $t1);
                                        $late = ceil(hoursToMinutes($diff) / $perJam);
                                    }
                                } else {
                                    $late = null;
                                }
                            }
                        } else {
                            //jika first out null

                            if (khusus_is_saturday($tgl)) {
                                if (Carbon::parse($second_in)->betweenIncluded('20:01', '22:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('22:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            } else {
                                if (Carbon::parse($second_in)->betweenIncluded('23:30', '23:59') || Carbon::parse($second_in)->betweenIncluded('00:00', '01:03')) {
                                    $late = null;
                                } else {
                                    $t1 = strtotime('01:03:00');
                                    $t2 = strtotime($second_in);

                                    $diff = gmdate('H:i:s', $t2 - $t1);
                                    $late = ceil(hoursToMinutes($diff) / $perJam);
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    return $late;
}


function khusus_noScan($first_in, $first_out, $second_in, $second_out, $overtime_in, $overtime_out)
{
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null && (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null))) {
        return 'No Scan';
    }
    if ($first_in != null && $second_out != null && $first_out == null && $second_in == null) {
        return null;
    }
    if (($first_in == null) & ($first_out != null) || ($first_in != null) & ($first_out == null)) {
        return 'No Scan';
    }
    if (($second_in == null) & ($second_out != null) || ($second_in != null) & ($second_out == null)) {
        return 'No Scan';
    }
    // if (( $second_in == null ) && ( $second_out == null )) {
    //     return 'No Scan';
    // }
    // if ( ( $first_in == null ) && ( $first_out == null ) ) {
    //     return 'No Scan';
    // }

    if (($overtime_in == null) & ($overtime_out != null) || ($overtime_in != null) & ($overtime_out == null)) {
        return 'No Scan';
    }
}

function khusus_hitungLembur($overtime_in, $overtime_out)
{
    if ($overtime_in != '' || $overtime_out != '') {
        $t1 = strtotime(pembulatanJamOvertimeIn($overtime_in));
        $t2 = strtotime(pembulatanJamOvertimeOut($overtime_out));

        $diff = gmdate('H:i:s', $t2 - $t1);
        $diff = explode(':', $diff);
        $jam = (int) $diff[0];
        $menit = (int) $diff[1];
        // if ( $menit<30 ) {
        //     $menit = 0;
        // } else {
        //     $menit = 30;
        // }
        $totalMenit = $jam * 60 + $menit;

        return $totalMenit;
    } else {
        return 0;
    }
}

function khusus_hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $tgl, $jabatan, $placement_id)
{
    $perJam = 60;
    if (is_puasa($tgl)) {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (khusus_is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (khusus_is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (khusus_is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    } else {
        if ($late == null) {
            if ($shift == 'Pagi') {
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } elseif (khusus_is_friday($tgl)) {
                    $jam_kerja = 7.5;
                } else {
                    $jam_kerja = 8;
                }
            } else {
                $jam_kerja = 8;
                if (khusus_is_saturday($tgl)) {
                    $jam_kerja = 6;
                } else {
                    $jam_kerja = 8;
                }
            }
        } else {
            // check late kkk
            $total_late = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id);
            //    dd($first_in, $first_out, $second_in, $second_out);
            //jok
            if ($second_in === null && $second_out === null && ($first_in === null && $first_out === null)) {
                $jam_kerja = 0;
            } elseif (($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)) {
                if (khusus_is_saturday($tgl)) {
                    if ($first_in === null && $first_out === null) {
                        $jam_kerja = 2 - $total_late;
                        // $jam_kerja = 2 ;
                    } else {
                        $jam_kerja = 4 - $total_late;
                        // $jam_kerja = 4 ;
                    }
                } else {
                    $jam_kerja = 4 - $total_late;
                    // $jam_kerja = 4 ;
                }
            } else {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } elseif (khusus_is_friday($tgl)) {
                        $jam_kerja = 7.5 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        $jam_kerja = 6 - $total_late;
                    } else {
                        $jam_kerja = 8 - $total_late;
                    }
                }
            }
        }
    }



    // lolo
    if (khusus_is_sunday($tgl)) {

        // $t1 = strtotime($first_in);
        // $t2 = strtotime($second_out);
        // $t1 = strtotime(pembulatanJamOvertimeIn($first_in));
        // $t2 = strtotime(pembulatanJamOvertimeOut($second_out));



        // $diff = gmdate('H:i:s', $t2 - $t1);

        // $diff = explode(':', $diff);
        // $jam = (int) $diff[0];
        // $menit = (int) $diff[1];

        // if ($menit >= 45) {
        //     $jam = $jam + 1;
        // } elseif ($menit < 45 && $menit > 15) {
        //     $jam = $jam + 0.5;
        // } else {
        //     $jam;
        // }
        // $jam_kerja = $jam * 2;
        $jam_kerja *= 2;
    }
    if ($jabatan == 17 && khusus_is_sunday($tgl) == false) {
        $jam_kerja = 12;
        // $jam_kerja = $jam_kerja - $total_late;
    }

    return $jam_kerja;
}

function khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $tgl, $jabatan, $placement_id)
{
    $late_1 = 0;
    $late_2 = 0;
    $late_3 = 0;
    $late_4 = 0;
    $late1 = khusus_checkFirstInLate($first_in, $shift, $tgl, $placement_id);
    $late2 = khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan, $placement_id);
    $late3 = khusus_checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan, $placement_id);
    $late4 = khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, $placement_id);



    return $late1 + $late2 + $late3 + $late4;
}

function khusus_saveDetail($user_id, $first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, $no_scan, $placement_id, $overtime_in, $overtime_out)
{
    $tambahan_shift_malam = 0;
    if ($no_scan === null) {
        $tgl = tgl_doang($date);
        $jam_kerja = khusus_hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, get_placement($user_id));
        $terlambat = khusus_late_check_jam_kerja_only($first_in, $first_out, $second_in, $second_out, $shift, $date, $jabatan_id, get_placement($user_id));

        $langsungLembur = khusus_langsungLembur($second_out, $date, $shift, $jabatan_id, $placement_id);
        if (khusus_is_sunday($date)) {
            $jam_lembur = khusus_hitungLembur($overtime_in, $overtime_out) / 60 * 2
                + $langsungLembur * 2;
        } else {
            $jam_lembur = khusus_hitungLembur($overtime_in, $overtime_out) / 60 + $langsungLembur;
        }

        if ($shift == 'Malam') {
            if (khusus_is_saturday($date)) {
                if ($jam_kerja >= 6) {
                    // $jam_lembur = $jam_lembur + 1;
                    $tambahan_shift_malam = 1;
                }
            } else if (khusus_is_sunday($date)) {
                if ($jam_kerja >= 16) {
                    // $jam_lembur = $jam_lembur + 2;
                    $tambahan_shift_malam = 1;
                }
            } else {
                if ($jam_kerja >= 8) {
                    // $jam_lembur = $jam_lembur + 1;
                    $tambahan_shift_malam = 1;
                }
            }
        }
        // 22 driver
        if (($jam_lembur >= 9) && (khusus_is_sunday($date) == false) && ($jabatan_id != 22)) {
            $jam_lembur = 0;
        }
        // yig = 12, ysm = 13
        // if ($placement_id == 12 || $placement_id == 13 || $jabatan_id == 17) {
        if ($jabatan_id == 17) {
            if (khusus_is_friday($date)) {
                $jam_kerja = 7.5;
            } elseif (khusus_is_saturday($date)) {
                $jam_kerja = 6;
            } else {
                $jam_kerja = 8;
            }
        }
        if ($jabatan_id == 17 && khusus_is_sunday($date)) {
            $jam_kerja = hitung_jam_kerja($first_in, $first_out, $second_in, $second_out, $late, $shift, $date, $jabatan_id, get_placement($user_id));
        }
        if ($jabatan_id == 17 && khusus_is_saturday($date)) {
            // $jam_lembur = 0;
        }
        // 23 translator
        if ($jabatan_id != 23) {
            if (
                khusus_is_libur_nasional($date) &&  !khusus_is_sunday($date)
                && $jabatan_id != 23

            ) {
                $jam_kerja *= 2;
                $jam_lembur *= 2;
            }
        } else {
            if (khusus_is_sunday($date)) {
                $jam_kerja /= 2;
                $jam_lembur /= 2;
            }
        }

        // $this->dataArr->push([
        //     'tgl' => $tgl,
        //     'jam_kerja' => $jam_kerja,
        //     'terlambat' => $terlambat,
        //     'jam_lembur' => $jam_lembur,
        //     'tambahan_shift_malam' => $tambahan_shift_malam,
        // ]);

        return [
            'tgl' => $tgl,
            'jam_kerja' => $jam_kerja,
            'terlambat' => $terlambat,
            'jam_lembur' => $jam_lembur,
            'tambahan_shift_malam' => $tambahan_shift_malam
        ];
    }
}

function khusus_langsungLembur($second_out, $tgl, $shift, $jabatan, $placement_id)
{

    // betulin
    if ($second_out != null) {
        $t2 = strtotime($second_out);
        if (!khusus_is_saturday($tgl) && $shift == 'Pagi' && $t2 < strtotime('04:00:00')) {
            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
            $diff = $diff + 7;
            return $diff;
        }
    }
    if (is_puasa($tgl)) {
        if ($second_out != null) {
            $lembur = 0;
            $t2 = strtotime($second_out);
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:00:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:00:00') && $t2 > strtotime('11:30:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:29:00') && $t2 >= strtotime('20:00:00')) {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('04:30:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:00:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('07:30:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (khusus_is_saturday($tgl)) {
                        if ($t2 < strtotime('15:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('14:30:00')) / 60;
                    } else {
                        if ($t2 < strtotime('17:00:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if (khusus_is_saturday($tgl)) {
                        // if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                        //     return $lembur = 0;
                        // }
                        // if ( $t2 <= strtotime('23:29:00'))  || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        $t23_29 = strtotime('23:29:00');
                        $t23_30 = strtotime('23:30:00');
                        $t23_00 = Carbon::parse('23:00:00');
                        $t00_00 = strtotime('00:00:00');
                        $t05_00 = strtotime('05:00:00');

                        $t2 = strtotime($second_out);
                        $t20_00 = strtotime('20:00:00');
                        $t23_29 = strtotime('23:29:00');

                        // Jika $t2 berada di antara 22:00:00 dan 23:29:00, lembur = 0
                        if ($t2 >= $t20_00 && $t2 <= $t23_29) {
                            return $lembur = 0;
                        }


                        // if ($t2 >= strtotime('23:30:00') || ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00)) {
                        //     $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        // }
                        if ($t2 >= strtotime('00:00:00') && $t2 <= $t05_00) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->addDay()->diffInMinutes($t23_00) / 60;
                        }
                        if ($t2 >= strtotime('23:30:00')) {
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes($t23_00) / 60;
                        }

                        // Default jika tidak masuk kondisi apapun
                    } else {
                        // if ($t2 < strtotime('05:00:00') && $t2 <= strtotime('23:29:00')) {
                        //     return $lembur = 0;
                        // }
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }
            if (isset($diff) && $diff !== null) return $diff;
            // return $diff;
        } else {
            return $lembur = 0;
        }
    } else {
        if ($second_out != null) {

            $lembur = 0;

            $t2 = strtotime($second_out);
            // ini puasa kah
            if ($jabatan == 17) {
                if ($shift == 'Pagi') {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam
                        if ($t2 < strtotime('17:30:00')) {
                            // dd($t2, 'bukan sabtu');

                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00'))/60;
                            // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('16:30:00')) / 60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('20:30:00') && $t2 > strtotime('12:00:00')) {
                            // dd($t2, 'bukan sabtu');
                            return $lembur = 0;
                        } else {
                            if ($t2 <= strtotime('23:59:00') && $t2 >= strtotime('20:30:00')) {

                                // mk

                                // return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('19:30:00')) / 60;
                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('20:00:00')) / 60;
                            } else {

                                return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60 + 3.5;
                            }
                        }
                        // kl
                    }
                } else {
                    if (khusus_is_saturday($tgl)) {
                        // rubah disini utk perubahan jam lembur satpam malam
                        if ($t2 < strtotime('05:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('08:30:00')) {
                            return $lembur = 0;
                        } else {
                            // $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00'))/60;
                            return Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('08:00:00')) / 60;
                        }
                    }
                }
            } else {
                if ($shift == 'Pagi') {
                    // Shift Pagi
                    if (khusus_is_saturday($tgl)) {
                        // if ($tgl == '2025-04-18') {
                        if (khusus_is_friday($tgl)) {
                            if ($t2 < strtotime('16:00:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:30:00')) / 60;
                        } else {
                            if ($t2 < strtotime('15:30:00')) {
                                return $lembur = 0;
                            }
                            $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('15:00:00')) / 60;
                        }
                    } else {
                        if ($t2 < strtotime('17:30:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('17:00:00')) / 60;
                    }
                } else {
                    //Shift Malam
                    if (khusus_is_saturday($tgl)) {
                        if ($t2 < (strtotime('00:30:00') && $t2 <= strtotime('23:59:00')) || ($t2 > strtotime('15:00:00') && $t2 < strtotime('23:59:00'))) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('00:00:00')) / 60;
                    } else {
                        if ($t2 < strtotime('05:30:00') && $t2 <= strtotime('23:59:00')) {
                            return $lembur = 0;
                        }
                        $diff = Carbon::parse(pembulatanJamOvertimeOut($second_out))->diffInMinutes(Carbon::parse('05:00:00')) / 60;
                    }
                }
            }

            return $diff;
        } else {
            return $lembur = 0;
        }
    }
}

function khusus_late_check_detail($first_in, $first_out, $second_in, $second_out, $overtime_in, $shift, $tgl, $id)
{
    // if ($tgl === '2025-04-18') {
    if (is_friday($tgl)) {
        return $late = 0;
    }
    try {
        $data_jabatan = Karyawan::where('id_karyawan', $id)->first();
        $jabatan = $data_jabatan->jabatan_id;
        $jabatan_khusus = is_jabatan_khusus($jabatan);
    } catch (\Exception $e) {
        dd('ID karyawan tidak ada dalam database = ', $id);
        return $e->getMessage();
    }

    $late5 = null;

    // if(($second_in === null && $second_out === null) || ($first_in === null && $first_out === null)){
    if (($second_in === '' && $second_out === '') || ($first_in === '' && $first_out === '')) {
        // $data->late = 1;
        // dd($data->late, $data->user_id);
        return $late = 1;
    }

    if (khusus_checkFirstInLate($first_in, $shift, $tgl, get_placement($id))) {
        //  return $late = $late + 1;
        return $late = 1;
        // $late1 = 1;
    }
    if (khusus_checkFirstOutLate($first_out, $shift, $tgl, $jabatan_khusus, get_placement($id))) {
        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
    }
    if (khusus_checkSecondOutLate($second_out, $shift, $tgl, $jabatan, get_placement($id))) {
        //  return $late = $late + 1;
        // if ($jabatan_khusus != '1') {
        //     return $late = 1;
        // }
        return $late = 1;

        // return $late = 1;
        // $late3 = 1;
    }


    if (khusus_checkSecondInLate($second_in, $shift, $first_out, $tgl, $jabatan_khusus, get_placement($id))) {
        // return $late = $late + 1 ;

        // if ($jabatan_khusus == '') {
        //     return $late = 1;
        // }
        return $late = 1;
        // $late5 = 1;
    }

    if ($second_in == null && $second_out == null) {
        return $late = 1;
    }
    if ($first_in == null && $first_out == null) {
        return $late = 1;
    }
    // $late = $late1 + $late2 + $late3+ $late4 + $late5 ;
    // return $late;
}

function khusus_is_friday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_friday === 0) {
        return false;
    }

    if ($tgl) {
        return Carbon::parse($tgl)->isFriday();
    }
}

function khusus_is_saturday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_saturday === 1) {
        return true;
    } else {
        return false;
    }
}

function khusus_is_libur_nasional($tanggal)
{
    $tgl_khusus = Harikhusus::where('date', $tanggal)->first();
    if ($tgl_khusus->is_hari_libur_nasional === 0) {
        return false;
    }

    $data = Liburnasional::where('tanggal_mulai_hari_libur', $tanggal)->first();
    if ($data != null) return true;
    return false;
}

function khusus_is_sunday($tgl)
{
    $tgl_khusus = Harikhusus::where('date', $tgl)->first();
    if ($tgl_khusus->is_sunday === 0) {
        return false;
    }
    if ($tgl) {
        return Carbon::parse($tgl)->isSunday();
    }
}
