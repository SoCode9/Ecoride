<?php

$popup_id = "popup-id";
$popup_title = "Valider le trajet";
ob_start(); ?>
        <!-- Step 1 -->
        <span>Est-ce que tout s’est bien passé ?</span>
        <div class="gap-4">
            <button id="yesButton" class="yes-no-btn" onclick="handleValidation(true)">Oui</button>
            <button id="noButton" class="yes-no-btn" onclick="handleValidation(false)">Non</button>
        </div>


        <!-- Step 2A : If Yes -->
        <div id="feedback-positive" style="display:none">
            <h4 class="m-tb-12">Souhaitez-vous laisser un avis ?</h4>
            <form class="block-column-g20" action="/0-ECFEcoride/back/carpool/validate.php" method="POST"
                onsubmit="console.log('Form submitted!')">
                <input type="hidden" name="idReservation" id="popup-idReservation-positive" value=<?= $carpool['reservationId'] ?>>
                <input type="hidden" name="action" value="positive">

                <div class="flex-row">
                    <label for="driver-rating-list">Note laissée au chauffeur : </label>
                    <select id="driver-rating-list" name="rating" class="short-field">
                        <optgroup>
                            <option value=""></option>
                            <option value="5">5</option>
                            <option value="4.5">4.5</option>
                            <option value="4">4</option>
                            <option value="3.5">3.5</option>
                            <option value="3">3</option>
                            <option value="2.5">2.5</option>
                            <option value="2">2</option>
                            <option value="2">1.5</option>
                            <option value="1">1</option>
                        </optgroup>
                    </select>
                    <label for="driver-rating-list"><img src="../icons/EtoileJaune.png" alt="EtoileJaune"
                            class="img-width-20"></label>
                </div>

                <label for="comment-positive">Laissez un commentaire :</label>
                <textarea name="comment" id="comment-positive"></textarea>

                <div class="btn bg-light-green">
                    <button type="submit"><strong>Valider le
                            covoiturage</strong><br>(avec ou sans avis)</button>
                </div>
            </form>

        </div>

        <!-- Step 2B : If No -->
        <div id="feedback-negative" style="display:none">

            <form class="block-column-g20" action="/0-ECFEcoride/back/carpool/validate.php" method="POST"
                onsubmit="console.log('Form submitted!')">
                <input type="hidden" name="idReservation" id="popup-idReservation-negative" value="<?= $carpool['reservationId'] ?>">
                <input type="hidden" name="action" value="negative">

                <label for="comment-negative">Décrivez le problème :</label>
                <textarea name="comment" id="comment-negative" required></textarea>
                <div class="btn bg-light-green">
                    <button type="submit">Soumettre</button>
                </div>
            </form>
        </div>
    

    
<?php $popup_content = ob_get_clean(); ?>

<script>
    function showPopupValidate(event) {
        const reservationId = event.target.getAttribute('data-id');
        document.getElementById('popup-idReservation-positive').value = reservationId;
        document.getElementById('popup-idReservation-negative').value = reservationId;
        document.getElementById('popup-id').style.display = 'block';
    }

    function closePopupValidate() {
        document.getElementById('popup-id').style.display = 'none';
        document.getElementById('feedback-positive').style.display = 'none';
        document.getElementById('feedback-negative').style.display = 'none';
        document.getElementById('yesButton').classList.remove('selected-btn');
        document.getElementById('noButton').classList.remove('selected-btn');

        // reset forms
        document.querySelector('#feedback-positive form')?.reset();
        document.querySelector('#feedback-negative form')?.reset();
    }

    function handleValidation($bool) {
        if ($bool == true) {
            document.getElementById('feedback-positive').style.display = 'block';
            document.getElementById('feedback-negative').style.display = 'none';
            document.getElementById('yesButton').classList.add('selected-btn');
            document.getElementById('noButton').classList.remove('selected-btn');

            const commentNegative = document.getElementById('comment-negative') ?? null;
            commentNegative?.removeAttribute('required');

        } else {
            document.getElementById('feedback-negative').style.display = 'block';
            document.getElementById('feedback-positive').style.display = 'none';
            document.getElementById('yesButton').classList.remove('selected-btn');
            document.getElementById('noButton').classList.add('selected-btn');

            const commentNegative = document.getElementById('comment-negative') ?? null;
            commentNegative?.setAttribute('required', '');
        }
    }

</script>

<?php include '../templates/components/template_popup.php'; ?>