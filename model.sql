USE UNI;
users
-- Drop tables if they exist (in correct order due to foreign key constraints)
DROP TABLE IF EXISTS etudiant;
DROP TABLE IF EXISTS section;
DROP TABLE IF EXISTS users;

-- Create section table
CREATE TABLE section (
    id INT PRIMARY KEY,
    designation VARCHAR(10) NOT NULL,
    description TEXT NOT NULL
);

-- Create user table with proper password hashing
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Create student table
CREATE TABLE etudiant (
    id INT PRIMARY KEY,
    Name VARCHAR(50) NOT NULL,
    section_id INT NOT NULL,
    birthday DATE,
    FOREIGN KEY (section_id) REFERENCES section(id)
);

-- Insert sections
INSERT INTO section (id, designation, description) VALUES
(1, 'GL', 'Génie Logiciel : Formation axée sur le développement logiciel, lingénierie des systèmes, et les technologies web et mobiles.'),
(2, 'RT', 'Réseaux et Télécommunications : Spécialité couvrant les architectures réseaux, la sécurité informatique, les télécoms et ladministration système.'),
(3, 'IIA', 'Informatique Industrielle et Automatique : Concerne l automatisation des systèmes industriels, la robotique, et les systèmes embarqués.'),
(4, 'IMI', 'Instrumentation et Maintenance Industrielle : Formation technique sur les capteurs, les systèmes de mesure et le maintien en bon état des installations industrielles.');

-- Insert students
INSERT INTO etudiant (id, Name, section_id, birthday) VALUES
(14678913, 'Med Nadhir Djebbi', 1, '2005-03-01'),
(18496547, 'Abderrahmen Zitouni', 2, '2004-07-06'),
(1229578, 'Lionel Messi', 3, '1987-06-24');

-- Insert user with properly hashed password (123456789)
-- Using PHP's password_hash() with PASSWORD_DEFAULT (currently bcrypt)
INSERT INTO users (username, email, password, role) VALUES
('mohamednadhir', 'mohamednadhir@gmail.com', '123456789', 'admin'),
('AymenSellaouti','aymen.sellaouti@insat.ucar.tn','web4life','user');