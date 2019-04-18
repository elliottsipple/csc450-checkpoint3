<?php

// get database connection information from config
include('config.php');

// get available vehicles
$sql_get_vehicles = file_get_contents('sql/getVehicles.sql');
$stmt = $conn->prepare($sql_get_vehicles);
$stmt->execute();
$vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get customers
$sql_get_customers = file_get_contents('sql/getCustomers.sql');
$stmt = $conn->prepare($sql_get_customers);
$stmt->execute();
$customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get VIN, customer_id, sdate and price from form
    $vin = $_POST['vin'];
    $customer_id = $_POST['customer'];
	$sdate = $_POST['sdate'];
	$price = $_POST['price'];

    // make sure sdate is not in future
    if (date("Y-m-d") >= $sdate) {
        // insert sale into sale table
        $sql = file_get_contents('sql/insertSale.sql');
	    $params = array(
            ':vin' => $vin,
            ':customer_id' => $customer_id,
            ':sdate' => $sdate,
            ':price' => $price
        );
        $conn->beginTransaction();
        $stmt = $conn->prepare($sql);
        $res = $stmt->execute($params);
        // if execution fails
        if ($res === false){
            $message = 'Error inserting sale.';
        } else {
            $message = 'Sale has been added.';
        }
        $conn->commit();
    } else {
        $message = 'Sale date cannot be in the future.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8">
		<title>Dealer</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<h1>Add Transaction</h1>
        <form method="POST" class="dealerForm">
            <div class="formElement">Vehicle:</div>
            <div class="formElement right">
                <select name="vin">
                    <?php foreach($vehicles as $vehicle): ?>
                        <option value="<?php echo $vehicle['VIN'] ?>">
                            <?php echo $vehicle['BNAME'] . " " . $vehicle['MNAME'] . " - " . $vehicle['VIN'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="formElement">Customer:</div>
            <div class="formElement right">
                <select name="customer">
                    <?php foreach($customers as $customer): ?>
                        <option value="<?php echo $customer['CUSTOMER_ID'] ?>">
                            <?php echo $customer['CNAME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="formElement">Sale Date:</div>
            <div class="formElement right">
                <input type="date" name="sdate" required />
            </div>
            <div class="formElement">Price Sold:</div>
            <div class="formElement right">
                <input type="number" name="price" placeholder="Price Sold" required />
            </div>
            <div class="formSubmit">
                <input type="submit" value="Add Sale" />
            </div>
            <div class="alert"><?php echo $message ?></div>
        </form>
        <div class="footer">
            <p>Logged in as '<?php echo $user->username ?>'</p>|
            <p><a href="logout.php">Log Out</a></p>
        </div>
	</body>
</html>