<div class="popup" id="popup-id">
    <h3>Valider le trajet</h3>
    <div class="filtersList">
        <!-- Step 1 -->
        <span>Est-ce que tout s’est bien passé ?</span>
        <div style="gap:4px;">
            <button id="yesButton" class="yesNoButton" onclick="handleValidation(true)">Oui</button>
            <button id="noButton" class="yesNoButton" onclick="handleValidation(false)">Non</button>
        </div>


        <!-- Step 2A : If Yes -->
        <div id="feedback-positive" style="display:none">

            <!-- <h3>Valider le trajet</h3>
            <form action="/0-ECFEcoride/back/validate_carpool.php" method="POST">
                <input type="hidden" name="idTravel" id="popup-idTravel-positive" value="">
                <input type="hidden" name="action" value="positive">

                <label for="comment">Laissez un commentaire :</label>
                <textarea name="comment" id="popup-comment" required></textarea>

                <button onclick="submitPositiveJS()">Valider</button>
            </form> 
            <button onclick="document.getElementById('popup-id').style.display = 'none'">Fermer</button>
-->
            <h4>Souhaitez-vous laisser un avis ?</h4>
            <form class="filtersList" action="/0-ECFEcoride/back/validate_carpool.php" method="POST"
                onsubmit="console.log('Form submitted!')">
                <input type="hidden" name="idTravel" id="popup-idTravel-positive" value="">
                <input type="hidden" name="action" value="positive">

                <div class="filter">
                    <label for="driver-rating-list">Note laissée au chauffeur : </label>
                    <select id="driver-rating-list" name="driver-rating-list" style="width: 50px;">
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
                            class="imgFilter"></label>
                </div>

                <label for="comment-positive">Laissez un commentaire :</label>
                <textarea name="comment" id="comment-positive"></textarea>

                <div class="searchButton">
                    <button type="submit" class="legendSearch" onclick="submitPositiveJS()"><strong>Valider le
                            covoiturage</strong><br>(avec ou sans avis)</button>
                </div>
            </form>

        </div>

        <!-- Step 2B : If No -->
        <div id="feedback-negative" style="display:none">
            <input type="hidden" name="idTravel" id="popup-idTravel-negative" value="">

            <form class="filtersList" action="../back/validate_carpool.php" method="POST">
                <label for="comment-negative">Décrivez le problème :</label>
                <textarea name="comment-negative" id="comment-negative" required></textarea>
                <div class="searchButton">
                    <button class="legendSearch">Soumettre</button>
                </div>
            </form>
        </div>
    </div>

    <button type="button" class="close-btn resetButton" style="justify-self:right;"
        onclick="closePopup()">Annuler</button>

</div>

<script>
    function showPopup(event) {
        /* event.preventDefault(); */
        const travelId = event.target.getAttribute('data-id');
        document.getElementById('popup-idTravel-positive').value = travelId;
        document.getElementById('popup-idTravel-negative').value = travelId;
        document.getElementById('popup-id').style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup-id').style.display = 'none';
        document.getElementById('feedback-positive').style.display = 'none';
        document.getElementById('feedback-negative').style.display = 'none';
        document.getElementById('yesButton').classList.remove('buttonSelected');
        document.getElementById('noButton').classList.remove('buttonSelected');

        // reset forms
        document.querySelector('#feedback-positive form')?.reset();
        document.querySelector('#feedback-negative form')?.reset();
    }

    function handleValidation($bool) {
        if ($bool == true) {
            document.getElementById('feedback-positive').style.display = 'block';
            document.getElementById('feedback-negative').style.display = 'none';
            document.getElementById('yesButton').classList.add('buttonSelected');
            document.getElementById('noButton').classList.remove('buttonSelected');

            const commentNegative = document.getElementById('comment-negative') ?? null;
            commentNegative?.removeAttribute('required');

        } else {
            document.getElementById('feedback-negative').style.display = 'block';
            document.getElementById('feedback-positive').style.display = 'none';
            document.getElementById('yesButton').classList.remove('buttonSelected');
            document.getElementById('noButton').classList.add('buttonSelected');

            const commentNegative = document.getElementById('comment-negative') ?? null;
            commentNegative?.setAttribute('required', '');
        }
    }

    function submitPositiveJS() {
        const travelId = document.getElementById('popup-idTravel-positive').value;
        const comment = document.getElementById('comment-positive').value;
        const rating = document.getElementById('driver-rating-list').value;

        fetch('/0-ECFEcoride/back/validate_carpool.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams({
                idTravel: travelId,
                action: 'positive',
                rating: rating,
                comment: comment
            })
        })
            .then(res => res.text())
            .then(data => {
                console.log("Réponse du backend :", data);
                closePopup();
                location.reload();
            })
            .catch(error => {
                console.error("Erreur :", error);
                alert("Une erreur s'est produite.");
            });
    }

</script>