<?php

$oNav = new Core\Navigation\clNavigation();

?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

        <meta charset="UTF-8">

        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.11.2/css/all.css">
        <link rel="stylesheet" href="/css/?include=libraries/bootstrap">
        <!-- Resources added by the rendering-engine -->
        <?php echo $sTop; ?>

    </head>
    <body class="bg-dark">        
        <div class="container">
	    
	</div>
	<?php echo $sContent; ?>
        <?php echo $sBottom; ?>
	<script src="/js/bootstrap/js/bootstrap.bundle.js"></script>
	<script src="/js/jquery/jquery.js"></script>
	<script src="/js/jquery-easing/jquery.easing.js"></script>
    </body>
</html>
