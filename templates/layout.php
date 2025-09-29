<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? "EcoRide") ?></title>
    <?php require_once __DIR__ . "/../functions.php"; ?>
    <script>
        const BASE_URL = "<?= BASE_URL ?>";
    </script>
    <script src="<?= BASE_URL ?>/script/popup.js" defer></script>
    <?php if (!empty($customScript)): ?>
        <script src="<?= BASE_URL ?>/script/<?= $customScript ?>" defer></script>
    <?php endif; ?>
</head>

<body>

    <?php
    include __DIR__ . '/../templates/components/header.php'; ?>


    <?php include $templatePage; ?>


    <?php include __DIR__ . '/../templates/components/footer.php'; ?>
</body>

</html>