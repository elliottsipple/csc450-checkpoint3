<?php

// include config file with db connection
include('config.php');

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
if (empty($cars)) {
    $message = 'No cars available.';
} else {
    $message = 'Showing all vehicles';
}

// if form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dealer_id = $_POST['dealer'];
    $min_price = $_POST['min_price'];
    $max_price = $_POST['max_price'];

    if ($dealer_id == 'any' && $min_price == '' && $max_price == '') {
        // return all vehicles, regarless of dealer or price
        $sql = file_get_contents('sql/getVehicles.sql');
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($cars)) {
            $message = 'No cars available.';
        } else {
            $message = 'Showing all vehicles';
        }
    } else if ($dealer_id == 'any') {
        $no_min = false;
        $no_max = false;
        if ($min_price == '') {
            $min_price = 0;
            $no_min = true;
        }
        if ($max_price == '') {
            $max_price = 999999999;
            $no_max = true;
        }
        // Find vehicles in price range, regarless of dealer
        $sql = file_get_contents('sql/getVehiclesPrice.sql');
        $params = array(
            ':min_price' => $min_price,
            ':max_price' => $max_price
        );
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($cars)) {
            $message = 'No cars available in given price range.';
        } else {
            if ($no_min && !$no_max) {
                $message = 'Showing cars with tag price below $'.$max_price;
            } else if ($no_max && !$no_min) {
                $message = 'Showing cars with tag price above $'.$min_price;
            } else {
                $message = 'Showing cars with tag price between $'.$min_price.' and $'.$max_price;
            }
        }
    } else if ($min_price == '' && $max_price == '') {
        // Find vehicles with selected dealer
        $sql = file_get_contents('sql/getVehiclesDealer.sql');
        $params = array(
            ':dealer_id' => $dealer_id
        );
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($cars)) {
            $message = 'Dealer has no cars available.';
        } else {
            $message = 'Showing cars at '.$cars[0]['DNAME'];
        }
    } else {
        $no_min = false;
        $no_max = false;
        if ($min_price == '') {
            $min_price = 0;
            $no_min = true;
        }
        if ($max_price == '') {
            $max_price = 999999999;
            $no_max = true;
        }
        // find vehicles with selected dealer in given price range
        $sql = file_get_contents('sql/getVehiclesDealerPrice.sql');
        $params = array(
            ':dealer_id' => $dealer_id,
            ':min_price' => $min_price,
            ':max_price' => $max_price
        );
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $cars = $stmt->fetchAll(PDO::FETCH_ASSOC);
        if (empty($cars)) {
            $message = 'Dealer has no cars available in given price range.';
        } else {
            if ($no_min && !$no_max) {
                $message = 'Showing cars at '.$cars[0]['DNAME'].' with tag price below $'.$max_price;
            } else if ($no_max && !$no_min) {
                $message = 'Showing cars at '.$cars[0]['DNAME'].' with tag price above $'.$min_price;
            } else {
                $message = 'Showing cars at '.$cars[0]['DNAME'].' with tag price between $'.$min_price.' and $'.$max_price;
            }
        }
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
            <h1>Find Vehicles</h1>
            <div class="filters">
                <form method="POST" class="customerForm">
                    <div class="customerFormElement">
                        <label for="dealer">Dealer:</label>
                        <select name="dealer" id="dealer">
                            <option value="any">Any</option>
                            <?php foreach($dealers as $dealer): ?>
                                <option value="<?php echo $dealer['DEALER_ID'] ?>">
                                    <?php echo $dealer['DNAME'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="customerFormElement">
                        <label for="min">Price Range:</label>
                        <input type="number" name="min_price" id="min" placeholder="Minimum Price" />
                        <input type="number" name="max_price" placeholder="Maximum Price" />
                    </div>
                    <div class="customerFormElement">
                        <input type="submit" value="Search" />
                    </div>
                </form>
            </div>
        </div>
        <div class="message">
            <?php echo $message; ?>
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
                        <div class="carInfoItem right">
                            <?php echo 'Color: '.ucfirst($car['COLOR']) ?>
                        </div>
                        <div class="carInfoItem">
                            <?php echo 'Engine Type: '.$car['ENGINE'] ?>
                        </div>
                        <div class="carInfoItem right">
                            <?php echo 'Transmission: '.ucfirst($car['TRANSMISSION']) ?>
                        </div>
                        <div class="carInfoItem">
                            <?php echo 'Production Date: '.date("M j, Y", strtotime($car['PRODUCTION_DATE'])) ?>
                        </div>
                        <div class="carInfoItem right">
                            <?php echo 'Price: $'.$car['TAG_PRICE'] ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        <div class="footer">
            <p>Logged in as '<?php echo $user->username ?>'</p>|
            <p><a href="logout.php">Log Out</a></p>
        </div>
    </body>
</html>