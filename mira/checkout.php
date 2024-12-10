<!--hasan-->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout - Fitness Equipment Store</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../main.css">
    <style>
        .shape {
            position: absolute;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            animation: float 15s infinite ease-in-out;
        }
        .shape:nth-child(1) { width: 80px; height: 80px; top: 10%; left: 10%; animation-delay: 0s; }
        .shape:nth-child(2) { width: 60px; height: 60px; top: 20%; right: 15%; animation-delay: 2s; }
        .shape:nth-child(3) { width: 100px; height: 100px; bottom: 15%; left: 20%; animation-delay: 4s; }
        .shape:nth-child(4) { width: 50px; height: 50px; bottom: 10%; right: 10%; animation-delay: 6s; }

        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            50% { transform: translateY(-80px) rotate(180deg); }
        }

        .checkout-container {
            background-color: var(--black);
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
            color: var(--text);
        }
        .checkout-container h2, .checkout-container h3 {
            color: var(--primary);
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .btn-checkout {
            width: 100%;
            padding: 0.75rem;
            font-weight: bold;
            background-color: var(--primary);
            color: var(--black);
            border: none;
            transition: background-color 0.3s ease;
        }
        .btn-checkout:hover {
            background-color: var(--primary-dark);
        }
        .required-field::after {
            content: '*';
            color: var(--price);
            margin-left: 4px;
        }
        .form-control {
            background-color: var(--greyishblue);
            color: var(--text);
            border: 1px solid var(--primary-dark);
        }
        .form-control:focus {
            background-color: var(--greyishblue);
            color: var(--text);
            border-color: var(--primary);
            box-shadow: 0 0 0 0.2rem rgba(255, 107, 53, 0.25);
        }
        .form-control::placeholder {
            color: var(--text-light);
        }
        #confirmationPage {
            display: none;
        }
        .confirmation-message {
            text-align: center;
            font-size: 1.2rem;
            margin-bottom: 2rem;
        }
        .payment-fields {
            display: none;
        }
    </style>
</head>

<body class="body-backg">
    <div id="header"></div>

    <main class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <form id="checkout-form" class="checkout-container">
                    <h2>Checkout</h2>
                    
                    <section class="mb-4">
                        <h3>Your Location</h3>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country" class="required-field">Country</label>
                                    <select class="form-control" id="country" name="country" required>
                                        <option value="">Select a country</option>
                                        <option value="Lebanon">Lebanon</option>
                                        <option value="United Arab Emirates">United Arab Emirates</option>
                                        <option value="Saudi Arabia">Saudi Arabia</option>
                                        <option value="Qatar">Qatar</option>
                                        <option value="Kuwait">Kuwait</option>
                                        <option value="Bahrain">Bahrain</option>
                                        <option value="Oman">Oman</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="region" class="required-field">Region/State</label>
                                    <input type="text" class="form-control" id="region" name="region" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="street" class="required-field">Street Address</label>
                                    <input type="text" class="form-control" id="street" name="street" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="building" class="required-field">Building/Apartment</label>
                                    <input type="text" class="form-control" id="building" name="building" required>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Payment Method Section -->
                    <section class="mb-4">
                        <h3>Payment Method</h3>
                        <div class="form-group">
                            <label for="payment-method" class="required-field">Choose a payment method</label>
                            <select class="form-control" id="payment-method" name="payment_method" required>
                                <option value="">Select payment method</option>
                                <option value="credit-card">Credit Card</option>
                                <option value="paypal">PayPal</option>
                                <option value="bank-transfer">Bank Transfer</option>
                                <option value="payment-on-delivery">Payment on Delivery</option>
                            </select>
                        </div>

                        <!-- Credit Card Fields -->
                        <div id="credit-card-fields" class="payment-fields">
                            <div class="form-group">
                                <label for="card-number" class="required-field">Card Number</label>
                                <input type="text" class="form-control" id="card-number" name="card_number" placeholder="1234 5678 9012 3456">
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="expiry-date" class="required-field">Expiry Date</label>
                                        <input type="text" class="form-control" id="expiry-date" name="expiry_date" placeholder="MM/YY">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cvv" class="required-field">CVV</label>
                                        <input type="text" class="form-control" id="cvv" name="cvv" placeholder="123">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- PayPal Fields -->
                        <div id="paypal-fields" class="payment-fields">
                            <div class="form-group">
                                <label for="paypal-email" class="required-field">PayPal Email</label>
                                <input type="email" class="form-control" id="paypal-email" name="paypal_email" placeholder="your@email.com">
                            </div>
                        </div>

                        <!-- Bank Transfer Fields -->
                        <div id="bank-transfer-fields" class="payment-fields">
                            <div class="form-group">
                                <label for="bank-name" class="required-field">Bank Name</label>
                                <input type="text" class="form-control" id="bank-name" name="bank_name">
                            </div>
                            <div class="form-group">
                                <label for="account-number" class="required-field">Account Number</label>
                                <input type="text" class="form-control" id="account-number" name="account_number">
                            </div>
                        </div>

                        <!-- Payment on Delivery Fields -->
                        <div id="payment-on-delivery-fields" class="payment-fields">
                            <p>No additional information required. You will pay when your order is delivered.</p>
                        </div>
                    </section>

                    <div id="error-message" class="alert alert-danger d-none"></div>
                    <button type="submit" class="btn btn-checkout">Proceed with Shipping</button>
                </form>

                <!-- fake confirmation page -->
                <div id="confirmationPage" class="checkout-container">
                    <h2>Order Confirmation</h2>
                    <div class="confirmation-message">
                        <p>Thank you for your order!</p>
                        <p>Your order has been successfully placed and is now being processed.</p>
                        <p>Order number: <strong id="orderNumber"></strong></p>
                        <p>Estimated delivery date: <strong id="deliveryDate"></strong></p>
                    </div>
                    <button onclick="window.location.href='../petra/shop.php'" class="btn btn-checkout">Continue Shopping</button>
                </div>
            </div>
        </div>
    </main>

    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>
    <div class="shape"></div>

    <div id="footer"></div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.2/dist/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="../js/main.js"></script>

    <script>
    document.getElementById('payment-method').addEventListener('change', function() {
        const paymentFields = document.querySelectorAll('.payment-fields');
        paymentFields.forEach(field => field.style.display = 'none');

        const selectedMethod = this.value;
        if (selectedMethod) {
            document.getElementById(`${selectedMethod}-fields`).style.display = 'block';
        }
    });

    document.getElementById('checkout-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        //basic form validation
        let isValid = true;
        const requiredFields = this.querySelectorAll('[required]');
        requiredFields.forEach(field => {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        });

        if (!isValid) {
            document.getElementById('error-message').textContent = 'Please fill in all required fields.';
            document.getElementById('error-message').classList.remove('d-none');
            return;
        }

        //simulate successful order placement (fake)
        document.getElementById('checkout-form').style.display = 'none';
        document.getElementById('confirmationPage').style.display = 'block';

        //generate fake order number and delivery date
        const orderNumber = Math.floor(100000 + Math.random() * 900000);
        const deliveryDate = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toLocaleDateString();

        document.getElementById('orderNumber').textContent = orderNumber;
        document.getElementById('deliveryDate').textContent = deliveryDate;
    });
    </script>
</body>
</html>

