`<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Inicio</title>
        <link rel="stylesheet" href="styles/styles.css">
	<link rel="stylesheet" href="styles/tables.css">
	<link rel="stylesheet" href="styles/form.css">
        <link rel="stylesheet" href="styles/lateralMenu.css">
        <link rel="stylesheet" href="styles/muro.css">

       <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
  <link rel="stylesheet" href="/resources/demos/style.css">   
</head>
<body>
	<header>
		<div class="wrapp">
			<div class="logo">
				<a href="#"><img src="./resources/reddit.png" alt="img"></a>
			</div>
			<nav>
				<ul>
					
					
					
					
                                <?php
                                        if (!session_id()) {
                                                session_start();
                                        }
                                        if(isset($_SESSION["user"])){
                                                echo '<li><a href="index.php">Inicio</a></li>';
                                                echo '<li><a href="index.php?section=salir">Salir</a></li>';
                                        }
                                        else{
                                            echo '<li><a href="index.php?section=login">Log in</a></li>';
                                            echo '<li><a href="index.php?section=reg_user">Sign in</a></li>';

                                        }
                                ?>
				</ul>
			</nav>
		</div>
	</header>
