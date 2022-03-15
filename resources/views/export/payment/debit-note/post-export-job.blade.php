<!DOCTYPE html>
<html lang="">
<style>
    .table,
    .table th,
    .table td {
        border: 1px solid #000;
        border-collapse: collapse;
        text-align: left;
    }

    .title {
        text-align: center;
        font-size: 20px;
    }

</style>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="8" style="text-align: center">
                    <h1>{{ $company->COMP_NAME }}</h1>
                </th>
            </tr>
            <tr>
                <th colspan="8" style="text-align: center">Add: {{ $company->COMP_ADDRESS1 }}</th>
            </tr>
            <tr>
                <th colspan="8" style="text-align: center">Tel: {{ $company->COMP_TEL1 }}/{{ $company->COMP_TEL2 }}
                </th>
            </tr>
            <tr>
                <th colspan="8" style="text-align: center">Fax: {{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}
                </th>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th colspan="8" class="title">
                    DEBIT NOTE
                </th>
            </tr>
        </thead>
    </table>
    <table class="table">
        <tr>
            <th colspan="5">
                RECEIVE
            </th>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th>To:</th>
            <td colspan="4">{{ $debit->CUST_NAME }}</td>
            <th colspan="2">Please Contact With:</th>
            <td>{{ $person->PNL_NAME }}</td>
        </tr>
        <tr>
            <th>Attn:</th>
            <td colspan="4">{{ $debit->CUST_BOSS }}</td>
            <th colspan="2">Accountting:</th>
            <td>{{ $phone }}</td>
        </tr>
        <tr>
            <th>Add:</th>
            <td colspan="4">{{ $debit->CUST_ADDRESS }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th>Tel:</th>
            <td colspan="4">{{ $debit->CUST_TEL1 }}</td>
            <td colspan="3"></td>
        </tr>
        <tr>
            <th>Fax:</th>
            <td colspan="4">{{ $debit->CUST_FAX }}</td>
            <td colspan="3"></td>
        </tr>
    </table>

    <table class="table">
        <tr>
            <th colspan="2">From:</th>
            <td colspan="3">{{ $debit->TRANS_FROM }}</td>
            <th>To:</th>
            <td colspan="2">{{ $debit->TRANS_TO }}</td>
        </tr>
        <tr>
            <th colspan="2">Customs No:</th>
            <td colspan="3">{{ $debit->CUSTOMS_NO }}</td>
            <th>Custom Date:</th>
            <td colspan="2">{{ $debit->CUSTOMS_DATE == null ? '' : date('Y/m/d', strtotime($debit->CUSTOMS_DATE)) }}
            </td>
        </tr>
        <tr>
            <th colspan="2">NW:</th>
            <td colspan="3">{{ $debit->NW }}</td>
            <th>GW:</th>
            <td colspan="2">{{ $debit->GW }}</td>
        </tr>
        <tr>
            <th colspan="2">Job Order:</th>
            <td colspan="3">{{ $debit->JOB_NO }}</td>
            <th>Note:</th>
            <td colspan="2">{{ $debit->NOTE }}</td>
        </tr>
        <tr>
            <td colspan="2">QTY:</td>
            <td colspan="3">{{ $debit->CONTAINER_QTY }}</td>
            <td>Invoices No:</td>
            <td colspan="2">{{ $debit->INVOICE_NO }}</td>
        </tr>
        <tr>
            <th colspan="2">Po No:</th>
            <td colspan="3"> {{ $debit->PO_NO }}</td>
            <th>Bill No:</th>
            <td colspan="2">{{ $debit->BILL_NO }}</td>
        </tr>
        <tr>
            <th colspan="2">Container No:</th>
            <td colspan="3">{{ $debit->CONTAINER_NO }}</td>
            <td colspan="3"></td>
        </tr>
    </table>
    <table>
        <tr>
            <th colspan="8">
                <h3>We would like to make the Debit Note as follows:</h3>
            </th>>
        </tr>
        <span style="display: none;"> {{ $total_vat_tax = 0 }} {{ $total_sum_amt = 0 }}</span>
    </table>
    <table class="table">
        <tr>
            <th>STT</th>
            <th>Descriptions</th>
            <th>Invoice No</th>
            <th>Unit</th>
            <th>Qty</th>
            <th>Price</th>
            <th>VAT Tax</th>
            <th>Total Amt</th>
        </tr>
        <span style="display: none;">
            {{ $total_amt = 0 }}
            {{ $total_amt_do = 0 }}
            {{ $total_vat = 0 }}
        </span>

        @foreach (\App\Models\Statistic\StatisticPayment::postDebitNote_D('job', null, null, $debit->JOB_NO, null) as $key => $item_d)
            <tr>
                <td class="text-center">{{ $key + 1 }}</td>
                <td>{{ $item_d->DESCRIPTION }}</td>
                <td>{{ $item_d->INV_NO }}</td>
                <td class="text-center">{{ $item_d->UNIT }}</td>
                <td class="text-center">{{ $item_d->QUANTITY }}</td>
                <td class="text-right">
                    {{ trim($bank->SWIFT_CODE) == '' ? $item_d->PRICE : $item_d->DOR_AMT }}
                </td>
                <td class="text-right">{{ trim($bank->SWIFT_CODE) == '' ? $item_d->TAX_AMT : '' }}</td>
                <td class="text-right">
                    {{ trim($bank->SWIFT_CODE) == '' ? $item_d->TOTAL_AMT : $item_d->DOR_AMT * $item_d->QUANTITY }}
                </td>
                <span style="display: none;">
                    {{ $total_amt += $item_d->TOTAL_AMT }}
                    {{ $total_vat += $item_d->TAX_AMT }}
                    {{ $total_amt_do += $item_d->DOR_AMT * $item_d->QUANTITY }}
                    {{ $curency = $item_d->DOR_NO }}
                </span>
            </tr>
        @endforeach
        <tr>
            <th colspan="6" style="text-align: right">JOB AMT</th>
            <th>{{ trim($bank->SWIFT_CODE) == '' ? $total_vat : '' }}
            <th> {{ trim($bank->SWIFT_CODE) == '' ? $total_amt : $total_amt_do }}
                {{ trim($bank->SWIFT_CODE) == '' ? '' : 'USD' }}</th>
        </tr>
    </table>

    <span style="display: none;">
        {{ $total_vat_tax += $total_vat }}
        {{ $total_sum_amt += trim($bank->SWIFT_CODE) == '' ? $total_amt : $total_amt_do }}
    </span>
    <table class="table">
        <tr>
            <th colspan="6" style="text-align: right">TOTAL AMT</th>
            <th>{{ trim($bank->SWIFT_CODE) == '' ? $total_vat_tax : '' }}
            <th>
                {{ $total_sum_amt }} {{ trim($bank->SWIFT_CODE) == '' ? '' : 'USD' }}
            </th>
        </tr>

    </table>
    <table>
        <tr>
            <th colspan="8">We are looking forwards to reveiving your payment in the soonest time.</th>
        </tr>
        <tr>
            <th colspan="8">If you have further infomation, please do not hesitate to contact with us.</th>
        </tr>
        <tr>
            <th colspan="8">Also you can settle the payment to:</th>
        </tr>
        <tr>
            <th colspan="8">Banker name: {{ $bank->BANK_NAME }}</th>
        </tr>
        <tr>
            <th colspan="8">Account no: {{ $bank->ACCOUNT_NO }}</th>
        </tr>
        <tr>
            <th colspan="8">Account name: {{ $bank->ACCOUNT_NAME }}</th>
        </tr>
        @if (trim($bank->SWIFT_CODE) != '')
            <tr>
                <th colspan="8">Swift code: {{ $bank->SWIFT_CODE }}</th>
            </tr>
            <tr>
                <th colspan="8">Bank address: {{ $bank->BANK_ADDRESS }}</th>
            </tr>
            <tr>
                <th colspan="8">Adress: {{ $bank->ADDRESS }}</th>
            </tr>
        @endif
    </table>
    <table>
        <tr>
            <th colspan="3">SALE</th>
            <th colspan="3">ACCOUNTANT</th>
            <th colspan="3">APPROVAL</th>
        </tr>
    </table>
</body>

</html>
