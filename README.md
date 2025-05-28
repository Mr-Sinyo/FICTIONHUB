
# 📚 Web-Based eBook Library Application

This project is a simple yet functional eBook library system developed using PHP and MySQL. It allows users to register, log in, browse digital books, borrow and return them, and for administrators to manage books, categories, and users through a dedicated dashboard.

---

## 🚀 Features

### 👤 User Side
- User registration and login
- Browse available eBooks
- Filter by genre or availability
- Borrow books (only if available)
- Read PDF/linked eBook (if authenticated)
- Return borrowed books
- View personal borrowing history (`my_borrowings.php`)

### 🛠 Admin Side
- Admin login and access control
- Add, edit, delete books and categories
- View borrowing activity (Dashboard)
- Manage book availability

---

## 🧩 Technologies Used

- **Frontend:** HTML, CSS, Bootstrap 5
- **Backend:** PHP (Procedural)
- **Database:** MySQL (with PDO)
- **Other Tools:** XAMPP / Apache

---

## 🗂 Project Structure

```
ebook-library-app/
│
├── admin/             # Admin dashboard
├── books/             # Book operations (add, edit, delete, view)
├── borrow/            # Borrow and return logic
├── includes/          # Shared files (db.php, header.php, footer.php)
├── uploads/           # Uploaded cover images
├── ebook.php          # eBook reading page
├── index.php          # Homepage
├── login.php          # User login
├── logout.php         # User logout
├── register.php       # User registration
├── my_borrowings.php  # User's borrowing history
├── return_book.php    # Process return
└── README.md          # Project overview
```

---

## 🧱 Database Tables

- `users(user_id, username, email, password, role)`
- `books(book_id, title, author, description, category_id, available, cover_image, file_path)`
- `categories(category_id, name)`
- `borrowing(borrowing_id, user_id, book_id, borrow_date, return_date, returned)`

---

## 📝 Setup Instructions

1. Clone or download the repository.
2. Import the SQL file into your MySQL database (`ebook_library`).
3. Configure your database connection in `includes/db.php`.
4. Place the project in your XAMPP `htdocs` folder.
5. Run the project on `http://localhost/ebook-library-app/`

---

## ✅ Author

Developed by [Your Name] – for educational purposes.
