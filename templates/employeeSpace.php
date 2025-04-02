<main>
    <div class="flex-row flex-between">
        <div class="flex-row gap-12 m-tb-12 m-8">
            <span>Pseudo</span>
            <span>mail@exemple.com</span>
        </div>
        <nav class="tabs">
            <button class="tab-btn active" data-target="validate">Valider les avis</button>
            <button class="tab-btn" data-target="bad-carpool">Covoiturages mal passés</button>
        </nav>
    </div>
    <section class="m-8  flex-column gap-12">
        <h2 class="subTitleGreen">Valider les avis des participants</h2>
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
                            <button class="btn bg-light-green">Valider</button>
                            <button class="btn bg-light-red">Refuser</button>
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
                                <span>(</span>
                                <img src="..\icons\EtoileJaune.png" class="imgFilter" alt="Icone étoile">
                                <span><?= htmlspecialchars($driver->getAverageRatings()) ?>)</span>
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
</main>