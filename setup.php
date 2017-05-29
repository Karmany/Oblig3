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
	die('Query failed(5):' . $db->errorInfo()[2]);
}


// Create table for items
$query = 'CREATE TABLE IF NOT EXISTS items (
	itemID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	name VARCHAR(128) NOT NULL,
	description TEXT NOT NULL,
	userID INT,
	mapLong INT NOT NULL,
	mapLat INT NOT NULL,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	categoryID INT NOT NULL,
	FOREIGN KEY (userID) REFERENCES users(userID),
	FOREIGN KEY (categoryID) REFERENCES categories(categoryID))';
if ($db->exec($query)===false){
	die('Query failed(6):' . $db->errorInfo()[2]);
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


$query = 'CREATE TABLE IF NOT EXISTS conversations (
	conversationID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	itemOwnerID INT NOT NULL,
	userID INT NOT NULL,
	itemID INT NOT NULL,
	FOREIGN KEY (itemID) REFERENCES items(itemID),
	FOREIGN KEY (itemOwnerID) REFERENCES users(userID),
	FOREIGN KEY (userID) REFERENCES users(userID)
	)';
if ($db->exec($query)===false){
	die('Query failed(8):' . $db->errorInfo()[2]);
}


// Create table for messages
$query = 'CREATE TABLE IF NOT EXISTS messages (
	messageID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
	writerID INT NOT NULL,
	message TEXT NOT NULL,
	date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
	status BOOLEAN DEFAULT 0,
	conversationID INT NOT NULL,
	FOREIGN KEY (writerID) REFERENCES users(userID),
	FOREIGN KEY (conversationID) REFERENCES conversations(conversationID)
	)';
if ($db->exec($query)===false){
	die('Query failed(9):' . $db->errorInfo()[2]);
}

// Create table for rating
$query = 'CREATE TABLE IF NOT EXISTS rating (
	userID INT NOT NULL,
	up INT NOT NULL DEFAULT 0,
	down INT NOT NULL DEFAULT 0,
	FOREIGN KEY (userID) REFERENCES users(userID))';
if ($db->exec($query)===false){
	die('Query failed(10):' . $db->errorInfo()[2]);
}

// Create table for images
$query = 'CREATE TABLE IF NOT EXISTS images (
	itemID INT NOT NULL,
	imgPath VARCHAR(256),
	FOREIGN KEY (itemID) REFERENCES items(itemID))';
if ($db->exec($query)===false){
	die('Query failed(11):' . $db->errorInfo()[2]);
}

// Array containing user data (firstname and lastname)
// All of these users have the same password: 'Password123'
$password = password_hash('Password123', PASSWORD_DEFAULT);
$users_array = array(
	array("Gunnar", "Grefsen", "gunnar_grefsen@gmail.com", $password),
	array("Ole", "Kristiansen", "ole_kristiansen@gmail.com", $password),
	array("Bjarne", "Bakken", "bjarne_bakken@gmail.com", $password),
	array("Helene", "Svendsen", "helene_svendsen@gmail.com", $password)
);

// Inserting user data, user_id is automatically added because of AUTO_INCREMENT
$sql = "INSERT INTO users (firstname, lastname, email, password) values (?,?,?,?)";
$query = $db->prepare($sql);

foreach($users_array as $user)
{
	$query->execute($user);
}

// Insert into categories
$query = 'INSERT INTO categories(name) VALUES
("fashion"),
("electronics"),
("collectibles"),
("home"),
("sport");
';
if ($db->exec($query)===false){
	die('Query failed(12):' . $db->errorInfo()[2]);
}


//Insert into Items
$query = 'INSERT INTO items (name, description, userID,	mapLong, mapLat, categoryID) VALUES
("The actual Mona Lisa", "Mona Lisa is a half-length portrait of Lisa Gherardini by the Italian Renaissance artist Leonardo da Vinci that has been described as the best known, the most visited, the most written about, the most sung about, the most parodied work of art in the world", 1, 1.123, 2.343, 3),

("28 inch LCD TV", "A 4 year old TV in good condition.", 3, 1.342, 2.1512, 2),

("One hoodie", "A blue hoodie with a white logo", 1, 2.111, 3.12, 1),

("Corner couch", "A corner couch with three seats, where one of them is a long seat", 2, 1.2, 2.1, 4),

("Squash racket", "A used squash racket, includes three balls, two 1dot and one 2dot. The racket is blue and red and has minimal wear.", 4, 1.2, 2.1, 5)
';
if ($db->exec($query)===false){
	die('Query failed(13):' . $db->errorInfo()[2]);
}

//Insert into images
$query = 'INSERT INTO images(itemID, imgPath) VALUES
(1, "img/mona_lisa.jpg"),
(1, "img/mona_lisa_bean.jpg"),
(2, "img/tv28inch.jpg"),
(3, "img/blue_hoodie"),
(4, "img/item_img.png")
';
if ($db->exec($query)===false){
	die('Query failed(14):' . $db->errorInfo()[2]);
}

//Insert into conversations
$query = 'INSERT INTO conversations(itemOwnerID, userID, itemID) VALUES
(1, 2, 1),
(3, 4, 2)
';
if ($db->exec($query)===false){
	die('Query failed(15):' . $db->errorInfo()[2]);
}

//Insert into messages
$query = 'INSERT INTO messages(writerID, message, conversationID) VALUES
(2, "Dis is good man", 1),
(4, "I lik dis shit", 2),
(1, "Good man", 1),
(3, "Dis shit good", 2)
';
if ($db->exec($query)===false){
	die('Query failed(16):' . $db->errorInfo()[2]);
}
