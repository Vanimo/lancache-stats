<?php
include 'conn.php';

header('Content-Type: application/json');  // Set correct content type for JSON

//Prepare Values

//Query to get Disk Values
$query = "SELECT GBUsed, GBFree FROM cache_disk";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $GBUsed = $row['GBUsed'];
    $GBFree = $row['GBFree'];
}

//Query for Upstream
$query = "SELECT Upstream, SUM(Bytes) AS TotalBytes FROM access_logs WHERE LStatus='HIT' GROUP BY Upstream";
$result = mysqli_query($conn, $query);
$labels = array();
$data = array();
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $labelvalues[] = strtoupper($row['Upstream']);
        $datavalues[] = $row['TotalBytes'] / 1024 / 1024 / 1024;
    }
}

// Query to get Cache Ratio
$query = "SELECT LStatus, SUM(Bytes) AS TotalBytes FROM access_logs GROUP BY LStatus";
$result = mysqli_query($conn, $query);
if ($result) {
    $data = [];
    while ($row = mysqli_fetch_assoc($result)) {
        $data[$row['LStatus']] = $row['TotalBytes'];
    }
    $totalHits = $data['HIT'] / 1024 / 1024 / 1024;
    $totalMiss = $data['MISS'] / 1024 / 1024 / 1024;
}
//Query to get Disk Values
$query = "SELECT sum(Bytes) as Total FROM access_logs WHERE LStatus='HIT'";
$result = mysqli_query($conn, $query);
if ($result) {
    $row = mysqli_fetch_assoc($result);
    $GBServed = number_format($row['Total'] / 1024 / 1024 / 1024, 2);
}

//Query for Games
$query = "SELECT GameName, TotalBytes
FROM (
    SELECT 
        CASE 
            WHEN steamapps.AppName IS NOT NULL AND steamapps.AppName != ''
            THEN steamapps.AppName 
            ELSE access_logs.App 
        END AS GameName,
        SUM(Bytes) AS TotalBytes
    FROM 
        access_logs
    LEFT JOIN 
        steamapps ON access_logs.App = steamapps.AppID
    WHERE 
        Upstream = 'steam'
        AND LStatus = 'HIT'
    GROUP BY 
        GameName
) AS CombinedResults
ORDER BY TotalBytes DESC;
";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $labelvalues4[] = $row['GameName'];
        $datavalues4[] = $row['TotalBytes'] / 1024 / 1024 / 1024;
    }
}

//Query for IPs
$query = "SELECT IP,sum(Bytes) as TotalBytes
FROM access_logs
WHERE LStatus='HIT'
GROUP BY IP
ORDER BY TotalBytes DESC
LIMIT 8;";
$result = mysqli_query($conn, $query);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $IPvalue[] = $row['IP'];
        $GBValuep[] = $row['TotalBytes'] / 1024 / 1024 / 1024;
    }
}

$response = array(
    'GBUsed' => $GBUsed,
    'GBFree' => $GBFree,
    'labelvalues' => $labelvalues,
    'datavalues' => $datavalues,
    'totalHits' => $totalHits,
    'totalMiss' => $totalMiss,
    'GBServed' => $GBServed,
    'labelvalues4' => $labelvalues4,
    'datavalues4' => $datavalues4,
    'IPvalue' => $IPvalue,
    'GBValuep' => $GBValuep,
);

echo json_encode($response);
?>