CREATE DATABASE IF NOT EXISTS student_record;
USE student_record;

CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE IF NOT EXISTS students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    roll_no VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL,
    course VARCHAR(100) NOT NULL,
    year VARCHAR(30) NOT NULL,
    phone VARCHAR(20) DEFAULT '',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT IGNORE INTO users (name, email, username, password) VALUES
('Admin', 'admin@srms.com', 'admin', '$2y$12$waRXAOsr7v.4kgThLFGYI.J74uURgCnaZPLLGWDNwrgmzo39NQoDG');

INSERT IGNORE INTO students (name, roll_no, email, course, year, phone) VALUES
('Jeet Joshi', '101', 'jeet@example.com', 'CE', '3rd Year', '9876543210'),
('Ravi Patel', '102', 'ravi@example.com', 'IT', '2nd Year', '9876543211'),
('Meet Shah', '103', 'meet@example.com', 'CE', '1st Year', '9876543212'),
('Karan Mehta', '104', 'karan@example.com', 'ME', '4th Year', '9876543213');
