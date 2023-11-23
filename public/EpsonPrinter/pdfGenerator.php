<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
    require_once __DIR__.'/vendor/autoload.php';
    require_once('./TCPDF/tcpdf.php');
    
    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
    use Endroid\QrCode\RoundBlockSizeMode\RoundBlockSizeModeMargin;
    use Endroid\QrCode\Writer\PngWriter;
    use Salla\ZATCA\GenerateQrCode;
    use Salla\ZATCA\Tags\InvoiceDate;
    use Salla\ZATCA\Tags\InvoiceTaxAmount;
    use Salla\ZATCA\Tags\InvoiceTotalAmount;
    use Salla\ZATCA\Tags\Seller;
    use Salla\ZATCA\Tags\TaxNumber;
    
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $inputJSON = file_get_contents('php://input');
        $input = json_decode($inputJSON, true);

        // Extracting store details
        $storeName = $input['store']['name'];
        $storeLogo = $input['store']['logo'];

        // Extracting order details
        $orderId = $input['order']['id'];
        $orderCreatedAt = $input['order']['createdAt'];
        // Split into date and time
        list($orderDate, $orderTime) = explode(' ', $orderCreatedAt);
        $products = $input['order']['products'];  // This is an array

        $subTotal = $input['order']['subTotal'];
        $discountValue = $input['order']['discount']['value'];
        $discountType = $input['order']['discount']['type'];
        $discountPercentage = $input['order']['discount']['percentage'];

        $coupons = $input['order']['coupons'];

        // Looping through taxes
        $taxes = $input['order']['taxes'];
        foreach ($taxes as $tax) {
            $taxName = isset($tax['name']) ? $tax['name'] : null;
            $taxValue = $tax['value'];

            // Process or store each tax detail as needed
        }

        $shipping = $input['order']['shipping'];
        $total = $input['order']['total'];

        // Looping through payments
        $payments = $input['order']['payments'];
        

        $tendered = $input['order']['tendered'];

        // Looping through refunds
        $refunds = $input['order']['refunds'];
        foreach ($refunds as $refund) {
            $refundValue = $refund['value'];

            // Process or store each refund detail as needed
        }

        $change = $input['order']['change'];
        $paymentStatus = $input['order']['paymentStatus'];

        $taxReceiptNumber = $input['taxReceiptNumber'];
        $invoiceFooter = $input['invoiceFooter'];
        
        $totalHeight = 170 + 50;
        
        $tempPdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);

        foreach ($products as $product) {
            $productName = $product['name'];
			$modifiers = $product['modifiers'];
            foreach($modifiers as $modifier){
                $productName.= $modifier['modifier'];
            }
            $productQuantity = $product['quantity'];
            $productTotalPrice = $product['totalPrice'];

            $n1 = $tempPdf->getStringHeight(44, $productName);
            $n2 = $tempPdf->getStringHeight(44, $productQuantity);
            $n3 = $tempPdf->getStringHeight(44, $productTotalPrice);

            // Get the maximum height
            $maxh = max($n1, $n2, $n3);

            // Add the maximum height for this product to totalHeight, also consider some padding or margin if needed
            $totalHeight += $maxh; // 10 is an arbitrary number for padding/margin
        }
        
        foreach ($payments as $payment) {
            $paymentType = $payment['type'];
            $paymentValue = $payment['value'];
            $totalHeight +=6;
        }
        
        foreach ($taxes as $tax) {
            $taxName = isset($tax['name']) ? $tax['name'] : null;
            $taxValue = $tax['value'];
            $totalHeight +=6;
        }
        
    
        $pdf = new TCPDF('P', 'mm', array(140, $totalHeight), true, 'UTF-8', false);
        $pdf->AddPage();

        // Get the width of the page
        $pageWidth = $pdf->GetPageWidth();

        // Specify the width of the image
        $imageWidth = 50; 

        // Calculate the X position of the image to center it horizontally
        $x = ($pageWidth - $imageWidth) / 2;

        // Specify the Y position of the image
        $y = 10; // example Y position in mm

        // Add the image to the PDF
        $pdf->Image($storeLogo, $x, $y, $imageWidth);

        $lg = Array();
        $lg['a_meta_charset'] = 'UTF-8';
        $lg['a_meta_dir'] = 'rtl';
        $lg['a_meta_language'] = 'fa';
        $lg['w_page'] = 'page';

        // set some language-dependent strings (optional)
        $pdf->setLanguageArray($lg);

        $pdf->setRTL(true);

        $pdf->setAutoPageBreak(false);

        $pdf->Ln();
        $pdf->SetFont('aealarabiya', '', 16);
        $pdf->setY(50);

        $pageWidth = $pdf->GetPageWidth();
        $cellWidth = 70; // combined width of your two cells
        $x = ($pageWidth - $cellWidth) / 2;

        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', "الرقم"), 'TB', 'C', false, 0, $x, '', true);
        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', $orderId), 'TB', 'C', false, 1, '', '', true);


        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', "التاريخ"), '', 'C', false, 0, $x, '', true);
        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', $orderDate), '', 'C', false, 1, '', '', true);


        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', "الوقت"), 'TB', 'C', false, 0, $x, '', true);
        $pdf->MultiCell(35, 6, iconv('UTF-8', 'UTF-8', $orderTime), 'TB', 'C', false, 1, '', '', true);

        $pdf->Ln(10);

        $x = ($pageWidth - 132) / 2;

        $pdf->MultiCell(132, 6, iconv('UTF-8', 'UTF-8', "المنتجات"), 'T', 'C', false, 1, $x, '', true);

        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', "المنتج"), 'TB', 'C', false, 0, $x, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', "عدد"), 'TB', 'C', false, 0, $x+44, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', "المجموع"), 'TB', 'C', false, 1, $x+88, '', true);
        
        foreach ($products as $product) {
            $productName = $product['name'];
			$modifiers = $product['modifiers'];
            foreach($modifiers as $modifier){
                $productName.= $modifier['modifier'];
            }
            $productQuanitity = $product['quantity'];
            $productTotalPrice = $product['totalPrice'];
            
            addNewProduct($pdf,$productName, $productQuanitity,$productTotalPrice, $x,$totalHeight);
        }
        
        $pdf->Ln(10);

        $pdf->SetFont('aealarabiya', '', 16);
        
        //Total
        $pdf->MultiCell(132, 6, iconv('UTF-8', 'UTF-8', "الدفع"), 'TB', 'C', false, 1, $x, '', true);

        $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "المجموع الفرعي"), '', 'R', false, 0, $x, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $subTotal), '', 'C', false, 1, $x+88, '', true);
        
        $totalTax = 0.0;;

        foreach ($taxes as $tax) {
            $taxName = isset($tax['name']) ? $tax['name'] : null;
            $taxValue = $tax['value'];
            $totalHeight +=6;
            $totalModified = strtr($taxValue,',','.');
            $totalTax += floatval($totalModified);
            $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "الضرائب"), '', 'R', false, 0, $x, '', true);
            $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $taxValue), '', 'C', false, 1, $x+88, '', true);
        }
        
        $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "المجموع"), '', 'R', false, 0, $x, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $total), '', 'C', false, 1, $x+88, '', true);

        foreach ($payments as $payment) {
            $paymentType = $payment['type'];
            $paymentValue = $payment['value'];
            
            if($paymentType == "Cash" || $paymentType == "كاش"){
                $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "كاش"), '', 'R', false, 0, $x, '', true);
                $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $paymentValue), '', 'C', false, 1, $x+88, '', true);
            }
            
        }
        
        $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "مدفوع"), '', 'R', false, 0, $x, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $tendered), '', 'C', false, 1, $x+88, '', true);

        $pdf->MultiCell(88, 6, iconv('UTF-8', 'UTF-8', "الصرف"), '', 'R', false, 0, $x, '', true);
        $pdf->MultiCell(44, 6, iconv('UTF-8', 'UTF-8', $change), '', 'C', false, 1, $x+88, '', true);

        $pdf->Ln(5);

        $y = $pdf->GetY();
		
        $tender2 = str_replace(" SR", "",$tendered);
        $tenderModified = strtr($tender2,',','.');
        
        $displayQRCodeAsBase64 = GenerateQrCode::fromArray([
            new Seller('مؤسسة ريان بن خالد بن سعود آل منقاش للوجبات السريعة'), // seller name        
            new TaxNumber('311216140200003'), // seller tax number
            new InvoiceDate($orderCreatedAt), // invoice date as Zulu ISO8601 @see https://en.wikipedia.org/wiki/ISO_8601
            new InvoiceTotalAmount($tenderModified), // invoice total amount
            new InvoiceTaxAmount($totalTax) // invoice tax amount
        ])->render();

        addQRCode($pdf, $displayQRCodeAsBase64 ,$x,$y);

        $pdf->MultiCell(132, 6, iconv('UTF-8', 'UTF-8', "الرقم الضرببي $taxReceiptNumber"), '', 'C', false, 0, $x+(0.5*$x), $pdf->GetY()+55, true);

        // Save the PDF to a file
        $pdfFile = '/home/touchpaysa/public_html/mansense5/public/EpsonPrinter/temp//' . time() . 'temp.pdf';

        $pdf->Output($pdfFile, 'F');

        // Return the PDF file URL as JSON
        $pdfFileUrl = 'http://mansense5.touchpaysa.com/EpsonPrinter/temp/' . basename($pdfFile); // adjust the URL accordingly
        echo json_encode(['pdfFileUrl' => $pdfFileUrl]);
        
    }
    
    function addNewProduct($pdf,$t1,$t2,$t3,$x,$totalHeight){
        $pdf->SetFont('aealarabiya', '', 14);
        
        // Calculate the heights
        $n1 = $pdf->getStringHeight(44, $t1);
        $n2 = $pdf->getStringHeight(44, $t2);
        $n3 = $pdf->getStringHeight(44, $t3);
        // Get the maximum height
        $maxh = max($n1, $n2, $n3);
        
        $pdf->MultiCell(44, $maxh, iconv('UTF-8', 'UTF-8', $t1), 'B', 'C', false, 0, $x, '', true);
        $pdf->MultiCell(44, $maxh, iconv('UTF-8', 'UTF-8', $t2), 'B', 'C', false, 0, $x+44, '', true);
        $pdf->MultiCell(44, $maxh, iconv('UTF-8', 'UTF-8', $t3), 'B', 'C', false, 0, $x+88, '', true);

        $pdf->Ln();
        
    }
    
    function addQRCode($pdf, $displayQRCodeAsBase64, $x,$y) {
        // Decode the Base64 image directly
        $base64Image = str_replace('data:image/png;base64,', '', $displayQRCodeAsBase64);
        // Save the decoded image as a temporary PNG file
        $filename = './temp/' . time() . 'tempqrcode.png';
        file_put_contents($filename, base64_decode($base64Image));
        //file_put_contents($filename, base64_decode($displayQRCodeAsBase64));

        $image = imagecreatefrompng($filename);
        $background = imagecolorallocate($image, 255, 255, 255);
        imagefill($image, 0, 0, $background);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        imagepng($image, $filename);

        // Add the QR code image to the TCPDF document at the specified position
        $middle = $pdf->GetPageWidth()/2;
        // Add QRCode image to PDF
        $pdf->Image($filename, $middle+25, $y, 50, 50, 'PNG');

        // Clean up by deleting the temporary QR code image from the server
        unlink($filename);

    }
