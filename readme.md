# 🎬 My_Cinema

Projet **My_Cinema** est une application web CRUD permettant de gérer un cinéma :

* 🎞️ Films
* 🏢 Salles
* 🕒 Séances

Le projet est développé en **PHP (architecture MVC légère)** avec une API REST consommée par une interface **HTML / JavaScript (Fetch API)**.

---

##  Fonctionnalités

### Films

* Ajouter un film
* Modifier un film
* Supprimer un film
* Lister les films avec pagination

### Salles

* Ajouter une salle
* Modifier une salle
* Supprimer une salle
* Activer / désactiver une salle

### Séances

* Ajouter une séance (film + salle + horaire)
* Modifier une séance
* Supprimer une séance
* Vérification des conflits horaires
* Vérification de l’existence du film et de la salle

---

## Architecture du projet

```
my_cinema/
│
├── index.php                # Point d’entrée (router)
│
├── controllers/
│   ├── MovieController.php
│   ├── RoomController.php
│   └── ScreeningController.php
│
├── repositories/
│   ├── MovieRepository.php
│   ├── RoomRepository.php
│   └── ScreeningRepository.php
│
├── services/
│   └── ScreeningService.php
│
├── models/
│   ├── Movie.php
│   ├── Room.php
│   └── Screening.php
│
├── public/
│   ├── index.html
│   └── index.js
│
├── config/
│   └── Database.php
│
└── README.md
```

---
📦 Prérequis
PHP 8.0+ avec extension PDO

MySQL 8.0+

Serveur web (Apache/Nginx) ou PHP intégré : php -S localhost:8000

Live Server VSCode (port 5500) pour le frontend

🚀 Installation rapide (2 minutes)

1. Cloner / Télécharger

bash
git clone <ton-repo>
cd my-cinema

2. Configurer la base de données
sql


CREATE DATABASE cinema;


## 🗄️ Base de données

### Table `movies`

```sql
CREATE TABLE movies (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255),
    description TEXT,
    genre VARCHAR(100),
    duration INT,
    director VARCHAR(255),
    release_year INT,
    created_at DATETIME
);
```

### Table `rooms`

```sql
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100),
    capacity INT,
    active TINYINT(1)
);
```

### Table `screenings`

```sql
CREATE TABLE screenings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    movie_id INT,
    room_id INT,
    start_time DATETIME,
    created_at DATETIME,
    FOREIGN KEY (movie_id) REFERENCES movies(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);
```
3. Lancer le backend
cd backend
php -S localhost:8000

4.  Lancer le frontend
Ouvrir frontend/index.html avec Live Server (port 5500)

---

##  API – Routes disponibles

### Films

| Méthode | Action                      | Description       |
| ------- | --------------------------- | ----------------- |
| GET     | `?action=list_movie`        | Liste des films   |
| GET     | `?action=get_movie&id=1`    | Détail d’un film  |
| POST    | `?action=add_movie`         | Ajouter un film   |
| POST    | `?action=update_movie&id=1` | Modifier un film  |
| GET     | `?action=delete_movie&id=1` | Supprimer un film |

### Salles

| Méthode | Action                     | Description         |
| ------- | -------------------------- | ------------------- |
| GET     | `?action=list_room`        | Liste des salles    |
| POST    | `?action=add_room`         | Ajouter une salle   |
| POST    | `?action=update_room&id=1` | Modifier une salle  |
| GET     | `?action=delete_room&id=1` | Supprimer une salle |

### Séances

| Méthode | Action                          | Description          |
| ------- | ------------------------------- | -------------------- |
| GET     | `?action=list_screening`        | Liste des séances    |
| POST    | `?action=add_screening`         | Ajouter une séance   |
| POST    | `?action=update_screening&id=1` | Modifier une séance  |
| GET     | `?action=delete_screening&id=1` | Supprimer une séance |

---


## 🧪 Tests via terminal (cURL)

### Ajouter un film

```bash
curl -X POST "http://localhost:8000/index.php?action=add_movie" \
-H "Content-Type: application/json" \
-d '{
  "title": "Interstellar",
  "director": "Christopher Nolan",
  "duration": 169,
  "genre": "Sci-Fi",
  "release_year": 2014
}'
```

### Ajouter une séance

```bash
curl -X POST "http://localhost:8000/index.php?action=add_screening" \
-H "Content-Type: application/json" \
-d '{
  "movie_id": 1,
  "room_id": 1,
  "start_time": "2026-02-10T18:30"
}'
```

---

## ⚠️ Règles métier importantes

* Une séance ne peut être créée que si :

  * le film existe
  * la salle existe
  * la salle n’est pas déjà occupée au même horaire
* Les horaires HTML `datetime-local` sont convertis en format MySQL

---

## 🛠️ Technologies utilisées

* PHP 8+
* MySQL
* HTML5
* JavaScript (Fetch API)
* TailwindCSS

---

## 👨‍💻 Auteur

Projet réalisé dans un but **pédagogique** pour comprendre :

* MVC en PHP
* API REST
* Séparation Controller / Service / Repository
* Communication Front / Back

---

## ✅ Améliorations possibles

* Authentification admin
* Pagination côté serveur
* Messages d’erreur normalisés
* Tests unitaires

---

🎉 **My_Cinema est prêt à être utilisé et amélioré !**
