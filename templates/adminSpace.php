<main>

    <?php
    $currentTab = $_GET['tab'] ?? 'employees-management';
    ?>

    <div class="flex-row flex-between">
        <div class="flex-row gap-12 m-tb-12 m-8">
            <span><?= htmlspecialchars($administrator->getPseudo()) ?></span>
            <span><?= htmlspecialchars($administrator->getMail()) ?></span>
        </div>
        <nav class="tabs">
            <button class="tab-btn <?= $currentTab === 'employees-management' ? 'active' : '' ?>"
                data-target="employees-management">Gestion des employés</button>
            <button class="tab-btn <?= $currentTab === 'users-management' ? 'active' : '' ?>"
                data-target="users-management">Gestion des utilisateurs</button>
            <button class="tab-btn <?= $currentTab === 'statistic' ? 'active' : '' ?>"
                data-target="statistic">Statistiques</button>
        </nav>
    </div>

    <section id="employees-management"
        class="tab-content <?= $currentTab === 'employees-management' ? 'active' : '' ?>">
        <div class="flex-row flex-between">
            <h2 class="subTitleGreen">Gérer les comptes des employés (nb employés)</h2>
            <a class="participateButton" href="xxx">Créer un compte employé</a>
        </div>
        <div class="half-separation m-tb-12 gap-12">
            <div class="block-light-grey">
                <div class="flex-row flex-between">
                    <div class="flex-column">
                        <span class="text-bold">Pseudo employé</span>
                        <span class="italic">Email employé</span>
                    </div>
                    <a class="btn bg-light-red" href="xxx">Suspendre</a>
                </div>
            </div>
            <div class="block-light-grey">
                <div class="flex-row flex-between">
                    <div class="flex-column">
                        <span class="text-bold">Pseudo employé2</span>
                        <span class="italic">Email employé2</span>
                    </div>
                    <a class="btn bg-light-red" href="xxx">Suspendre</a>
                </div>
            </div>
            <div class="block-light-grey">
                <div class="flex-row flex-between">
                    <div class="flex-column">
                        <span class="text-bold">Pseudo employé3</span>
                        <span class="italic"> Email employé3</span>
                    </div>
                    <a class="btn bg-light-red" href="xxx">Suspendre</a>
                </div>
            </div>
            <div class="block-light-grey">
                <div class="flex-row flex-between">
                    <div class="flex-column">
                        <span class="text-bold">Pseudo employé4</span>
                        <span class="italic">Email employé4</span>
                    </div>
                    <a class="btn bg-light-red" href="xxx">Suspendre</a>
                </div>
            </div>
        </div>



    </section>


</main>