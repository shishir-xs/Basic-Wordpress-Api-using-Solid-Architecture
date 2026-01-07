# 🚀 Postman দিয়ে API Test করার সম্পূর্ণ গাইড

## ⚠️ গুরুত্বপূর্ণ: সাধারণ Username/Password কাজ করবে না!

WordPress REST API সরাসরি username/password দিয়ে Basic Auth support করে না। আপনাকে **Application Password** ব্যবহার করতে হবে।

---

## 🔧 Localhost এ Application Password Enable করুন

### সমস্যা: HTTPS Required Error
যদি এই error দেখেন:
```
The application password feature requires HTTPS, which is not enabled on this site.
```

### ✅ সমাধান: wp-config.php এ যোগ করুন

`wp-config.php` file এ এই line যোগ করুন (আমি ইতিমধ্যে করে দিয়েছি):

```php
// Enable Application Passwords on localhost
define( 'WP_ENVIRONMENT_TYPE', 'local' );
```

এটি `/* That's all, stop editing! Happy publishing. */` এর আগে add করতে হবে।

**অথবা** এই alternatives ব্যবহার করতে পারেন:

```php
// Option 1: Set environment as development
define( 'WP_ENVIRONMENT_TYPE', 'development' );

// Option 2: Force allow Application Passwords
add_filter( 'wp_is_application_passwords_available', '__return_true' );
```

---

## ✅ Method 1: Application Password (সবচেয়ে সহজ - Recommended)

### Step 1: Application Password তৈরি করুন

1. WordPress Admin এ Login করুন: `http://localhost/wp-atlas/wp-admin`
2. **Users → Profile** এ যান (বা আপনার নিজের profile)
3. নিচে scroll করুন **"Application Passwords"** section এ
4. **Name:** `Postman API` (যেকোনো নাম দিতে পারেন)
5. **"Add New Application Password"** বাটন ক্লিক করুন
6. Generated password **কপি করুন** (দেখতে এরকম: `xxxx xxxx xxxx xxxx xxxx xxxx`)

### Step 2: Postman এ ব্যবহার করুন

#### GET Request (No Auth):
```
Method: GET
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books

Authorization: No Auth (GET public হওয়ায়)
```

#### POST Request (With Application Password):
```
Method: POST
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books

Authorization:
├── Type: Basic Auth
├── Username: your-wordpress-username (যেমন: admin)
└── Password: xxxx xxxx xxxx xxxx xxxx xxxx (Application Password - spaces সহ)

Headers:
└── Content-Type: application/json

Body (raw - JSON):
{
    "student_name": "আব্দুল করিম",
    "book_title": "PHP Programming",
    "isbn": "978-1234567890",
    "borrowed_date": "2026-01-07",
    "return_date": "2026-02-07"
}
```

#### PUT Request (Update):
```
Method: PUT
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books/1

Authorization:
├── Type: Basic Auth
├── Username: admin
└── Password: xxxx xxxx xxxx xxxx xxxx xxxx

Headers:
└── Content-Type: application/json

Body (raw - JSON):
{
    "student_name": "নতুন নাম",
    "book_title": "নতুন বইয়ের নাম"
}
```

#### DELETE Request:
```
Method: DELETE
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books/1

Authorization:
├── Type: Basic Auth
├── Username: admin
└── Password: xxxx xxxx xxxx xxxx xxxx xxxx
```

---

## ✅ Method 2: Cookie-based Authentication

### Step 1: Browser এ Login করুন
1. Browser এ যান: `http://localhost/wp-atlas/wp-admin`
2. Username/Password দিয়ে login করুন

### Step 2: Cookie Export করুন

#### Option A: Browser Extension ব্যবহার করুন
1. Install করুন: **"EditThisCookie"** (Chrome) বা **"Cookie-Editor"** (Firefox)
2. WordPress site এ login করা অবস্থায় extension open করুন
3. WordPress cookies খুঁজুন (যেমন: `wordpress_logged_in_...`)
4. Cookie value কপি করুন

#### Option B: Developer Tools থেকে
1. Browser এ `F12` চাপুন (Developer Tools)
2. **Application** tab → **Cookies** → `http://localhost`
3. `wordpress_logged_in_...` cookie খুঁজুন
4. Value কপি করুন

### Step 3: Postman এ Cookie যোগ করুন

```
Method: POST
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books

Headers:
├── Content-Type: application/json
└── Cookie: wordpress_logged_in_xxx=your-cookie-value-here

Body (raw - JSON):
{
    "student_name": "Test User",
    "book_title": "Test Book"
}
```

---

## ✅ Method 3: Postman Interceptor (সবচেয়ে সহজ Cookie Sync)

