<form method="POST" enctype="multipart/form-data" action="../../back/user/user_new_photo.php" id="photo-user"
    class="hidden">
    <input type="hidden" name="action" value="photo-user">
   <!--  <label for="photo">Choisir une photo</label> -->
    <input type="file" name="new_photo" id="photo">
    <div class="btn bg-light-green" style="width:100px; align-self:self-end;">
        <input type="submit" value="Enregistrer"></input>
    </div>
</form>