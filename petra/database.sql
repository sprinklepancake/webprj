CREATE DATABASE gym_equipment_store;

CREATE TABLE USERS (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    user_username VARCHAR(30) UNIQUE NOT NULL,
    user_password VARCHAR(30) NOT NULL,
    user_email VARCHAR(100) NOT NULL,
    user_first_name VARCHAR(50),
    user_last_name VARCHAR(50),
    user_role INT NOT NULL,
    user_country VARCHAR(50),
    user_region VARCHAR(100),
    user_street VARCHAR(50),
    user_bldg VARCHAR(50),
    user_phone_number INT
);

CREATE TABLE CATEGORY (
    category_id INT AUTO_INCREMENT UNIQUE,
    category_name VARCHAR(100) NOT NULL,
    category_description TEXT,
    PRIMARY KEY (category_id)
);

/*hasan: new table for the equipment guide items since they are diff than the regular items*/
CREATE TABLE EQUIPMENT_GUIDE (
    guide_id INT PRIMARY KEY AUTO_INCREMENT,
    guide_title VARCHAR(100) NOT NULL,
    guide_description TEXT NOT NULL,
    guide_image VARCHAR(255) NOT NULL,
    guide_specs TEXT NOT NULL,
    shop_link VARCHAR(255) NOT NULL,
    display_order INT NOT NULL,
    date_added TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

/*hasan: insert items for the equipment guide*/
INSERT INTO EQUIPMENT_GUIDE (guide_title, guide_description, guide_image, guide_specs, shop_link, display_order) VALUES 
(
    'Dumbbells',
    'Dumbbells are versatile pieces of equipment used for strength training. They come in various weights, suitable for all fitness levels.',
    'https://gymequipment.co.uk/pub/media/magefan_blog/img_23-Blog-DumbbellsMix-opti.jpg',
    'Weight range: 2-50 lbs|Material: Steel with rubber coating|Ideal for: Arm, shoulder, and chest exercises',
    '../petra/shop.html?item=dumbbells',
    1
),
(
    'Yoga Mat',
    'A yoga mat provides cushioning for joints and support during exercises. Essential for yoga, pilates, and stretching routines.',
    'https://branded.disruptsports.com/cdn/shop/articles/how-long-should-your-yoga-mat-be.jpg?v=1602856064&width=1100',
    'Dimensions: 72" x 24"|Thickness: 6mm|Material: Non-slip PVC',
    '../petra/shop.html?item=yoga-mat',
    2
),
(
    'Resistance Bands',
    'Resistance bands are flexible and portable, used for adding resistance to workouts, especially for warm-ups and rehab exercises.',
    'https://swolverine.com/cdn/shop/articles/Resistance_Band_Exercises_1024x.jpg?v=1645052429',
    'Resistance: Light, Medium, Heavy|Material: Latex|Ideal for: Full-body workout',
    '../petra/shop.html?item=resistance-bands',
    3
),
(
    'Kettlebell',
    'Kettlebells are a great tool for cardio, strength, and flexibility workouts. Their unique design enables a wide range of movements for functional training.',
    'https://static01.nyt.com/images/2023/04/25/multimedia/WNT-KETTLEBELLS1-kgwl/WNT-KETTLEBELLS1-kgwl-superJumbo.jpg',
    'Weight range: 5-40 lbs|Material: Cast iron with rubber coating|Ideal for: Strength and cardio exercises',
    '../petra/shop.html?item=kettlebell',
    4
),
(
    'Flat Utility Bench',
    'A versatile bench that can be used for various exercises, including chest press, incline press, and more. Perfect for home and commercial gyms.',
    'https://assets.roguefitness.com/f_auto,q_auto,c_fill,g_center,w_1000,h_633,b_rgb:f8f8f8/catalog/Strength%20Equipment/Strength%20Training/Weight%20Benches/Flat%20Utility%20Benches/RA1362/RA1362-AC1_o00vcl.png',
    'Dimensions: 52" x 18" x 18"|Material: Heavy-duty steel|Weight Capacity: 500 lbs',
    '../petra/shop.html?item=flat-bench',
    5
);

CREATE TABLE ITEM (
    item_id INT AUTO_INCREMENT UNIQUE,
    item_name VARCHAR(100) NOT NULL,
    item_description TEXT NOT NULL,
    item_price DECIMAL(10, 2) NOT NULL,
    item_news TEXT,
    item_image VARCHAR(255),
    item_quantity INT NOT NULL,
    item_date_added DATE,
    aggregate_rating DECIMAL(3, 2) NOT NULL,
    item_user_id INT,
    FOREIGN KEY (item_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (item_id)
);

CREATE TABLE ITEM_RATING (
    rating_id INT AUTO_INCREMENT UNIQUE,
    rating DECIMAL(3, 2) NOT NULL,
    item_id INT,
    rating_user_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (rating_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (rating_id)
);

CREATE TABLE ITEM_REVIEW (
    review_id INT AUTO_INCREMENT UNIQUE,
    review_body VARCHAR(150),
    item_id INT,
    review_user_id INT,
    rating_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (review_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (rating_id) REFERENCES ITEM_RATING(rating_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (review_id)
);

CREATE TABLE ORDERS (
    order_id INT AUTO_INCREMENT UNIQUE,
    total_price DECIMAL(10, 2) NOT NULL,
    order_status VARCHAR(50),
    order_date DATE NOT NULL,
    order_size INT NOT NULL,
    order_user_id INT,
    order_country VARCHAR(50),
    order_region VARCHAR(100),
    order_street VARCHAR(50),
    order_bldg VARCHAR(50),
    order_payment_method VARCHAR(50),
    FOREIGN KEY (order_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (order_id)
);

CREATE TABLE ORDER_ITEM (
    order_item_id INT AUTO_INCREMENT UNIQUE,
    order_item_quantity INT NOT NULL,
    order_item_price DECIMAL(10, 2),
    item_id INT,
    order_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (order_item_id)
);

CREATE TABLE PAYMENT (
    payment_id INT AUTO_INCREMENT UNIQUE,
    payment_method VARCHAR(50),
    payment_status VARCHAR(50),
    payment_date DATE,
    payment_amount DECIMAL(10, 2),
    order_id INT,
    FOREIGN KEY (order_id) REFERENCES ORDERS(order_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (payment_id)
);

CREATE TABLE CART (
    cart_id INT AUTO_INCREMENT UNIQUE,
    cart_quantity INT,
    cart_user_id INT,
    FOREIGN KEY (cart_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (cart_id)
);

CREATE TABLE WISHLIST (
    wishlist_id INT AUTO_INCREMENT,
    wishlist_quantity INT,
    wishlist_user_id INT,
    FOREIGN KEY (wishlist_user_id) REFERENCES USERS(user_id)
    ON UPDATE CASCADE ON DELETE CASCADE,
    PRIMARY KEY (wishlist_id)
);

CREATE TABLE ITEM_IN_CATEGORY (
    item_id INT,
    category_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id),
    FOREIGN KEY (category_id) REFERENCES CATEGORY(category_id),
    PRIMARY KEY (item_id, category_id)
);

CREATE TABLE ITEM_IN_CART (
    item_id INT,
    cart_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id),
    FOREIGN KEY (cart_id) REFERENCES CART(cart_id),
    PRIMARY KEY (item_id, cart_id)
);

CREATE TABLE ITEM_IN_WISHLIST (
    item_id INT,
    wishlist_id INT,
    FOREIGN KEY (item_id) REFERENCES ITEM(item_id),
    FOREIGN KEY (wishlist_id) REFERENCES WISHLIST(wishlist_id),
    PRIMARY KEY (item_id, wishlist_id)
);

-- Triggers

DELIMITER $$
CREATE TRIGGER update_aggregate_rating_insert
AFTER INSERT ON item_rating
FOR EACH ROW
BEGIN
    DECLARE total_rating FLOAT;
    DECLARE user_count INT;
    SELECT SUM(rating) INTO total_rating 
    FROM item_rating 
    WHERE item_id = NEW.item_id;
    SELECT COUNT(DISTINCT rating_user_id) INTO user_count 
    FROM item_rating
    WHERE item_id = NEW.item_id;
    IF user_count > 0 THEN
        UPDATE item
        SET aggregate_rating = total_rating / user_count
        WHERE item_id = NEW.item_id;
    END IF;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER update_aggregate_rating_update
AFTER UPDATE ON item_rating
FOR EACH ROW
BEGIN
    DECLARE total_rating FLOAT;
    DECLARE user_count INT;
    SELECT SUM(rating) INTO total_rating 
    FROM item_rating 
    WHERE item_id = NEW.item_id;
    SELECT COUNT(DISTINCT user_id) INTO user_count 
    FROM item_rating
    WHERE item_id = NEW.item_id;
    IF user_count > 0 THEN
        UPDATE item 
        SET aggregate_rating = total_rating / user_count
        WHERE item_id = NEW.item_id;
    END IF;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER after_item_rating_delete
AFTER DELETE ON item_rating
FOR EACH ROW
BEGIN
    DECLARE total_rating FLOAT;
    DECLARE user_count INT;
    SELECT SUM(rating) INTO total_rating 
    FROM item_rating 
    WHERE item_id = OLD.item_id;
    SELECT COUNT(DISTINCT rating_user_id) INTO user_count 
    FROM item_rating
    WHERE item_id = OLD.item_id;
    IF user_count > 0 THEN
        UPDATE item
        SET aggregate_rating = total_rating / user_count
        WHERE item_id = OLD.item_id;
    ELSE
        UPDATE item
        SET aggregate_rating = 0
        WHERE item_id = OLD.item_id;
    END IF;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER after_order_item_insert
AFTER INSERT ON order_item
FOR EACH ROW
BEGIN
    UPDATE item
    SET item_quantity = item_quantity - NEW.order_item_quantity
    WHERE item_id = NEW.item_id;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER after_order_item_delete
AFTER DELETE ON order_item
FOR EACH ROW
BEGIN
    UPDATE item
    SET item_quantity = item_quantity + OLD.order_item_quantity
    WHERE item_id = OLD.item_id;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER before_order_item_insert
BEFORE INSERT ON order_item
FOR EACH ROW
BEGIN
    IF (SELECT item_quantity FROM item WHERE item_id = NEW.item_id) < NEW.order_item_quantity THEN
        SIGNAL SQLSTATE '45000'
        SET MESSAGE_TEXT = 'Insufficient stock for this item.';
    END IF;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER after_payment_update
AFTER UPDATE ON payment
FOR EACH ROW
BEGIN
    IF NEW.payment_status = 'Paid' THEN
        UPDATE orders
        SET order_status = 'Completed'
        WHERE order_id = NEW.order_id;
    END IF;
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER user_after_insert
AFTER INSERT ON users
FOR EACH ROW
BEGIN
    INSERT INTO cart (user_id) VALUES (NEW.user_id);
    INSERT INTO wishlist (user_id) VALUES (NEW.user_id);
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER item_in_cart_after_delete
AFTER DELETE ON item_in_cart
FOR EACH ROW
BEGIN
    DELETE FROM item_in_wishlist
    WHERE item_id = OLD.item_id 
    AND wishlist_id = (SELECT wishlist_id FROM wishlist WHERE user_id = (SELECT user_id FROM cart WHERE cart_id = OLD.cart_id));
END$$
DELIMITER $$

DELIMITER $$
CREATE TRIGGER item_in_wishlist_after_delete
AFTER DELETE ON item_in_wishlist
FOR EACH ROW
BEGIN
    DELETE FROM item_in_cart
    WHERE item_id = OLD.item_id 
    AND cart_id = (SELECT cart_id FROM cart WHERE user_id = (SELECT user_id FROM wishlist WHERE wishlist_id = OLD.wishlist_id));
END$$
DELIMITER $$

-- Insert to tables

INSERT INTO category (category_id, category_name, category_description) 
VALUES
(1, 'Powerlifting', 'Specialized equipment for powerlifting.'),
(2, 'Gym machines', 'Equipment for strength training and full-body gym workouts.');

INSERT INTO item (item_id, item_name, item_description, item_price, item_news, item_image, item_quantity, item_date_added, aggregate_rating, item_user_id) 
VALUES
(1, 'Dumbbells Set (6KG – 12KG - 20KG)', 'Black iron dumbbells set with 3 weights', 15.99, 'Limited time offer!', 'uploads/dumbbells.webp', 10, '2024-11-06', 0, 2),
(2, 'SOLE F63 Treadmill', 'Treadmill with more than 10 features', 1199.99, 'New Edition', 'uploads/treadmill.jpg', 7, '2024-11-05', 0, 4),
(3, 'BalanceFrom Yoga Mat', 'All-Purpose 1/2 In. Foam Yoga Mat with Carrying Strap - Blue', 14.99, NULL, 'uploads/yogamat.webp', 15, '2024-12-03', 0, 4),
(4, 'LTrevFIT Gym Gloves', 'LTrevFIT Women/Men Gym Gloves With Wrist Wrap', 16.99, NULL, 'uploads/gymgloves.jpg', 20, '2024-12-03', 0, 4),
(5, 'The Grid Foam Roller', 'Multi-density foam roller - Black', 27.99, NULL, 'uploads/foamroller.jpg', 5, '2024-12-06', 0, 2),
(6, 'Hack Athletics 13mm Belt', '4 inch-wide for Weightlifting & Powerlifting', 97.18, 'Save $68.72', 'uploads/gymbelt.jpg', 6, '2024-11-10', 0, 2),
(7, 'Aerofit Leg Press Machine', 'Model Af 4411 - Adjustable seat, bench, floor rest', 1356.99, NULL, 'uploads/legpress.jpg', 15, '2024-11-28', 0, 2),
(8, 'Shaker Bottle 16oz', 'Leak Proof with Powder Storage & Pill Organizer - BPA Free - Black', 8.99, NULL, 'uploads/shakerbottle.jpg', 30, '2024-12-04', 0, 2);

INSERT INTO item_rating (rating_id, rating, item_id, rating_user_id) 
VALUES
(1, 4.90, 1, 1),
(2, 4.23, 1, 3),
(3, 5.00, 2, 1),
(4, 4.50, 2, 3);

INSERT INTO item_review (review_id, review_body, item_id, review_user_id, rating_id) 
VALUES 
(1, 'Great quality dumbbells!', 1, 1, 1), 
(2, 'Could be better.', 2, 1, 2), 
(3, 'Perfect for home gym.', 1, 3, 4), 
(4, 'Excellent treadmill!', 2, 3, 3);

INSERT INTO orders (order_id, total_price, order_status, order_date, order_size, order_user_id, order_country, order_region, order_street, order_bldg, order_payment_method) 
VALUES 
(1, 31.98, 'Delivered', '2024-11-09', 1, 1, 'Lebanon', 'Beirut', 'Street1', 'Building1', 'Credit Card'),
(2, 1199.99, 'In Progress', '2024-12-01', 1, 1, 'Lebanon', 'Beirut', 'Street1', 'Building1', 'PayPal'),
(3, 1215.98, 'Delivered', '2024-11-20', 2, 3, 'Lebanon', 'Beirut', 'Street3', 'Building3', 'Payment on Delivery');

INSERT INTO order_item (order_item_id, order_item_quantity, order_item_price, item_id, order_id) 
VALUES 
(1, 2, 15.99, 1, 1),
(2, 1, 1199.99, 2, 2),
(3, 1, 1199.99, 2, 3),
(4, 1, 15.99, 1, 3);

INSERT INTO payment (payment_id, payment_method, payment_status, payment_date, payment_amount, order_id) 
VALUES 
(1, 'Credit Card', 'Paid', '2024-11-09', 31.98, 1),
(2, 'PayPal', 'Pending', NULL, 1199.99, 2),
(3, 'Payment on Delivery', 'Paid', '2024-11-20', 1215.98, 3);

INSERT INTO cart (cart_id, cart_quantity, cart_user_id) 
VALUES 
(1, 3, 1),
(2, 2, 3);

INSERT INTO wishlist (wishlist_id, wishlist_quantity, wishlist_user_id) 
VALUES 
(1, 1, 1),
(2, 2, 3);

INSERT INTO item_in_category (item_id, category_id) 
VALUES 
(6, 1),
(2, 2),
(7, 2);

INSERT INTO item_in_cart (item_id, cart_id) 
VALUES 
(5, 1),
(6, 1),
(7, 1),
(4, 2),
(8, 2);

INSERT INTO item_in_wishlist (item_id, wishlist_id) 
VALUES 
(8, 1),
(3, 2),
(4, 2);