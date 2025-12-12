CREATE DATABASE code_crumble;
USE code_crumble;

-- 1) Users table 
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL

);

-- 2) Recipes table 
CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    dessert_name VARCHAR(100) NOT NULL,
    type VARCHAR(20) NOT NULL,
    tags VARCHAR(255) NOT NULL,                
    ingredients TEXT NOT NULL,
    instructions TEXT NOT NULL,
    image VARCHAR(255),
    description text,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,

    FOREIGN KEY (user_id) REFERENCES users(id)
);