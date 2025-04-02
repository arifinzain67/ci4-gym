<!DOCTYPE html>
<html>

<head>
    <title>Struk Transaksi</title>
    <style>
    @media print {
        .no-print {
            display: none;
        }

        body {
            font-family: monospace;
            font-size: 12px;
            margin: 0;
            padding: 10px;
        }

        .receipt {
            width: 80mm;
            margin: 0 auto;
        }

        .text-center {
            text-align: center;
        }

        .divider {
            border-top: 1px dashed #000;
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 3px;
        }
    }

    /* Style untuk tampilan di layar */
    body {
        font-family: monospace;
        font-size: 12px;
        margin: 0;
        padding: 10px;
    }

    .receipt {
        width: 80mm;
        margin: 0 auto;
        background: #fff;
        padding: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .text-center {
        text-align: center;
    }

    .divider {
        border-top: 1px dashed #000;
        margin: 5px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th,
    td {
        padding: 3px;
    }

    .btn {
        display: inline-block;
        padding: 8px 15px;
        margin: 10px 5px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        font-weight: bold;
    }

    .btn-print {
        background: #007bff;
        color: white;
    }

    .btn-close {
        background: #6c757d;
        color: white;
    }
    </style>
</head>

<body>
    <div class="receipt">
        <div class="text-center">
            <h2 style="margin: 0;">REVIVE GYM</h2>
            <p>Jl. Raya Kedungjaya No. 123, Kedungjaya<br>
                Telp: (021) 1234567<br>
                Email: info@revivegym.com</p>
        </div>
        <div class="divider"></div>
        <div>
            <p>No. Transaksi: <?= $transaction['id_transaction'] ?><br>
                Tanggal: <?= date('d/m/Y H:i', strtotime($transaction['payment_date'])) ?><br>
                Kasir: Admin<br>
                Metode Bayar: <?= ucfirst($transaction['payment_type']) ?></p>
        </div>
        <div class="divider"></div>
        <table>
            <tr>
                <th align="left">Item</th>
                <th align="center">Qty</th>
                <th align="right">Harga</th>
                <th align="right">Total</th>
            </tr>
            <tr>
                <td><?= $transaction['package_name'] ?></td>
                <td align="center">1</td>
                <td align="right">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
                <td align="right">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
            </tr>
        </table>
        <div class="divider"></div>
        <table>
            <tr>
                <td colspan="3" align="right">Total:</td>
                <td align="right">Rp <?= number_format($transaction['amount'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="3" align="right">Bayar:</td>
                <td align="right">Rp <?= number_format($transaction['amount_paid'], 0, ',', '.') ?></td>
            </tr>
            <tr>
                <td colspan="3" align="right">Kembali:</td>
                <td align="right">Rp
                    <?= number_format($transaction['amount_paid'] - $transaction['amount'], 0, ',', '.') ?></td>
            </tr>
        </table>
        <div class="divider"></div>
        <div class="text-center">
            <p>Terima kasih atas kunjungan Anda<br>
                Silakan datang kembali<br>
                www.gymcenter.com</p>
        </div>
    </div>
    <div class="text-center no-print">
        <button class="btn btn-print" onclick="window.print()">Cetak Struk</button>
        <button class="btn btn-close" onclick="window.close()">Tutup</button>
    </div>
</body>

</html>