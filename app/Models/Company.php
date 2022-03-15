<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Company extends Model
{
    public static function list()
    {
        $data = DB::table(config('constants.COMPANY_TABLE'))->get();
        return $data;
    }
    public static function des($id)
    {
        $data = DB::table(config('constants.COMPANY_TABLE'))->where('COMP_NO',$id)->first();
        return $data;
    }
    public static function add($request)
    {
        try {
            DB::table(config('constants.COMPANY_TABLE'))->insert(
                [
                    'COMP_NO' => $request['COMP_NO'],
                    'COMP_NAME' => $request['COMP_NAME'],
                    'COMP_CNAME' => $request['COMP_CNAME'],
                    'COMP_ADDRESS1' => $request['COMP_ADDRESS1'],
                    'COMP_ADDRESS2' => $request['COMP_ADDRESS2'],
                    'COMP_TEL1' => $request['COMP_TEL1'],
                    'COMP_TEL2' => $request['COMP_TEL2'],
                    'COMP_FAX1' => $request['COMP_FAX1'],
                    'COMP_FAX2' => $request['COMP_FAX2'],
                    'COMP_TAX' => $request['COMP_TAX'],
                    'KET_SO' => $request['KET_SO'],
                    'BANKER_NAME' => $request['BANKER_NAME'],
                    'ACCOUNT_NO' => $request['ACCOUNT_NO'],
                    'ACCOUNT_NAME' => $request['ACCOUNT_NAME'],
                ]
            );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function edit($request)
    {
        try {
            DB::table(config('constants.COMPANY_TABLE'))
                ->where('COMP_NO', $request['COMP_NO'])
                ->update(
                    [
                        'COMP_NAME' => $request['COMP_NAME'],
                        'COMP_CNAME' => $request['COMP_CNAME'],
                        'COMP_ADDRESS1' => $request['COMP_ADDRESS1'],
                        'COMP_ADDRESS2' => $request['COMP_ADDRESS2'],
                        'COMP_TEL1' => $request['COMP_TEL1'],
                        'COMP_TEL2' => $request['COMP_TEL2'],
                        'COMP_FAX1' => $request['COMP_FAX1'],
                        'COMP_FAX2' => $request['COMP_FAX2'],
                        'COMP_TAX' => $request['COMP_TAX'],
                        'KET_SO' => $request['KET_SO'],
                        'BANKER_NAME' => $request['BANKER_NAME'],
                        'ACCOUNT_NO' => $request['ACCOUNT_NO'],
                        'ACCOUNT_NAME' => $request['ACCOUNT_NAME'],
                    ]
                );
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }
    public static function remove($request)
    {
        try {
            DB::table(config('constants.COMPANY_TABLE'))
                ->where('COMP_NO', $request['COMP_NO'])
                ->delete();
            return '200';
        } catch (\Exception $e) {
            return $e;
        }
    }

}
