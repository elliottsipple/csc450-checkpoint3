<?php

include('config.php');
include('functions.php');

// get dealers for form
$sql_get_dealers = file_get_contents('sql/getDealers.sql');
$stmt = $conn->prepare($sql_get_dealers);
$stmt->execute();
$dealers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// initialize cars as all vehicles, regardless of dealer
$sql_get_cars = file_get_contents('sql/getVehicles.sql');
$stmt = $conn->prepare($sql_get_cars);
$stmt->execute();
$cars = $stmt->fetchAll(PDO::FETCH_ASSOC);

// if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dealer_id = $_POST['dealer'];

    // Find vehicles with selected dealer
    $sql = file_get_contents('sql/getVehiclesDealer.sql');
    $params = array(
        ':dealer_id' => $dealer_id,
    );
    $stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if (empty($cars)) {
        'Dealer has no cars available.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Customer</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="page">
            <h1>Search for Cars</h1>
            <div class="filters">
                <form method="POST">
                    <label for="dealer">Dealer:</label>
                    <select name="dealer" id="dealer">
                        <?php foreach($dealers as $dealer): ?>
                            <option value="<?php echo $dealer['DEALER_ID'] ?>">
                                <?php echo $dealer['DNAME'] ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="submit" value="Search for Cars" />
                </form>
            </div>
        </div>
        <div class="carListing">
            <?php foreach($cars as $car): ?>
                <div class="carCard">
                    <img class="brandLogo" src="<?php echo $car['LOGO'] ?>" alt="<?php echo $car['BNAME'] ?>"><br />
                    <?php echo $car['MNAME'] ?><br />
                    <div class="carInfo">
                        <div class="carInfoItem">
                            <?php echo 'VIN: '.$car['VIN'] ?>
                        </div>
                        <div class="carInfoItemRight">
                            <?php echo 'Color: '.ucfirst($car['COLOR']) ?>
                        </div>
                        <div class="carInfoItem">
                            <?php echo 'Engine Type: '.$car['ENGINE'] ?>
                        </div>
                        <div class="carInfoItemRight">
                            <?php echo 'Transmission: '.ucfirst($car['TRANSMISSION']) ?>
                        </div>
                        <div class="carInfoItem">
                            <?php echo 'Production Date: '.date("M j, Y", strtotime($car['PRODUCTION_DATE'])) ?>
                        </div>
                        <div class="carInfoItemRight">
                            <?php echo 'Price: $'.$car['TAG_PRICE'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </body>
</html>