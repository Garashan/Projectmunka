<?php
$path = trim($_GET['path'] ?? '', '/');
$segments = $path === '' ? [] : explode('/', $path);

echo '<nav class="breadcrumb">';
echo '<a href="/termekek">Termékek</a>';

$url = '/termekek';

foreach ($segments as $segment) {

    $url .= '/' . rawurlencode($segment);

    echo ' &raquo; ';
    echo '<a href="' .
         htmlspecialchars($url, ENT_QUOTES, 'UTF-8') .
         '">' .
         htmlspecialchars($segment, ENT_QUOTES, 'UTF-8') .
         '</a>';
}

echo '</nav>';

?>
<?php



$parentId = null;
$category = null;
$product = null;

foreach ($segments as $index => $slug) {

    // Kategória keresése az aktuális szinten
    if ($parentId === null) {

        $stmt = $connect->prepare(
            "SELECT id, nev
             FROM projectmunka_kategoriak
             WHERE parent_id IS NULL AND slug = ?"
        );

        $stmt->bind_param("s", $slug);

    } else {

        $stmt = $connect->prepare(
            "SELECT id, nev
             FROM projectmunka_kategoriak
             WHERE parent_id = ? AND slug = ?"
        );

        $stmt->bind_param("is", $parentId, $slug);
    }

    $stmt->execute();

    $category = $stmt->get_result()->fetch_assoc();

    $stmt->close();

    if ($category) {

        $parentId = $category['id'];

        continue;
    }

    // Ha nem kategória, ellenőrizzük, hogy termék-e.
    // Termék kizárólag az útvonal végén szerepelhet.

    if ($index === count($segments) - 1 && $parentId !== null) {

        $stmt = $connect->prepare(
            "SELECT id, nev, leiras
             FROM projectmunka_termekek
             WHERE kategoria_id = ? AND slug = ?"
        );

        $stmt->bind_param("is", $parentId, $slug);

        $stmt->execute();

        $product = $stmt->get_result()->fetch_assoc();

        $stmt->close();

        if ($product) {
            break;
        }
    }

    http_response_code(404);
    exit('A keresett kategória vagy termék nem található!');
}

?>