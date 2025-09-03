<?php
	$manip = fopen(''.$idArticle.'.php', "w+");
	fputs($manip, 
	'
		<?php
			// ---- Inclusion Connection Serveur ---- // 

			include("../Modele/serveur.php");
			
			// ---- Inclusion Requete SQL ---- //
			
			include("../Modele/membre.php");
			include("../Modele/article_description.php");
			include("../Modele/panier.php");
			
			// ---- Cookies taille produit panier ---- //
			
			if(!isset($_COOKIE[\'panier\']) || isset($_COOKIE[\'panier\']))
			{
				$sortieBoucle=0;
				$indiceCookie=1;
				
				while(!$sortieBoucle)
				{
					if( isset($_COOKIE[\'newarticle\'.$indiceCookie.\'\']) || isset($_COOKIE[\'oldarticle\'.$indiceCookie.\'\']) )
					{
						$indiceCookie++;
					}
					else
					{
						$sortieBoucle=1;
					}				
				}
				setcookie(\'panier\', $indiceCookie, time()+3600*24*30, null, null, false, true);
			}
			
			// ---- Gestion cookies recherche/trie ---- //
			
			if(isset($_COOKIE[\'reach\']) && isset($_COOKIE[\'categorie\']))
			{
				$recherchetext=$_COOKIE[\'reach\'];
				$rechechecategorie=$_COOKIE[\'categorie\'];
				
				setcookie(\'reach\', NULL, -1);
				setcookie(\'categorie\', NULL, -1);
			}
			
			if(isset($_COOKIE[\'trie\']))
			{
				setcookie(\'trie\', NULL, -1);
			}
			
			// ---- Membre Connecté ou Déconnecté ---- //
			
			$connect=0;  //Membre connecté ou non
			
			if(isset($_COOKIE[\'pseudo\']))  //Test si membre connecté
			{
				
				$Inter_Membre=recupererMembre($bdd, $_COOKIE[\'pseudo\']);
				$Membre=$Inter_Membre->fetch();
				if($_COOKIE[\'pass\'] == $Membre[\'pass\'])   //Membre Connecté si vrai
				{
					setcookie(\'pseudo\', $_COOKIE[\'pseudo\'], time()+3600*2, null, null, false, true);
					setcookie(\'pass\', $_COOKIE[\'pass\'], time()+3600*2, null, null, false, true);
					
					$connect=1;
					
					//Bonton Déconnection
					
					if(isset($_POST["Deconnection"]))
					{
						setcookie(\'pseudo\', NULL, -1);
						setcookie(\'pass\', NULL, -1);
						
						header(\'Location: Acceuil.php\');
					}
				}
				
				//Affichage header panier
				
				$nbArticleAffichage=0;
				$prixPanierAffichage=0;
				$petitPrixPanierAffichage=0;
				
				$Inter_article=recupererPanierPseudo($bdd, $_COOKIE[\'pseudo\']);
				while($article=$Inter_article->fetch())
				{
					for($i=0;$i<=$article[\'nombre\']-1;$i++)
					{
						$nbArticleAffichage++;
						
						$Inter_article_Prix=recupererArticleDescriptionID($bdd, $article[\'id_article\']);
						$article_Prix=$Inter_article_Prix->fetch();
						$prixPanierAffichage+=$article_Prix[\'prix\'];
						$petitPrixPanierAffichage+=$article_Prix[\'prixsecond\'];
					}
				}
				
				
				if($petitPrixPanierAffichage > 99)
				{
					$sortieBoucleAffichagePanier=0;
					while(!$sortieBoucleAffichagePanier)
					{
						$petitPrixPanierAffichage-=100;
						$prixPanierAffichage++;
						if($petitPrixPanierAffichage < 100)
						{
							$sortieBoucleAffichagePanier=1;
						}
					}
				}
			}
			
			
			// ---- Recuperation information ---- //
			
			$nom="";
			$description="";
			$prix="";
			$prixsecond="";
			$image="";
			$stock=1;
			$note=0;
			
			$Inter_article = recupererArticleDescriptionID($bdd, '.$idArticle.');
			while($article=$Inter_article->fetch())
			{
				$nom=$article["nom"];
				$description=$article["description"];
				$prix=$article["prix"];
				$prixsecond=$article["prixsecond"];
				$image=$article["nom_photo"];
				$note=$article["note"];
				
				if($article["stock"] <= 0)
				{
					$stock=0;
				}
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
				?>
				<link rel="stylesheet" href="../Vue/css/css_Article_Vue.css"/>
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
								include("../Vue/html/html_Aside_Left.php");
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
									include("../Vue/html/html_Article_Vue.php");
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
		?>	
	')
?>