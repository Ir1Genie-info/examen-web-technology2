<?php
    ob_start(); 
    $titre = "Apropos";
?>


<?php
        $contenu = ob_get_clean();
        require('temblate.php')
    ?>