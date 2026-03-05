<?php 

class RoomRepository{
    private $pdo;
    public function __construct(){
        global $pdo;
        $this->pdo = $pdo;
    }

    public function getAll(){
        $stmt = $this->pdo->query("SELECT * FROM rooms WHERE active = 1");
        return $stmt->fetchAll(PDO::FETCH_CLASS,"Room");
    }

    public function add(Room $room){
        $stmt = $this->pdo->prepare("INSERT INTO rooms(name, capacity, type, active, created_at, updated_at)
         VALUES (?, ?, ? ,? ,? ,?)");
        $stmt->execute([
            $room->name,
            $room->capacity,
            $room->type,
            $room->active,
            $room->created_at,
            $room->updated_at
        ]);
    }

    public function findById(int $id) {
    $stmt = $this->pdo->prepare("SELECT * FROM rooms WHERE id = ?");
    $stmt->execute([$id]);
    $stmt->setFetchMode(PDO::FETCH_CLASS, "Room"); 
    $room = $stmt->fetch();
    if ($room === false) {
        return null;
    }
    
    return $room;
}

     public function update(Room $room){
        $stmt = $this->pdo->prepare("UPDATE rooms SET name=?, capacity=?, 
        type=?, active=?, updated_at=? WHERE id=?");
        
     return $stmt->execute([
        $room->name,
        $room->capacity,
        $room->type,
        $room->active,
        $room->updated_at,
        $room->id,
      ]);
    
    }
      public function softdelete (int $id){
        $stmt = $this->pdo->prepare("UPDATE rooms SET active = 0, 
        updated_at = NOW() WHERE id = ?");
       return  $stmt->execute([$id]);
        
   }
     

}
?>
    