### Step 1: Postman Interceptor Install করুন
1. Chrome Web Store থেকে **"Postman Interceptor"** install করুন
2. Postman Desktop App খুলুন
3. **Capture Requests** icon ক্লিক করুন (satellite icon)

### Step 2: WordPress এ Login করুন
1. Chrome browser এ `http://localhost/wp-atlas/wp-admin` এ login করুন

### Step 3: Postman এ Test করুন
Postman automatically browser এর cookies ব্যবহার করবে!

```
Method: POST
URL: http://localhost/wp-atlas/wp-json/solid-api/v1/student-books

Headers:
└── Content-Type: application/json

Body (raw - JSON):
{
    "student_name": "Test User",
    "book_title": "Test Book"
}
```

---

## 🐛 Common Errors এবং সমাধান

### Error 1: Authentication required
```json
{
    "code": "rest_forbidden",
    "message": "Authentication required.",
    "data": {"status": 401}
}
```

**সমাধান:**
- ✅ Application Password ব্যবহার করুন (regular password না)
- ✅ Username/Password সঠিক আছে কিনা চেক করুন
- ✅ WordPress এ login করা আছে কিনা যাচাই করুন

### Error 2: You do not have permission
```json
{
    "code": "rest_forbidden",
    "message": "You do not have permission to manage student books.",
    "data": {"status": 403}
}
```

**সমাধান:**
- ✅ আপনার user role **Administrator** বা **Editor** হতে হবে
- ✅ **Subscriber** বা **Author** role দিয়ে কাজ করবে না

### Error 3: Invalid username or password
```json
{
    "code": "incorrect_password",
    "message": "The password you entered is incorrect."
}
```

**সমাধান:**
- ✅ Application Password সঠিকভাবে কপি করেছেন কিনা দেখুন
- ✅ Spaces সহ paste করুন (WordPress automatically handle করবে)
- ✅ নতুন Application Password তৈরি করে try করুন

---

## 📋 Complete Postman Collection

### Collection Settings:
```
Collection Name: Solid API
Base URL: http://localhost/wp-atlas/wp-json/solid-api/v1
```

### Variables:
```
base_url: http://localhost/wp-atlas/wp-json/solid-api/v1
username: admin
app_password: xxxx xxxx xxxx xxxx xxxx xxxx
```

### Requests:

#### 1. GET All Books (Public)
```
GET {{base_url}}/student-books
No Auth
```

#### 2. CREATE Book (Protected)
```
POST {{base_url}}/student-books
Auth: Basic Auth
Username: {{username}}
Password: {{app_password}}

Body:
{
    "student_name": "Test Student",
    "book_title": "Test Book",
    "isbn": "978-1234567890",
    "borrowed_date": "2026-01-07",
    "return_date": "2026-02-07"
}
```

#### 3. UPDATE Book (Protected)
```
PUT {{base_url}}/student-books/1
Auth: Basic Auth
Username: {{username}}
Password: {{app_password}}

Body:
{
    "student_name": "Updated Name",
    "book_title": "Updated Title"
}
```

#### 4. DELETE Book (Protected)
```
DELETE {{base_url}}/student-books/1
Auth: Basic Auth
Username: {{username}}
Password: {{app_password}}
```

---

## 🎯 Quick Test Script

Postman এর **Tests** tab এ এই code যোগ করুন:

```javascript
// Check if request was successful
pm.test("Status code is 200 or 201", function () {
    pm.expect(pm.response.code).to.be.oneOf([200, 201]);
});

// Check if response has success property
pm.test("Response has success property", function () {
    var jsonData = pm.response.json();
    pm.expect(jsonData).to.have.property('success');
    pm.expect(jsonData.success).to.eql(true);
});

// Save created ID for future use
if (pm.response.code === 201) {
    var jsonData = pm.response.json();
    if (jsonData.data && jsonData.data.id) {
        pm.environment.set("book_id", jsonData.data.id);
        console.log("Created book ID:", jsonData.data.id);
    }
}
```

---

## ✅ Success Response Example:

```json
{
    "success": true,
    "data": {
        "id": 1,
        "student_name": "আব্দুল করিম",
        "book_title": "PHP Programming",
        "isbn": "978-1234567890",
        "borrowed_date": "2026-01-07 00:00:00",
        "return_date": "2026-02-07 00:00:00",
        "created_at": "2026-01-07 10:30:45",
        "updated_at": "2026-01-07 10:30:45"
    },
    "message": "Record created successfully"
}
```

---

## 🔑 সারাংশ:

1. **সাধারণ Password কাজ করবে না** ❌
2. **Application Password ব্যবহার করুন** ✅ (Recommended)
3. **Cookie-based Auth** ও কাজ করবে ✅
4. **GET public, POST/PUT/DELETE protected** 🔒

এখন test করুন! 🚀
