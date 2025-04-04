<main>
    <div class="flex-row flex-between">
        <div class="flex-row gap-12 m-tb-12 m-8">
            <span><?= htmlspecialchars($employee->getPseudo()) ?></span>
            <span><?= htmlspecialchars($employee->getMail()) ?></span>
        </div>
        <nav class="tabs">
            <button class="tab-btn active" data-target="validate-rating">Valider les avis</button>
            <button class="tab-btn" data-target="bad-carpool">Covoiturages mal passés</button>
        </nav>
    </div>
    <section id="validate-rating" class="tab-content active">
        <h2 class="subTitleGreen">Valider les avis des participants (<?= $totalRatings ?>)</h2>
        <?php
        if (isset($ratingsInValidation)):
            $totalRatings = count($ratingsInValidation);
            $index = 0;
            //var_dump($ratingsInValidation);
            foreach ($ratingsInValidation as $rating):
                $driver = new Driver($pdo, $rating['driver_id']);
                $index++;
                ?>
                <div class="flex-column gap-8 block-light-grey">
                    <div class="flex-row flex-between ">
                        <span><?= htmlspecialchars($rating['passenger_pseudo']) ?></span>
                        <div class="flex-row gap-8">
                            <a class="btn bg-light-green"
                                href="../back/employeeSpaceBack.php?action=validate_rating&id=<?= $rating['id'] ?>">Valider</a>
                            <a class="btn bg-light-red"
                                href="../back/employeeSpaceBack.php?action=reject_rating&id=<?= $rating['id'] ?>">Refuser</a>
                        </div>
                    </div>
                    <div class="flex-row flex-between">
                        <span class="text-bold">
                            <?php if (isset($rating['description'])) {
                                echo '"' . htmlspecialchars($rating['description']) . '"';
                            } else {
                                echo '<span class="font-size-very-small italic" style="font-weight:normal">(pas de commentaire)</span>';
                            }
                            ?></span>
                        <div class="flex-row m-8">
                            <img src="..\icons\EtoileJaune.png" class="imgFilter" alt="Icone étoile">
                            <span class="text-bold"><?= htmlspecialchars($rating['rating']) ?></span>
                        </div>
                    </div>
                    <div class="flex-row flex-between">
                        <div class="flex-row gap-4">
                            <img src="..\icons\Voiture.png" class="imgFilter" alt="Icone voiture">
                            <span><?= htmlspecialchars($rating['driver_pseudo']) ?></span>
                            <div class="flex-row font-size-very-small">
                                <?php $averageRating = $driver->getAverageRatings();
                                if ($averageRating !== null) {
                                    echo '<span>(</span>
                                    <img src="..\icons\EtoileJaune.png" class="imgFilter" alt="Icone étoile">'
                                        . htmlspecialchars($averageRating) . '<span> )</span>';
                                } else {
                                    echo "<span class = 'italic'>(0 avis)</span>";
                                } ?>
                            </div>
                        </div>
                        <span
                            class="italic font-size-very-small"><?= formatDate(htmlspecialchars($rating['created_at'])) ?></span>
                    </div>
                </div>
                <?php if ($index !== $totalRatings):
                    echo '<hr>' ?>
                <?php endif; ?>
            <?php endforeach; ?>
            <div class="flex-row gap-12 flex-center m-8">
                <?php if ($page > 1): ?>
                    <a href="?page=<?= $page - 1 ?>" class="btn">Page précédente</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?page=<?= $i ?>" class="btn <?= $i === $page ? 'bg-light-green' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?page=<?= $page + 1 ?>" class="btn">Page suivante</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <section id="bad-carpool" class="tab-content">
        <h2 class="subTitleGreen">Contrôler les covoiturages mal passés (<?= $totalRatings ?>)</h2>

        <div class="flex-column gap-12 block-light-grey">
            <div class="grid-4-1">
                <div class="flex-row gap-12" style="grid-column : 1,2">
                    <span>Pseudo passager</span>
                    <span>~</span>
                    <span class="font-size-very-small" style="padding-right:15px;">mail@passager.com</span>
                </div>
                <a class="btn bg-light-green" 
                    href="../back/employeeSpaceBack.php?action=XXX&id=<?= $rating['id'] ?>">Litige
                    résolu</a>

                <p class="text-bold" style="padding-right:15px;">"Je suis arrivé en retard à mon rendez-vous à cause des multiples arrêts faits par
                    le
                    chauffeur.
                    J'ai tenté de lui expliquer ma situation mais il n'a rien voulu savoir"</p>
                <div class="flex-column flex-center">
                    <span>De Annecy </span>
                    <span>À Grenoble</span>
                </div>
                <div class="flex-row gap-4">
                    <img src="..\icons\Voiture.png" class="imgFilter" alt="Icone voiture">
                    <div class="flex-row gap-12">
                        <span>Pseudo chauffeur</span>
                        <div style="padding-right:15px;">
                            <?php $averageRating = $driver->getAverageRatings();
                            if ($averageRating !== null) {
                                echo '<span>(</span>
                                    <img src="..\icons\EtoileJaune.png" class="imgFilter" alt="Icone étoile">'
                                    . htmlspecialchars($averageRating) . '<span> )</span>';
                            } else {
                                echo "<span class = 'italic font-size-very-small'>(0 avis)</span>";
                            } ?>
                        </div>
                        <span>~</span>
                        <span class="font-size-very-small">mail@chauffeur.com</span>
                    </div>

                </div>
                <span> Date du trajet : 13/07/2025</span>

            </div>




        </div>
        </div>
        <hr>
    </section>
</main>