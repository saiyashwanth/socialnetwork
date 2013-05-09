High Level==================
/accounts
	id
	name
	email
	password
	birthday
	school

/friends
	/invite
	/privacy
		not friend - only see profile information
		friend - see all posts 
		both - send message
/posts
	/status updates
		.comment
		.likes
	post on friends walls
Database=====================
/accounts
	id - INT AUTO INCREMENT
	name - TEXT
	email - TEXT
	password - TEXT
	birthday - TEXT
	school - TEXT
	
/friends
	firstperson id - INT
	second person id - INT
	
/invite
	initiator id - INT 
	potential id - INT
	
/posts
	id - INT
	timestamp - DATE
	poster id - INT
	receiver id(yourself if status update, other person if post) - INT
	
MySQL Queries==================
CREATE DATABASE socialnetwork;
USE socialnetwork;

CREATE TABLE `accounts` (
  `id` INT(11) AUTO_INCREMENT, `name` text, `email` text, `password` text,`birthday` TEXT, `school` TEXT, PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=1;

CREATE TABLE `friends` (`firstperson` INT(11), `secondperson` INT(11));

CREATE TABLE `invite` (`initiator` INT(11), `respondent` INT(11));

CREATE TABLE `posts` (`id` INT(11), `content` TEXT, `timestamp` DATE, `poster` INT(11), `receiver` INT(11), `postername` TEXT, `receivername` TEXT);





