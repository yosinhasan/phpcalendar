<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_include_path(get_include_path().PATH_SEPARATOR."assets/libs".PATH_SEPARATOR."assets/config".PATH_SEPARATOR."assets/model");
	spl_autoload_register();
AbstractModel::setDB(Database::getDB());
if (isset($_POST['action']) && $_POST['action'] == "add") {
    $name = $_POST["name"];
    $date = $_POST["date"];
    $newData = new Holiday();
    $cond = AbstractModel::getOnWhere("holidays","*","name = ? AND date = ?", [$name, $date]);
    if (!$cond) {
        $newData->name = $name;
        $newData->date = $date;
        $newData->save();
    }
   
}
if (isset($_GET['action']) && $_GET['action'] == "delete") {
    $id = abs((int) $_GET["id"]);
    if ($id > 0) {
        $data = new Holiday();
        $data->load($id);
        $data->delete();
    }
}
$data =  AbstractModel::getAll("holidays",false);

?>


<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8" />
            <title>Add holiday</title>
            <link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
            <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>
        </head>
        <body>
            <section class="add">
                <h1>Add new holiday</h1>
                <form action="holidays.php" method="post">
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">Name</label>
                    <div class="col-sm-10">
                        <input class="form-control"  required="required" type="text" name="name" placeholder="write holiday's name" />
                    </div>
                    </div>
                    <div class="form-group">
                        <label  class="col-sm-2 control-label">Date</label>
                        <div class="col-sm-10">
                            <input class="form-control"  required="required" type="date" name="date" placeholder="yyyy-mm-dd" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">
                        </div>
                     </div>
               
                    <input  type="hidden" name="action" value="add" >
                    <input class="btn btn-primary btn-lg active" type="submit" value="add holidays" />
                    
                </form>
            </section>
            <section>
                <h1>Delete/edit holiday</h1>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>id</th>
                            <th>name</th>
                            <th>date</th>
                            <th>delete</th>
                            <th>edit</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($data as $item):?>
                        <tr>
                            <td><?=$item['id'];?></td>
                            <td><?=$item['name'];?></td>
                            <td><?=$item['date'];?></td>
                            <td class="delete"><a href="holidays.php?action=delete&id=<?=$item['id'];?>">delete</a></td>
                            <td class="edit">edit</td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
              </section>
                <script type="text/javascript" src="assets/js/jquery.js"></script>
                <script type="text/javascript" src="assets/js/work.js"></script>
            <div id="editForm">
                <form>
                    <div class="col-sm-10">
                        <input class="form-control"  required="required" type="text" name="name"
                               placeholder="name" />
                    </div>
                     <div class="col-sm-10">
                        <input class="form-control"  required="required" type="date" name="date" placeholder="yyyy-mm-dd"
                               pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])">
                      </div>
                    <div class="col-sm-10">
                        <input  type="hidden" name="id" value="" >
                    
                        <button class="btn btn-primary btn-sm active">save</button>
                    </div>
                </form>
            </div>                 
        </body>
        
    </html>