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
            <h2 class="subTitleGreen">Gérer les comptes des employés (<?= count($employeeList) ?>)</h2>
            <a class="participateButton" onclick="showPopup(event)">Créer un compte employé</a>

            <?php include "../templates/newEmployeePopup.php"; ?>
        </div>
        <div class="half-separation m-tb-12 gap-12">
            <?php
            foreach ($employeeList as $employee): ?>
                <div class="block-light-grey">
                    <div class="flex-row flex-between">
                        <div class="flex-column">
                            <span class="text-bold"><?= htmlspecialchars($employee['pseudo']) ?></span>
                            <span class="italic"><?= htmlspecialchars($employee['mail']) ?></span>
                        </div>
                        <?php if ($employee['is_activated'] === 1): ?>
                            <a class="btn bg-light-red"
                                href="../back/adminSpaceBack.php?action=suspend-employee&id=<?= $employee['id'] ?>">Suspendre</a>
                        <?php elseif ($employee['is_activated'] === 0): ?>
                            <a class="btn bg-light-green"
                                href="../back/adminSpaceBack.php?action=reactivate-employee&id=<?= $employee['id'] ?>">Réactiver</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>



    </section>


</main>