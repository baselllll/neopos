<?php
use App\Models\Order;
use App\Classes\Hook;
use Illuminate\Support\Facades\View;


use Salla\ZATCA\GenerateQrCode;
use Salla\ZATCA\Tags\InvoiceDate;
use Salla\ZATCA\Tags\InvoiceTaxAmount;
use Salla\ZATCA\Tags\InvoiceTotalAmount;
use Salla\ZATCA\Tags\Seller;
use Salla\ZATCA\Tags\TaxNumber;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;

// data:image/png;base64, .........
// $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
//     new Seller('مؤسسة ريان بن خالد بن سعود آل منقاش للوجبات السريعة'), // seller name        
//     new TaxNumber('311216140200003'), // seller tax number
//     new InvoiceDate('2021-07-12T14:25:09Z'), // invoice date as Zulu ISO8601 see https://en.wikipedia.org/wiki/ISO_8601
//     new InvoiceTotalAmount($order->total_with_tax), // invoice total amount
//     new InvoiceTaxAmount($order->total_tax_value) // invoice tax amount
//     // TODO :: Support others tags
// ])->render();
$displayQRCodeAsBase64 = GenerateQrCode::fromArray([
    new Seller('مؤسسة ريان بن خالد بن سعود آل منقاش للوجبات السريعة'), // seller name        
    new TaxNumber('311216140200003'), // seller tax number
    new InvoiceDate($order->created_at), // invoice date as Zulu ISO8601 see https://en.wikipedia.org/wiki/ISO_8601
    new InvoiceTotalAmount($order->total_with_tax), // invoice total amount
    new InvoiceTaxAmount($order->tax_value) // invoice tax amount
    // TODO :: Support others tags
])->render();


?>


<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js" integrity="sha512-BNaRQnYJYiPSqHHDb58B0yaPfCu+Wgds8Gp/gU33kqBtgNS4tSPHuGibyoeqMV/TJlSKda6FXzoEyYGjTe+vXA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/1.5.3/jspdf.min.js"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.3/html2pdf.bundle.min.js" integrity="sha512-YcsIPGdhPK4P/uRW6/sruonlYj+Q7UHWeKfTAkBW+g83NKM+jMJFJ4iAPfSnVp7BKD4dKMHmVSvICUbE/V1sSw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js "></script>-->
<!--<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>-->
<script src="
https://cdn.jsdelivr.net/npm/jquery@3.7.0/dist/jquery.min.js
"></script>
<!--<script type="text/javascript" src="https://www.take-my-order.com/js/epos-2.24.0.js"></script>-->
<!--<script type="text/javascript">-->

<!--  var ePosDev = new epson.ePOSDevice();-->
<!--  var ePosDisplay = new epson.ePOSDevice();-->

<!--	function connectLineDisplay(){-->
<!--    var ip = '192.168.8.152'-->
<!--    var port = '8008'-->
<!--    ePosDisplay.connect(ip, port, display_callback_connect)-->
<!--  }-->
<!--  function display_callback_connect(resultConnect) {-->
<!--    var options = {crypto: false, buffer: false};-->
<!--    var deviceID = 'local_display';-->

<!--    if (resultConnect === 'OK' || resultConnect === 'SSL_CONNECT_OK') {-->
<!--      ePosDisplay.createDevice(deviceID, ePosDisplay.DEVICE_TYPE_DISPLAY, options, callback_createDisplayDevice);-->
<!--    } else {-->
<!--      alert(resultConnect);-->
<!--    }-->
<!--  }-->
<!--  var display = null;-->
<!--  function callback_createDisplayDevice (deviceObj, errorCode) {-->
<!--    if (deviceObj === null) {-->
<!--      alert(`error: ${errorCode}`)-->
<!--      return;-->
<!--    }-->
<!--    display = deviceObj;-->
<!--    alert('connected');-->

<!--    display.onreceive = function (response) {-->
<!--      if (response.success) {-->

<!--      } else {-->
<!--        alert(`Display Error: ${response.code}`);-->
<!--      }-->
<!--    }-->
<!--    display.startMonitor();-->
<!--  }-->

