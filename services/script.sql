-- Création de la base de données 
 
CREATE TABLE IF NOT EXISTS rooms(
   id INT AUTO_INCREMENT,
   name VARCHAR(100) NOT NULL,
   capacity INT NOT NULL,
   type VARCHAR(100),
   active TINYINT(1) NOT NULL,
   created_at DATETIME,
   updated_at DATETIME,
   PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS movies(
   id INT AUTO_INCREMENT,
   title VARCHAR(100) NOT NULL,
   description VARCHAR(500),
   duration INT NOT NULL,
   release_year INT NOT NULL,
   genre VARCHAR(100),
   director VARCHAR(100),
   created_at DATETIME,
   updated_at DATETIME,
   PRIMARY KEY(id)
);

CREATE TABLE IF NOT EXISTS screenings(
   id INT AUTO_INCREMENT,
   start_time DATETIME NOT NULL,
   created_at DATETIME,
   room_id INT NOT NULL,
   movie_id INT NOT NULL,
   PRIMARY KEY(id),
   FOREIGN KEY(room_id) REFERENCES rooms(id),
   FOREIGN KEY(movie_id) REFERENCES movies(id)
);