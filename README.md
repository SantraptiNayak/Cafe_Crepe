# Cafe_Crepe

Hotel Management System
A fully functional Hotel Management System built using PHP, MySQL, XAMPP, HTML, CSS, and JavaScript. This project provides an efficient way to manage hotel room bookings, food orders, user authentication, and an admin panel for hotel management.

# Features

# 🏠 Homepage
- Navigation bar with links: Home, Book Now, Order Food, Signup/Login
- Slideshow of images
•	Information containers for hotel details
•	Footer at the bottom

# 🏨 Booking System
•	Users must sign in to book a room
Booking form includes:
•	Name, Phone Number, Email ID
•	Bed Type (Single, Double, King)
•	Number of Rooms (1-10)
•	Check-in & Check-out Dates
•	Room Type & Specific Room Number Selection
Room Booking Rules:
•	Once booked, a room is marked as "Booked"
•	Automatically becomes "Free" after the checkout date
•	A room cannot be double-booked for the same dates
•	Redirects non-logged-in users to the login page before booking

# 🍽️ Food Ordering System
•	Only logged-in users can order food
•	Menu includes categories like Paneer, Chicken, Starters, Chinese, Rice, etc.
•	Each dish displays: Image, Price, Quantity Selection, Add to Cart
Order Checkout Process:
•	Users fill a checkout form (Name, Room Number, Total Amount)
•	Order is confirmed with a success message
•	Redirects non-logged-in users to the login page before ordering

# 🔐 User Authentication
•	Login Page: Users enter their User ID & Password
•	Signup Page: Users provide Email ID, Password, User ID, Name

🛠️ Admin Panel
•	Admin login is restricted to hotel management
Admin Dashboard Includes:
•	View & update room status (Booked/Free)
•	Automated room status updates based on check-in/check-out dates
•	Monitor & manage food orders
•	Ensure users can select room type and specific room number

# 🏢 Room Details
•	Total Rooms: 15
•	Single Bed: 3 (101, 102, 103)
•	Double Bed: 7 (104, 105, 106, 107, 108, 201, 202)
•	King Bed: 5 (203, 204, 205, 206, 207)

# 🚀 Technology Stack
•	Frontend: HTML, CSS, JavaScript
•	Backend: PHP
•	Database: MySQL (phpMyAdmin)
•	 Server: XAMPP


# 📌 Installation Guide
1. Download & Extract the Project
2. Move the Project to XAMPP Directory (C:\xampp\htdocs\)
3. Start XAMPP (Enable Apache & MySQL)
4. Import Database
5. Open http://localhost/phpmyadmin/
6. Create a new database (hotel_management)
7. Import the provided SQL file
8. Run the Project
9. Open browser & visit http://localhost/hotel_management/

# 📢 Notes
• Ensure XAMPP is running before accessing the site
•	Replace images in the project as needed
•	Update admin email credentials if required
•	Contact me via email for SQL file & support

# 📧 Contact
Developed by: [Santrapti Nayak]
Email: santraptinayak@gmail.com
