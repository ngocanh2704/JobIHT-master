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

    .table2 {
        border: 1px solid #000;
        border-collapse: collapse;
        text-align: left;
    }

    .title {
        text-align: center;
        font-size: 22px;
    }

</style>

<body>
    <table>
        <thead>
            <tr>
                <th colspan="9" style="text-align: center">
                    <h2>{{ $company->COMP_NAME }}</h2>
                </th>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center">Add: {{ $company->COMP_ADDRESS1 }}</td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center">Tel: {{ $company->COMP_TEL1 }}/{{ $company->COMP_TEL2 }}
                </td>
            </tr>
            <tr>
                <td colspan="9" style="text-align: center">Fax: {{ $company->COMP_FAX1 }}/{{ $company->COMP_FAX2 }}
                </td>
            </tr>
        </thead>
    </table>
    <table>
        <thead>
            <tr>
                <th colspan="9" class="title">
                    <h2>DEBIT NOTE</h2>
                </th>
            </tr>
        </thead>
    </table>
    <table class="table">
        <tr>
            <th colspan="5" style="text-align: center">
                RECEIVE
            </th>
            <td colspan="4"></td>
        </tr>
        <tr>
            <th>To:</th>
            <td colspan="4">{{ $customer->CUST_NAME }}</td>
            <th colspan="2">Please Contact With:</th>
            <td colspan="2">{{ $person->PNL_NAME }}</td>
        </tr>
        <tr>
            <th>Attn:</th>
            <td colspan="4">{{ $customer->CUST_BOSS }}</td>
            <th colspan="2">Accountting:</th>
            <td colspan="2">{{ $phone }} {{ '' }}</td>
        </tr>
        <tr>
            <th>Add:</th>
            <td colspan="4">{{ $customer->CUST_ADDRESS }}</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <th>Tel:</th>
            <td colspan="4">{{ $customer->CUST_TEL1 }}</td>
            <td colspan="4"></td>
        </tr>
        <tr>
            <th>Fax:</th>
            <td colspan="4">{{ $customer->CUST_FAX }}</td>
            <td colspan="4"></td>
        </tr>
    </table>
    <span style="display: none;"> {{ $total_vat_tax = 0 }} {{ $total_sum_amt = 0 }}</span>
    @foreach ($debit as $item)
        <table>
            <tr>
                <th colspan="2" class="font-size-small">From:</th>
                <td colspan="3">{{ $item->TRANS_FROM }}</td>
                <th class="font-size-small">To:</th>
                <td colspan="3">{{ $item->TRANS_TO }}</td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">Customs No:</th>
                <td colspan="3">{{ $item->CUSTOMS_NO }}</td>
                <th class="font-size-small">Custom Date:</th>
                <td colspan="3">
                    {{ $item->CUSTOMS_DATE == null ? '' : date('d/m/Y', strtotime($item->CUSTOMS_DATE)) }}
                </td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">NW:</th>
                <td colspan="3">{{ $item->NW }}</td>
                <th class="font-size-small">GW:</th>
                <td colspan="3">{{ $item->GW }} {{ '' }}</td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">Job Order:</th>
                <td colspan="3">{{ $item->JOB_NO }}</td>
                <th class="font-size-small">Note:</th>
                <td colspan="3">{{ $item->NOTE }}</td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">QTY:</th>
                <td colspan="3">{{ $item->CONTAINER_QTY }}</td>
                <th>Invoices No:</th>
                <td colspan="3">{{ $item->INVOICE_NO }}</td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">Po No:</th>
                <td colspan="3"> {{ $item->PO_NO }}</td>
                <th class="font-size-small">Bill No:</th>
                <td colspan="3">{{ $item->BILL_NO }}</td>
            </tr>
            <tr>
                <th colspan="2" class="font-size-small">Container No:</th>
                <td colspan="3">{{ $item->CONTAINER_NO }}</td>
                <td colspan="4"></td>
            </tr>
        </table>

        <table class="table">
            <tr>
                <th style="text-align: center">STT</th>
                <th style="text-align: center" colspan="2">Descriptions</th>
                <th style="text-align: center">Invoice No</th>
                <th style="text-align: center">Unit</th>
                <th style="text-align: center">Qty</th>
                <th style="text-align: center">Price</th>
                <th style="text-align: center">VAT Tax</th>
                <th style="text-align: center">Total Amt</th>
            </tr>
            <span style="display: none;">
                {{ $total_amt = 0 }}
                {{ $total_amt_do = 0 }}
                {{ $total_vat = 0 }}
            </span>
            @foreach ($item->debit_d as $key => $item_d)
                <tr>
                    <td style="text-align: center">{{ $key + 1 }}</td>
                    <td colspan="2">{{ $item_d->DESCRIPTION }}</td>
                    <td style="text-align: center">{{ $item_d->INV_NO }}</td>
                    <td style="text-align: center">{{ $item_d->UNIT }}</td>
                    <td style="text-align: center">
                        {{ $item_d->QUANTITY }}
                    </td>
                    <td style="text-align: right" data-format="0,0">
                        {{ trim($bank->SWIFT_CODE) == '' ? $item_d->PRICE : number_format($item_d->DOR_AMT, 2, '.', ',') }}
                    </td>
                    <td style="text-align: right" data-format="0,0">
                        {{ trim($bank->SWIFT_CODE) == '' ? ($item_d->TAX_AMT == 00 ? '-' : $item_d->TAX_AMT) : '' }}
                    </td>
                    <td style="text-align: right" data-format="0,0">
                        {{ trim($bank->SWIFT_CODE) == '' ? $item_d->TOTAL_AMT : number_format($item_d->DOR_AMT * $item_d->QUANTITY, 2, '.', ',') }}
                    </td>
                    <span style="display: none;">
                        {{ $total_amt += $item_d->TOTAL_AMT }}
                        {{ $total_vat += $item_d->TAX_AMT }}
                        {{ $total_amt_do += $item_d->DOR_AMT * $item_d->QUANTITY }}
                    </span>
                </tr>
            @endforeach
            <tr>
                <th colspan="7" style="text-align: right">JOB AMT</th>
                <th style="text-align: right" data-format="0,0">{{ trim($bank->SWIFT_CODE) == '' ? $total_vat : '' }}
                </th>
                <th style="text-align: right" data-format="0,0">
                    {{ trim($bank->SWIFT_CODE) == '' ? $total_amt : number_format($total_amt_do, 2, '.', ',') }}
                    {{ trim($bank->SWIFT_CODE) == '' ? '' : 'USD' }}
                </th>
            </tr>
        </table>

        <span style="display: none;" data-format="0,0">
            {{ $total_vat_tax += $total_vat }}
            {{ $total_sum_amt += trim($bank->SWIFT_CODE) == '' ? $total_amt : $total_amt_do }}
        </span>
    @endforeach
    <table class="table">
        <tr>
            <th colspan="7" style="text-align: right">TOTAL AMT</th>
            <th style="text-align: right" data-format="0,0">
                {{ trim($bank->SWIFT_CODE) == '' ? $total_vat_tax : '' }}</th>
            <th style="text-align: right" data-format="0,0">
                {{ trim($bank->SWIFT_CODE) == '' ? $total_sum_amt : number_format($total_sum_amt, 2, '.', ',') }}
                {{ trim($bank->SWIFT_CODE) == '' ? '' : 'USD' }}
            </th>
        </tr>

    </table>

    <table>
        <tr>
            <th colspan="9">We are looking forwards to reveiving your payment in the soonest time.</th>
        </tr>
        <tr>
            <th colspan="9">If you have further infomation, please do not hesitate to contact with us.</th>
        </tr>
        <tr>
            <th colspan="9">Also you can settle the payment to:</th>
        </tr>
        <tr>
            <th colspan="9">Banker name: {{ $bank->BANK_NAME }}</th>
        </tr>
        <tr>
            <th colspan="9">Account no: {{ $bank->ACCOUNT_NO }}</th>
        </tr>
        <tr>
            <th colspan="9">Account name: {{ $bank->ACCOUNT_NAME }}</th>
        </tr>

        @if (trim($bank->SWIFT_CODE) != '')
            <tr>
                <th colspan="9">Swift code: {{ $bank->SWIFT_CODE }}</th>
            </tr>
            <tr>
                <th colspan="9">Bank address: {{ $bank->BANK_ADDRESS }}</th>
            </tr>
            <tr>
                <th colspan="9">Adress: {{ $bank->ADDRESS }}</th>
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
    {{-- {{dd(1)}} --}}

</body>

</html>
