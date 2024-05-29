<?php
require_once('../tcpdf/tcpdf.php');
require_once('../db.php'); // Include your database connection

if (isset($_GET['order_id'])) {
    $orderId = $_GET['order_id'];

    // Fetch order details from the database
    $query = "SELECT oi.*, e.enterprise_name, e.enterprise_phone, e.enterprise_address, p.product_name, p.product_price, p.product_image, 
                     o.order_status, o.payment_method, o.created_order, o.updated_order AS received_order, o.tracking_number, c.customer_name, c.customer_phone, c.customer_address 
              FROM `order_item` oi 
              JOIN product p ON oi.product_id = p.product_id 
              JOIN enterprise e ON p.enterprise_id = e.enterprise_id 
              JOIN `order` o ON oi.order_id = o.order_id 
              JOIN customers c ON o.customer_id = c.customer_id 
              WHERE oi.order_id = $orderId";
    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        // Fetch the first result for general order details
        $orderData = $result->fetch_assoc();

        // Create new PDF document
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        // Set document information
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('MusicTIMeS');
        $pdf->SetTitle('MusicTIMeS Invoice');
        $pdf->SetSubject('Invoice for Order #' . $orderId);

        // Add a page
        $pdf->AddPage();

        // Add logo
        $logoPath = '../image/logo.png';
        if (file_exists($logoPath)) {
            $pdf->Image($logoPath, 10, 10, 30, '', 'PNG');
        }

        // Initialize the HTML variable
        $html = '';
        
        $html .= '<style>
                    .table th, .table td {
                        border: 1px solid #dee2e6;
                    }
                    </style>';

        $html .= '<h1 style="text-align:center;">MusicTIMeS</h1>';
        $html .= '<h2 style="text-align:center;">Invoice for Order #' . $orderId . '</h2>';
        $html .= '<table border="0" cellpadding="4">';
        $html .= '<tr><td><strong>Invoice Number:</strong></td><td>' . $orderId . '</td></tr>';
        $html .= '<tr><td><strong>Order Date:</strong></td><td>' . date('d/m/Y h:i A', strtotime($orderData['created_order'])) . '</td></tr>';
        $html .= '<tr><td><strong>Receive Order Date:</strong></td><td>' . date('d/m/Y h:i A', strtotime($orderData['received_order'])) . '</td></tr>';
        $html .= '<tr><td><strong>Tracking Number:</strong></td><td>' . $orderData['tracking_number'] . '</td></tr>';
        $orderStatus = $orderData['order_status'];
        $statusStyle = ($orderStatus === 'Complete') ? 'color: green; font-weight: bold;' : 'font-weight: bold;';
        $html .= '<tr><td><strong>Order Status:</strong></td><td><span style="' . $statusStyle . '">' . $orderStatus . '</span></td></tr>';
        $html .= '<tr><td><strong>Payment Method:</strong></td><td>Credit/Debit card</td></tr>';
        $html .= '</table>';

        $html .= '<h3>Customer Information</h3>';
        $html .= '<table border="0" cellpadding="4">';
        $html .= '<tr><td><strong>Name:</strong></td><td>' . $orderData['customer_name'] . '</td></tr>';
        $html .= '<tr><td><strong>Phone:</strong></td><td>' . $orderData['customer_phone'] . '</td></tr>';
        $html .= '<tr><td><strong>Address:</strong></td><td>' . $orderData['customer_address'] . '</td></tr>';
        $html .= '</table>';

        // Collect all involved enterprises
        $enterprises = [];
        $result->data_seek(0); // Reset result pointer
        while ($data = $result->fetch_assoc()) {
            $enterprises[$data['enterprise_name']] = [
                'phone' => $data['enterprise_phone'],
                'address' => $data['enterprise_address']
            ];
        }

        // Display all involved enterprises
        $html .= '<h3>Enterprise Information</h3>';
        $html .= '<table border="0" cellpadding="4">';
        foreach ($enterprises as $name => $details) {
            $html .= '<tr><td><strong>Name:</strong></td><td>' . $name . '</td></tr>';
            $html .= '<tr><td><strong>Phone:</strong></td><td>' . $details['phone'] . '</td></tr>';
            $html .= '<tr><td><strong>Address:</strong></td><td>' . $details['address'] . '</td></tr>';
            $html .= '<tr><td colspan="2"><hr></td></tr>'; // Add a separator between enterprises
        }
        $html .= '</table>';

        $html .= '<h3>Order Details</h3>';
        $html .= '<table class="table">';
        $html .= '<thead>
                    <tr>
                        <th style="text-align: center; font-weight: bold; background-color: black; color: white;">Enterprise</th>
                        <th style="text-align: center; font-weight: bold; background-color: black; color: white;">Product Image</th>
                        <th style="text-align: center; font-weight: bold; background-color: black; color: white;">Product Name</th>
                        <th style="text-align: center; font-weight: bold; background-color: black; color: white;">Quantity</th>
                        <th style="text-align: center; font-weight: bold; background-color: black; color: white;">Price (RM)</th>
                    </tr>
                  </thead>';
        $html .= '<tbody>';

        // Reset result pointer and iterate over result again for order details
        $result->data_seek(0);
        $totalPrice = 0;
        while ($data = $result->fetch_assoc()) {
            $price = $data['order_quantity'] * $data['product_price'];
            $totalPrice += $price;
            $html .= '<tr>
                        <td style="text-align: center;">' . $data['enterprise_name'] . '</td>
                        <td style="text-align: center;"><img src="../image/product/' . $data['product_image'] . '" width="50" height="50"></td>
                        <td style="text-align: center;">' . $data['product_name'] . '</td>
                        <td style="text-align: center; font-weight: bold;">' . $data['order_quantity'] . '</td>
                        <td style="text-align: center; font-weight: bold;">' . number_format($price, 2) . '</td>
                      </tr>';
        }

        $html .= '<tr>
                    <td colspan="4" style="text-align: center;"><strong>Total (RM)</strong></td>
                    <td style="text-align: center; font-weight: bold;">' . number_format($totalPrice, 2) . '</td>
                  </tr>';
        $html .= '</tbody></table>';

        $html .= '<p style="text-align:center;">Thank you for your purchase on <strong>MusicTIMeS</strong>!</p>';

        // Print text using writeHTMLCell()
        $pdf->writeHTML($html, true, false, true, false, '');

        // Close and output PDF document
        $pdf->Output('invoice_' . $orderId . '.pdf', 'D');
    } else {
        echo 'No order details found.';
    }
} else {
    echo 'Invalid order ID.';
}
?>
