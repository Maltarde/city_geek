<?php
	//Inclusion Connection Serveur

	include("../Modele/serveur.php");
	
	//Inclusion Requete SQL
	
	include("../Modele/membre.php");
	
	//Membre Connecté ou Déconnecté
	
	$connect=0;  //Membre connecté ou non
	
	if(isset($_COOKIE['pseudo']))  //test membre connecté
	{
		
		$Inter_Membre=recupererMembre($bdd, $_COOKIE['pseudo']);
		$Membre=$Inter_Membre->fetch();
		if($_COOKIE['pass'] == $Membre['pass'])   //Membre Connecté si vrai
		{
			setcookie('pseudo', $_COOKIE['pseudo'], time()+3600*2, null, null, false, true);
			setcookie('pass', $_COOKIE['pass'], time()+3600*2, null, null, false, true);
			
			$connect=1;
			
			//Bonton Déconnection
			
			if(isset($_POST["Deconnection"]))
			{
				setcookie('pseudo', NULL, -1);
				setcookie('pass', NULL, -1);
				
				header('Location: Acceuil.php');
			}
			
		}
	}
	else
	{
		header('Location: Acceuil.php');
	}
	

	//-------------
	
	$action="Acceuil";
?>

<!-- Inclusion du code html/css -->

<!DOCTYPE html>
<html>
    <head>
		<?php
			include("../Vue/html_css/head_General.php");
			include("../Vue/html_css/head_Nav_Administration.php");
		?>	
    </head>
	
	<body>
		<div class="container">
			<div class="header item">
				<?php
					include("../Vue/html/html_Header.php");
				?>	
			</div>
			<div class="flexbody">
				<div class="aside_left item">
					<?php
						include("../Vue/html/html_Aside_Left_Administration.php");
					?>	
				</div>
				<div class="content item">
					<div class="nav">
						<?php
							include("../Vue/html/html_Nav_General.php");
						?>	
					</div>
					<div class="article">
						<?php
							include("../Vue/html/html_Article_Administration.php");
						?>	
					</div>
				</div>
			</div>
			<div class="footer">
				<?php
					include("../Vue/html/html_Footer.php");
				?>	
			</div>
		</div>
	</body>
</html>