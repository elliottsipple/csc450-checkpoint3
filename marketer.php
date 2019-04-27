<?php

// include config file with db connection
include('config.php');

// initialize query as 0
$query = 0;

// if form is submitted
if($_SERVER['REQUEST_METHOD'] == 'POST') {
	// set queryNum to given query
	$query = $_POST['query'];

	// get query data
	$sql = file_get_contents('sql/marketerQ'.$query.'.sql');
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$results = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
        <div class="page">
            <h1>View Query Data</h1>
            <div class="filters">
                <form method="POST">
                    <select name="query">
                        <option value="2">Show sales trends over the past 3 years</option>
						<option value="3">Show top brand(s) by dollar-amount sold in the past year</option>
						<option value="4">Show top dealer(s) by dollar-amount sold in the past year</option>
						<option value="5">Show top brand(s) by unit sales in the past year</option>
						<option value="6">In what month(s) in which convertibles sell best?</option>
						<option value="7">Which dealer(s) keep a vehicle in inventory for the longest average time?</option>
                    </select>
                    <input type="submit" value="Search" />
                </form>
            </div>
        </div>
		<?php if($query != 0): ?>
        <div class="tableContainer">
			<?php switch($query):
			case 2: ?>
				<h3>Sales Trends</h3>
				<table>
					<tr>
						<th>Brand</th>
						<th>Year</th>
						<th>Gender</th>
						<th>Income Range</th>
						<th>Total Sold</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td class="left"><?php echo $result['BNAME'] ?></td>
							<td><?php echo $result['YEAR'] ?></td>
							<td><?php echo $result['GENDER'] ?></td>
							<td><?php echo $result['INCOME_RANGE'] ?></td>
							<td class="right"><?php echo '$'.$result['TOTAL_SOLD'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php case 3: ?>
				<h3>Brands by Dollar-Amount Sold in the Past Year</h3>
				<table>
					<tr>
						<th>Brand</th>
						<th>Total Sold</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td class="left"><?php echo $result['BNAME'] ?></td>
							<td class="right"><?php echo '$'.$result['TOTAL'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php case 4: ?>
				<h3>Dealers by Dollar-Amount Sold in the Past Year</h3>
				<table>
					<tr>
						<th>Dealer Name</th>
						<th>Total Sold</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td class="left"><?php echo $result['DNAME'] ?></td>
							<td class="right"><?php echo '$'.$result['TOTAL'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php case 5: ?>
				<h3>Brands by Units Sold in the Past Year</h3>
				<table>
					<tr>
						<th>Brand</th>
						<th>Total Sold</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td class="left"><?php echo $result['BNAME'] ?></td>
							<td class="right"><?php echo $result['TOTAL'].' units' ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php case 6: ?>
				<h3>Convertible Sales</h3>
				<table>
					<tr>
						<th>Month</th>
						<th>Convertibles Sold</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td><?php echo ucfirst($result['MONTH']) ?></td>
							<td class="right"><?php echo $result['CONVERTIBLES_SOLD'] ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php case 7: ?>
				<h3>Dealers by Average Time a Vehicle Stays in Inventory</h3>
				<table>
					<tr>
						<th>Dealer Name</th>
						<th>Average Time in Inventory</th>
					</tr>
					<?php foreach($results as $result): ?>
						<tr>
							<td class="left"><?php echo $result['DEALER'] ?></td>
							<td class="right"><?php echo round($result['AVG_TIME_IN_INVENTORY'], 2).' days' ?></td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php break; ?>
			<?php endswitch; ?>
        </div>
		<?php endif; ?>
        <div class="footer">
            <p>Logged in as '<?php echo $user->username ?>'</p>|
            <p><a href="logout.php">Log Out</a></p>
        </div>
    </body>
</html>