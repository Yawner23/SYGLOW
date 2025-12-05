<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Print All Waybills</title>

    <style>
        * {
            box-sizing: border-box;
        }

        @page {
            size: 100mm 150mm portrait;
            /* sticker size, PORTRAIT */
            margin: 3mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 9px;
            margin: 0;
            padding: 0;
        }

        /* printable height = 150mm - 3mm top - 3mm bottom = 144mm */
        .sheet {
            width: 100mm;
            height: 144mm;
            margin: 0 auto;

            /* center the label block */
            display: flex;
            align-items: center;
            /* vertical */
            justify-content: center;
            /* horizontal */
        }

        /* LABEL WRAPPER JUST SITS INSIDE (NO ABSOLUTE BOTTOM) */
        .waybill-wrapper {
            width: 100%;
            padding: 4px 4px 6px 4px;
        }

        .waybill {
            border: 1px solid #000;
            padding: 4px;
            display: block;
            background: #fff;
            page-break-inside: avoid;
            transform: scale(0.92);
            /* shrink slightly to fit */
            transform-origin: top left;
        }

        .no-scale {
            transform: none !important;
        }

        .no-scale svg {
            width: 80px;
            height: 80px;
            display: block;
            margin: 0 auto;
        }

        .wb-logo-row {
            display: block;
            width: 100%;
            border-bottom: 1px solid #000;
            padding: 3px 2px;
        }

        .logo-left,
        .logo-right {
            font-size: 15px;
            font-weight: bold;
        }

        .logo-left {
            float: left;
            text-align: left;
        }

        .logo-right {
            float: right;
            text-align: right;
        }

        .wb-logo-row::after {
            content: "";
            display: block;
            clear: both;
        }

        .wb-tagline {
            text-align: center;
            font-weight: bold;
            font-size: 12px;
            border-bottom: 1px solid #000;
            padding: 4px 0;
            margin-bottom: 3px;
        }

        .barcode-block {
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 3px;
            text-align: center;
        }

        .barcode-text {
            text-align: center;
            font-size: 15px;
        }

        .sort-code {
            font-size: 30px;
            text-align: center;
            border-bottom: 1px solid #000;
            padding: 3px 0;
            margin-bottom: 3px;
        }

        .box {
            /* border-bottom: 1px solid #000; */
        }

        .box-inner {
            border: 1px solid #000;
            padding: 2px;
        }

        .section-inner {
            margin-bottom: 1px;
        }

        .label {
            font-weight: bold;
            font-size: 10px;
            margin-bottom: 1px;
        }

        .value {
            font-size: 11px;
            font-weight: bold;
        }

        .small {
            font-size: 8px;
        }

        table.items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 6px;
        }

        table.items th,
        table.items td {
            /* border: 1px solid #000; */
            padding: 5px;
            font-size: 9px;
            text-align: left;
        }

        table.items th {
            background: #f1f1f1;
        }

        /* MAIN LEFT/RIGHT LAYOUT (no flex, DomPDF-safe) */
        table.wb-main {
            width: 100%;
            /* border-collapse: collapse; */
        }


        td.wb-main-left {
            width: 270px;
        }

        td.wb-main-right {
            padding-left: 3px;
        }

        /* RIGHT SIDE COLUMN (QR + vertical barcode) */
        .side-col-inner {
            /* border: 1px solid #000; */
            padding: 4px 2px;

            display: flex;
            flex-direction: column;
            justify-content: space-between;
            /* pushes QR to top, barcode to bottom */
            height: 60%;
            /* important! */
        }


        .qr-box {
            width: 100%;
        }

        .side-barcode-block {
            width: 1%;
        }

        .side-barcode-block .rotate-barcode {
            transform: rotate(90deg);
            margin-top: -30px;
            transform-origin: right bottom;
        }

        .signature-box {
            display: block;
            width: 100%;
        }

        .signature-left {
            float: left;
            width: 58%;
        }

        .signature-right {
            float: right;
            width: 40%;
            text-align: center;
        }

        .signature-box::after {
            content: "";
            /* display: block; */
            clear: both;
        }

        .attempts-table {
            border: 1px solid #000;
            border-collapse: collapse;
            width: 100%;
            margin-top: 1px;
        }

        .attempts-table td,
        .attempts-table th {
            border: 1px solid #000;
            padding: 1px;
            text-align: center;
            font-size: 10px;
        }

        .side-label {
            position: absolute;
            font-size: 6px;
            color: #000;
            background: #fff;
            padding: 1px 3px;
        }

        .side-label-top {
            top: -8px;
            left: 50%;
            transform: translateX(-50%);
        }

        .side-label-bottom {
            bottom: 40px;
            left: 50%;
            transform: translateX(-50%);
        }

        .side-label-left {
            left: -30px;
            top: 50%;
            transform: translateY(-50%) rotate(-90deg);
        }

        .side-label-right {
            right: -35px;
            top: 50%;
            transform: translateY(-50%) rotate(90deg);
        }

        .logo-img {
            height: 30px;
            /* adjust to fit your label */
            object-fit: contain;
        }

        .peso {
            font-family: 'DejaVu Sans', sans-serif !important;
        }
    </style>
