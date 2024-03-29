<?php 
	include "config.php";
    include "src/PlznitoReader.php";
    
    use JasnaPaka\Plznito\PlznitoReader;
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />

	<title>Plznito Dashboard</title>

	<link href="./public/css/style.css" rel="stylesheet" />
</head>
<body>

<div id="content">

<h1>Plznito Dashboard</h1>

<p>Neoficiální přehled hlášení zadaných do aplikace <a href="http://plznito.cz/">Plznito</a>. Jednotlivá hlášení
jsou řazena podle data poslední úpravy, aby bylo možné sledovat, jak se s jednotlivými hlášeními na městě pracuje.
Připomínky? Náměty? <a href="mailto:jasnapaka@jasnapaka.com">Pište na e-mail</a> či připomínkujte na 
<a href="https://github.com/JasnaPaka/plznito-dashboard">GitHubu</a>.</p>

<?php
	
    $reader = new PlznitoReader(DATA_PATH);

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

<?php if (!$reader->getIsUMO()) { ?>
    <p>
        Hlášení dle obvodu:
        <a href="./?umo=1">UMO 1</a> |
        <a href="./?umo=2">UMO 2</a> |
        <a href="./?umo=3">UMO 3</a> |
        <a href="./?umo=4">UMO 4</a> |
        <a href="./?umo=5">UMO 5</a> |
        <a href="./?umo=6">UMO 6</a> |
        <a href="./?umo=7">UMO 7</a> |
        <a href="./?umo=8">UMO 8</a> |
        <a href="./?umo=9">UMO 9</a> |
        <a href="./?umo=10">UMO 10</a>
    </p>
<?php } else { ?>
    <p><strong>Hlášení jsou omezena na městský obvod Plzeň <?php echo (int) $_GET["umo"] ?>.
    </strong> (<a href="./">zrušit filtr</a>)</p>

<?php } ?>
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

</div>

</body>
</html>