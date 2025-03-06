-- Create Database
CREATE DATABASE IF NOT EXISTS cafe_crepe;
USE cafe_crepe;

-- Users Table
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    user_id VARCHAR(50) NOT NULL UNIQUE,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user'
);

-- Admin Users Table (Only One Admin Allowed)
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);
INSERT IGNORE INTO admin_users (email, password) VALUES ('santraptinayak@gmail.com', '12345');

-- Rooms Table
CREATE TABLE IF NOT EXISTS rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_number VARCHAR(10) UNIQUE NOT NULL,
    bed_type ENUM('Single', 'Double', 'King') NOT NULL,
    status ENUM('Free', 'Booked') NOT NULL DEFAULT 'Free'
);

-- Insert 15 Rooms
INSERT INTO rooms (room_number, bed_type, status) VALUES
('101', 'Single', 'Free'), ('102', 'Single', 'Free'), ('103', 'Single', 'Free'),
('104', 'Double', 'Free'), ('105', 'Double', 'Free'), ('106', 'Double', 'Free'),
('107', 'Double', 'Free'), ('108', 'Double', 'Free'), ('201', 'Double', 'Free'),
('202', 'Double', 'Free'), ('203', 'King', 'Free'), ('204', 'King', 'Free'),
('205', 'King', 'Free'), ('206', 'King', 'Free'), ('207', 'King', 'Free')
ON DUPLICATE KEY UPDATE status = VALUES(status);

-- Bookings Table (Prevents Overlapping Bookings)
CREATE TABLE IF NOT EXISTS bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id VARCHAR(50) NOT NULL,
    first_name VARCHAR(50) NOT NULL,
    middle_name VARCHAR(50),
    last_name VARCHAR(50) NOT NULL,
    phone_number VARCHAR(15) NOT NULL,
    email VARCHAR(100) NOT NULL,
    bed_type ENUM('Single', 'Double', 'King') NOT NULL,
    room_number VARCHAR(10) NOT NULL,
    check_in DATE NOT NULL,
    check_out DATE NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    status ENUM('Booked', 'Checked Out', 'Cancelled') DEFAULT 'Booked',
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE,
    FOREIGN KEY (room_number) REFERENCES rooms(room_number) ON DELETE CASCADE,
    CHECK (check_out > check_in)
);

-- Prevent Overlapping Bookings
DELIMITER //
CREATE TRIGGER before_booking_insert
BEFORE INSERT ON bookings
FOR EACH ROW
BEGIN
    IF EXISTS (
        SELECT 1 FROM bookings 
        WHERE room_number = NEW.room_number 
        AND (NEW.check_in BETWEEN check_in AND check_out 
        OR NEW.check_out BETWEEN check_in AND check_out
        OR check_in BETWEEN NEW.check_in AND NEW.check_out)
    ) THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Error: Room already booked for selected date range.';
    END IF;

    UPDATE rooms SET status = 'Booked' WHERE room_number = NEW.room_number;
END;
//
DELIMITER ;

-- Food Menu Table
CREATE TABLE IF NOT EXISTS food_menu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category ENUM('Biryani', 'Chicken', 'Paneer', 'Chinese', 'Desserts', 'South Indian') NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image TEXT NOT NULL
);

-- Orders Table (Allowing Non-Room Orders)
    CREATE TABLE IF NOT EXISTS orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id VARCHAR(50) NOT NULL,
        first_name VARCHAR(50) NOT NULL,
        last_name VARCHAR(50) NOT NULL,
        room_number VARCHAR(10) NOT NULL,
        total_amount DECIMAL(10,2) NOT NULL,
        order_status ENUM('Pending', 'Delivered', 'Cancelled') DEFAULT 'Pending',
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
    );

    -- Food Orders Table (Fixed Relationships)
    CREATE TABLE IF NOT EXISTS food_orders (
        id INT AUTO_INCREMENT PRIMARY KEY,
        order_id INT NOT NULL,
        item_name VARCHAR(100) NOT NULL,
        quantity INT NOT NULL,
        total_price DECIMAL(10,2) NOT NULL,
        room_number VARCHAR(10) NOT NULL,
        order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
    );


-- Insert Food Menu
INSERT INTO food_menu (category, item_name, price, image) VALUES
('Biryani', 'Ambur Biryani', 350.00, 'images/ambur_biryani.jpg'),
('Biryani', 'Hyderabadi Biryani', 370.00, 'images/hyderabadi_biryani.jpg'),
('Biryani', 'Egg Biryani', 320.00, 'images/egg_biryani.jpg'),
('Biryani', 'Goan Fish Biryani', 400.00, 'images/goan_fish_biryani.jpg'),
('Biryani', 'Mutton Biryani', 450.00, 'images/mutton_biryani.jpg'),
('Biryani', 'Kamrupi Biryani', 380.00, 'images/kamrupi_biryani.jpg'),
('Biryani', 'Kashmiri Biryani', 390.00, 'images/kashmiri_biryani.jpg'),
('Biryani', 'Memoni Biryani', 410.00, 'images/memoni_biryani.jpg'),
('Biryani', 'Mughlai Biryani', 420.00, 'images/mughlai_biryani.jpg'),

