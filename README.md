# BookStore+: A PHP-Based Product Catalog System

A dynamic product catalog system inspired by National Book Store, built with PHP and MySQL. This project provides a complete solution for managing books and school supplies with a user-friendly interface and admin panel.

## Features

### User Side
- Browse products by category
- View product details
- Contact form for inquiries
- Responsive design for all devices

### Admin Side
- Secure admin login
- Dashboard with statistics
- Product management (CRUD)
- Category management (CRUD)
- Contact message management

## Requirements

- PHP 7.4 or higher
- MySQL 5.7 or higher
- Apache web server
- XAMPP (recommended for local development)

## Installation

1. Clone the repository to your XAMPP htdocs directory:
   ```
   git clone https://github.com/yourusername/bookstore-plus.git
   ```

2. Create a new MySQL database named `bookstore_plus`

3. Import the database schema:
   - The database and tables will be automatically created when you first access the website
   - Default admin credentials:
     - Username: admin
     - Password: admin123

4. Configure your web server:
   - Point your web server to the project directory
   - Ensure the `uploads` directory is writable

5. Access the website:
   - Frontend: `http://localhost/national-book-store/bookstore-plus`
   - Admin Panel: `http://localhost/national-book-store/bookstore-plus/admin`

## Directory Structure

```
bookstore-plus/
├── admin/              # Admin panel files
├── assets/            # Static assets
├── config/            # Configuration files
├── css/               # Stylesheets
├── includes/          # Common PHP includes
├── js/                # JavaScript files
├── uploads/           # Uploaded files
└── index.php          # Main entry point
```

## Security Features

- Password hashing for admin accounts
- SQL injection prevention
- XSS protection
- File upload validation
- Session management

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

## Acknowledgments

- Bootstrap for the frontend framework
- Font Awesome for icons
- National Book Store for inspiration

## Support

For support, email support@bookstoreplus.com or create an issue in the repository. 