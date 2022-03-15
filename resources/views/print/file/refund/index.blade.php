<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 12pt "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        overflow: hidden;
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
    }

    @page {
        size: A4 landscape;
        margin: 0;
    }

    @media print {
        @page {
            margin: 0;
            border: initial;
            border-radius: initial;
            width: initial;
            min-height: initial;
            box-shadow: initial;
            background: initial;
            page-break-after: always;
        }
    }

    .title {
        text-align: center;
        position: relative;
        font-size: 24px;
        font-weight: bold;
    }

    .title-sub {
        text-align: center;
        position: relative;
        font-size: 13px;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .table,
    .table th,
    .table td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size: 13px;
    }

    .table .amount td {
        border: none;
    }

    table {
        width: 100%;
    }

    .amount td {
        font-weight: bold;
    }

    .text-align-left td {
        text-align: left !important;
    }

</style>

<body onload="window.print();">
    <div id="page" class="page">
        <div class="title">BÁO CÁO REFUND {{ $type_name }}</div>
        <div class="title-sub"> TỪ NGÀY: {{ date('d/m/Y', strtotime($fromdate)) }} - ĐẾN NGÀY:
            {{ date('d/m/Y', strtotime($todate)) }}
        </div>
        <br>
        <table class="table">
            <tr>
                <th>MÃ {{ ($type_name == 'ĐẠI LÝ') || ($type_name == 'KHÁCH HÀNG') ? 'KH' : 'HT' }}</th>
                <th>TÊN {{ $type_name == 'ĐẠI LÝ' ? 'KHÁCH HÀNG' : $type_name }}</th>
                <th>JOB NO</th>
                <th>BILL NO</th>
                <th>STT</th>
                <th>MÔ TẢ</th>
                <th>ĐVT</th>
                <th>SL</th>
                <th>ĐƠN GIÁ</th>
                <th>SAU THUẾ</th>
                <th>TỔNG TIỀN</th>
                <th >THANH TOÁN</th>
            </tr>
            <span style="display: none">{{$sum_price=0}} {{ $sum_money_after = 0}}{{$sum_money=0}}</span>
            @foreach ($data as $item)
                <tr class="text-align-left">
                    <td>{{ $type_name == 'ĐẠI LÝ' ? $item->CUST_NO2 : $item->CUST_NO }}</td>
                    <td>{{ $type_name == 'ĐẠI LÝ' ? $item->CUST_NAME2 : $item->CUST_NAME }}</td>
                    <td>{{ $item->JOB_NO }}</td>
                    <td>{{ $item->BILL_NO }}</td>
                    <td>{{ $item->SER_NO }}</td>
                    <td>{{ $item->DESCRIPTION }}</td>
                    <td>{{ (int) $item->UNIT }}</td>
                    <td>{{ number_format($item->QTY,0,",",".") }}</td>
                    <td>{{ number_format($item->PRICE,0,",",".") }}</td>
                    <td>{{number_format($item->PRICE + $item->TAX_AMT,0,",",".") }}</td>
                    <td>{{number_format(($item->PRICE + $item->TAX_AMT)*$item->QTY,0,",",".") }}</td>
                    <td>{{ $item->THANH_TOAN_MK == 'Y' ? 'Đã thanh toán' : 'Chưa thanh toán' }}</td>
                </tr>
                <span style="display: none">{{$sum_price+=$item->PRICE}} {{ $sum_money_after += ($item->PRICE + $item->TAX_AMT)}}{{ $sum_money += ($item->PRICE + $item->TAX_AMT)*$item->QTY}}</span>
            @endforeach
            <tr class="amount">
                <td colspan="8" style="text-align: right">TỔNG TIỀN:</td>
                <td style="text-align: left">{{ number_format($sum_price,0,",",".") }}</td>
                <td  style="text-align: left">{{ number_format($sum_money_after,0,",",".") }}</td>
                <td colspan="2" style="text-align: left">{{ number_format($sum_money,0,",",".") }}</td>
            </tr>
        </table>
    </div>
</body>
