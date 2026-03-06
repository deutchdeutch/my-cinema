<?php
class ScreeningService {
    private $screeningRepo;
    private $movieRepo;

    public function __construct() {
        $this->screeningRepo = new ScreeningRepository();
        $this->movieRepo = new MovieRepository();
    }

public function canCreateScreening(int $movieId, int $roomId, string $startTime) {
    $movie = $this->movieRepo->findById($movieId);
    if (!$movie) {
        return ['error' => 'Film introuvable'];
    }

    $duration = $movie->duration;
    $newStart = new DateTime($startTime);
    $newEnd = (clone $newStart)->modify("+$duration minutes");
    
    // On récupère les séances de la journée
    $existingScreenings = $this->screeningRepo->findByRoomAndDate($roomId, $newStart->format('Y-m-d'));

    foreach ($existingScreenings as $s) {
        $existStart = new DateTime($s->start_time);
        // On s'assure d'avoir la durée du film existant
        $existEnd = (clone $existStart)->modify("+{$s->duration} minutes");

        // Logique de chevauchement : (Début1 < Fin2) ET (Fin1 > Début2)
        if ($newStart < $existEnd && $newEnd > $existStart) {
            return ['error' => "La salle est déjà occupée par une autre séance entre " . $existStart->format('H:i') . " et " . $existEnd->format('H:i')];
        }
    }
    return true;
}
   
}
?>