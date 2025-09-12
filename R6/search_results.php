<?php
if (isset($_GET['search_query'])) {
    $search_query = $_GET['search_query'];

    $sanitized_query = htmlspecialchars($search_query); 


    $data = ["manzana", "banana", "naranja", "uva", "mora"];
    $results = [];

    foreach ($data as $item) {
        if (stripos($item, $sanitized_query) !== false) { // buscar sin importar las mayusculas o minusculas
            $results[] = $item;
        }
    }

    // Mostar los resultados
    if (!empty($results)) {
        echo "<h2>Mostrando resultados de: \"{$sanitized_query}\"</h2>";
        echo "<ul>";
        foreach ($results as $result) {
            echo "<li>{$result}</li>";
        }
        echo "</ul>";
    } else {
        echo "<p>No hay resultados para \"{$sanitized_query}\".</p>";
    }

} else {
    echo "<p>>>>>Buscar en los registros.</p>";
}
?>

