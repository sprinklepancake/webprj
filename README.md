# Fitness Equipment Store - Project Changes

## Back-end Changes

### 1. Registration Handling
- Implemented a new `register_handler.php` file to handle user registration.
- Added input validation for required fields, email format, username format, and password length.
- Implemented checks to ensure uniqueness of username and email.
- Hashed the user password before storing it in the database.
- Set session variables upon successful registration and redirected the user to the landing page.

### 2. Login Functionality
- Created a `login_handler.php` file to handle user login.
- Implemented input validation for username and password.
- Checked if the user exists in the database and verified the password using the stored hash.
- Set session variables upon successful login and redirected the user to the landing page.

### 3. Logout Functionality
- Created a `logout_handler.php` file to handle user logout.
- Cleared all session variables and destroyed the user's session.
- Redirected the user to the login page after a successful logout.

### 4. Profile Page
- Implemented a `profile.php` file to display the user's account information, order history, and wishlist.
- Fetched the user's details from the database and displayed them in the profile page.
- Queried the `ORDERS` and `WISHLIST` tables to retrieve the user's order history and wishlist items, respectively.
- Displayed the order details and wishlist items in the profile page.

### 5. Wishlist Management
- Created a `remove_from_favorites.php` file to handle the removal of items from the user's wishlist.
- Implemented a check to ensure the user is logged in before allowing the removal of an item.
- Verified that the item exists in the user's wishlist before deleting it.
- Returned a JSON response with the success status and a message.

### 6. Error Handling and Logging
- Added error reporting and logging throughout the PHP code to help with debugging and troubleshooting.
- Implemented appropriate HTTP response codes (400, 500, etc.) and error messages for various scenarios.

### 7. Database Design
- Designed a database schema with the following tables:
  - `USERS`: Stores user registration information, including username, email, password, role, and contact details.
  - `ITEMS`: Stores information about the fitness equipment items, including name, description, price, and image.
  - `ORDERS`: Stores order details, such as order ID, user ID, total price, and order status.
  - `WISHLIST`: Stores the items added to a user's wishlist, linking the user ID and item ID.
  - `ITEM_IN_WISHLIST`: A junction table that connects the `WISHLIST` and `ITEMS` tables.

### 8. Shop Functionality
- Implemented a `shop.php` file to display the list of available fitness equipment items.
- Allowed users to filter and sort the items based on various criteria, such as price, category, and availability.
- Provided a search functionality to help users find specific items.

### 9. Checkout Process
- Created a `cart.php` file to manage the user's shopping cart.
- Implemented the ability to add items to the cart, update quantities, and remove items.
- Developed a `checkout.php` file to handle the checkout process, including order summary, payment information, and order placement.
- Stored the order details in the `ORDERS` table, including the total price, user ID, and order status.

## Front-end Changes

### 1. Registration Page
- Created a `register.php` file to handle the user registration form.
- Added HTML structure, form fields, and client-side validation for the registration form.
- Implemented AJAX-based form submission to the `register_handler.php` file.
- Displayed error messages and successful registration redirects based on the server response.

### 2. Login Page
- Created a `login.php` file to handle the user login form.
- Added HTML structure, form fields, and client-side validation for the login form.
- Implemented AJAX-based form submission to the `login_handler.php` file.
- Displayed error messages and successful login redirects based on the server response.

### 3. Profile Page
- Updated the `profile.php` file to display the user's account information, order history, and wishlist.
- Added HTML structure and styling for the profile page sections.
- Implemented AJAX-based removal of items from the user's wishlist, using the `remove_from_favorites.php` file.
- Updated the UI to reflect the changes in the user's wishlist.

### 4. Shop Page
- Created a `shop.php` file to display the list of available fitness equipment items.
- Implemented filtering and sorting options, as well as a search functionality.
- Added the ability to add items to the user's shopping cart.

### 5. Cart and Checkout Pages
- Developed a `cart.php` file to manage the user's shopping cart.
- Allowed users to update quantities and remove items from the cart.
- Implemented a `checkout.php` file to handle the checkout process, including order summary, payment information, and order placement.
- Updated the order details in the `ORDERS` table upon successful checkout.

### 6. Styling and Layout
- Updated the overall styling and layout of the website using CSS, including the use of a consistent color scheme, typography, and responsive design.
- Incorporated the use of the Bootstrap library for improved UI elements and layout.

## Online Resources Used

1. [PHP Manual](https://www.php.net/manual/en/index.php) - For reference on various PHP functions, language constructs, and best practices.
2. [MySQL Documentation](https://dev.mysql.com/doc/) - For guidance on SQL syntax, database management, and table operations.
3. [jQuery Documentation](https://api.jquery.com/) - For reference on the jQuery library, specifically the AJAX-related functions.
4. [Bootstrap Documentation](https://getbootstrap.com/docs/4.5/getting-started/introduction/) - For guidance on using the Bootstrap CSS framework and its UI components.
5. [MDN Web Docs](https://developer.mozilla.org/en-US/) - For reference on HTML, CSS, and JavaScript best practices and language features.