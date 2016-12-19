<?php

class PlznitoReader
{
	const LIST_URL = "http://plznito.cz/api/1.0/tickets/list";

	private $error = false;
	private $data;

	function __construct()
	{
		$this->loadList();
	}

	private function loadList() {
		$str = @file_get_contents(self::LIST_URL);
		if ($str === FALSE) {
			$this->error = true;
			return;
		}

		$this->data = json_decode($str);
		if ($this->data == null) {
			$this->error = true;
			return;
		}

		usort($this->data->items, function($a, $b)
		{
			if ($a->edited == null && $b->edited == null) {
				return strcmp($b->name, $a->name);
			}

			if ($a->edited == null && $b->edited != null) {
				return -1;
			}

			if ($a->edited != null && $b->edited == null) {
				return 1;
			}

			$date1 = strtotime ($a->edited->date);
			$date2 = strtotime ($b->edited->date);

			if ($date1 == $date2) {
				return 0;
			}

			return $date1 > $date2 ? -1 : 1;
		});
	}

	public function getError() {
		return $this->error;
	}

	public function getCount() {
		return $this->data->count;
	}

	public function getItems() {
		return $this->data->items;
	}

	public function getCategoryName($id) {
		switch ($id) {
			case 1:
				return "Pískoviště, hřiště, sportoviště";
			case 2:
				return "Jiné";
			case 3:
				return "Silnice, cyklostezky";
			case 4:
				return "Odpadky, černá skládka";
			case 5:
				return "Chodník";
			case 6:
				return "Kanalizace";
			case 7:
				return "Dopravní značení";
			case 8:
				return "Lavičky, zábradlí";
			case 9:
				return "Veřejné osvětlení";
			case 10:
				return "Veřejná zeleň";
			case 11:
				return "Autovrak";
			case 12:
				return "Zastávky MHD";
			default:
				return "(neznámá - ".$id.")";
		}
	}

	public function getStatusName($id) {
		switch ($id) {
			case 1:
				return "Nové";
			case 2:
				return "V řešení";
			case 3:
				return "Vyřešené";
			case 4:
				return "Odmítnuto";
			case 5:
				return "Nepatří";
			case 6:
				return "Zodpovězeno";
			default:
				return "(neznámý - ".$id.")";
		}
	}

	public function getStatusBackground($id) {
		switch ($id) {
			case 1:
				return "#4986e7";
			case 2:
				return "#ff7537";
			case 3:
				return "#16a765";
			case 4:
				return "#fb4c2f";
			case 5:
				return "#fb4c2f";
			case 6:
				return "#16a765";
			default:
				return "white";
		}
	}

	public function getReportPerson($description) {

		// Tomáš Benda
		if (strpos($description, "TB") !== false || strpos($description, "/tb") !== false) {
			return '<abbr title="Tomáš Benda">TB</abbr>';
		}

		// Tomáš Halada
		if (strpos($description, "TH") !== false || strpos($description, "/th") !== false
			|| strpos($description, "T.H.") !== false) {
			return '<abbr title="Tomáš Halada">TH</abbr>';
		}

		// Pavel Cvrček
		if (strpos($description, "PC") !== false) {
			return '<abbr title="Pavel Cvrček">PC</abbr>';
		}

		// Eva Haunerová
		if (strpos($description, "EH") !== false) {
			return '<abbr title="Eva Haunerová">EH</abbr>';
		}

		return "";
	}

}