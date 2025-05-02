-- Buat database
CREATE DATABASE IF NOT EXISTS todolist_db;
USE todolist_db;

-- Tabel user
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Tabel todolist
CREATE TABLE IF NOT EXISTS todolist (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    teks VARCHAR(255) NOT NULL,
    selesai BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

