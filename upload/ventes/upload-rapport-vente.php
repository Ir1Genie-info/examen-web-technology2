<?php
    if(isset($_POST['date1']) && isset($_POST['date2'])){
        $date1 = $_POST['date1'];
        $date2 = $_POST['date2'];
        header('Location:../../views/rapport-ventes.php?date1='.$date1.'&date2='.$date2);
    }