('Chicken', 'Chicken Roast', 300.00, 'images/chicken_roast.jpg'),
('Chicken', 'Chicken Curry', 280.00, 'images/chicken_curry.jpg'),
('Chicken', 'Chicken Do Pyaza', 290.00, 'images/chicken_do_pyaza.jpg'),
('Chicken', 'Chicken Masala', 310.00, 'images/chicken_masala.jpg'),
('Chicken', 'Handi Chicken', 320.00, 'images/handi_chicken.jpg'),
('Chicken', 'Murgh Musallam', 350.00, 'images/murgh_musallam.jpg'),
('Chicken', 'Chicken Korma', 330.00, 'images/chicken_korma.jpg'),
('Chicken', 'Chicken Tikka Masala', 340.00, 'images/chicken_tikka_masala.jpg'),
('Chicken', 'Tandoori Chicken', 360.00, 'images/tandoori_chicken.jpg'),
('Chicken', 'Chicken 65', 270.00, 'images/chicken_65.jpg'),

('Paneer', 'Matar Paneer', 250.00, 'images/matar_paneer.jpg'),
('Paneer', 'Palak Paneer', 260.00, 'images/palak_paneer.jpg'),
('Paneer', 'Paneer Butter Masala', 270.00, 'images/paneer_butter_masala.jpg'),
('Paneer', 'Paneer Do Pyaza', 280.00, 'images/paneer_do_pyaza.jpg'),
('Paneer', 'Hyderabadi Paneer', 290.00, 'images/hyderabadi_paneer.jpg'),
('Paneer', 'Paneer Lababdar', 300.00, 'images/paneer_lababdar.jpg'),
('Paneer', 'Shahi Paneer', 310.00, 'images/shahi_paneer.jpg'),
('Paneer', 'Kadai Paneer', 320.00, 'images/kadai_paneer.jpg'),
('Paneer', 'Malai Kofta', 330.00, 'images/malai_kofta.jpg'),
('Paneer', 'Achari Paneer', 340.00, 'images/achari_paneer.jpg'),

('Chinese', 'Momos', 180.00, 'images/momos.jpg'),
('Chinese', 'Chicken Manchurian', 200.00, 'images/chicken_manchurian.jpg'),
('Chinese', 'Chili Chicken', 220.00, 'images/chili_chicken.jpg'),
('Chinese', 'Chowmein', 190.00, 'images/chowmein.jpg'),
('Chinese', 'Spring Roll', 210.00, 'images/spring_roll.jpg'),
('Chinese', 'Szechuan Chicken', 230.00, 'images/szechuan_chicken.jpg'),
('Chinese', 'Fried Rice', 200.00, 'images/fried_rice.jpg'),
('Chinese', 'Hakka Noodles', 210.00, 'images/hakka_noodles.jpg'),
('Chinese', 'Sweet and Sour Chicken', 240.00, 'images/sweet_sour_chicken.jpg'),
('Chinese', 'Hot and Sour Soup', 180.00, 'images/hot_sour_soup.jpg'),

('Desserts', 'Gulab Jamun', 100.00, 'images/gulab_jamun.jpg'),
('Desserts', 'Rasmalai', 120.00, 'images/rasmalai.jpg'),
('Desserts', 'Jalebi', 110.00, 'images/jalebi.jpg'),
('Desserts', 'Gajar Ka Halwa', 130.00, 'images/gajar_ka_halwa.jpg'),
('Desserts', 'Malpua', 140.00, 'images/malpua.jpg'),
('Desserts', 'Kheer', 150.00, 'images/kheer.jpg'),
('Desserts', 'Rabri', 160.00, 'images/rabri.jpg'),
('Desserts', 'Chocolate Cake', 200.00, 'images/chocolate_cake.jpg'),
('Desserts', 'Black Forest Cake', 220.00, 'images/black_forest_cake.jpg'),
('Desserts', 'Red Velvet Cake', 250.00, 'images/red_velvet_cake.jpg'),

('South Indian', 'Butter Masala Dosa', 150.00, 'images/butter_masala_dosa.jpg'),
('South Indian', 'Idli', 120.00, 'images/idli.jpg'),
('South Indian', 'Masala Dosa', 140.00, 'images/masala_dosa.jpg'),
('South Indian', 'Mysore Bonda', 130.00, 'images/mysore_bonda.jpg'),
('South Indian', 'Onion Uttapam', 150.00, 'images/onion_uttapam.jpg'),
('South Indian', 'Plain Dosa', 120.00, 'images/plain_dosa.jpg'),
('South Indian', 'Rava Uttapam', 160.00, 'images/rava_uttapam.jpg'),
('South Indian', 'Sambhar Vada', 140.00, 'images/sambhar_vada.jpg'),
('South Indian', 'Medu Vada', 130.00, 'images/medu_vada.jpg'),
('South Indian', 'Upma', 120.00, 'images/upma.jpg')

ON DUPLICATE KEY UPDATE 
    price = VALUES(price),
    image = VALUES(image);



CREATE TABLE IF NOT EXISTS menu_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    category VARCHAR(50) NOT NULL,
    item_name VARCHAR(100) NOT NULL,
    price DECIMAL(10,2) NOT NULL,
    image VARCHAR(255) DEFAULT NULL
);
