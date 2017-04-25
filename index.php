<?php  
require_once 'config.php';
require_once 'class.tracker.php';

//$start = isset($_GET['page']) ? ($_GET['page'] * 5) - 5 : 0;

$tracker = new Tracker($pdo, $start);
$records = $tracker->currentlog;

if ( isset($_GET['submit']) && $_GET['pee'] == 1 || $_GET['poop'] == 1 ) {
	$tracker->set($_GET);
	header("location:index.php");
}


?>
<!DOCTYPE html>
<html>
	<head>
		<title>Dog Potty Tracker</title>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maxiumum-scale=1">
		<link rel="stylesheet" href="_css/style.css">
	</head>
	<body>
		<div class="wrap">
			<header>
				<h1>Dog Potty Tracker</h1>
			</header>
			<form action="" method="get">
				<div>
					<input type="hidden" name="pee" value="0">
					<input id="peebox" class="check-with-label" type="checkbox" value="1" name="pee">
					<label for="peebox" class="form-button">Pee</label>
				</div>
				<div>
					<input type="hidden" name="poop" value="0">
					<input class="check-with-label" type="checkbox" id="poopbox" value="1" name="poop">
					<label for="poopbox" class="form-button">Poop</label>
				</div>
				<div>
					<input type="hidden" name="is_accident" value="0">
					<input class="check-with-label" type="checkbox" value="1" id="accidentbox" name="is_accident">
					<label for="accidentbox" class="form-button accident">Accident</label>
				</div>
				<input type="submit" name="submit" value="log it">
			</form>
			<section id="currentlog">
				<table cellspacing="0">
					<th>Bathroom</th>
					<th>Time</th>
					<th>Accident</th>
						<?php foreach ($tracker->currentpage as $key => $value): ?>
							<tr>
								<td> 
									<?php if ( $value['poop'] == 1 && $value['pee'] == 1 ) {
													echo "Both";
											}
												elseif ( $value['pee'] == 1 ) {
													echo "Pee";
											}
											elseif ( $value['poop'] == 1 ) {
													echo "Poop";
											}
									 ?> 
								 </td>
								 <td>
								 	<?php echo $value['time']; ?>
								 </td>
								 <td>
								 	<?php echo $value['is_accident'] == 1 ? 'Yes' : 'No'; ?>
								 </td>
							 </tr>
						<?php endforeach ?>
				</table>
				<div id="pagination">
					<?php echo isset($_GET['page']) && $_GET['page'] != 1 ? "<a id='arrow-left' href='?page=" . ($_GET['page']-1) . "'>&#8249;</a>" : ''; ?>
					<?php for ($i = 0; $i < ($records->rowCount()/5); $i++) { 
						if ($_GET['page'] == $i + 1 ) {
							echo "<a class='paged' href='?page=" . ($i + 1) . "'><strong>" . ($i + 1) . "</strong></a>";
						} else {
							echo "<a class='paged' href='?page=" . ($i + 1) . "'>" . ($i + 1) . "</a>";
						}
					} ?>
					<?php echo $_GET['page'] <= $records->rowCount()/5 ? "<a id='arrow-right' href='?page=" . ($_GET['page']+1) . "'>&#8250;</a>" : ''; ?>
				</div>
			</section>
			<?php $tracker->avg(); 

			$testinger = array();
			$i = 0;
			foreach ($tracker->whole_log as $arr) {
				if ( in_array_r($arr['date'], $testinger) ) {
					if ( $arr['is_accident'] != 1 ) {
						$testinger[$i-1]['time'][] = $arr['time'];
					}	
				}else{
				$testinger[$i] = array($arr['date']);
				$testinger[$i]['time'][] = $arr['time'];
				$i++;
				}
			}

			//debug($testinger);

			foreach ($testinger as $key) {
					foreach ($key['time'] as $item) {
						$times[] = strtotime("$item");
						//print_r($key['time']);
					}
				//$total[] = array_sum($times)/count($times);
					// $res = 0;
					// for ($i = 1, $num = count($times); $i < $num; $i++) { 
					// 	$res =+ abs($total['times'][$i] - $total['times'][$i-1]);
					// 		echo "\n";
					// 	}
					// 	echo $res/($num-1);
					}
			//debug($times);
			//debug($total);

			$info = array();
			foreach ($tracker->whole_log as $arr) {
				if ( $arr['is_accident'] != 1 ) {
					$info['log'][] = $arr;
					}
				}
			// 	$total = array();
			// foreach ($info['log'] as $key => $value) {
			// 	$total['times'][] = strtotime("$value[date] $value[time]");
			// }

			$res = 0;
			for ($i = 1, $num = count($total); $i < $num; $i++) { 
				$res =+ abs($total['times'][$i] - $total['times'][$i-1]);
				echo "\n";
			}
				echo $res/($num-1);
			 ?>
			<table id="thelog">
			 	<?php foreach ($testinger as $key => $value): ?>
			 			<th>
			 				<?php echo "$value[0]"; ?>
			 			<th>
			 			<th>
			 				Bathroom
			 			</th>
			 			<?php foreach ($value['time'] as $time): ?>
			 				<?php $query = $pdo->query("SELECT * FROM log WHERE date = '$value[0]' and time = '$time'")->fetch(); ?>
			 				<tr <?php echo $query['is_accident'] ? "id='accident'" : ''; ?>>
			 					<td><?php echo "$time"; ?></td>
			 					<td>
			 						<?php 
			 							if ( $query['poop'] == 1 && $query['pee'] == 1 ) {
													echo "Both";
											}
												elseif ( $query['pee'] == 1 ) {
													echo "Pee";
											}
											elseif ( $query['poop'] == 1 ) {
													echo "Poop";
											}
			 						 ?>
			 					</td>
			 				</tr>
			 			<?php endforeach ?>
				 <?php endforeach ?>
			</table>
		</div>
	</body>
</html>