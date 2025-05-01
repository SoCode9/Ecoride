<?php

$popup_id = "popup-new-photo";
$popup_title = "Modifier la photo de profil";
ob_start(); ?>
<span>Sélectionner une photo de profil</span>
<form action="<?= BASE_URL ?>/back/user/user_new_photo.php" method="POST" enctype="multipart/form-data"
    onsubmit="console.log('Form submitted!')" class="flex-column gap-24">
    <input type="hidden" name="action" value="edit-photo-user">
    <input type="file" name="new_photo" id="photo" required>
    <div class="btn bg-light-green">
        <button type="submit">Enregistrer la photo</button>
    </div>
</form>
<?php $popup_content = ob_get_clean();

include __DIR__ . '/template.php';

?>