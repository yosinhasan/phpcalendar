<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_include_path(get_include_path().PATH_SEPARATOR."assets/libs".PATH_SEPARATOR."assets/config".PATH_SEPARATOR."assets/model");
	spl_autoload_register();
AbstractModel::setDB(Database::getDB());
if (isset($_POST['action']) && $_POST['action'] == "edit") {
	$id = abs((int) $_POST["id"]);
    if ($id > 0) {
        $data = new Holiday();
        $data->load($id);
        $name = $_POST['name'];
		$date = $_POST['date'];
		if (!empty($name)) {
			$data->name = $name;
		}
		if (!empty($date)) {
			$data->date = $date;
		}
		if (!empty($name) && !empty($date)) {
			if ($data->save()) {
				echo 1;
			}
		}
    }
	echo 0;
    exit;
}