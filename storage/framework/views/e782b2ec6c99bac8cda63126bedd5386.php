<!DOCTYPE html>
<html lang="hu">
<head>
    
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            margin: 0;
            padding: 0;
            width: 100%;
            box-sizing: border-box;
        }
        .invoice-container {
            margin: auto;
            border: 1px solid #ddd;
        }
        .header {
            text-align: right;
            padding: 8px;
        }
        .customer-details {
            background-color: #eee;
            padding: 10px;
        }
        .table-frame {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 8px;
            text-align: right;
        }
        th {
            background-color: #f4f4f4;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="invoice-container">

        
        <table style="width: 100%; margin-bottom: 5px; border-collapse: collapse;">
            <tr>
                <td style="text-align: left; vertical-align: middle; width: 70%;">
                    <h2>Marianna Present Kft.</h2>
                    <p>2118 Dány, Dózsa utca 25.</p>
                    <p>Phone: +36-20-842-0324</p>
                </td>
                <td style="text-align: right; vertical-align: middle; width: 30%;">
                    <img src="<?php echo e(public_path('images/marianna-present-logo-bw.png')); ?>" width="250">
                </td>
            </tr>
        </table>

        
        <div class="header">
            <h2>Invoice</h2>
            <p><strong>Invoice ID:</strong> <?php echo e($order->ref_code); ?></p>
            <p><strong>Date:</strong> <?php echo e(date('Y-m-d')); ?></p>
        </div>

        
        <div class="customer-details">
            <p><strong>Customer details:</strong></p>
            <p><?php echo e($order->name); ?></p>
            <p><?php echo e($order->billing_address); ?></p>
            <p><?php echo e($order->phone); ?></p>
            <p><?php echo e($order->email); ?></p>
        </div>

        
        <table class="table-frame">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($item->item->name); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td><?php echo e(intval($item->item->price * (1 - $item->item->discount / 100) / 5) * 5); ?> Ft</td>
                        <td><?php echo e((intval($item->item->price * (1 - $item->item->discount / 100) / 5) * 5) * $item->quantity); ?> Ft</td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        
        <p class="header"><strong>Total: <?php echo e($order->total_price); ?> Ft</strong></p>

        <div class="footer">
            <p>Thank you for your purchase!</p>
        </div>
    </div>
</body>
</html>
<?php /**PATH C:\Users\teme3\OneDrive\Herd\marianna-present\resources\views\invoice.blade.php ENDPATH**/ ?>