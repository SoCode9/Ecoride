<main>
    <?php
    $currentTab = $_GET['tab'] ?? 'validate-rating';
    ?>

    <div class="flex-row flex-between">
        <div class="flex-row gap-12 m-tb-12 m-8">
            <span><?= htmlspecialchars($employee->getPseudo()) ?></span>
            <span><?= htmlspecialchars($employee->getMail()) ?></span>
        </div>
        <nav class="tabs">
            <button class="tab-btn <?= $currentTab === 'validate-rating' ? 'active' : '' ?>"
                data-target="validate-rating">Valider les avis</button>
            <button class="tab-btn <?= $currentTab === 'bad-carpool' ? 'active' : '' ?>"
                data-target="bad-carpool">Covoiturages mal passés</button>
        </nav>
    </div>
    <section id="validate-rating" class="tab-content <?= $currentTab === 'validate-rating' ? 'active' : '' ?>">
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
                <?php
                $currentTab = $_GET['tab'] ?? 'validate-rating';

                if ($page > 1): ?>
                    <a href="?tab=<?= $currentTab ?>&page=<?= $page - 1 ?>" class="btn">Page précédente</a>
                <?php endif; ?>

                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                    <a href="?tab=<?= $currentTab ?>&page=<?= $i ?>"
                        class="btn <?= $i === $page ? 'bg-light-green' : '' ?>"><?= $i ?></a>
                <?php endfor; ?>

                <?php if ($page < $totalPages): ?>
                    <a href="?tab=<?= $currentTab ?>&page=<?= $page + 1 ?>" class="btn">Page suivante</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    </section>

    <section id="bad-carpool" class="tab-content <?= $currentTab === 'bad-carpool' ? 'active' : '' ?>">
        <h2 class="subTitleGreen">Contrôler les covoiturages mal passés (<?= $totalBadComments ?>)</h2>
        <?php $index = 0;
        foreach ($badComments as $badComment):
            $index++; ?>
            <div class="flex-column gap-12 block-light-grey">
                <div class="grid-4-1">
                    <div class="flex-row flex-between" style="grid-column : 1,2">
                        <div class="flex-row gap-12">
                            <span><?= htmlspecialchars($badComment['pseudoPassenger']) ?></span>
                            <span>~</span>
                            <span class="font-size-very-small"
                                style="padding-right:15px;"><?= htmlspecialchars($badComment['mailPassenger']) ?></span>
                        </div>
                        <span style="padding:0px 15px ">Id du covoiturage : <?= htmlspecialchars($badComment['travelId']) ?></span>
                    </div>
                    <a class="btn bg-light-green"
                        href="../back/employeeSpaceBack.php?action=resolved&id=<?= $badComment['id'] ?>">Litige
                        résolu</a>

                    <p class="text-bold" style="padding-right:15px;">"<?= htmlspecialchars($badComment['bad_comment']) ?>"
                    </p>
                    <div class="flex-column flex-center">
                        <span>De <?= htmlspecialchars($badComment['travel_departure_city']) ?> </span>
                        <span>À <?= htmlspecialchars($badComment['travel_arrival_city']) ?></span>
                    </div>
                    <div class="flex-row gap-4">
                        <img src="..\icons\Voiture.png" class="imgFilter" alt="Icone voiture">
                        <div class="flex-row gap-12">
                            <span><?= htmlspecialchars($badComment['pseudoDriver']) ?></span>
                            <div class="flex-row" style="padding-right:15px;">
                                <?php $driverOfBadComment = new Driver($pdo, $badComment['idDriver']);
                                $averageRating = $driverOfBadComment->getAverageRatings();
                                if ($averageRating !== null) {
                                    echo '<span>(</span>
                                    <img src="..\icons\EtoileJaune.png" class="imgFilter" alt="Icone étoile">'
                                        . htmlspecialchars($averageRating) . '<span> )</span>';
                                } else {
                                    echo "<span class = 'italic font-size-very-small'>(0 avis)</span>";
                                } ?>
                            </div>
                            <span>~</span>
                            <span class="font-size-very-small"><?= htmlspecialchars($badComment['mailDriver']) ?></span>
                        </div>

                    </div>
                    <span> Date du trajet : <?= formatDate(htmlspecialchars($badComment['travel_date'])) ?></span>

                </div>

            </div>
            </div>
            <?php if ($index < $totalBadComments) {
                echo '<hr>';
            }
            ?>

        <?php endforeach; ?>
        <div class="flex-row gap-12 flex-center m-8">
            <?php if ($pageBadComments > 1): ?>
                <a href="?tab=<?= $currentTab ?>&page=<?= $pageBadComments - 1 ?>" class="btn">Page précédente</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $totalPagesBadComments; $i++): ?>
                <a href="?tab=<?= $currentTab ?>&page=<?= $i ?>"
                    class="btn <?= $i === $pageBadComments ? 'bg-light-green' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <?php if ($pageBadComments < $totalPagesBadComments): ?>
                <a href="?tab=<?= $currentTab ?>&page=<?= $pageBadComments + 1 ?>" class="btn">Page suivante</a>
            <?php endif; ?>
        </div>




    </section>
</main>