<!--	function connect(){-->
<!--    var ip = '192.168.8.152'-->
<!--    var port = '8008'-->
<!--    ePosDev.connect(ip, port, callback_connect)-->
<!--  }-->
<!--  function callback_connect(resultConnect) {-->
<!--    var options = {crypto: false, buffer: false};-->
<!--    var deviceID = 'local_printer';-->

<!--    if (resultConnect === 'OK') {-->
<!--      ePosDev.createDevice(deviceID, ePosDev.DEVICE_TYPE_PRINTER, options, callback_createDevice);-->
<!--    } else {-->
<!--      alert(resultConnect);-->
<!--    }-->
<!--  }-->
<!--  var printer = null;-->
<!--  function callback_createDevice(deviceObj, errorCode) {-->
<!--    printer = deviceObj;-->
<!--    alert('connected')-->

<!--    printer.onreceive = function (response) {-->
<!--      console.log(response)-->
<!--      if (response.success) {-->
<!--        alert('Print Complete')-->
<!--        printer.addPulse(printer.DRAWER_1, printer.PULSE_100);-->
         <!--printer.send();-->
<!--      } else {-->
<!--        alert('Error', response.code)-->
<!--      }-->
<!--    };-->

<!--    printer.oncoveropen = function (status) {-->
<!--      alert('cover open');-->
<!--    };-->
<!--    printer.startMonitor();-->
<!--  }-->

<!--  function setText(text, [reverse, underline, bold], size, align = 'ALIGN_LEFT') {-->
    <!--// var reverse = false;-->
    <!--// var underline = false;-->
    <!--// var bold = true;-->
<!--    var color = printer.COLOR_1;-->
<!--    var height = size;-->
<!--    var width = size;-->
<!--    printer.addTextStyle(reverse, underline, bold, color);-->
<!--    printer.addTextSize(height, width);-->
    <!--printer.addTextAlign(printer[align]); // Set text alignment to be at center-->
<!--    printer.addTextSmooth(true);-->
    <!--// printer.addTextAlign(printer.ALIGN_RIGHT); // Set text alignment to be at right-->
    <!--// printer.addTextAlign(printer.ALIGN_LEFT); // Set text alignment to be at left-->
    <!--printer.addText(text); // Add the text "Hello World"-->
<!--  }-->

<!--  function setBarcode() {-->
<!--    var data = '10000000103';-->
<!--    var barcodeType = printer.BARCODE_CODE39;-->
<!--    var width = 2;-->
<!--    var height = 32;-->
<!--    printer.addBarcode(data, barcodeType, printer.HR_BELOW, printer.FONT_A, width, height)-->
<!--  }-->

<!--  function setQRcode() {-->
<!--    printer.addSymbol('https://google.com', printer.SYMBOL_QRCODE_MODEL_2, printer.LEVEL_M, 8, 8, 0);-->
<!--  }-->

<!--  function setLogo() {-->
<!--    printer.addLogo(34, 34);-->
<!--  }-->

<!--//   function createProductSection(text){-->
<!--//                   		connect();-->
<!--// $( window ).on( "load", function() {-->
<!--//     setText(text, [false, true, false], 2, 'ALIGN_CENTER');-->
<!--//         printer.addFeed();-->
<!--//         printer.send();-->
<!--//       });-->
      
<!--//   }-->

<!--	function createData(){-->
    <!--// setLogo();-->
<!--    setText('تجربه عبدالصبور', [true, false, false], 3, 'ALIGN_CENTER');-->
<!--    printer.addFeedLine(3);-->
<!--    setText('Order Number : ', [false, false, false], 1);-->
<!--    printer.addTextPosition(180)-->
<!--    setText(<?php echo "$order->id"; ?>, [false, false, false], 1);-->
<!--    printer.addFeed();-->
<!--        setText('Time : ', [false, false, false], 1);-->
<!--    printer.addTextPosition(180)-->
<!--    setText(new Date("<?php echo date("F d, Y G:i:s",strtotime($order->created_at)) ?>"), [false, false, false], 1);-->
<!--    printer.addFeed();-->
<!--    setText('Products', [false, true, false], 2, 'ALIGN_CENTER');-->
<!--    printer.addFeedLine(3);-->
<!--    setBarcode();-->
<!--    printer.addFeed();-->
    <!--// setQRcode();-->
    <!--// printer.addFeed();-->

    <!--// printer.addTextLang('th');-->
    <!--// printer.addText('สวัสดี\n');-->
<!--    setText('Tax # : 311216140200003', [false, false, false], 1);-->

    <!--printer.addFeedLine(2); // Add 5 lines feed to printer command buffer-->
    <!--printer.addCut(); // Add cut command-->
<!--    printer.send();-->
<!--	}-->

