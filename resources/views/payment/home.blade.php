<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
</head>
<body>
    <h1>Welcome to Our Shop!</h1>

    <!-- Checkout button -->
    <form action="{{ url('checkout') }}" method="GET">
        <button type="submit">Checkout with Stripe</button>
    </form>
</body>
</html>