</head>

<body>

    @foreach($payments as $payment)
    @php
    $jtPost = $payment->jt_post_data ? json_decode($payment->jt_post_data, true) : null;

    $logistics = $jtPost && isset($jtPost['logistics_interface'])
    ? json_decode($jtPost['logistics_interface'], true)
    : null;

    $sender = $logistics['sender'] ?? null;
    $receiver = $logistics['receiver'] ?? null;

    $tracking = $payment->tracking_number
    ?? ($logistics['txlogisticid'] ?? 'JT000000000000');

    $weight = isset($logistics['weight'])
    ? (float) $logistics['weight']
    : (float) ($payment->weight ?? 0.250);

    $jtResponse = $payment->jt_response_body
    ? json_decode($payment->jt_response_body, true)
    : null;

    $jtItem = $jtResponse['responseitems'][0] ?? [];
    $sortingCode = $jtItem['sortingcode'] ?? ($payment->sorting_code ?? '840-E026 00');
    $seq = $jtItem['sortingNo'] ?? '000';

    $qrHtml = DNS2D::getBarcodeHTML($tracking, 'QRCODE', 4, 4);


    @endphp

    {{-- PAGE 1: WAYBILL --}}
    <div class="sheet page-break">
        <div class="waybill-wrapper">

            <div class="side-label side-label-top"> {{ $tracking }}</div>
            <div class="side-label side-label-bottom"> {{ $tracking }}r</div>
            <div class="side-label side-label-left"> {{ $tracking }}</div>
            <div class="side-label side-label-right"> {{ $tracking }}</div>

            <div class="waybill">

                {{-- Logo row --}}
                <div class="wb-logo-row">
                    <div class="logo-left">
                        <img src="{{ public_path('images/About-Us-Logo.png') }}"
                            alt="SY Glow Logo"
                            class="logo-img">
                    </div>

                    <div class="logo-right">
                        <img src="{{ public_path('images/jnt-logo.jpg') }}"
                            alt="JNT Logo"
                            class="logo-img">
                    </div>
                </div>

                {{-- Tagline --}}
                <div class="wb-tagline">On Time Delivery Promised</div>

                {{-- Main barcode --}}
                <div class="barcode-block">
                    <div style="display:inline-block;">
                        {!! DNS1D::getBarcodeHTML($tracking, 'C128', 1.8, 50) !!}
                    </div>
                    <div class="barcode-text">
                        {{ $tracking }}
                    </div>
                </div>

                {{-- Sorting code --}}
                <div class="sort-code">
                    {{ $sortingCode . ' - ' . $seq }}
                </div>

                {{-- MAIN CONTENT --}}
                <table class="wb-main box">
                    <tr>
                        <td class="wb-main-left">

                            {{-- Receiver --}}
                            <div class="box-inner section-inner">
                                <div class="label">
                                    Receiver:
                                    <span style="font-weight: normal;">
                                        {{ $receiver['name']
                                    ?? $payment->deliveryAddress->deliver_name
                                    ?? $payment->customer->name
                                    ?? 'N/A' }}
                                        &nbsp; | &nbsp;
                                        {{ $receiver['mobile']
                                    ?? $payment->deliveryAddress->contact_no
                                    ?? '' }}
                                    </span>
                                </div>

                                <div class="value" style=" margin-top: 2px;">
                                    @if($receiver)
                                    {{ $receiver['prov'] ?? '' }},
                                    {{ $receiver['city'] ?? '' }},
                                    {{ $receiver['area'] ?? '' }}<br>
                                    {{ $receiver['address'] ?? '' }}<br>
                                    @else
                                    {{ $payment->deliveryAddress->full_address ?? 'No address' }}<br>
                                    @endif
                                </div>
                            </div>


                            {{-- Sender --}}
                            <div class="box-inner section-inner">
                                <div class="label">
                                    Sender:
                                    <span style="font-weight: normal;">
                                        {{ $sender['name'] ?? config('app.name', 'Your Shop Name') }}
                                        &nbsp; | &nbsp;
                                        {{ $sender['mobile'] ?? $payment->sender_phone ?? '' }}
                                    </span>
                                </div>

                                <div class="value" style="margin-top: 2px;">
                                    @if($sender)
                                    {{ $sender['prov'] ?? '' }},
                                    {{ $sender['city'] ?? '' }},
                                    {{ $sender['area'] ?? '' }}<br>
                                    {{ $sender['address'] ?? '' }}<br>
                                    @else
                                    {{ $payment->sender_address ?? 'Your warehouse address here' }}<br>
                                    @endif
                                </div>
                            </div>


                            {{-- Signature + attempts --}}
                            <div class="box-inner section-inner">
                                <div class="signature-box">
                                    <div class="signature-left">
                                        <div class="label">Signature:</div>
                                        <div style="border-top:1px solid #000; height:8mm;"></div>
                                    </div>

                                    <div class="signature-right" style="margin-top:70px;">
                                        <div class="label small" style="margin-bottom:8px;">
                                            Delivery Attempts
                                        </div>
                                        <table class="attempts-table">
                                            <tr>
                                                <th>1</th>
                                                <th>2</th>
                                                <th>3</th>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>

                            {{-- Weight / payment --}}
                            <div class="box-inner">
                                <div class="value">
                                    <strong>Weight:</strong> {{ number_format($weight, 3) }}<br>
                                    <strong>Payment:</strong> {{ strtoupper($payment->payment_method ?? 'COD') }}<br>
                                    <strong>Amount to Collect:</strong> <span class="peso">₱</span>{{ number_format($payment->total, 2) }}<br>
                                </div>
                            </div>
                        </td>

                        <td class="wb-main-right">
                            <div class="side-col-inner">

                                <div class="qr-box no-scale">
                                    {!! $qrHtml !!}
                                </div>




                                {{-- Vertical Barcode BELOW QR --}}
                                <div class="side-barcode-block">
                                    <div class="rotate-barcode">
                                        {!! DNS1D::getBarcodeHTML($tracking, 'C128', 1.2, 80) !!}
                                    </div>
                                </div>

                            </div>
                        </td>

                    </tr>
                </table>
            </div>
        </div>
    </div>

    {{-- PAGE 2: ITEMS --}}
    <div class="sheet page-break">
        <div class="waybill-wrapper">
            <div class="waybill">
                <!-- <div class="label" style="margin-bottom:4px;">Items Purchased</div>
                <div class="value" style="margin-bottom:6px;">
                    Waybill: {{ $tracking }}<br>
                    Customer: {{ $receiver['name'] ?? $payment->customer->name ?? 'N/A' }}
                </div> -->

                @php
                $totalQty = 0;
                @endphp

                <table class="items">
                    <thead>
                        <tr>
                            <!-- <th>#</th> -->
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Qty</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $i = 1; @endphp
                        @foreach($payment->products ?? [] as $item)
                        @php
                        $qty = $item->quantity ?? 1;
                        $totalQty += $qty;
                        @endphp
                        <tr>
                            <!-- <td>{{ $i++ }}</td> -->
                            <td>{{ $item->product->product_name ?? 'N/A' }}</td>
                            <td>{{ $item->product->seller_sku ?? 'N/A' }}</td>
                            <td>{{ $qty }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div style="margin-top:8px; text-align:right;">
                    <span class="label">Total Qty:</span>
                    <span class="value">{{ $totalQty }}</span>
                </div>
            </div>
        </div>
    </div>


    @endforeach

</body>

</html>