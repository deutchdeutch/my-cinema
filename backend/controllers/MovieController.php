<?php 
class MovieController {

private $repository ;
public function __construct(){
    $this->repository = new MovieRepository();
     header('Content-Type: application/json');
}

public function list(){
   
    echo json_encode($this->repository->getAll());
}
public function get(int $id){
   $movie = $this->repository->findById($id);
   if(!$movie){
     http_response_code(404);
    echo json_encode(['error'=> 'film non trouvé']);
    return;
   }
   echo json_encode($movie); 
}

public function add(){
    $data = json_decode(file_get_contents("php://input"),true);
    if(empty($data['title'])||empty($data['director'])){
         http_response_code(404);
        echo json_encode(['error'=>'Titre et Réalisateur requis']);
        return;
    }

        $movie = new Movie();
        $movie->title = $data['title'];
        $movie->description = $data['description'] ?? null;
        $movie->duration = (int)$data['duration'] ?? null;
        $movie->release_year = $data['release_year'] ?? null;
        $movie->genre = $data['genre'] ?? null;
        $movie->director = $data['director'];
        $movie->created_at = date('Y-m-d H:i:s');
        $movie->updated_at = date('Y-m-d H:i:s');
        $this->repository->add($movie);
        http_response_code(201);
        echo json_encode(['message'=>'film crée avec succes']);
    
}
public function update(int $id){
     
     $movie = $this->repository->findById($id);
     if(!$movie){
     http_response_code(404);
        echo json_encode(['error'=> 'film non trouvé']);
        return;
     }
    
     $data = json_decode(file_get_contents('php://input'),true);
     if(!$data){
     http_response_code(404);
        echo json_encode(['error'=> 'aucune donnée trouvée']);
        return;
     }

     foreach($data as $key=>$value){
        if(property_exists($movie, $key)){
            $movie->$key = $value;
        }

     }
     $movie->updated_at = date('Y-m-d H:i:s');
     $this->repository->update($movie); 
     http_response_code(200);
     echo json_encode(['message'=>'film mis à jour']);

     
}
public function delete(int $id){
    $movie = $this->repository->findById($id);
    if(!$movie){
        http_response_code(404);
         echo json_encode(['error'=>'film non trouvé']);
        return;
    }
    
   $this->repository->delete($id);
   http_response_code(200);
   echo json_encode(['message'=>'film supprimé avec succes']);
}
}


?>
   