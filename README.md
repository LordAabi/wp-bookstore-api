# 📚 Bookstore API (WordPress Plugin)

A custom WordPress plugin that provides a RESTful API for managing books in a bookstore.  
Built by **Abdullah** as part of a backend development internship project.

---

## 🚀 Features
- Full **CRUD operations** on books:
  - Create (`POST /books`)
  - Read all (`GET /books`)
  - Read one (`GET /books/{id}`)
  - Update (`PUT /books/{id}`)
  - Delete (`DELETE /books/{id}`)
- Custom database table (`wp_books`) created on plugin activation.
- Tested with **Postman** for API validation.

---

## 🛠️ Tech Stack
- **PHP** (WordPress plugin development)
- **WordPress REST API**
- **MySQL** (via `$wpdb`)
- **Postman** (API testing)
- **GitHub** (version control)

---

## 📂 Project Structure
```
bookstore-api/
├── bookstore-api.php    # Main plugin file
```
---

## ⚙️ Installation
1. Download or clone this repository.
2. Copy the plugin folder into your WordPress `wp-content/plugins/` directory.
3. Activate **Bookstore API** from the WordPress admin dashboard.
4. On activation, a new table `wp_books` will be created automatically.

---

## 🔗 API Endpoints

| Method | Endpoint | Description |
|--------|----------|-------------|
| `POST` | `/wp-json/bookstore/v1/books` | Add a new book |
| `GET`  | `/wp-json/bookstore/v1/books` | Fetch all books |
| `GET`  | `/wp-json/bookstore/v1/books/{id}` | Fetch a single book by ID |
| `PUT`  | `/wp-json/bookstore/v1/books/{id}` | Update a book |
| `DELETE` | `/wp-json/bookstore/v1/books/{id}` | Delete a book |

---

## 📖 Example Requests

### Create a Book
```http
POST /wp-json/bookstore/v1/books
Content-Type: application/json

{
  "title": "Atomic Habits",
  "author": "James Clear",
  "price": 20,
  "isbn": "1234567890",
  "publishedDate": "2018-10-16"
}
```
### Get All Books
```http
GET /wp-json/bookstore/v1/books
```
### Get One Book
```http
GET /wp-json/bookstore/v1/books/1
```
### Update a Book
```http
PUT /wp-json/bookstore/v1/books/1
Content-Type: application/json

{
  "title": "Atomic Habits (Updated)",
  "author": "James Clear",
  "price": 25,
  "isbn": "1234567890",
  "publishedDate": "2019-01-01"
}
```
### Delete a Book
```http
DELETE /wp-json/bookstore/v1/books/1
```
## Testing

Use Postman to test each endpoint.

All CRUD operations return JSON responses with success and message fields.
