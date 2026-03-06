<?php
header("Access-Control-Allow-Origin: https://ton-pseudo.github.io");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
header('Content-Type: application/json; charset=UTF-8');
if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

require_once __DIR__ . "/config/database.php"; // inclusion  de la base de données
// inclusion des différents fichiers
// require_once "models/Movie.php";
// require_once "repositories/MovieRepository.php" ;
// require_once "controllers/MovieController.php" ;
// require_once "models/Room.php";
// require_once "repositories/RoomRepository.php" ;
// require_once "controllers/RoomController.php" ;
// require_once "models/Screening.php";
// require_once "repositories/ScreeningRepository.php" ;
// require_once "controllers/ScreeningController.php" ;
// script autoload
spl_autoload_register(function ($class_name) {
    // Liste des dossiers où chercher tes classes
    $dirs = [
        'models/',
        'repositories/',
        'controllers/',
        'services/'
    ];

    foreach ($dirs as $dir) {
        $file = __DIR__ . '/' . $dir . $class_name . '.php';
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});
header('Content-Type: application/json');

// récupération de l'action demandée 
$request = $_GET['action'] ?? '';
$id = isset($_GET['id']) ? intval($_GET['id']) : null;


// 4. Le Routeur (Switch)
switch ($request) {

    /*films*/
  case 'list_movie':
        $controller = new MovieController();
        $controller->list();
        break;
    
   case 'get_movie':
        if (!$id) { http_response_code(404);
            echo json_encode(['error' => 'ID manquant']); 
            break; }
         $controller = new MovieController();
         $controller->get($id); 
         break;    

    case 'add_movie':
        $controller = new MovieController();
        $controller->add();
        break;

    case 'update_movie':
        if (!$id) { echo json_encode(['error' => 'ID manquant']); 
         break; }
        $controller = new MovieController();
        $controller->update($id);
        break;

    case 'delete_movie':
        if (!$id) { echo json_encode(['error' => 'ID manquant']); 
        break; }

        $controller = new MovieController();
        $controller->delete($id);
        break;


        /* salles*/
        
    case 'list_room':
        $controller = new RoomController();
        $controller->list();
        break;

   case 'get_room':
        if (!$id) { echo json_encode(['error' => 'ID manquant']); 
        break; }
        $controller = new RoomController();
        $controller->get($id);
        break;
     

    case 'add_room':
        $controller = new RoomController();
        $controller->add();

        break;

    case 'delete_room':
        if (!$id) { echo json_encode(['error' => 'ID manquant']);
         break; }
        $controller = new RoomController();
        $controller->delete($id);

        break;

    case  'update_room':
        if (!$id) { echo json_encode(['error' => 'ID manquant']);
         break; }
         $controller = new RoomController();
         $controller->update($id);  
         break;
         
         /*screenings*/
    
    case 'list_screening':
          $controller = New ScreeningController();
          $controller->list();
          break;


   case 'get_screening':
        if (!$id) { echo json_encode(['error' => 'ID manquant']);
         break; }
         $controller = new ScreeningController();
         $controller->get($id);
         break;
       

    case 'add_screening':
          $controller = new ScreeningController();
          $controller->add();
          break;
          
    case 'update_screening':
            if (!$id) { echo json_encode(['error' => 'ID manquant']);
             break; }
          $controller = new ScreeningController();
          $controller->update($id);   
          break;
    
    case 'delete_screening':
            if (!$id) { echo json_encode(['error' => 'ID manquant']); 
            break; }
          $controller = new ScreeningController();
          $controller->delete($id);   
          break;      

    default:
        
        echo json_encode(["error" => "Action non trouvée"]);
        break;
}

?>