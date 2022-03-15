<?php

namespace App\Http\Controllers\Api\v1\Prints;

use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\Statistic\StatisticPayment;

use App\Models\Company;
use App\Models\Bank;
use App\Models\Customer;
use App\Models\JobD;
use App\Models\Personal;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    //1.1 phieu chi tam ung new
    public function advance($advance_no)
    {
        //khai bao bien
        $type = '1';
        $title_vn = '';
        $title_en = '現金借支取款單';
        $SUM_PORT_AMT = 0;
        $SUM_LENDER_AMT = 0;
        $title_sum_money = "";
        $INPUT_USER_jobD = "";
        $INPUT_DT_jobD = "";

        $advance = StatisticPayment::advance($advance_no);
        $advance_d = StatisticPayment::advance_D($advance_no);
        $job_d = JobD::getJob($advance->JOB_NO, "JOB_ORDER")->whereIn('jd.THANH_TOAN_MK', [null, 'N']);
        foreach ($advance_d as $i) {
            $SUM_LENDER_AMT += $i->LENDER_AMT;
        }

        foreach ($job_d as $i) {
            $SUM_PORT_AMT += $i->PORT_AMT + $i->INDUSTRY_ZONE_AMT;
            $INPUT_USER_jobD = $i->INPUT_USER;
            $INPUT_DT_jobD = $i->INPUT_DT;
        }
        if ($SUM_PORT_AMT == 0) {
            $title_sum_money = "TỔNG TIỀN / TOTAL AMOUNT";
        }
        if ($advance->LENDER_TYPE == 'T') {
            $title_vn = 'Chi Tạm Ứng';
            $title_en = 'Advance ';
            if ($SUM_LENDER_AMT > $SUM_PORT_AMT) {
                $title_sum_money = "TỔNG TIỀN TRẢ / TOTAL PAYMENT";
            } elseif ($SUM_LENDER_AMT < $SUM_PORT_AMT) {
                $title_sum_money = "TỔNG TIỀN BÙ / TOTAL COMPENSATION";
            }
        } elseif ($advance->LENDER_TYPE == 'C') {
            $title_vn = 'Chi Trực Tiếp';
            $title_en = 'Applicant';
            $title_sum_money = "TỔNG TIỀN / TOTAL AMOUNT";
        } elseif ($advance->LENDER_TYPE == 'U') {
            $title_sum_money = "TỔNG TIỀN / TOTAL AMOUNT";
            $title_vn = 'Tạm Ứng';
            $title_en = 'Advance ';
        }

        if ($advance) {
            return view('print\payment\advance\index', [
                'advance' => $advance,
                'advance_d' => $advance_d,
                'type' => $type,
                'title_vn' => $title_vn,
                'title_en' => $title_en,
                'SUM_PORT_AMT' => $SUM_PORT_AMT,
                'SUM_LENDER_AMT' => $SUM_LENDER_AMT,
                'title_sum_money' => $title_sum_money,
                'INPUT_USER_jobD' => $INPUT_USER_jobD,
                'INPUT_DT_jobD' => $INPUT_DT_jobD,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'PHẢI CHỌN PHIẾU THEO THỨ TỰ NHỎ ĐẾN LỚN'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //1.2 thống kê phiếu bù/trả
    public function postReplenishmentWithdrawalPayment(Request $request)
    {
        $lender = StatisticPayment::postReplenishmentWithdrawalPayment($request->advanceno);

        if ($lender) {
            return view('print\payment\advance\post-replenishment-withdrawal-payment', [
                'lender' => $lender,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn số phiếu!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //2 print- phiếu yêu cầu thanh toán
    public function postDebitNote(Request $request)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $bank_no = ($request->bankno == 'undefined' || $request->bankno == 'null' || $request->bankno == null) ? 'ACB' : $request->bankno;
        $total_amt = 0;
        $debit = StatisticPayment::postDebitNote($request);
        $bank = Bank::des($bank_no);
        $person = Personal::des($request->person);
        $company = Company::des('IHT');
        if ($debit == 'error-job-empty') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào số job để xem dữ liệu!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-person-empty') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào thông tin người liên lạc!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-phone') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Nhập vào số điện thoại người liên lạc!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-debittype') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn debit type!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($debit == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } else {
            switch ($request->type) {
                case 'job':
                    return view('print\payment\debit-note\post-job', [
                        'debit' => $debit,
                        'company' => $company,
                        'person' => $person,
                        'phone' => $request->phone,
                        'total_amt' => $total_amt,
                        'bank' => $bank
                    ]);
                    break;
                case 'customer':
                    $customer = Customer::postCustomer($request->custno);
                    return view('print\payment\debit-note\post-customer', [
                        'debit' => $debit,
                        'company' => $company,
                        'person' => $person,
                        'phone' => $request->phone,
                        'bank' => $bank,
                        'customer' => $customer,
                    ]);
                    break;
                case  'debit_date':

                    return view('print\payment\debit-note\post-debit-date', [
                        'debit' => $debit,
                        'fromdate' => $request->fromdate,
                        'todate' => $request->todate,
                        'debittype' => $request->debittype,
                    ]);
                    break;
                default:
                    break;
            }
        }
    }
    //4. báo biểu lợi nhuận
    public function profit($type, $jobno, $custno, $fromdate, $todate)
    {
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::profit($type, $jobno, $custno, $fromdate, $todate);
        if ($data == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        if ($data) {
            return view('print\payment\profit\index', [
                // 'thanh_toan' => $data['thanh_toan'],
                // 'chi_phi' => $data['chi_phi'],
                'thanh_toan' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng kiểm tra lại!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }
    //5. thống kê số job trong tháng
    public function jobMonthly($type = 'job_start', $custno, $fromdate, $todate)
    {
        $title_vn = '';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::jobMonthly($type, $custno, $fromdate, $todate);
        switch ($type) {
            case 'job_start':
                $title_vn = 'THỐNG KẾ JOB';
                break;
            case 'job_order':
                $title_vn = 'THỐNG KẾ JOB ORDER';
                break;
            case  'debit_note':
                $title_vn = 'THỐNG KẾ DEBIT NOTE';
                break;
            default:
                break;
        }
        if ($data == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($data == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dd($data);
        if ($data) {
            return view('print\payment\job-monthly\index', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'type' => $type,
                'title_vn' => $title_vn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Phải chọn phiếu theo thứ tự từ nhỏ đến lớn!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    //6. thống kê thanh toan cua khach hang
    public function paymentCustomers($type = 'all', $custno, $fromdate, $todate)
    {
        $title_vn = '';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::paymentCustomers($type, $custno, $fromdate, $todate);
        switch ($type) {
            case 'all':
                $title_vn = 'THỐNG KẾ SỐ JOB ĐÃ LÀM DEBIT NOTE';
                break;
            case 'unpaid':
                $title_vn = 'THỐNG KẾ SỐ DEBIT NOTE CHƯA THANH TOÁN';
                break;
            case  'paid':
                $title_vn = 'THỐNG KẾ DEBIT NOTE ĐÃ THANH TOÁN';
                break;
            default:
                break;
        }
        if ($data == 'error-date') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn lại ngày!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        } elseif ($data == 'error-custno') {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Vui lòng chọn Khách Hàng!',
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
        // dd($data);
        if ($data) {
            return view('print\payment\payment-customers\index', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'type' => $type,
                'title_vn' => $title_vn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Phải chọn phiếu theo thứ tự từ nhỏ đến lớn!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    //7. thong ke job order
    public function jobOrder($type, $custno, $person, $fromdate, $todate)
    {
        $title_vn = '';
        date_default_timezone_set('Asia/Ho_Chi_Minh');
        $today = date("Ymd");
        $fromdate = $fromdate != 'null' ? $fromdate : '19000101';
        $todate = $todate != 'null' ? $todate : $today;
        $data = StatisticPayment::jobOrder($type, $custno, $person, $fromdate, $todate);
        switch ($type) {
            case 'truck_fee':
                $title_vn = 'BÁO BIỂU THỐNG KÊ TRUCKING FEE';
                break;
            case 'have_not_debit_note':
                $title_vn = 'BÁO BIỂU THỐNG KÊ JOB ORDER CHƯA MỞ DEBIT NOTE';
                break;
            case  'unpaid_cont':
                $title_vn = 'BÁO BIỂU THỐNG KÊ CƯỢC TÀU (CHƯA DUYỆT)';
                break;
            case  'paid_cont':
                $title_vn = 'BÁO BIỂU THỐNG KÊ CƯỢC TÀU (ĐÃ DUYỆT)';
                break;
            default:
                break;
        }
        if ($data) {
            return view('print\payment\job-order\index', [
                'data' => $data,
                'fromdate' => $fromdate,
                'todate' => $todate,
                'type' => $type,
                'title_vn' => $title_vn,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Phải chọn phiếu theo thứ tự từ nhỏ đến lớn!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    //8. thống kê phiếu thu
    public function receipt($type, $receiptno)
    {
        $title_vn = 'PHIẾU THU';
        $receipt = StatisticPayment::receipt($type, $receiptno);

        // $list=Receipts::list();
        $company = Company::des('IHT');
        if ($receipt) {
            return view('print\payment\receipt\index', [
                'receipt' => $receipt,
                'company' => $company,
                'title_vn' => $title_vn,
                // 'list' => $list,
            ]);
        } else {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Đã có lỗi xảy ra, vui lòng kiểm tra lại!'
                ],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    //test
    public function test(Request $request)
    {
        StatisticPayment::test($request->advanceno);
        return 1;
    }
}
