<main>

    <?php
    $currentTab = $_GET['tab'] ?? 'employees-management';
    ?>

    <div class="flex-row flex-between flex-column-ss">
        <div class="flex-row gap-12 m-tb-12 m-8">
            <span><?= htmlspecialchars($administrator->getPseudo()) ?></span>
            <span><?= htmlspecialchars($administrator->getMail()) ?></span>
        </div>
        <nav class="tabs" style="flex-wrap: wrap;">
            <a class="tab-btn <?= $currentTab === 'employees-management' ? 'active' : '' ?> flex-row"
                data-target="employees-management" href="?tab=employees-management">Comptes employés</a>
            <a class="tab-btn <?= $currentTab === 'users-management' ? 'active' : '' ?> flex-row"
                data-target="users-management" href="?tab=users-management">Comptes utilisateurs</a>
            <a class="tab-btn <?= $currentTab === 'statistic' ? 'active' : '' ?> flex-row" data-target="statistic"
                href="?tab=statistic">Statistiques</a>
        </nav>
    </div>

    <section id="employees-management"
        class="tab-content <?= $currentTab === 'employees-management' ? 'active' : '' ?>">
        <div class="main-header">
            <h2 class="text-green text-bold">Gérer les comptes des employés (<?= count($employeeList) ?>)</h2>
            <a class="btn action-btn" onclick="showPopup('popup-new-employee')">Créer un compte employé</a>

            <?php include __DIR__ . '/../components/popup/new_employee.php'; ?>
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
                                href="../back/user/admin_space.php?action=suspend-employee&id=<?= $employee['id'] ?>">Suspendre</a>
                        <?php elseif ($employee['is_activated'] === 0): ?>
                            <a class="btn bg-light-green"
                                href="../back/user/admin_space.php?action=reactivate-employee&id=<?= $employee['id'] ?>">Réactiver</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <section id="users-management" class="tab-content <?= $currentTab === 'users-management' ? 'active' : '' ?>">
        <div class="flex-row flex-between">
            <h2 class="text-green text-bold">Gérer les comptes des utilisateurs
                (<?= count($passengersList) + count($driversList) + count($passengersAndDriversList) ?>)</h2>

        </div>
        <h3 style="color: black;">Passagers</h3>
        <div class="half-separation m-tb-12 gap-12">

            <?php
            foreach ($passengersList as $passenger): ?>
                <div class="block-light-grey">
                    <div class="flex-row flex-between">
                        <div class="flex-column">
                            <span class="text-bold"><?= htmlspecialchars($passenger['pseudo']) ?></span>
                            <span class="italic"><?= htmlspecialchars($passenger['mail']) ?></span>
                        </div>
                        <?php if ($passenger['is_activated'] === 1): ?>
                            <a class="btn bg-light-red"
                                href="../back/user/admin_space.php?action=suspend-user&id=<?= $passenger['id'] ?>">Suspendre</a>
                        <?php elseif ($passenger['is_activated'] === 0): ?>
                            <a class="btn bg-light-green"
                                href="../back/user/admin_space.php?action=reactivate-user&id=<?= $passenger['id'] ?>">Réactiver</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h3 style="color: black ;">Chauffeurs</h3>
        <div class="half-separation m-tb-12 gap-12">

            <?php foreach ($driversList as $driver): ?>
                <div class="block-light-grey">
                    <div class="flex-row flex-between">
                        <div class="flex-column">
                            <span class="text-bold"><?= htmlspecialchars($driver['pseudo']) ?></span>
                            <span class="italic"><?= htmlspecialchars($driver['mail']) ?></span>
                        </div>
                        <?php if ($driver['is_activated'] === 1): ?>
                            <a class="btn bg-light-red"
                                href="../back/user/admin_space.php?action=suspend-user&id=<?= $driver['id'] ?>">Suspendre</a>
                        <?php elseif ($driver['is_activated'] === 0): ?>
                            <a class="btn bg-light-green"
                                href="../back/user/admin_space.php?action=reactivate-user&id=<?= $driver['id'] ?>">Réactiver</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <h3 style="color: black ;">Passagers-Chauffeurs</h3>
        <div class="half-separation m-tb-12 gap-12">

            <?php foreach ($passengersAndDriversList as $both): ?>
                <div class="block-light-grey">
                    <div class="flex-row flex-between">
                        <div class="flex-column">
                            <span class="text-bold"><?= htmlspecialchars($both['pseudo']) ?></span>
                            <span class="italic"><?= htmlspecialchars($both['mail']) ?></span>
                        </div>
                        <?php if ($both['is_activated'] === 1): ?>
                            <a class="btn bg-light-red"
                                href="../back/user/admin_space.php?action=suspend-user&id=<?= $both['id'] ?>">Suspendre</a>
                        <?php elseif ($both['is_activated'] === 0): ?>
                            <a class="btn bg-light-green"
                                href="../back/user/admin_space.php?action=reactivate-user&id=<?= $both['id'] ?>">Réactiver</a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </section>


    <section id="statistic" class="tab-content <?= $currentTab === 'statistic' ? 'active' : '' ?>">
        <div class="flex-row flex-between">
            <div class="block-light-grey flex-column gap-12 flex-center text-bold item-center"
                style="width:fit-content;">
                <span class="text-green">Crédits gagnés par la plateforme</span>
                <?php $travels = new Travel($pdo); ?>
                <span class="font-size-very-big "><?= htmlspecialchars($travels->getCreditsEarned()); ?></span>
            </div>
            <div class="block-light-grey flex-column gap-12 flex-center text-bold item-center"
                style="width:fit-content;">
                <span class="text-green">Nombre d'utilisateurs</span>
                <span
                    class="font-size-very-big "><?= count($passengersList) + count($driversList) + count($passengersAndDriversList) ?></span>
            </div>
        </div>

        <!--CHART nb carpools in the next 10 days-->
        <div class="block-light-grey flex-column gap-12 flex-center text-bold">
            <span class="text-green">Evolution des covoiturages</span>
            <canvas id="carpools-per-day-chart" width="2500" height="2000"></canvas>
        </div>

        <!--CHART credits earned by the platform over the last 10 days-->
        <div class="block-light-grey flex-column gap-12 flex-center text-bold">
            <span class="text-green">Evolution des crédits gagnés par la plateforme</span>
            <canvas id="credits-earned-by-platform" width="2500" height="2000"></canvas>
        </div>

    </section>

</main>