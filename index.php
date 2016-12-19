<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<title>Plznito Dashboard</title>

	<link href="./css/style.css" rel="stylesheet" />
</head>
<body>

<h1>Plznito Dashboard</h1>

<p>Neoficiální přehled hlášení zadaných do aplikace <a href="http://plznito.cz/">Plznito</a>. Jednotlivá hlášení
jsou řazena podle data poslední úpravy, aby bylo možné sledovat, jak se s jednotlivými hlášeními na městě pracuje.
Připomínky? Náměty? <a href="mailto:jasnapaka@jasnapaka.com">Pište</a>.</p>

<?php
	include "PlznitoReader.php";
	$reader = new PlznitoReader();

	if ($reader->getError()) {
?>

<div id="error">
    <strong>Seznam hlášení se nepodařilo načíst. Server je pravděpodobně nedostupný. Zkuste to prosím později.</strong>
</div>

<?php
	} else  {
?>

<p><strong>Počet hlášení</strong>: <?php print($reader->getCount()) ?> |
	<a href="http://plznito.cz/map#!/add">Přidat nové</a></p>

<?php
		if ($reader->getCount() > 0) {
			print('<table><col width="5%" /><col width="35%" /><col width="25%" /><col width="15%" /><col width="15%" /><col width="5%" />');
			print('<thead><tr><th>Id</th><th>Název</th><th>Kategorie</th><th>Stav</th><th>Poslední úprava</th>
				<th><abbr title="Iniciály některých osob, které si podepisují svá hlášení do aplikace.">Inic.</abbr></th></tr></thead><tbody>');

			foreach ($reader->getItems() as $item) {
				print ('<tr>');
				printf ('<td>%s</td>', $item->id);
				printf ('<td><a href="http://plznito.cz/map#!/activity/%d">%s</a></td>', $item->id, $item->name);
				printf ('<td>%s</td>', $reader->getCategoryName($item->category_id));
				printf ('<td><span class="item-status" style="background-color: %s">%s</span></td>', $reader->getStatusBackground($item->status_id), $reader->getStatusName($item->status_id));
				printf ('<td>%s</td>', $item->edited != null ? date('d. m. Y H:i', strtotime($item->edited->date)) : "(neuvedeno)");
				printf ('<td>%s</td>', $reader->getReportPerson($item->description));
				print ('</tr>');
			}

			print('</tbody></table>');
		}
	}
?>

</body>
</html>