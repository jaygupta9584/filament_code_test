<?php

define('USE_LOAD_DATA', false); 

try {
    $pdo = new PDO('mysql:host=localhost;dbname=test', 'root', '', [
        PDO::MYSQL_ATTR_LOCAL_INFILE => true
    ]);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    processCSVWithBatchInsert($pdo);
    echo "Data imported using batch insert!";
      
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}

// Function for batch processing using fgetcsv()
function processCSVWithBatchInsert($pdo) {
    $csvFile = fopen('large_file.csv', 'r');
    $batchSize = 5; 
    $batchData = [];

    if ($csvFile !== false) {
        fgetcsv($csvFile);

        for ($i = 0; $i < $batchSize; $i++) {
            $batchData[] = fgetcsv($csvFile);
            if (count($batchData) >= $batchSize) {
                insertIntoDatabase($pdo, $batchData);
                $batchData = [];
            }
        }

        if (!empty($batchData)) {
            insertIntoDatabase($pdo, $batchData);
        }

        fclose($csvFile);
    }
}

// Function for batch inserting data
function insertIntoDatabase($pdo, $batchData) {
    try {
        $placeholders = implode(',', array_fill(0, count($batchData), '(?, ?, ?, ?)')); // Adjust column count
        $stmt = $pdo->prepare("INSERT INTO importtable (name, email, age, country) VALUES $placeholders");

        $flatData = [];
        foreach ($batchData as $row) {
            $flatData = array_merge($flatData, $row);
        }

        $stmt->execute($flatData);
    } catch (Exception $e) {
        echo "Insert Error: " . $e->getMessage();
    }
}
?>
