<?PHP
function menu(){
	if (isset($_GET['q'])){
		$q=$_GET['q'];
	} else {
		return "kezdolap";
	}
	switch ($q) {
		case "fiok":
			return "fiok";
			break;
		case "termekek":
			return "termekek";
			break;
		case "rolunk":
			return "rolunk";
			break;
		default:
			return "illetektelen-hozzaferes";
			break;
	}
}
?>