# 🔐 Solid API - WordPress CRUD API with Authentication

A complete CRUD API built with SOLID architecture principles and WordPress REST API authentication.

## 🚀 Features

- ✅ SOLID Architecture (Single Responsibility, Open/Closed, Liskov Substitution, Interface Segregation, Dependency Inversion)
- ✅ Complete CRUD Operations (Create, Read, Update, Delete)
- ✅ WordPress Native Authentication (Nonce-based)
- ✅ Permission-based Access Control
- ✅ Clean Code Structure with Interfaces and Abstract Classes
- ✅ Database Migration System

## 📁 Project Structure

```
solid-api/
├── database/
│   └── Migration.php              # Database table creation
├── src/
│   ├── Abstracts/
│   │   ├── AbstractController.php # Base controller
│   │   ├── AbstractRepository.php # Base repository
│   │   └── AbstractService.php    # Base service
│   ├── Controllers/
│   │   └── StudentBookController.php
│   ├── Interfaces/
│   │   ├── RepositoryInterface.php
│   │   └── ServiceInterface.php
│   ├── Middleware/
│   │   └── AuthMiddleware.php     # Authentication & Authorization
│   ├── Models/
│   │   └── StudentBook.php
│   ├── Repositories/
│   │   └── StudentBookRepository.php
│   └── Services/
│       └── StudentBookService.php
├── examples/
│   └── api-usage.html             # JavaScript examples
└── solid-api.php                  # Main plugin file
```

## 🔧 Installation

1. Copy the `solid-api` folder to `wp-content/plugins/`
2. Activate the plugin from WordPress Admin → Plugins
3. The database table will be created automatically

## 🌐 API Endpoints

### 📖 GET All Books (Public - No Authentication)
```
GET /wp-json/solid-api/v1/student-books
```

### ➕ CREATE Book (Authentication Required)
```
POST /wp-json/solid-api/v1/student-books
Headers: X-WP-Nonce: {nonce}
Body: {
    "student_name": "John Doe",
    "book_title": "PHP Programming",
    "isbn": "978-1234567890",
    "borrowed_date": "2026-01-07",
    "return_date": "2026-02-07"
}
```

### ✏️ UPDATE Book (Authentication Required)
```
PUT /wp-json/solid-api/v1/student-books/{id}
Headers: X-WP-Nonce: {nonce}
Body: {
    "student_name": "Updated Name",
    "book_title": "Updated Title"
}
```

### 🗑️ DELETE Book (Authentication Required)
```
DELETE /wp-json/solid-api/v1/student-books/{id}
Headers: X-WP-Nonce: {nonce}
```

## 🔐 Authentication Methods

### Method 1: WordPress Nonce (Recommended for same-site requests)

```javascript
// Get nonce in WordPress
const wpNonce = '<?php echo wp_create_nonce('wp_rest'); ?>';

// Use in fetch request
fetch('/wp-json/solid-api/v1/student-books', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-WP-Nonce': wpNonce
    },
    credentials: 'include',
    body: JSON.stringify(data)
});
```

### Method 2: Postman with Cookie Authentication

1. Login to WordPress (`/wp-login.php`)
2. Go to Postman
3. Enable "Save cookies" in Postman settings
4. GET request to any WordPress page to get cookies
5. Use cookies for authenticated requests

### Method 3: Application Password (WordPress 5.6+)

1. Go to WordPress Admin → Users → Profile
2. Scroll to "Application Passwords"
3. Create new application password
4. Use Basic Auth in Postman:
   - Username: your-wordpress-username
   - Password: generated-application-password

## 👥 Permission Levels

### Public Access:
- ✅ GET all books (anyone can view)

### Authenticated Access (Login Required):
- ✅ CREATE book (must be logged in + Editor/Admin role)
- ✅ UPDATE book (must be logged in + Editor/Admin role)
- ✅ DELETE book (must be logged in + Editor/Admin role)

## 🧪 Testing with Postman

### Step 1: Get WordPress Cookies
```
GET http://localhost/wp-atlas/wp-login.php
Save cookies in Postman
```

### Step 2: Login
```
POST http://localhost/wp-atlas/wp-login.php
Body (form-data):
- log: your-username
- pwd: your-password
```

### Step 3: Test CREATE
```
POST http://localhost/wp-atlas/wp-json/solid-api/v1/student-books
Headers:
- Content-Type: application/json
Body (raw JSON):
{
    "student_name": "Test User",
    "book_title": "Test Book"
}
```

## 🛡️ Security Features

1. **Nonce Verification** - Prevents CSRF attacks
2. **Permission Checks** - Role-based access control
3. **User Authentication** - Must be logged in for write operations
4. **Data Validation** - Input sanitization and validation
5. **WordPress Standards** - Follows WordPress REST API best practices

## 📝 Response Format

### Success Response:
```json
{
    "success": true,
    "data": {
        "id": 1,
        "student_name": "John Doe",
        "book_title": "PHP Programming"
    },
    "message": "Record created successfully"
}
```

### Error Response (Unauthorized):
```json
{
    "code": "rest_forbidden",
    "message": "You must be logged in.",
    "data": {
        "status": 401
    }
}
```

### Error Response (No Permission):
```json
{
    "code": "rest_forbidden",
    "message": "You do not have permission to manage student books.",
    "data": {
        "status": 403
    }
}
```

## 🎓 SOLID Principles Applied

1. **Single Responsibility**: Each class has one job
   - Controller: Handle HTTP requests
   - Service: Business logic & validation
   - Repository: Database operations

2. **Open/Closed**: Extend via abstract classes without modifying base code

3. **Liskov Substitution**: All implementations follow interface contracts

4. **Interface Segregation**: Separate interfaces for Repository and Service

5. **Dependency Inversion**: Dependencies injected through constructors

## 📚 Learning Resources

- [WordPress REST API Handbook](https://developer.wordpress.org/rest-api/)
- [Authentication Methods](https://developer.wordpress.org/rest-api/using-the-rest-api/authentication/)
- [SOLID Principles](https://en.wikipedia.org/wiki/SOLID)

## 🤝 Contributing

This is a learning project. Feel free to fork and experiment!

## 📄 License

GPL v2 or later

---

Made with ❤️ for learning WordPress API development with SOLID principles
