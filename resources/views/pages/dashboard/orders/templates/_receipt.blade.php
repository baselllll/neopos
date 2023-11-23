<?php
use App\Models\Order;
use App\Classes\Hook;
use Illuminate\Support\Facades\View;

?>
<div class="w-full h-full">
    <div class="w-full md:w-1/2 lg:w-1/3 shadow-lg bg-white p-2 mx-auto">
        <canvas id="pdfCanvas" willReadFrequently="true"></canvas>
        <button id="printButton">Print</button>
        <!--<script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.10.377/pdf.min.js"></script>-->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.10.111/pdf.min.js"></script>
        <script src="http://mansense5.touchpaysa.com/EpsonPrinter/epos-print-4.1.0.js"></script> <!-- Adjust the path to the ePOS JS SDK -->
        <script>
            const jsonData = {
                "store": {
                  "name": "{{ ns()->option->get('ns_store_name') }}",
                  "logo": "{{ ns()->option->get('ns_invoice_receipt_logo') }}"
                },
                "order": {
                  "id": "{{ $order->id }}",
                  "createdAt": "{{ $order->created_at }}",
                  "products": [
                    @foreach($order->products as $product)
                    {   
                      <?php $orderProduct = Modules\NsGastro\Models\OrderProduct::with('modifiers')->find($product->id);?>
                      "name": "{{ $orderProduct->name }} (x{{ $orderProduct->quantity }}) ",
                      "modifiers": [ 
                        @if( ! empty( $orderProduct->modifiers ) )
                          @foreach( $orderProduct->modifiers as $modifier )
                          {
                            "modifier":"\n{{$modifier->name}}(x {{$modifier->quantity}}) - {{ns()->currency->define( $modifier->total_price )}}"
                          },
                          @endforeach
                        @endif
                      ],
                      "totalPrice": "{{ ns()->currency->define($product->total_price) }}",
                      "quantity":"(x{{ $product->quantity }})"
                    },
                    @endforeach
                  ],
                  "subTotal": "{{ ns()->currency->define($order->subtotal) }}",
                  "discount": {
                    "value": "{{ $order->discount }}",
                    "type": "{{ $order->discount_type }}",
                    "percentage": "{{ $order->discount_percentage }}"
                  },
                  "coupons": "{{ ns()->currency->define($order->total_coupons) }}",
                  "taxes": [
                    @if(ns()->option->get('ns_invoice_display_tax_breakdown') === 'yes')
                    @foreach($order->taxes as $tax)
                    {
                      "name": "{{ $tax->tax_name }}",
                      "value": "{{ ns()->currency->define($tax->tax_value) }}"
                    },
                    @endforeach
                    @else
                    {
                      "value": "{{ ns()->currency->define($order->tax_value) }}"
                    },
                    @endif
                  ],
                  "shipping": "{{ ns()->currency->define($order->shipping) }}",
                  "total": "{{ ns()->currency->define($order->total) }}",
                  "payments": [
                    @foreach($order->payments as $payment)
                    {
                      "type": "{{ $paymentTypes[$payment['identifier']] ?? __('Unknown Payment') }}",
                      "value": "{{ ns()->currency->define($payment['value']) }}"
                    },
                    @endforeach
                  ],
                  "tendered": "{{ ns()->currency->define($order->tendered) }}",
                  "refunds": [
                    @if(in_array($order->payment_status, ['refunded', 'partially_refunded']))
                    @foreach($order->refund as $refund)
                    {
                      "value": "{{ ns()->currency->define(-$refund->total) }}"
                    },
                    @endforeach
                    @endif
                  ],
                  "change": "{{ ns()->currency->define($order->change) }}",
                  "paymentStatus": "{{ $order->payment_status }}"
                },
                "taxReceiptNumber": "311216140200003",
                "invoiceFooter": "{{ ns()->option->get('ns_invoice_receipt_footer') }}",
            }

              // After constructing jsonData, you can send it wherever you need.


            document.addEventListener("DOMContentLoaded", function() {
                // Fetch the PDF file URL from the index.php
                fetch('http://mansense5.touchpaysa.com/EpsonPrinter/pdfGenerator.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(jsonData)
                })
                .then(response => response.json())
                .then(data => {
                    var pdfFileUrl = data.pdfFileUrl;

                    // Load PDF using PDF.js
                    var pdfjsLib = window['pdfjs-dist/build/pdf'];
                    var pdfCanvas = document.getElementById('pdfCanvas');
                    var context = pdfCanvas.getContext('2d');

                    pdfjsLib.getDocument(pdfFileUrl).promise.then(function(pdfDoc_) {
                        pdfDoc = pdfDoc_;
                        var pageNumber = 1;
                        pdfDoc.getPage(pageNumber).then(function(page) {
                            console.log("Rendering");
                            var viewport = page.getViewport({ scale: 1.5 });
                            pdfCanvas.height = viewport.height;
                            pdfCanvas.width = viewport.width;
                            var renderContext = {
                                canvasContext: context,
                                viewport: viewport,
								intent: "print"
                            };
                            var renderTask = page.render(renderContext);
                            // Chain another then to the renderTask.promise
                            renderTask.promise.then(function() {
                                @includeWhen( request()->query( 'autoprint' ) === 'true', '/pages/dashboard/orders/templates/_customautoprint' );
                                // Send a POST request to delete the temp file after rendering
                                fetch('http://mansense5.touchpaysa.com/EpsonPrinter/deleteTempFile.php', {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({ pdfFileUrl: pdfFileUrl })
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        console.log("Temp file deleted successfully.");
                                    } else {
                                        console.error("Error deleting temp file:", data.message);
                                    }
                                })
                                .catch(error => console.error('Error:', error));
                            }).catch(function(error) {
                                console.error("Render task failed:", error);
                            });;
                        });
                    });
                    

                    // Send for printing using ePOS JS SDK
                    document.getElementById('printButton').onclick = function () {
                        printUsingEposSDK();
                    };
                })
            });
            
            function printUsingEposSDK() {
                var address = 'http://192.168.8.119/cgi-bin/epos/service.cgi?devid=local_printer&timeout=60000';
                var epos = new epson.CanvasPrint(address);

                epos.onreceive = function (res) { alert(res.success); };
                epos.onerror = function (err) { alert(err.status); };
                epos.oncoveropen = function () { alert('coveropen'); };
                epos.cut = true;
                epos.mode = epos.MODE_MONO;

                epos.print(pdfCanvas);
				console.log("sent for printing");
            }
            
        </script>
    </div>
</div>