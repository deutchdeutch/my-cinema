<?php 
class RoomController {
private $repository ;
public function __construct(){
    $this->repository = new RoomRepository();
    header('Content-Type: application/json');
}
public function list(){
    
    echo json_encode($this->repository->getAll());
}

public function get(int $id){
   $room = $this->repository->findById($id);
   if(!$room){
     http_response_code(404);
    echo json_encode(['error'=> 'salle non trouvée']);
    return;
   }
   echo json_encode($room); 
}
public function add(){
    $data = json_decode(file_get_contents("php://input"),true);
    if(empty($data['name'])||empty($data['capacity'])){
         http_response_code(400);
        echo json_encode(['error'=>'Nom de la salle et capacité requis']);
        return;
    }

        $room = new Room();
        $room->name = $data['name'];
        $room->capacity = $data['capacity'];
        $room->type = $data['type'] ?? null;
        $room->active = $data['active'] ?? 1;
        $room->created_at = date('Y-m-d H:i:s');
        $room->updated_at = date('Y-m-d H:i:s');
        $this->repository->add($room);
        http_response_code(201);
        echo json_encode(['message'=>'salle créee avec succes']);
    
}
public function update(int $id){
     
     $room = $this->repository->findById($id);
     if(!$room){
     http_response_code(404);
        echo json_encode(['error'=> 'salle non trouvée']);
        return;
     }
    
     $data = json_decode(file_get_contents('php://input'),true);
     if(!$data){
     http_response_code(400);
        echo json_encode(['error'=> 'aucune donnée trouvée']);
        return;
     }

     foreach($data as $key=>$value){
        if(property_exists($room, $key)){
            $room->$key = $value;
        }

     }
     $room->updated_at = date('Y-m-d H:i:s');
     $this->repository->update($room); 
     http_response_code(200);
     echo json_encode(['message'=>'salle mise à jour']);

     
}
public function delete(int $id){
    $room = $this->repository->findById($id);
    if(!$room){
        http_response_code(404);
         echo json_encode(['error'=>'salle non trouvée']);
        return;
    }
    
   $this->repository->softdelete($id);
   http_response_code(200);
   echo json_encode(['message'=>'salle desactivée avec succes']);
}
}
?>