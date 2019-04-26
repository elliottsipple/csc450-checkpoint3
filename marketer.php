<?php

include('config.php');

// get query 2 for form
$sql_Marketer_q2 = file_get_contents('sql/Marketer_q2.sql');
$stmt = $conn->prepare($sql_Marketer_q2);
$stmt->execute();
$q2 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get query 3 for form
$sql_Marketer_q3 = file_get_contents('sql/Marketer_q3.sql');
$stmt = $conn->prepare($sql_Marketer_q3);
$stmt->execute();
$q3 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get query 4 for form
$sql_Marketer_q4 = file_get_contents('sql/Marketer_q4.sql');
$stmt = $conn->prepare($sql_Marketer_q4);
$stmt->execute();
$q4 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get query 5 for form
$sql_Marketer_q5 = file_get_contents('sql/Marketer_q5.sql');
$stmt = $conn->prepare($sql_Marketer_q5);
$stmt->execute();
$q5 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get query 6 for form
$sql_Marketer_q6 = file_get_contents('sql/Marketer_q6.sql');
$stmt = $conn->prepare($sql_Marketer_q6);
$stmt->execute();
$q6 = $stmt->fetchAll(PDO::FETCH_ASSOC);

// get query 7 for form
$sql_Marketer_q7 = file_get_contents('sql/Marketer_q7.sql');
$stmt = $conn->prepare($sql_Marketer_q7);
$stmt->execute();
$q7 = $stmt->fetchAll(PDO::FETCH_ASSOC);


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
        <title>Marketer</title>
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <!--div class="page">
            <h1>Find Vehicles</h1>
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
                    <input type="submit" value="Search" />
                </form>
            </div>
        </div-->
        <div>
			<table><!--query 2-->
				<tr><th>bname</th><th>year</th><th>gender</th><th>income_range</th><th>total_sold</th></tr>
				<?php foreach($q2 as $r): ?>
					<tr>
						<td>
							<?php echo $r['BNAME'] ?>
						</td>
						<td>
							<?php echo $r['YEAR'] ?>
						</td>
						<td>
							<?php echo $r['GENDER'] ?>
						</td>
						<td>
							<?php echo $r['INCOME_RANGE'] ?>
						</td>
						<td>
						<?php echo $r['TOTAL_SOLD'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
					
			<table><!--query 3-->
				<tr><th>bname</th><th>total</th>
				<?php foreach($q3 as $r): ?>
					<tr>
						<td>
							<?php echo $r['BNAME'] ?>
						</td>
						<td>
							<?php echo $r['TOTAL'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			
			
			<table><!--query 4-->
				<tr><th>dname</th><th>total</th>
				<?php foreach($q4 as $r): ?>
					<tr>
						<td>
							<?php echo $r['DNAME'] ?>
						</td>
						<td>
							<?php echo $r['TOTAL'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			
			<table><!--query 5-->
				<tr><th>bname</th><th>total</th>
				<?php foreach($q5 as $r): ?>
					<tr>
						<td>
							<?php echo $r['BNAME'] ?>
						</td>
						<td>
							<?php echo $r['TOTAL'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			
			<table><!--query 6-->
				<tr><th>month</th><th>convertibles_sold</th>
				<?php foreach($q6 as $r): ?>
					<tr>
						<td>
							<?php echo $r['MONTH'] ?>
						</td>
						<td>
							<?php echo $r['CONVERTIBLES_SOLD'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
			
			<table><!--query 7-->
				<tr><th>dealer</th>
				<?php foreach($q7 as $r): ?>
					<tr>
						<td>
							<?php echo $r['DEALER'] ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
        </div>
        <div class="footer">
            <p>Logged in as '<?php echo $user->username ?>'</p>|
            <p><a href="logout.php">Log Out</a></p>
        </div>
    </body>
</html>