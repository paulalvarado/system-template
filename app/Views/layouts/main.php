<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Mi App' ?></title>
    <!-- Styles -->
    <?= view('layouts/styles') ?>
    <!-- Scripts -->
    <?= view('layouts/scripts') ?>
</head>

<body>

    <div class="min-h-screen flex flex-col">
        <!-- Encabezado -->
        <?= view('partials/header') ?>

        <!-- Contenido principal -->
        <main class="flex-1 flex items-center justify-center p-4 md:p-8">
            <?= $this->renderSection('content') ?>
        </main>

        <!-- Pie de página -->
        <?= view('partials/footer') ?>
    </div>
</body>

</html>