<!--  function showDisplayData () {-->
    <!--display.reset(); // Reset/Clear line display buffer-->
    <!--display.addText('Hello LineDisplay Ak'); // Add the text-->

<!--    if (ePosDisplay.isConnected) {-->
      <!--display.send(); // send the command to line display-->
<!--    }-->
<!--  }-->

<!--//initialisation-->
<!--$(document).ready(function(){-->
<!--	$("#connectBtn").click(function(){-->
<!--		connect();-->
<!--	});-->
<!--	$("#printBtn").click(function(){-->
<!--		createData();-->
<!--	});-->
<!--	$("#connectDisplay").click(function(){-->
<!--		connectLineDisplay();-->
<!--	});-->
<!--	$("#showDisplay").click(function(){-->
<!--		showDisplayData();-->
<!--	});-->
<!--});-->

<!--</script>-->
    <canvas id="canvas" width="360" height="512"></canvas>

<script type="text/javascript" src="https://touchpaysa.com/assets/epos-print-4.1.0.js"></script>
<script type="text/javascript">
<!--


    // draw text
    function drawText(text) {
        // get context of canvas
        var canvas = document.getElementById('canvas');
        if (canvas.getContext) {
            var context = canvas.getContext('2d');
            // font
            var bold = true;
            var italic = true;
            var caps = true;
            var size = 30;
            var font = "'Arial', sans-serif";
            context.font =
                (italic ? 'italic ' : 'normal ') + (caps ? 'small-caps ' : 'normal ') +
                (bold ? 'bold ' : 'normal ') + size + 'px ' + font;
            // text-align
            var textalign = 'start';
            context.textAlign = textalign;
            // text-baseline
            var textbase = 'alphabetic';
            context.textBaseline = textbase;
            // fill text
            var x = 0;
            var y = 54;
            // var text = document.getElementById('text').value;
            console.log('texxt',text)
            context.fillText('ss', x, y);
            // line feed
            var line = 30;
            // console.log(context)

        }
    }

    // clear text
    function clearText() {
        // get context of canvas
        var canvas = document.getElementById('canvas');
        if (canvas.getContext) {
            var context = canvas.getContext('2d');
            // clear canvas
            context.clearRect(0, 0, canvas.width, canvas.height);
        }
    }
    // print canvas
    function printCanvas() {
        
        // drawText(text);
        // create print object
        var address = 'http://192.168.8.153/cgi-bin/epos/service.cgi?devid=local_printer&timeout=60000';
        var epos = new epson.CanvasPrint(address);
        // register callback function
        epos.onreceive = function (res) {
            // show message
            alert('Print ' + (res.success ? 'Success' : 'Failure') + '\nCode: ' + res.code + '\n' + getStatusText(epos, res.status));
        }
        // register callback function
        epos.onerror = function (err) {
            // show message
            alert('HTTP Status Code: ' + err.status + '\n' + err.responseText);
        }
        // print
        // var mode = document.getElementById('mode').value;
        // var brightness = document.getElementById('brightness').value;
        // var halftone = document.getElementById('halftone').value;
        // var align = document.getElementById('align').value;
        // var color = document.getElementById('color').value;
        // var cut = document.getElementById('cut').checked;
        var canvas = document.getElementById('canvas');
        // var context = canvas.getContext('2d');


        epos.mode = epos['MODE_MONO'];
        epos.brightness = 1.0;
        epos.halftone = epos['HALFTONE_THRESHOLD'];
        epos.align = epos['ALIGN_LEFT'];
        epos.color = epos['COLOR_1'];
        epos.cut = true;
        console.log(epos)
        epos.print(canvas);
        // console.log(canvas)
    }

    // get status text
    function getStatusText(e, status) {
        var s = 'Status: \n';
        if (status & e.ASB_NO_RESPONSE) {
            s += ' No printer response\n';
        }
        if (status & e.ASB_PRINT_SUCCESS) {
            s += ' Print complete\n';
        }
        if (status & e.ASB_DRAWER_KICK) {
            s += ' Status of the drawer kick number 3 connector pin = "H"\n';
        }
        if (status & e.ASB_BATTERY_OFFLINE) {
            s += ' Offline due to a weak battery (only for supported models)\n';
        }
        if (status & e.ASB_OFF_LINE) {
            s += ' Offline status\n';
        }
        if (status & e.ASB_COVER_OPEN) {
            s += ' Cover is open\n';
        }
        if (status & e.ASB_PAPER_FEED) {
            s += ' Paper feed switch is feeding paper\n';
        }
        if (status & e.ASB_WAIT_ON_LINE) {
            s += ' Waiting for online recovery\n';
        }
        if (status & e.ASB_PANEL_SWITCH) {
            s += ' Panel switch is ON\n';
        }
        if (status & e.ASB_MECHANICAL_ERR) {
            s += ' Mechanical error generated\n';
        }
        if (status & e.ASB_AUTOCUTTER_ERR) {
            s += ' Auto cutter error generated\n';
        }
        if (status & e.ASB_UNRECOVER_ERR) {
            s += ' Unrecoverable error generated\n';
        }
        if (status & e.ASB_AUTORECOVER_ERR) {
            s += ' Auto recovery error generated\n';
        }
        if (status & e.ASB_RECEIPT_NEAR_END) {
            s += ' No paper in the roll paper near end detector\n';
        }
        if (status & e.ASB_RECEIPT_END) {
            s += ' No paper in the roll paper end detector\n';
        }
        if (status & e.ASB_BUZZER) {
            s += ' Sounding the buzzer (only for supported models)\n';
        }
        if (status & e.ASB_WAIT_REMOVE_LABEL) {
            s += ' Waiting to remove label (only for supported models)\n';
        }
        if (status & e.ASB_NO_LABEL) {
            s += ' No paper in the label peeling detector (only for supported models)\n';
        }
        if (status & e.ASB_SPOOLER_IS_STOPPED) {
            s += ' Stop the spooler\n';
        }
        return s;
    }

