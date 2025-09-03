<?PHP
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";
require_once __DIR__ . "/../../class/User.php";

$pdo = pdo();

$userId = $_SESSION['user_id'];
$connectedUser = User::fromId($pdo, $userId);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['action'] === 'edit-photo-user') {

    try {
        // Check if a file was sent and if there were no upload errors
        if (!isset($_FILES['new_photo']) || $_FILES['new_photo']['error'] !== UPLOAD_ERR_OK) {
            throw new Exception("Erreur lors du téléchargement du fichier");
        }
        $file = $_FILES['new_photo'];
        $maxFileSize = 8000000; // 8 MB
        $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];



        // Check if the file size is acceptable
        if ($file['size'] > $maxFileSize) {
            throw new Exception("Le fichier est trop volumineux. Taille maximale autorisée : 8 Mo");
        }

        // Retrieve and validate the file extension
        $fileInfo = pathinfo($file['name']);
        $extension = strtolower($fileInfo['extension'] ?? '');

        if (!in_array($extension, $allowedExtensions)) {
            throw new Exception("Seules les extensions suivantes sont autorisées : .jpg, .jpeg, .gif, .png");
        }

        // Generate a unique filename to avoid conflicts
        $uniqueName = uniqid($userId . '_', true) . '.' . $extension;
        $uploadDir = __DIR__ . '/../../photos/';
        $destination = $uploadDir . $uniqueName;

        // Delete the user's old photos from the folder
        $photoFolder = scandir($uploadDir);
        foreach ($photoFolder as $photo) {
            $photoSearched = strstr($photo, $userId . "_");
            if ($photoSearched !== false) {
                unlink($uploadDir . $photoSearched);
            }
        }

        // Move the uploaded file to the destination folder
        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            throw new Exception("Erreur lors de l'enregistrement du fichier");
        }


        // Update the user's photo path in the database
        $connectedUser->setPhoto($uniqueName);
        $_SESSION['success_message'] = "Votre photo a été mise à jour avec succès";
    } catch (Exception $e) {
        error_log("Erreur upload photo (user ID $userId) : " . $e->getMessage());
        $_SESSION['error_message'] = $e->getMessage();
    }

    header('Location: ../../controllers/user_space.php');
    exit;
}
