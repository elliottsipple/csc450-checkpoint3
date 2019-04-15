<?php

include('config.php');
include('functions.php');

$message = '';

// get models for form
$sql_get_models = file_get_contents('sql/getModels.sql');
$stmt = $conn->prepare($sql_get_models);
$stmt->execute();
$models = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get dealers for form
$sql_get_dealers = file_get_contents('sql/getDealers.sql');
$stmt = $conn->prepare($sql_get_dealers);
$stmt->execute();
$dealers = $stmt->fetchAll(PDO::FETCH_ASSOC);

// if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // get vehicle information from form
    $vin = strtoupper($_POST['vin']);
    $model_id = $_POST['model'];
    $dealer_id = $_POST['dealer'];
    $color = strtolower($_POST['color']);
    $engine = $_POST['engine'];
    $transmission = $_POST['transmission'];
    $pdate = $_POST['pdate'];
    $tag_price = $_POST['tag_price'];

    // Make sure pdate is not in the future
    if (date("Y-m-d") >= $pdate && strlen($vin) <= 10) {
        // Make sure VIN is unique
        $sql_get_vehicle = file_get_contents('sql/getVehicle.sql');
        $params = array(
            ':vin' => $vin
        );
        $stmt = $conn->prepare($sql_get_vehicle);
        $stmt->execute($params);
        $vehicles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // if vehicles is empty
        if (empty($vehicles)) {
            // Insert vehicle into vehicle table
            $sql = file_get_contents('sql/insertVehicle.sql');
            $params = array(
                ':vin' => $vin,
                ':model_id' => $model_id,
                ':dealer_id' => $dealer_id,
                ':color' => $color,
                ':engine' => $engine,
                ':transmission' => $transmission,
                ':pdate' => $pdate,
                ':tag_price' => $tag_price
            );
            $conn->beginTransaction();
            $stmt = $conn->prepare($sql);
            $res = $stmt->execute($params);
            // if execution fails
            if ($res === false){
                $message = 'Error inserting vehicle.';
            } else {
                $message = 'Vehicle has been added.';
            }
            $conn->commit();
        } else {
            $message = 'VIN already in use.';
        }
    } else {
        $message = 'Invalid production date or VIN.';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>Producer</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <h1>Add Vehicle To Inventory</h1>
        <form method="POST" class="productionForm">
            <div class="formElement">Model:</div>
            <div class="formElement right">
                <select name="model" id="model">
                    <?php foreach($models as $model): ?>
                        <option value="<?php echo $model['MODEL_ID'] ?>">
                            <?php echo $model['BNAME'] . " " . $model['MNAME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="formElement">VIN:</div>
            <div class="formElement right">
                <input type="text" name="vin" placeholder="VIN" required />
            </div>
            <div class="formElement">Dealer:</div>
            <div class="formElement right">
                <select name="dealer" id="dealer">
                    <?php foreach($dealers as $dealer): ?>
                        <option value="<?php echo $dealer['DEALER_ID'] ?>">
                            <?php echo $dealer['DNAME'] ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="formElement">Color:</div>
            <div class="formElement right">
                <input type="text" name="color" placeholder="Color" required />
            </div>
            <div class="formElement">Engine:</div>
            <div class="formElement right">
                <select name="engine" id="engine">
                    <option value="V4">V4</option>
                    <option value="V6">V6</option>
                    <option value="V8">V8</option>
                </select>
            </div>
            <div class="formElement">Transmission:</div>
            <div class="formElement right">
                <select name="transmission" id="transmission">
                    <option value="automatic">automatic</option>
                    <option value="manual">manual</option>
                </select>
            </div>
            <div class="formElement">Production Date:</div>
            <div class="formElement right">
                <input type="date" name="pdate" id="date" required />
            </div>
            <div class="formElement">Tag Price:</div>
            <div class="formElement right">
                <input type="number" name="tag_price" placeholder="Tag Price" required />
            </div>
            <div class="formSubmit">
                <input type="submit" value="Add Vehicle" />
            </div>
            <div class="alert"><?php echo $message ?></div>
        </form>
        <div class="footer">
            <p>Logged in as '<?php echo $user->username ?>'</p>|
            <p><a href="logout.php">Log Out</a></p>
        </div>
    </body>
</html>