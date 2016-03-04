<?php
ini_set('display_errors', 1);
ini_set('error_reporting', E_ALL);
set_include_path(get_include_path().PATH_SEPARATOR."assets/libs".PATH_SEPARATOR."assets/config".PATH_SEPARATOR."assets/model");
	spl_autoload_register();
	
AbstractModel::setDB(Database::getDB());


if (isset($_GET['date'])) echo "выбрана дата ".$_GET['date'];
?>
<!doctype html>
	<html>
		<head>
			<meta charset="utf-8">
			<title>Calendar</title>
			<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.0/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
            <link href="assets/css/style.css" rel="stylesheet" type="text/css"/>

		</head>
		<body>
			<header>
				<section>
				<h1>Choose date</h1>
				<form action="" method="get">
					 <div class="col-sm-10">
                  <input class="form-control" type="number" name="m" placeholder="xx" max="12" min="1" value="<?=$_GET['m']?>"/>
					 </div>
					  <div class="col-sm-10">
                   
				<input class="form-control" type="number" name="y" placeholder="xxxx" max="2037" min="1970" value="<?=$_GET['y']?>"/>
					  </div>
				<input class="btn btn-primary btn-lg active" type="submit" value="show"/>
				</form>
				<a href="holidays.php">manage holidays</a>		
				</section>
				<?php
					my_calendar(array(date("Y-m-d")));
				?>
	
			</header>
			<br />
			<br />
		</body>
		
	</html>

<?php
function my_calendar($fill=array())
{
    $month_names=["январь","февраль","март","апрель","май","июнь",
                "июль","август","сентябрь","октябрь","ноябрь","декабрь"]; 
    if (isset($_GET['y'])) {
        $y=$_GET['y'];
    }
    if (isset($_GET['m'])) {
     $m=$_GET['m'];
    }
    if (isset($_GET['date']) AND strstr($_GET['date'],"-")) {
        list($y,$m)=explode("-",$_GET['date']);
    }
    if (!isset($y) OR $y < 1970 OR $y > 2037) {
        $y=date("Y");
    }
    if (!isset($m) OR $m < 1 OR $m > 12) {
        $m=date("m");
    }
   
    $month_stamp=mktime(0,0,0,$m,1,$y);
    $day_count=date("t",$month_stamp);
    $weekday=date("w",$month_stamp);
    if ($weekday==0) {
        $weekday=7;
    }
    $start=-($weekday-2);
    $last=($day_count+$weekday-1) % 7;
    if ($last==0) {
        $end=$day_count;
    } else {
        $end=$day_count+7-$last;
    }
    $date_start = $y."-".$m."-1";
    $date_end = $y."-".$m."-".$day_count;
   
    $data = $data = AbstractModel::getOnWhere("holidays","*","date BETWEEN ? AND ?", [$date_start, $date_end]); 
    $dates = [];
    $names = [];
    if (is_array($data)) {
        foreach ($data as $date) {
            $dates[$date["id"]] = $date["date"];
            $names[$date["id"]] = $date["name"];
        }
    }
    $today=date("Y-m-d");
    $prev=date('?\m=m&\y=Y',mktime (0,0,0,$m-1,1,$y));  
    $next=date('?\m=m&\y=Y',mktime (0,0,0,$m+1,1,$y));
        $i=0;
    ?>
	<section class="calendar">
	<table class="table table-striped"> 
        <tr>
            <td colspan=7> 
                <table width="100%" border=0 cellspacing=0 cellpadding=0> 
                 <tr> 
                     <td align="left"><a href="<?=$prev ?>" class="glyphicon glyphicon-backward"></a></td> 
                     <td align="center"><?=$month_names[$m-1]," ",$y ?></td> 
                     <td align="right"><a href="<?=$next ?>" class="glyphicon glyphicon-forward"></a></td> 
                 </tr> 
               </table> 
            </td> 
        </tr> 
        <tr>
        
            <td>Пн</td>
            <td>Вт</td>
            <td>Ср</td>
            <td>Чт</td>
            <td>Пт</td>
            <td>Сб</td>
            <td>Вс</td>
        <tr>
    <?php 
        for($d=$start;$d<=$end;$d++) { 
           if (!($i++ % 7)) {
            echo " <tr>\n";
           }
            echo '  <td align="center">';
            if ($d < 1 OR $d > $day_count) {
                 echo "&nbsp";
            } else {
                $now="$y-$m-".sprintf("%02d",$d);
                if (is_array($dates) AND in_array($now,$dates)) {
                    $key = array_search($now,$dates);
                    
                    echo '<b><a title="'.$names[$key].'" href="'.$_SERVER['PHP_SELF'].'?date='.$now.'">'.$d.'</a></b>'; 
                } else {
                    if (($i % 7) == 6 || ($i % 7) == 0) {
                        echo "<b title='weekend'>".$d."</b>";    
                    } else {
                        echo $d;    
                    }
                    
                }
            } 
            echo "</td>\n";
            if (!($i % 7)) {
                echo " </tr>\n";
            }
        } 
    echo "</table></section>"; 

} ?>