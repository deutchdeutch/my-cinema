<?php 
class ScreeningController {
private $repository ;
public function __construct(){
    $this->repository = new ScreeningRepository();
    header('Content-Type: application/json');
}

public function list(){
    
    echo json_encode($this->repository->getAll());
}
public function get(int $id){
   $screening = $this->repository->findById($id);
   if(!$screening){
     http_response_code(404);
    echo json_encode(['error'=> 'Aucune séance trouvée']);
    return;
   }
   echo json_encode($screening); 
}
public function add(){
    $data = json_decode(file_get_contents("php://input"),true);
    
     if (
            empty($data['movie_id']) ||
            empty($data['room_id']) ||
            empty($data['start_time'])
        ) {
            http_response_code(400);
            echo json_encode(['error' => 'Film, salle et horaire requis']);
            return;
        }
        // Format datetime-local → MySQL
        $startTime = str_replace('T', ' ', $data['start_time']) . ':00';

        $service = new ScreeningService();
        $canCreate = $service->canCreateScreening(
            (int)$data['movie_id'],
            (int)$data['room_id'],
            $startTime); 
        if ($canCreate !== true) {
            http_response_code(409);
            echo json_encode($canCreate);
            return;
        }

     $screening = new Screening();
        $screening->movie_id = (int)$data['movie_id'];
        $screening->room_id = (int)$data['room_id'];
        $screening->start_time = $startTime;
        $screening->created_at = date('Y-m-d H:i:s');

        $this->repository->add($screening);

        http_response_code(201);
        echo json_encode(['message' => 'Séance créée avec succès']);
}
public function update(int $id){
     
     $screening = $this->repository->findById($id);
     if(!$screening){
     http_response_code(404);
        echo json_encode(['error'=> 'Séance non trouvée']);
        return;
     }
    
     $data = json_decode(file_get_contents('php://input'),true);
     if(!$data){
     http_response_code(400);
        echo json_encode(['error'=> 'aucune donnée trouvée']);
        return;
     }

     foreach($data as $key=>$value){
        if(property_exists($screening, $key)){
            $screening->$key = $value;
        }

     }
     $this->repository->update($screening); 
     http_response_code(201);
     echo json_encode(['message'=>'Séance mise à jour']);

     
}
public function delete(int $id){
    $screening = $this->repository->findById($id);
    if(!$screening){
        http_response_code(404);
         echo json_encode(['error'=>'Séance non trouvée']);
        return;
    }
    $this->repository->delete($id);
   http_response_code(201);
   echo json_encode(['message'=>'Séance supprimé  avec succes']);
}
}
?>