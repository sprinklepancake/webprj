<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History - Fitness Equipment Store</title>

    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main.css">

    <style>
        .transaction-container {
            background-color: var(--black);
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            margin-top: 2rem;
        }

        .page-title {
            color: var(--primary-dark);
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 2rem;
        }

        .transaction-table th,
        .transaction-table td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid #373535;
        }

        .transaction-table th {
            background-color: var(--secondary-dark);
            color: var(--text);
            font-weight: bold;
            text-transform: uppercase;
        }

        .transaction-table tr:nth-child(even) {
            background-color: #312b2b;
        }

        .transaction-table tr:hover {
            background-color: #423e3e;
        }

        .status {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 4px;
            font-weight: bold;
        }

        .status.completed {
            background-color: var(--dark-green);
            color: var(--white);
        }

        .status.in-progress {
            background-color: #ffc107;
            color: #000;
        }

        .status.pending {
            background-color: #6c757d;
            color: var(--white);
        }

        @media (max-width: 768px) {
            .transaction-container {
                padding: 1rem;
            }

            .transaction-table th,
            .transaction-table td {
                padding: 0.75rem;
            }
        }
        td {
            color: var(--text);
        }
        
        .loading-spinner {
            display: none;
            text-align: center;
            padding: 2rem;
        }
    </style>
</head>
<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="transaction-container">
            <h2 class="page-title">Transaction History</h2>
            <div class="loading-spinner">
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table transaction-table">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Amount</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-body">
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>
    
    <script>
$(document).ready(function() {
    $.ajax({
        url: '../handlers/transaction_handler.php',
        type: 'GET',
        dataType: 'json',
        success: function(response) {
            if (response.success) {
                const tbody = $('.transaction-table tbody');
                tbody.empty();
                
                if (response.transactions.length === 0) {
                    tbody.append(`
                        <tr>
                            <td colspan="4" class="text-center">No transactions found</td>
                        </tr>
                    `);
                    return;
                }

                response.transactions.forEach(transaction => {
                    const date = new Date(transaction.order_date).toLocaleDateString();
                    tbody.append(`
                        <tr>
                            <td>${date}</td>
                            <td>${transaction.product}</td>
                            <td>$${parseFloat(transaction.amount).toFixed(2)}</td>
                            <td><span class="status completed">${transaction.status}</span></td>
                        </tr>
                    `);
                });
            } else {
                console.error('Server response error:', response.message);
                alert(response.message || 'Error loading transactions');
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', {xhr, status, error});
            if (xhr.status === 401) {
                window.location.href = 'login.php'; //redirect to login if not authenticated
            } else {
                alert('Error connecting to server. Please try again later.');
            }
        }
    });
});
</script>
</body>
</html>