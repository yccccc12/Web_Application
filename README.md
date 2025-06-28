# UECS2094 Assignment Group 1
## Project Title: Fashion Retail E-commerce (Clothing)

A comprehensive e-commerce platform for fashion retail built with PHP, MySQL, and modern web technologies. This application provides a complete shopping experience with user authentication, product management, shopping cart functionality, and order processing.

## ✨ Features
- **User Authentication**: Login and registration system
- **Product Catalog**: Browse and filter clothing items by category, size, and color
- **Shopping Cart**: Add, update, and remove items from cart
- **Order Management**: Complete order history and tracking
- **Payment Processing**: Secure payment integration
- **User Profile**: Personal information management and statistics
- **Responsive Design**: Mobile-friendly interface
- **Interactive Charts**: Purchase statistics and analytics

## 📁 Project Structure
```
Web_Application/
├── about-us/          # Company information pages
├── cart/              # Shopping cart functionality
├── classes/           # PHP classes (Database, User, Product, Order)
├── img/               # Product and site images
├── includes/          # Common components (header, footer)
├── js/                # JavaScript files
├── payment/           # Payment processing
├── product/           # Product listing and details
├── profile/           # User profile and statistics
├── style/             # CSS stylesheets
├── user/              # Authentication (login, signup)
└── database.sql       # Database schema
```

## 🚀 Getting Started

### 📋 Prerequisites
- **WampServer**: Download and install from [WampServer](https://www.wampserver.com/)
- **PHP**: Version 7.4 or higher
- **MySQL**: Version 5.7 or higher
- **Apache**: Version 2.4 or higher
- **Modern Web Browser**: Chrome, Firefox, Safari, or Edge

### 💾 Installation

1. **Clone the Repository**
   ```bash
   git clone https://github.com/yccccc12/Web_Application.git
   ```
   Or download and extract the project files directly to your WampServer `www` directory.

2. **Navigate to Project Directory**
   ```bash
   cd /c:/wamp64/www/Web_Application
   ```

### ⚙️ Configuration

1. **Start WampServer Services**
   - Launch WampServer
   - Ensure all services (Apache, MySQL, PHP) are running (green icon)

2. **Database Setup**
   - Open your browser and go to `http://localhost/phpmyadmin`
   - Create a new database or use the existing one
   - Import the database schema:
     - Click on "Import" tab
     - Choose the `database.sql` file from the project root
     - Click "Go" to execute the import

3. **Database Configuration** (if needed)
   - Update database connection settings in `classes/database.php`
   - Ensure the database credentials match your local setup

### ▶️ Running the Application

1. **Access the Application**
   ```
   http://localhost/Web_Application
   ```

2. **Test User Accounts** (if provided in database)
   - Check the imported database for test user credentials
   - Or create a new account using the registration form

## 📖 Usage Guide

### 👤 For Customers
1. **Browse Products**: Visit the homepage to view featured products
2. **Product Search**: Use filters to find specific items by category, size, or color
3. **Add to Cart**: Select product variants and add items to your shopping cart
4. **Checkout**: Review cart and proceed to payment
5. **Order Tracking**: View order history in your profile

### 👨‍💻 For Development
- **Adding Products**: Use the admin interface or database directly
- **Styling**: Modify CSS files in the `style/` directory
- **Functionality**: Update PHP classes in the `classes/` directory

## 🔗 API Endpoints

### 📦 Product Management
- `GET /product/get_products.php` - Retrieve product list
- `GET /product/product_details.php?id={id}` - Get product details

### 🛒 Cart Operations
- `POST /cart/add_to_cart.php` - Add item to cart
- `POST /cart/update_cart.php` - Update cart quantities

### 👥 User Operations
- `POST /user/login.php` - User authentication
- `POST /user/signUp.php` - User registration

## 🛠️ Technologies Used

- **Backend**: PHP, MySQL
- **Frontend**: HTML5, CSS3, JavaScript
- **Libraries**: 
  - Chart.js (for statistics visualization)
  - RemixIcon (for icons)
  - Google Fonts (Figtree, Inter)
- **Server**: Apache (via WampServer)

## 📝 Development Notes

- The application uses session-based authentication
- Product images are stored in the file system with database references
- Shopping cart data is session-based
- Charts and statistics are generated using Chart.js

## ⚠️ Important Notes

- **Security**: This is an educational project. For production use, implement additional security measures
- **Internet Connection**: Required for external libraries (Google Fonts, RemixIcon, Chart.js)
- **Browser Compatibility**: Tested on modern browsers; may require polyfills for older versions
- **Development**: Restart WampServer after making configuration changes

## 📄 License

This project is created for educational purposes as part of UECS2094 course assignment.