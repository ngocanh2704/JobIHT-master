<!DOCTYPE html>
<html>
<style>
    body {
        margin: 0;
        padding: 0;
        background-color: #FAFAFA;
        font: 10pt "Tohoma";
    }

    * {
        box-sizing: border-box;
        -moz-box-sizing: border-box;
    }

    .page {
        width: 21cm;
        overflow: hidden;
        min-height: 297mm;
        padding: 0.5cm;
        margin-left: auto;
        margin-right: auto;
        background: white;
        box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        border: 1px solid;
    }

    @page {
        size: A4;
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
        font-size: 18px;
        font-weight: bold;
        text-transform: uppercase;
    }

    .title-2 {
        text-align: center;
        position: relative;
        font-size: 18px;
        font-weight: bold;
        margin-top: -1em;
        margin-bottom: -0.2em;
        text-transform: uppercase;
    }

    nav ul {
        list-style-type: none;
        padding: 0;
    }

    .title-sub {
        font-size: 13px;
        font-weight: bold;
    }

    .padding-left-15 {
        padding-left: 15px;
    }

    .text-align-right {
        text-align: right
    }

    .text-align-left {
        text-align: left
    }

    .col-10 {
        width: 100%;
        display: flex;

    }

    .border {
        border-bottom: 1px solid #ccc;
        padding-top: 0.5em;
    }

    .col-8 {
        width: 80%;
        display: flex;
    }

    .col-7 {
        width: 70%;
        display: flex;
    }

    .col-6 {
        width: 60%;
        display: flex;
    }

    .col-5 {
        width: 50%;
        display: flex;
    }

    .col-4 {
        width: 40%;
        display: flex;
    }

    .col-3 {
        width: 30%;
        display: flex;
    }

    .col-25 {
        width: 25%;
        display: flex;
    }

    .col-2 {
        width: 20%;
        display: flex;
    }

    .col-1 {
        width: 10%;
        display: flex;
    }

    table,
    th,
    td {
        border: 1px solid black;
        border-collapse: collapse;
        text-align: center;
        font-size: 12px;
    }


    #sum-money {
        font-size: 12px;
        font-weight: bold;
        margin-left: 50%;
    }

    #lnkPrint {
        margin-top: 1em;
        background: aquamarine;
    }

    #lnkPrint:active::after {
        display: none;
    }

</style>

<body>
    <div id="page" class="page ">
        <button type="button" id="lnkPrint" onclick="myFunction()">Click the button
            to print the current page</button>
        <div class="border">
            <p class="title">thống kê bù và trả</p>
            <table style="width:100%">
                <tr>
                    <th>STT</th>
                    <th>Tên</th>
                    <th>Số Phiếu</th>
                    <th style="width: 10%">Số Job</th>
                    <th>Loại phiếu</th>
                    <th>Mã K.H</th>
                    <th>Nội dung</th>
                    <th>Tạm Ứng</th>
                    <th>Chi Trực Tiếp</th>
                    <th>Tiền Job</th>
                    <th>Bù/Trả</th>
                </tr>
                @foreach ($lender as $key => $item)
                    <tr>
                        <td>{{ $key + 1 }}</td>
                        <td>{{ $item->INPUT_USER }}</td>
                        <td>{{ $item->LENDER_NO }}</td>
                        <td>{{ $item->JOB_NO }}</td>
                        <td>{{ $item->LENDER_TYPE == 'U' ? 'PTU' : ($item->LENDER_TYPE == 'C' ? 'CTT' : 'CTU') }}</td>
                        <td>{{ $item->CUST_NO }}</td>
                        <td>{{ $item->LEND_REASON }}</td>
                        <td>{{ number_format($item->SUM_LENDER_AMT, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->SUM_DIRECT, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->SUM_JOB_ORDER, 0, ',', '.') }}</td>
                        <td>{{ number_format($item->SUM_REPLENISHMENT_WITHDRAWAL, 0, ',', '.') }}</td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="7"></td>
                    <td>{{ number_format($lender->sum('SUM_LENDER_AMT'), 0, ',', '.') }}</td>
                    <td>{{ number_format($lender->sum('SUM_DIRECT'), 0, ',', '.') }}</td>
                    <td>{{ number_format($lender->sum('SUM_JOB_ORDER'), 0, ',', '.') }}</td>
                    <td>{{ number_format($lender->sum('SUM_REPLENISHMENT_WITHDRAWAL'), 0, ',', '.') }}</td>
                </tr>
                <tr>
                    <th colspan="10" class="text-align-right">Tổng Phiếu Trả và Bù/Chi</th>
                    <th>{{ number_format($lender->sum('SUM_REPLENISHMENT_WITHDRAWAL'), 0, ',', '.') }}</th>
                </tr>
            </table>

        </div>
    </div>
</body>
<script>
    function myFunction() {
        var x = document.getElementById("lnkPrint");
        x.style.display = "none";
        window.print();
    }

</script>

</html>
