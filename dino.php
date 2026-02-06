<?php
echo "<h1>DINOAPI</h1>";

$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$url = "https://dinoapi.brunosouzadev.com/api/dinosaurs";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!$data) {
    die("Error al consumir la API");
}

$filteredData = $data;
if (!empty($search)) {
    $filteredData = array_filter($data, function($dino) use ($search) {
        return stripos($dino['name'], $search) !== false || 
               stripos($dino['diet'], $search) !== false ||
               stripos($dino['period'], $search) !== false;
    });
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DINOAPI</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 20px auto;
            padding: 0 20px;
        }
        .search-box {
            margin: 20px 0;
            padding: 20px;
            background: #f5f5f5;
            border-radius: 8px;
        }
        .search-box input[type="text"] {
            width: 70%;
            padding: 10px;
            font-size: 16px;
            border: 2px solid #ddd;
            border-radius: 4px;
        }
        .search-box button {
            padding: 10px 20px;
            font-size: 16px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            margin-left: 10px;
        }
        .search-box button:hover {
            background: #45a049;
        }
        .dino-card {
            border: 1px solid #ddd;
            padding: 15px;
            margin: 10px 0;
            border-radius: 8px;
            background: white;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .dino-card h3 {
            margin-top: 0;
            color: #2c3e50;
        }
        .no-results {
            text-align: center;
            padding: 40px;
            color: #666;
        }
        .results-count {
            color: #666;
            margin: 10px 0;
        }
    </style>
</head>
<body>

<div class="search-box">
    <form method="GET" action="">
        <input 
            type="text" 
            name="search" 
            placeholder="Buscar por nombre, dieta o período..." 
            value="<?= htmlspecialchars($search) ?>"
        >
        <button type="submit">Buscar</button>
        <?php if (!empty($search)): ?>
            <a href="?" style="margin-left: 10px; text-decoration: none; color: #666;">✖ Limpiar</a>
        <?php endif; ?>
    </form>
</div>

<?php if (!empty($search)): ?>
    <p class="results-count">
        Se encontraron <strong><?= count($filteredData) ?></strong> resultados para "<?= htmlspecialchars($search) ?>"
    </p>
<?php else: ?>
    <p class="results-count">
        Mostrando <strong><?= count($filteredData) ?></strong> dinosaurios
    </p>
<?php endif; ?>

<?php if (empty($filteredData)): ?>
    <div class="no-results">
        <h3>No se encontraron resultados</h3>
        <p>Intenta con otra búsqueda</p>
    </div>
<?php else: ?>
    <?php foreach ($filteredData as $dino): ?>
        <div class="dino-card">
            <h3><?= htmlspecialchars($dino['name']) ?></h3>
            <p><strong>Dieta:</strong> <?= htmlspecialchars($dino['diet']) ?></p>
            <p><strong>Período:</strong> <?= htmlspecialchars($dino['period']) ?></p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

</body>
</html>