CREATE DATABASE IF NOT EXISTS lost_found_diu CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lost_found_diu;

CREATE TABLE roles(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(50) NOT NULL UNIQUE,
 code VARCHAR(30) NOT NULL UNIQUE
);

CREATE TABLE users(
 id INT AUTO_INCREMENT PRIMARY KEY,
 role_id INT NOT NULL,
 name VARCHAR(120) NOT NULL,
 email VARCHAR(190) NOT NULL UNIQUE,
 password_hash VARCHAR(255) NOT NULL,
 is_active TINYINT(1) NOT NULL DEFAULT 1,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(role_id) REFERENCES roles(id)
);

CREATE TABLE categories(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(100) NOT NULL UNIQUE,
 code VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE locations(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(150) NOT NULL UNIQUE,
 code VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE statuses(
 id INT AUTO_INCREMENT PRIMARY KEY,
 name VARCHAR(80) NOT NULL UNIQUE,
 code VARCHAR(50) NOT NULL UNIQUE
);

CREATE TABLE lost_items(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 title VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 category_id INT NOT NULL,
 location_id INT NOT NULL,
 lost_date DATE NOT NULL,
 status_id INT NOT NULL,
 image_path VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id),
 FOREIGN KEY(category_id) REFERENCES categories(id),
 FOREIGN KEY(location_id) REFERENCES locations(id),
 FOREIGN KEY(status_id) REFERENCES statuses(id),
 INDEX idx_lost_search(title),
 INDEX idx_lost_date(lost_date)
);

CREATE TABLE found_items(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 title VARCHAR(180) NOT NULL,
 description TEXT NOT NULL,
 category_id INT NOT NULL,
 location_id INT NOT NULL,
 found_date DATE NOT NULL,
 status_id INT NOT NULL,
 image_path VARCHAR(255) NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id),
 FOREIGN KEY(category_id) REFERENCES categories(id),
 FOREIGN KEY(location_id) REFERENCES locations(id),
 FOREIGN KEY(status_id) REFERENCES statuses(id),
 INDEX idx_found_search(title),
 INDEX idx_found_date(found_date)
);

CREATE TABLE claims(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 item_type ENUM('lost','found') NOT NULL,
 item_id INT NOT NULL,
 details TEXT NOT NULL,
 status_id INT NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 reviewed_at DATETIME NULL,
 FOREIGN KEY(user_id) REFERENCES users(id),
 FOREIGN KEY(status_id) REFERENCES statuses(id),
 INDEX idx_claim_item(item_type,item_id)
);

CREATE TABLE item_images(
 id INT AUTO_INCREMENT PRIMARY KEY,
 item_type ENUM('lost','found') NOT NULL,
 item_id INT NOT NULL,
 image_path VARCHAR(255) NOT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 INDEX idx_item_images(item_type,item_id)
);

CREATE TABLE notifications(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NOT NULL,
 title VARCHAR(180) NOT NULL,
 message TEXT NOT NULL,
 is_read TINYINT(1) DEFAULT 0,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id)
);

CREATE TABLE activity_logs(
 id INT AUTO_INCREMENT PRIMARY KEY,
 user_id INT NULL,
 action VARCHAR(120) NOT NULL,
 entity_type VARCHAR(80) NOT NULL,
 entity_id INT NULL,
 details TEXT NULL,
 created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
 FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
);

INSERT INTO roles(name,code) VALUES ('Administrator','admin'),('Student/User','user');
INSERT INTO categories(name,code) VALUES
('Electronics','electronics'),('ID Card','id_card'),('Wallet','wallet'),('Keys','keys'),('Documents','documents'),('Bag','bag'),('Other','other');
INSERT INTO locations(name,code) VALUES
('Daffodil Smart City','smart_city'),('Main Campus','main_campus'),('Library','library'),('Food Court','food_court'),('Academic Building','academic_building'),('Transport Area','transport'),('Other','other');
INSERT INTO statuses(name,code) VALUES
('Pending Review','pending'),('Approved','approved'),('Rejected','rejected'),('Claim Pending','claim_pending'),('Claim Approved','claim_approved'),('Resolved','resolved');

-- Demo admin: password is ChangeMe123!
INSERT INTO users(role_id,name,email,password_hash)
VALUES (1,'System Administrator','admin@diu-lostfound.local',
'$2y$12$MSXfZVQRHxu75PMEWXS0GOtuyi6ushcSsikWfREYXWLImVVgE1IH.');

CREATE OR REPLACE VIEW approved_lost_items AS
SELECT l.id,l.title,l.description,c.name category,loc.name location,l.lost_date
FROM lost_items l
JOIN categories c ON c.id=l.category_id
JOIN locations loc ON loc.id=l.location_id
JOIN statuses s ON s.id=l.status_id
WHERE s.code='approved';

DELIMITER //
CREATE PROCEDURE get_user_report_summary(IN p_user_id INT)
BEGIN
 SELECT
   (SELECT COUNT(*) FROM lost_items WHERE user_id=p_user_id) lost_reports,
   (SELECT COUNT(*) FROM found_items WHERE user_id=p_user_id) found_reports,
   (SELECT COUNT(*) FROM claims WHERE user_id=p_user_id) claims;
END//
CREATE TRIGGER after_claim_insert
AFTER INSERT ON claims
FOR EACH ROW
BEGIN
 INSERT INTO notifications(user_id,title,message)
 VALUES(NEW.user_id,'Claim submitted','Your ownership claim has been submitted for admin review.');
END//
DELIMITER ;



USE lost_found_diu;
DELIMITER //
CREATE PROCEDURE get_user_report_summary(IN p_user_id INT)
BEGIN
 SELECT
   (SELECT COUNT(*) FROM lost_items WHERE user_id=p_user_id) lost_reports,
   (SELECT COUNT(*) FROM found_items WHERE user_id=p_user_id) found_reports,
   (SELECT COUNT(*) FROM claims WHERE user_id=p_user_id) claims;
END//
DELIMITER ;




USE lost_found_diu;
DELIMITER //
CREATE TRIGGER after_claim_insert
AFTER INSERT ON claims
FOR EACH ROW
BEGIN
 INSERT INTO notifications(user_id,title,message)
 VALUES(NEW.user_id,'Claim submitted','Your ownership claim has been submitted for admin review.');
END//
DELIMITER ;



USE lost_found_diu;
CREATE OR REPLACE VIEW approved_lost_items AS
SELECT l.id,l.title,c.name category,loc.name location,l.lost_date
FROM lost_items l
JOIN categories c ON c.id=l.category_id
JOIN locations loc ON loc.id=l.location_id
JOIN statuses s ON s.id=l.status_id
WHERE s.code='approved';
