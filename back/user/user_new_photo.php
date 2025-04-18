<?PHP
if (session_status() === PHP_SESSION_NONE)
    session_start();

require_once __DIR__ . "/../../database.php";

try {
    // Check if a file was sent and if there were no upload errors
    if (!isset($_FILES['new_photo']) || $_FILES['new_photo']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("Erreur lors du téléchargement du fichier.");
    }
    $file = $_FILES['new_photo'];
    $max_file_size = 8000000; // 8 MB
    $allowed_extensions = ['jpg', 'jpeg', 'gif', 'png'];

    // Check if the file size is acceptable
    if ($file['size'] > $max_file_size) {
        throw new Exception("Le fichier est trop volumineux. Taille maximale autorisée : 8 Mo.");
    }

    // Retrieve and validate the file extension
    $file_info = pathinfo($file['name']);
    $extension = strtolower($file_info['extension'] ?? '');

    if (!in_array($extension, $allowed_extensions)) {
        throw new Exception("Seules les extensions suivantes sont autorisées : .jpg, .jpeg, .gif, .png");
    }

    // Generate a unique filename to avoid conflicts
    $user_id = 1;
    $unique_name = uniqid($user_id . '_', true) . '.' . $extension;
    $upload_dir = __DIR__ . '/../../photos/';
    $destination = $upload_dir . $unique_name;

    // Move the uploaded file to the destination folder
    if (!move_uploaded_file($file['tmp_name'], $destination)) {
        throw new Exception("Erreur lors de l'enregistrement du fichier.");
    }

    // Update the user's photo path in the database
    $sql = 'UPDATE users SET photo = :photo_user WHERE id = :user_id';
    $statement = $pdo->prepare($sql);
    $statement->bindParam(':photo_user', $unique_name);
    $statement->bindParam(':user_id', $user_id, PDO::PARAM_INT);

    if ($statement->execute()) {
        $_SESSION['success_message'] = "Votre photo a été mise à jour avec succès.";
        header('Location: ../../controllers/user_space.php');
        exit;
    } else {
        throw new Exception("Une erreur est survenue lors de la mise à jour. Veuillez réessayer.");
    }
    
} catch (Exception $e) {
    $_SESSION['error_message'] = $e->getMessage();
    header('Location: ../../controllers/user_space.php');
    exit;
}