//-->
</script>

<div dir="rtl" class="w-full h-full">
    <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
        <div class="flex items-center justify-center">
            <h5 class="text-sm">فاتورة ضريبة مبسطة <br></h5>
            <button onclick="button1_Click()">Print</button>

        </div>
        <div class="flex items-center justify-center">
            @if ( empty( ns()->option->get( 'ns_invoice_receipt_logo' ) ) )
            <h3 class="text-3xl font-bold">{{ ns()->option->get( 'ns_store_name' ) }}</h3>
            @else
            <img width="150" height="100" src="{{ ns()->option->get( 'ns_invoice_receipt_logo' ) }}" alt="{{ ns()->option->get( 'ns_store_name' ) }}">
            @endif
        </div>

        <div class="text-3xl text-center font-bold">
        {{$order->id}} <br>
        </div>
        <div class="text-center">
        وقت الطلب : {{$order->created_at}}
        </div>
        <div class="p-2 border-b border-gray-700">
            <div class="flex flex-wrap -mx-2 text-sm">
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_a', $order ) ) !!}
                </div>
                <div class="px-2 w-1/2">
                    {!! nl2br( $ordersService->orderTemplateMapping( 'ns_invoice_receipt_column_b', $order ) ) !!}
                </div>
            </div>
        </div>
        <div class="table w-full">
            <table class="w-full">
            <thead>

  <script type="text/javascript">
          drawText('products');
  </script>
                    <tr class="font-semibold">
                        <td colspan="3" class="border-b border-gray-800 text-center text-sm">المنتجات</td>
                    </tr>
                </thead>
                <thead>
                    <tr class="font-semibold">
                        <td colspan="2" class="p-1 border-b border-gray-800 text-sm">{{ __( 'Product' ) }}</td>
                        <td class="p-1 border-b border-gray-800 text-right text-sm">{{ __( 'Total' ) }}</td>
                    </tr>
                </thead>
                <tbody class="text-sm">
                    @foreach( Hook::filter( 'ns-receipt-products', $order->combinedProducts ) as $product )
                    <tr>
                        <td colspan="2" class="p-0 border-b border-gray-700 text-xs">
                            <?php $productName  =   View::make( 'pages.dashboard.orders.templates._product-name', compact( 'product' ) );?>
                            <?php echo Hook::filter( 'ns-receipt-product-name', $productName->render(), $product );?>
                        </td>
                        
                        <td class="p-0 border-b border-gray-800 text-right text-xs">{{ ns()->currency->define( $product->total_price ) }}</td>
                    </tr>
                    @endforeach
                </tbody>
                <tbody>
                <thead >
                    <tr class="font-semibold">
                        <td colspan="3" class="p-1 border-b border-gray-800 text-center text-sm">الدفع</td>
                    </tr>
                </thead>
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Sub Total' ) }}</td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->subtotal ) }}</td>
                    </tr>
                    @if ( $order->discount > 0 )
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">
                            <span>{{ __( 'Discount' ) }}</span>
                            @if ( $order->discount_type === 'percentage' )
                            <span>({{ $order->discount_percentage }}%)</span>
                            @endif
                        </td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->discount ) }}</td>
                    </tr>
                    @endif
                    @if ( $order->total_coupons > 0 )
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">
                            <span>{{ __( 'Coupons' ) }}</span>
                        </td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->total_coupons ) }}</td>
                    </tr>
                    @endif
                    @if ( ns()->option->get( 'ns_invoice_display_tax_breakdown' ) === 'yes' ) 
                        @foreach( $order->taxes as $tax )
                        <tr>
                            <td colspan="2" class="p-2 border-b border-gray-800 text-xs">
                                <span>{{ $tax->tax_name }}</span>
                            </td>
                            <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $tax->tax_value ) }}</td>
                        </tr>
                        @endforeach
                    @else                     
                        @if ( $order->tax_value > 0 )
                        <tr>
                            <td colspan="2" class="p-2 border-b border-gray-800 text-xs">
                                <span>{{ __( 'Taxes' ) }}</span>
                            </td>
                            <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->tax_value ) }}</td>
                        </tr>
                        @endif
                    @endif
                    @if ( $order->shipping > 0 )
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Shipping' ) }}</td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->shipping ) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Total' ) }}</td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->total ) }}</td>
                    </tr>
                    @foreach( $order->payments as $payment )
                    <tr>
                        <td class="p-2 border-b border-gray-800 text-xs" colspan="2">{{ $paymentTypes[ $payment[ 'identifier' ] ] ?? __( 'Unknown Payment' ) }}</td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $payment[ 'value' ] ) }}</td>
                    </tr>
                    @endforeach
                    <tr>
                        <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Paid' ) }}</td>
                        <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->tendered ) }}</td>
                    </tr>
                    @if ( in_array( $order->payment_status, [ 'refunded', 'partially_refunded' ]) )
                        @foreach( $order->refund as $refund )
                        <tr>
                            <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Refunded' ) }}</td>
                            <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( - $refund->total ) }}</td>
                        </tr>
                        @endforeach
                    @endif
                    @switch( $order->payment_status )
                        @case( Order::PAYMENT_PAID )
                        <tr>
                            <td colspan="2" class="p-2 border-b border-gray-800 text-xs">{{ __( 'Change' ) }}</td>
                            <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( $order->change ) }}</td>
                        </tr>
                        @break
                        @case( Order::PAYMENT_PARTIALLY )
                        <tr>
                            <td colspan="2" class="p-2 border-b border-gray-800 text-xs font-semibold">{{ __( 'Due' ) }}</td>
                            <td class="p-2 border-b border-gray-800 text-xs text-right">{{ ns()->currency->define( abs( $order->change ) ) }}</td>
                        </tr>
                        @break
                    @endswitch
                </tbody>
            </table>

            <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
                <div class="flex justify-center h-32">
                    <img  src="{{$displayQRCodeAsBase64}}">
                </div>
                <div class="pt-6 pb-4 text-center text-gray-800 text-sm">
                    <p>الرقم الضريبي: 311216140200003 </p>
                </div>
            </div>

            @if( $order->note_visibility === 'visible' )
            <div class="pt-6 pb-4 text-center text-gray-800 text-sm">
                <strong>{{ __( 'Note: ' ) }}</strong> {{ $order->note }}
            </div>
            @endif
            <div class="pt-6 pb-4 text-center text-gray-800 text-sm">
                {{ ns()->option->get( 'ns_invoice_receipt_footer' ) }}
            </div>
            <button id="connectBtn">connect</button>
<!--<button id="printBtn">basic print</button>-->
<!--<br><br>-->
<!--<button id="connectDisplay">connect display</button>-->
<!--<button id="showDisplay">show Display</button>-->

        </div>
    </div>
</div>

<!--@includeWhen( request()->query( 'autoprint' ) === 'true', '/pages/dashboard/orders/templates/_autoprint' )-->
<p><button id='button' onclick="printCanvas()">Print PDF</button></p>

<!--@includeWhen( request()->query( 'autoprint' ) === 'true', '/pages/dashboard/orders/templates/_autoprint' )-->