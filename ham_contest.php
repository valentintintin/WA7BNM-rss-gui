<?php

$contests = json_decode(file_get_contents('contest.json'), true);

//echo '<pre>' . var_export($contests, true) . '</pre>';

function dateToString(?string $dateString): ?string {
	if (!trim($dateString)) {
		return null;	
	}
	
	try {
	return (new Datetime($dateString))->format('D d/m à H:i');
	} catch (\Exception $e) {
		return null;
	}
}

?>

<!doctype html>
<html lang="fr">
<head>
<meta charset="utf-8">
<title>Concours radio</title>
	<style>
		table {
		  border: 1px solid #1C6EA4;
		  background-color: #EEEEEE;
		  width: 100%;
		  text-align: left;
		  border-collapse: collapse;
		}
		table td, tableth {
		  border: 1px solid #AAAAAA;
		  padding: 3px 6px;
		}
		table tr:nth-child(even) {
		  background: #D0E4F5;
		}
		table thead {
		  background: #1C6EA4;
		  background: -moz-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		  background: -webkit-linear-gradient(top, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		  background: linear-gradient(to bottom, #5592bb 0%, #327cad 66%, #1C6EA4 100%);
		  border-bottom: 2px solid #444444;
		}
		table thead th {
		  font-size: 15px;
		  font-weight: bold;
		  color: #FFFFFF;
		  border-left: 2px solid #D0E4F5;
		}
		table thead th:first-child {
		  border-left: none;
		}
	</style>
</head>
<body>
	<h1>Concours radio</h1>
	
	<p><strong><?= count($contests) ?></strong> concours dans les prochaines semaines</p>
	
	<table>
		<thead>
			<tr>
				<th>Nom</th>
				<th>Date début</th>
				<th>Date fin</th>
				<th>Cible</th>
				<th>Bandes</th>
				<th>Modes</th>
				<th>Règles</th>
			</tr>
		</thead>
		
		<tbody>
			<?php foreach ($contests as $contest): ?>
				<tr>
					<td><a target="_blank" href="<?= $contest['link'] ?>"><?= $contest['name'] ?></td>
					<td><?= dateToString($contest['dateStart']) ?? $contest['dateStartString'] ?></td>
					<td><?= dateToString($contest['dateEnd']) ?? $contest['dateEndString'] ?></td>
					<td><?= $contest['geographic focus'] ?? '-' ?></td>
					<td><?= $contest['bands'] ?? '-' ?></td>
					<td><?= $contest['mode'] ?? '-' ?></td>
					<td>
						<?php if (!empty($contest['find rules at'])): ?>
							<a target="_blank" href="<?= $contest['find rules at'] ?>">Règles
						<?php else: ?>
							-
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</body>
</html>