<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class LenderD extends Model
{
    // C:chi truc tiep,T: chi tam ung,U:phieu tam ung
    public static function des($LENDER_NO)
    {
        $data = DB::table('LENDER_D as l')
            ->where('l.LENDER_NO', $LENDER_NO)
            ->where('l.BRANCH_ID', 'IHTVN1')
            ->get();
        return $data;
    }
    public static function generateSerNo($LENDER_NO)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $lender = DB::table('LENDER_D')
            ->where('LENDER_NO', $LENDER_NO)
            ->where('BRANCH_ID', 'IHTVN1')
            ->orderByDesc('LENDER_NO')
            ->first();
        if ($lender) {
            $data = sprintf("%'.02d", $lender->SER_NO + 1);
        } else {
            $count = DB::table('LENDER_D')
                ->where('LENDER_NO', $LENDER_NO)
                ->where('BRANCH_ID', 'IHTVN1')
                ->count();
            $count = (int) $count + 1;
            $data = sprintf("%'.02d", $count);
        }
        return $data;
    }
    public static function add($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            $err = '';
            $serno = LenderD::generateSerNo($request['LENDER_NO']);
            $count =  DB::table('LENDER_D as l')
                ->where('l.LENDER_NO', $request['LENDER_NO'])
                ->where('l.BRANCH_ID', 'IHTVN1')
                ->count();
            if ($count > 5) {
                $err = 'Không được vượt quá 5 lần!';
                return $err;
            } else {
                DB::table('LENDER_D')
                    ->insert(
                        [
                            'LENDER_NO' => ($request['LENDER_NO'] == 'undefined' || $request['LENDER_NO'] == 'null' || $request['LENDER_NO'] == null) ? '' : $request['LENDER_NO'],
                            'SER_NO' => $serno,
                            'LENDER_AMT' => ($request['LENDER_AMT'] == 'undefined' || $request['LENDER_AMT'] == 'null' || $request['LENDER_AMT'] == null) ? '' : $request['LENDER_AMT'],
                            'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                            'INPUT_USER' => ($request['INPUT_USER'] == 'undefined' || $request['INPUT_USER'] == 'null' || $request['INPUT_USER'] == null) ? '' : $request['INPUT_USER'],
                            "INPUT_DT" => date("YmdHis"),
                            "BRANCH_ID" => ($request['BRANCH_ID'] == 'undefined' || $request['BRANCH_ID'] == 'null' || $request['BRANCH_ID'] == null) ?  'IHTVN1' : $request['BRANCH_ID'],
                        ]
                    );
                $data = LenderD::des($request['LENDER_NO']);
                return $data;
            }
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function edit($request)
    {
        try {
            date_default_timezone_set('Asia/Ho_Chi_Minh');
            DB::table('LENDER_D')
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->where('SER_NO', $request['SER_NO'])
                ->update(
                    [
                        'LENDER_AMT' => ($request['LENDER_AMT'] == 'undefined' || $request['LENDER_AMT'] == 'null' || $request['LENDER_AMT'] == null) ? '' : $request['LENDER_AMT'],
                        'NOTE' => ($request['NOTE'] == 'undefined' || $request['NOTE'] == 'null' || $request['NOTE'] == null) ? '' : $request['NOTE'],
                        'MODIFY_USER' =>  $request['MODIFY_USER'] == 'undefined' ? '' : $request['MODIFY_USER'],
                        'MODIFY_DT' =>  date("YmdHis"),
                    ]
                );
            $data = LenderD::des($request['LENDER_NO']);
            return $data;
        } catch (\Exception $e) {
            return '201';
        }
    }
    public static function remove($request)
    {
        try {
            DB::table('LENDER_D')
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->where('SER_NO', $request['SER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function removeAll($request)
    {
        try {
            DB::table('LENDER_D')
                ->where('LENDER_NO', $request['LENDER_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
}
