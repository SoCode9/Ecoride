<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? "Ecoride") ?></title>
    <script src="../script/popup.js" defer></script>
    <?php if (!empty($customScript)): ?>
        <script src="../script/<?= $customScript ?>" defer></script>
    <?php endif; ?>
</head>

<body>
    <?php include 'components/header.php'; ?>


    <?php include $templatePage; ?>


    <?php include 'components/footer.php'; ?>
</body>

</html>