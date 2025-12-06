<?php
$host = 'localhost';
$username = 'lab5_user';
$password = 'password123';
$dbname = 'world';

$conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
$stmt = $conn->query("SELECT * FROM countries");

$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['country'])) {
        $country = filter_var($_GET['country'], FILTER_SANITIZE_STRING); // Sanitize input
        
        // Use a prepared statement to safely insert the variable into the query
        $stmt = $conn->prepare("SELECT * FROM countries WHERE name LIKE :country_name");
        $search_term = '%' . $country . '%'; // Add wildcards for partial search
        $stmt->bindParam(':country_name', $search_term);
        $stmt->execute();
    }
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?php if (count($results) > 0): ?>
    <ul>
        <?php foreach ($results as $row): ?>
            <li>
                <?php echo $row['name']; ?> - Ruled by <?php echo $row['head_of_state']; ?>
            </li>
        <?php endforeach; ?>
    </ul>
<?php else: ?>
    <p>No countries found matching your search.</p>
<?php endif; ?>