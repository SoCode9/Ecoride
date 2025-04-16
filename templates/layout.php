<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($pageTitle ?? "Ecoride") ?></title>

    <?php if (!empty($customScript)): ?>
        <script src="../script/<?= $customScript ?>" defer></script>
    <?php endif; ?>
</head>

<body>
    <?php include 'components/header.php'; ?>

   <!--  <main> -->
        <?php include $templatePage; ?>
  <!--   </main> -->

    <?php include 'components/footer.php'; ?>
</body>

</html>