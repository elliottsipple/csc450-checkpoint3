
<?php
$tns = "(DESCRIPTION =
  (ADDRESS = (PROTOCOL = TCP)(HOST = CITDB.NKU.EDU)(PORT = 1521))
  (CONNECT_DATA = (SERVER = DEDICATED)(SERVICE_NAME = csc450.citdb.nku.edu)))";
$db_username = "SIPPLEE1";
$db_password = "csc684";

try {
    $conn = new PDO("oci:dbname=".$tns,$db_username,$db_password);
} catch(PDOException $e) {
    echo ($e->getMessage());
}
//include('config.php');

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get vin, customerid, saledate and price from form as variables
    $vin = $_POST['vin'];
    $customer_id = $_POST['customer_id'];
	$sale_date = $_POST['sale_date'];
	$price = $_POST['price'];

	$sql = file_get_contents('sql/addSale.sql');
	$params = array(
		':vin' => $vin,
        ':customer_id' => $customer_id,
		':sale_date' => $sale_date,
		':price' => $price
    );
	
	$stmt = $conn->prepare($sql);
    $stmt->execute($params);
    $users = $stmt->fetch(PDO::FETCH_ASSOC);
  

}
?>

<!DOCTYPE html>
<html lang="en">
	<head>
        <meta charset="utf-8">
		<title>dealer</title>
		<link rel="stylesheet" href="css/style.css">
	</head>
	<body>
		<h1>Sale</h1>
		<div class="page">
            <form method="POST">
                <!--Gather username and password data from user to attemp login-->
                <input type="text" name="vin" placeholder="VIN" /><br>
                <input type="number" name="customer_id" placeholder="Customer ID" /><br>
				<input type="date" name="sale_date" placeholder="Sale Date" /><br>
				<input type="number" name="price" placeholder="Price" />
                &nbsp <input type="submit" value="Submit" />
            </form>
        </div>
	</body>
</html>