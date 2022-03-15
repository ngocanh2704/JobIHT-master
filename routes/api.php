<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

//Access-Control-Allow-Origin: *
// header('Access-Control-Allow-Methods:  POST, GET, OPTIONS, PUT, DELETE');
// header('Access-Control-Allow-Headers:  Content-Type, X-Auth-Token, Origin, Authorization');


//web quản lý chương trình
Route::namespace('Api\v1')->group(function () {
    Route::group(['prefix' => 'v1'], function () {
        //user
        Route::group(['middleware' =>  'cors', 'prefix' => 'user'], function () {
            Route::post('login', 'UserController@login');
        });
        //menu
        Route::group(['prefix' => 'menu'], function () {
            Route::get('list-header', 'MenuController@listMenuGroup');
            Route::get('list-sidebar', 'MenuController@listMenu');
        });
        //I.data basic (du lieu co ban)
        Route::group(['prefix' => 'data-basic'], function () {
            Route::group(['prefix' => 'company'], function () {
                Route::get('/', 'CompanyController@list');
                Route::post('add', 'CompanyController@add');
                Route::post('edit', 'CompanyController@edit');
                Route::post('remove', 'CompanyController@remove');
            });
            Route::group(['prefix' => 'bank'], function () {
                Route::get('/', 'BankController@list');
                Route::post('add', 'BankController@add');
                Route::post('edit', 'BankController@edit');
                Route::post('remove', 'BankController@remove');
            });
            Route::group(['prefix' => 'customer'], function () {
                Route::get('/type={type}', 'CustomerController@list');
                Route::get('/type={type}/page={page}', 'CustomerController@listPage');
                Route::get('list-take/type={type}&take={take}', 'CustomerController@listTake');
                Route::get('des/id={id}/type={type}', 'CustomerController@des');
                Route::get('search/group={group}&type={type}&value={value}&page={page}', 'CustomerController@search');
                Route::post('add', 'CustomerController@add');
                Route::post('edit', 'CustomerController@edit');
                Route::post('remove', 'CustomerController@remove');
            });
            //staff-customs(nhan vien hai quan)
            Route::group(['prefix' => 'staff-customs'], function () {
                Route::get('/', 'StaffCustomerController@list');
                Route::get('page={page}', 'StaffCustomerController@listPage');
                Route::get('des/{id}', 'StaffCustomerController@des');
                Route::post('add', 'StaffCustomerController@add');
                Route::post('edit', 'StaffCustomerController@edit');
                Route::post('remove', 'StaffCustomerController@remove');
            });
            Route::group(['prefix' => 'type-cost'], function () {
                Route::get('/', 'TypeCostController@list');
                Route::get('page={page}', 'TypeCostController@listPage');
                Route::get('des/{id}', 'TypeCostController@des');
                Route::post('add', 'TypeCostController@add');
                Route::post('edit', 'TypeCostController@edit');
                Route::post('remove', 'TypeCostController@remove');
            });
            Route::group(['prefix' => 'branch'], function () {
                Route::get('/', 'BranchController@list');
            });
        });
        //II.system manager
        Route::group(['prefix' => 'system'], function () {
            Route::post('login', 'UserController@login');
            //info
            Route::group(['prefix' => 'user'], function () {
                Route::get('/', 'UserController@list');
                Route::get('des/{id}', 'UserController@des');
                Route::post('add', 'UserController@add');
                Route::post('edit', 'UserController@edit');
                Route::post('remove', 'UserController@remove');
            });
            //phan quyen
            Route::group(['prefix' => 'permission'], function () {
                Route::get('/', 'PermissionController@list');
                Route::get('des/{USER_NO?}', 'PermissionController@des');
                Route::post('edit', 'PermissionController@edit');
            });
        });
        //III.file manager(quan ly ho so)
        Route::group(['prefix' => 'file'], function () {
            //phieu theo doi
            Route::group(['prefix' => 'job-start'], function () {

                Route::get('page={page}', 'JobStartController@listPage');
                Route::get('take={take}', 'JobStartController@listTake');
                Route::get('', 'JobStartController@list');
                Route::get('search/type={type}&value={value}&page={page}', 'JobStartController@search');
                Route::get('not-created', 'JobStartController@listNotCreatedOrder');
                Route::get('des/{id}', 'JobStartController@des');
                Route::post('add', 'JobStartController@add');
                Route::post('edit', 'JobStartController@edit');
                Route::post('remove', 'JobStartController@remove');
                Route::get('filter-job', 'JobStartController@filterJob');//filter job with cust_no & date(print/export Job start)
            });
            Route::group(['prefix' => 'job-order'], function () {
                Route::get('/', 'JobOrderController@list');
                Route::get('take={take}', 'JobOrderController@listTake');
                Route::get('page={page}', 'JobOrderController@listPage');
                Route::get('search/type={type}&value={value}&page={page}', 'JobOrderController@searchPage');
                Route::get('search/type={type}&value={value}', 'JobOrderController@search');
                Route::get('des/job={id}&type={TYPE}', 'JobOrderController@des');
                Route::post('add', 'JobOrderController@add');
                Route::post('add-d', 'JobOrderController@addJobD');
                Route::post('edit', 'JobOrderController@edit');
                Route::post('edit-d', 'JobOrderController@editJobD');
                Route::post('remove', 'JobOrderController@remove');
                Route::post('remove-d', 'JobOrderController@removeJobD');
                Route::get('filter-job', 'JobOrderController@filterJob');//filter job with cust_no & date(print/export Job order)
            });
            Route::group(['prefix' => 'approved'], function () {
                Route::get('list-pending/page={page}', 'JobOrderController@listPending');
                Route::get('list-approved/page={page}', 'JobOrderController@listApproved');
                Route::post('/', 'JobOrderController@approved');
            });
            //5. duyet cuoc cont
            Route::group(['prefix' => 'approved-cont'], function () {
                Route::get('type={type}&list={list}&year={year}', 'JobOrderController@listApprovedCont');
                Route::post('/', 'JobOrderController@approvedCont');
            });
            //pay
            Route::group(['prefix' => 'pay'], function () {
                Route::get('list-type', 'PayController@listPayType');
                Route::get('list-note', 'PayController@listPayNote');
            });
        });
        //IV. payment manager(quan ly thu chi)
        Route::group(['prefix' => 'payment'], function () {
            //1.phieu chi tam ung
            Route::group(['prefix' => 'lender'], function () {
                Route::get('', 'LenderController@list');
                Route::get('take={take}', 'LenderController@listTake');
                Route::get('page={page}', 'LenderController@listpage');
                Route::get('job-not-created', 'LenderController@listJobNotCreated');
                Route::get('list-advance', 'LenderController@listAdvance');
                Route::get('search/type={type}&value={value}&page={page}', 'LenderController@search');
                Route::get('des/{id}', 'LenderController@des');
                Route::post('add', 'LenderController@add');
                Route::post('edit', 'LenderController@edit');
                Route::post('add-d', 'LenderController@addD');
                Route::post('edit-d', 'LenderController@editD');
                Route::post('remove', 'LenderController@remove');
                Route::post('remove-d', 'LenderController@removeD');
            });
            //3.yeu cau thanh toan
            Route::group(['prefix' => 'debit-note'], function () {
                Route::get('/', 'DebitNoteController@list');
                Route::get('/custno={custno}', 'DebitNoteController@listCustomer'); //print
                Route::post('list-cust-job', 'DebitNoteController@postListCustomerJob'); //print
                Route::get('/list-job-has-d', 'DebitNoteController@listJobHasD'); //print 2. list debit có chi phí
                Route::get('page={page}', 'DebitNoteController@listPage');
                Route::get('take={take}', 'DebitNoteController@listTake');
                Route::get('search/type={type}&value={value}&page={page}', 'DebitNoteController@searchPage');
                Route::get('search/type={type}&value={value}', 'DebitNoteController@search');

                Route::get('des/{id}', 'DebitNoteController@des');
                Route::get('not-created', 'DebitNoteController@listNotCreated');
                Route::get('des-job-not-created/{id}', 'DebitNoteController@desJobNotCreated');
                Route::post('add', 'DebitNoteController@add');
                Route::post('edit', 'DebitNoteController@edit');
                Route::post('remove', 'DebitNoteController@remove');

                Route::post('add-d', 'DebitNoteController@addDebitD');
                Route::post('edit-d', 'DebitNoteController@editDebitD');
                Route::post('remove-d', 'DebitNoteController@removeDebitD');
            });
            //4. duyet thanh toan khach hang
            Route::group(['prefix' => 'paid-debit'], function () {
                Route::get('list-paid', 'DebitNoteController@listPaid');
                Route::get('list-pending', 'DebitNoteController@listPending');
                Route::get('list-paid/page={page}', 'DebitNoteController@listPaidPage');
                Route::get('list-pending/page={page}', 'DebitNoteController@listPendingPage');
                Route::post('change', 'DebitNoteController@change');
            });
            //6. bang kiem tra du lieu
            Route::group(['prefix' => 'check-data'], function () {
                Route::post('/', 'DebitNoteController@checkData');
            });
            //8. chi phi tien tau/cuoc cont
            Route::group(['prefix' => 'boat-fee'], function () {
                Route::get('list-boat-month-m', 'BoatFeeController@listBoatMonthM');
                Route::get('list-fee-month-m', 'BoatFeeController@listFeeMonthM');
                Route::get('list-boat-month-m/page={page}', 'BoatFeeController@listBoatMonthMPage');
                Route::get('list-fee-month-m/page={page}', 'BoatFeeController@listFeeMonthMPage');
                Route::get('des-month/type={FEE_TYPE}&value={BOAT_FEE_MONTH}', 'BoatFeeController@desMonth');
                Route::post('edit', 'BoatFeeController@edit');
            });
            //10. phieu thu
            Route::group(['prefix' => 'receipts'], function () {
                Route::get('', 'ReceiptsController@list');
                Route::get('page={page}', 'ReceiptsController@listpage');
                Route::get('des/{id}', 'ReceiptsController@des');
                Route::post('add', 'ReceiptsController@add');
                Route::post('edit', 'ReceiptsController@edit');
                Route::post('remove', 'ReceiptsController@remove');
                Route::get('search/type={type}&value={value}&page={page}', 'ReceiptsController@search');
            });
        });
        //print
        Route::group(['prefix' => 'print', 'namespace' => 'Prints'], function () {
            //1. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                // 1.in phieu theo doi
                Route::group(['prefix' => 'job-start'], function () {
                    Route::get('fromjob={fromjob}&tojob={tojob}', 'FileController@jobStart');
                });
                //2.in job order
                Route::group(['prefix' => 'job-order'], function () {
                    Route::get('/', 'FileController@getJobOrder');
                    Route::post('/', 'FileController@getJobOrder');
                    Route::get('jobno={jobno}', 'FileController@jobOrder');
                    Route::get('boat/jobno={jobno}', 'FileController@jobOrderBoat');
                    Route::get('custno={id}&jobno={jobno}', 'FileController@jobOrderCustomer');
                    Route::get('custno={id}', 'FileController@getJobOrderCustomer');
                    Route::get('fromdate={fromdate}&todate={todate}', 'FileController@jobOrder_Date');

                    Route::get('filter-job', 'FileController@filterJobOrder');//filter job with cust_no & date
                });
                //3.bao bieu refund
                Route::group(['prefix' => 'refund'], function () {
                    //1.hang tau, 2.khach hang, 3.dai ly
                    Route::get('type={type}&custno={custno}&jobno={jobno}&fromdate={fromdate}&todate={todate}', 'FileController@refund');
                    Route::get('/', 'FileController@getRefund');
                    Route::get('post-export', 'FileController@postExportRefund');
                });
                //4.thong ke job order
                Route::group(['prefix' => 'statistic'], function () {
                    //thống kê tạo job
                    Route::get('created-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'FileController@statisticCreatedJob');
                    Route::get('user-import-job/cust={cust}&user={user}&fromdate={fromdate}&todate={todate}', 'FileController@statisticUserImportJob');
                });
            });
            //2. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.phieu chi tam ung
                Route::group(['prefix' => 'advance'], function () {
                    //1.1 phieu chi
                    Route::get('advance_no={advanceno}', 'PaymentController@advance');
                    //1.2thống kê phiếu bù và phiếu trả
                    Route::post('replenishment-withdrawal-payment', 'PaymentController@postExportReplenishmentWithdrawalPayment');
                    // Route::get('replenishment-withdrawal-payment', 'PaymentController@getReplenishmentWithdrawalPayment');
                });
                //2. phiếu yêu cầu thanh toán
                Route::group(['prefix' => 'debit-note'], function () {
                    Route::get('type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}&debittype={debittype}&person={person}&phone={phone}&bankno={bankno}', 'PaymentController@debitNote');
                    Route::post('/', 'PaymentController@postDebitNote');
                    Route::get('/', 'PaymentController@postDebitNote');
                });
                //4. báo biểu lợi nhuận
                Route::get('profit/type={type}&jobno={jobno}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@profit');
                //5. thống kê số job trong tháng
                Route::get('job-monthly/type={type}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@jobMonthly');
                //6. thong ke thanh toan cua khach hang
                Route::get('payment-customers/type={type}&custno={custno}&fromdate={fromdate}&todate={todate}', 'PaymentController@paymentCustomers');
                //7. thong ke job order
                Route::get('job-order/type={type}&custno={custno}&person={person}&fromdate={fromdate}&todate={todate}', 'PaymentController@jobOrder');
                //8. thống kê phiếu thu
                Route::get('receipts/type={type}&receiptno={receiptno}', 'PaymentController@receipt');
            });

        });
        //export
        Route::group(['prefix' => 'export', 'namespace' => 'Exports'], function () {
            //1. báo biểu hồ sơ
            Route::group(['prefix' => 'file'], function () {
                //1. JOB START
                Route::post('job-start', 'FileController@jobStart');//function update
                //2.job order
                // Route::post('job-order', 'FileController@exportJobOrder');
                Route::post('job-order-new', 'FileController@exportJobOrderNew');
                //3. báo biểu refund
                Route::post('refund', 'FileController@postExportRefund');
                //4.thong ke job order--tạo job
                Route::post('created-job', 'FileController@statisticCreatedJob');
                //5. thống kê nâng hạ
                Route::get('lifting/fromdate={fromdate}&todate={todate}', 'FileController@lifting');
            });
            //2. payment manager(quan ly thu chi)
            Route::group(['prefix' => 'payment'], function () {
                //1.2 thống kê phiếu bù và phiếu trả
                Route::post('advance/replenishment-withdrawal-payment', 'PaymentController@postExportReplenishmentWithdrawalPayment');
                //2. phiếu yêu cầu thanh toán
                Route::post('debit-note', 'PaymentController@postExportDebitNote');
                //4. báo biểu lợi nhuận
                Route::post('profit', 'PaymentController@profit');
                //5. thống kê số job trong tháng
                Route::post('job-monthly', 'PaymentController@postExportJobMonthly');
                //6. thong ke thanh toan cua khach hang
                Route::post('payment-customers', 'PaymentController@postExportPaymentCustomers');
                //7. thong ke job order
                Route::post('job-order', 'PaymentController@postExportjobOrder');
            });
        });
        //import
        Route::group(['prefix' => 'import', 'namespace' => 'Exports'], function () {
            //1. payment manager(quan ly thu chi)--Chị Quỳnh import-> xuất file excel
            Route::post('payment', 'PaymentController@importPayment');
            //chị Phấn
            Route::post('debit-note', 'PaymentController@importDebitNote');//add table debit_note_d
            Route::post('job-order-boat', 'FileController@importJobOrderBoat');// add table job_order_d (chi phí book tàu)
            //inport upload phí kéo--Chị Huệ
            Route::post('job-order', 'FileController@importJobOrder');
        });
        //test
        Route::group(['prefix' => 'test', 'namespace' => 'Statistic'], function () {
            Route::post('/', 'PaymentController@test')->name('showView');
        });
    });
});
