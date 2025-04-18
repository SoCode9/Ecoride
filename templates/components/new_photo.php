<form method="POST" enctype="multipart/form-data" action="../../back/user/user_new_photo.php" id="photo-user">
    <input type="hidden" name="action" value="photo-user">
    <label for="photo">Choisir une photo</label>
    <input type="file" name="new_photo" id="photo">
    <button type="submit" name="submit">Envoyer le fichier</button>
</form>