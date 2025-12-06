<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

try {
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", 
                    $username, 
                    $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Connection failed: " . htmlspecialchars($e->getMessage()));
}

$country = isset($_GET['country']) ? trim($_GET['country']) : '';
$lookup = isset($_GET['lookup']) ? trim($_GET['lookup']) : '';
$country = htmlentities($country, ENT_QUOTES, 'UTF-8');

if ($lookup === 'cities') {
    $query = "SELECT cities.name, cities.district, cities.population 
              FROM cities 
              JOIN countries ON cities.country_code = countries.code 
              WHERE countries.name LIKE :country 
              ORDER BY cities.population DESC";
} else {
    $query = "SELECT name, continent, indep_year, head_of_state 
              FROM countries 
              WHERE name LIKE :country 
              ORDER BY name ASC";
}

try {
    $stmt = $conn->prepare($query);
    $search_param = "%$country%";
    $stmt->bindParam(':country', $search_param, PDO::PARAM_STR);
    $stmt->execute();
    $results = $stmt->fetchAll();
    
    if (count($results) > 0) {
        if ($lookup === 'cities') {
            echo '<h3>Cities in ' . htmlspecialchars($country) . '</h3>';
            echo '<table>';
            echo '<thead><tr><th>Name</th><th>District</th><th>Population</th></tr></thead>';
            echo '<tbody>';
            
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['district']) . '</td>';
                echo '<td>' . number_format($row['population']) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        } else {
            echo '<h3>Country Information</h3>';
            echo '<table>';
            echo '<thead><tr><th>Name</th><th>Continent</th><th>Independence Year</th><th>Head of State</th></tr></thead>';
            echo '<tbody>';
            
            foreach ($results as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['continent']) . '</td>';
                echo '<td>' . ($row['indep_year'] ? htmlspecialchars($row['indep_year']) : 'N/A') . '</td>';
                echo '<td>' . htmlspecialchars($row['head_of_state']) . '</td>';
                echo '</tr>';
            }
            
            echo '</tbody></table>';
        }
    } else {
        echo '<div class="message">No results found for "' . htmlspecialchars($country) . '"</div>';
    }
    
} catch(PDOException $e) {
    echo '<div class="error">Database error: ' . htmlspecialchars($e->getMessage()) . '</div>';
}

$conn = null;
?>