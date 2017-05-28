<?php
require_once("connect.php");

// Delete database if it already exists
$query = 'DROP DATABASE IF EXISTS oblig3';
if ($db->exec($query)===false){
	die('Query failed(1):' . $db->errorInfo()[2]);
}

// Create database
$query = 'CREATE DATABASE IF NOT EXISTS oblig3';
// Runs query. Returns false if some error has happened.
// exec returns number of rows affected by the query. If query does not actually affect any rows
// this can be 0. Must therefore check for false to see if something wrong happened with the query
if ($db->exec($query)===false){
	die('Query failed(2):' . $db->errorInfo()[2]);
}

// Select the database
$query = 'USE oblig3';
if ($db->exec($query)===false){
	die('Can not select db:' . $db->errorInfo()[2]);
}

// Delete user table if it alrady exists
$query = 'DROP TABLE IF EXISTS users';
if ($db->exec($query)===false){
	die('Query failed(3):' . $db->errorInfo()[2]);
}


// Create table for users
$query = 'CREATE TABLE IF NOT EXISTS users (
	userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	firstname VARCHAR(64),
	lastname VARCHAR(64),
	email VARCHAR(256),
	profileImg VARCHAR(256),
	password VARCHAR(256))';
if ($db->exec($query)===false){
	die('Query failed(4):' . $db->errorInfo()[2]);
}
// Create table for category
$query = 'CREATE TABLE IF NOT EXISTS categories (
	categoryID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(128) NOT NULL)';
if ($db->exec($query)===false){
	die('Query failed(6):' . $db->errorInfo()[2]);
}


// Create table for items
$query = 'CREATE TABLE IF NOT EXISTS items (
	itemID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(128) NOT NULL,
	description VARCHAR(256) NOT NULL,
	userID INT,
	mapLong INT NOT NULL,
	mapLat INT NOT NULL,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	categoryID INT NOT NULL,
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY (categoryID) REFERENCES categories(categoryID))';
if ($db->exec($query)===false){
	die('Query failed(5):' . $db->errorInfo()[2]);
}


// Create table for reviews
$query = 'CREATE TABLE IF NOT EXISTS reviews (
	reviewsID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	receiverID INT NOT NULL,
	writerID INT NOT NULL,
	FOREIGN KEY (receiverID) REFERENCES users(userID),
	FOREIGN KEY (writerID) REFERENCES users(userID))';
if ($db->exec($query)===false){
	die('Query failed(7):' . $db->errorInfo()[2]);
}

// Create table for messages
$query = 'CREATE TABLE IF NOT EXISTS messages (
	messageID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	receiverID INT NOT NULL,
	writerID INT NOT NULL,
	message TEXT NOT NULL,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	itemID INT NOT  NULL,
	status BOOLEAN DEFAULT 0,
	FOREIGN KEY (itemID) REFERENCES items(itemID))';
if ($db->exec($query)===false){
	die('Query failed(8):' . $db->errorInfo()[2]);
}

// Create table for rating
$query = 'CREATE TABLE IF NOT EXISTS rating (
	userID INT NOT NULL,
	up INT NOT NULL DEFAULT 0,
	down INT NOT NULL DEFAULT 0,
	FOREIGN KEY (userID) REFERENCES users(userID))';
if ($db->exec($query)===false){
	die('Query failed(9):' . $db->errorInfo()[2]);
}

// Create table for images
$query = 'CREATE TABLE IF NOT EXISTS images (
	itemID INT NOT NULL,
	imgPath VARCHAR(256),
	FOREIGN KEY (itemID) REFERENCES items(itemID))';
if ($db->exec($query)===false){
	die('Query failed(9):' . $db->errorInfo()[2]);
}

// Insert into categories
$query = 'INSERT INTO categories(name) VALUES
("kunst"),
("elektronikk"),
("fritid"),
("friluftsliv");
';
if ($db->exec($query)===false){
	die('Query failed(9):' . $db->errorInfo()[2]);
}

//Insert into Items
$query = 'INSERT INTO items (name, description, userID,	mapLong, mapLat, categoryID) VALUES
("Mona Lisa", "Dette er en description", NULL, 1.123, 2.343, 1),
("28 tommer LCD TV", "Description hyyyyype", NULL, 1.342, 2.1512, 2),
("Maling til Warhammer figurer", "Hater å komme på ting", NULL, 2.111, 3.12, 3),
("Tursekk 80L", "LOOOOOOOOOREM IPSUUUUUM", NULL, 1.2, 2.1, 4)
';
if ($db->exec($query)===false){
	die('Query failed(9):' . $db->errorInfo()[2]);

}
