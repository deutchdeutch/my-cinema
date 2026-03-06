<?php 

class ScreeningRepository {

private $pdo;
public function __construct(){
global $pdo;
$this->pdo = $pdo;

}

public function getAll(){
    $stmt = $this->pdo->query("SELECT 
                screenings.*, 
                movies.title AS movie_title,
                movies.genre AS movie_genre,
                rooms.name AS room_name
            FROM screenings
            INNER JOIN movies ON screenings.movie_id = movies.id
            INNER JOIN rooms ON screenings.room_id = rooms.id
            ORDER BY screenings.start_time ASC");
    return $stmt->fetchAll(PDO::FETCH_CLASS,"Screening");
}

public function add(Screening $screening){
    $stmt =$this->pdo->prepare("INSERT INTO screenings (movie_id, room_id, start_time, created_at) VALUES(?, ?, ?, ?)");
    $stmt->execute([
        $screening->movie_id,
        $screening->room_id,
        $screening->start_time,
        $screening->created_at,
    ]);
}
public function findById(int $id) {
    $stmt = $this->pdo->prepare("SELECT * FROM screenings WHERE id = ?");
    $stmt->execute([$id]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Screening"); 
    $screening = $stmt->fetch();
    if ($screening === false) {
        return null;
    }
    
    return $screening;
}

public function update(Screening $screening){
    $stmt = $this->pdo->prepare(" UPDATE screenings  SET movie_id=?, room_id=?, start_time=?, 
    created_at=? WHERE id=?");
     return $stmt->execute([
        $screening->movie_id,
        $screening->room_id,
        $screening->start_time,
        $screening->updated_at,
        $screening->id,
    ]);
    
   }

   public function delete(int $id){
    $stmt = $this->pdo->prepare("DELETE  FROM screenings WHERE id =?");
    return $stmt->execute([$id]);
   
   }
public function findByRoomAndDate(int $roomId, string $date) {
    $sql= "SELECT screenings.*, movies.duration 
     FROM screenings 
     INNER JOIN movies ON screenings.movie_id = movies.id 
     WHERE screenings.room_id = ? AND DATE(screenings.start_time) = ?";
    $stmt = $this->pdo->prepare($sql);
    $stmt->execute([$roomId, $date]);
    return $stmt->fetchAll(PDO::FETCH_OBJ);
}

}    
?>