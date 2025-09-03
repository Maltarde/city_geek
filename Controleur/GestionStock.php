<?php
	// ---- Inclusion Connection Serveur ---- // 

	include("../Modele/serveur.php");
	
	// ---- Inclusion Requete SQL ---- //
	
	include("../Modele/membre.php");
	include("../Modele/article_description.php");
	include("../Modele/panier.php");
	
	// ---- Cookies taille produit panier ---- //
	
	if(!isset($_COOKIE['panier']) || isset($_COOKIE['panier']))
	{
		$sortieBoucle=0;
		$indiceCookie=1;
		
		while(!$sortieBoucle)
		{
			if(isset($_COOKIE['newarticle'.$indiceCookie.'']) ||  isset($_COOKIE['oldarticle'.$indiceCookie.'']))
			{
				$indiceCookie++;
			}
			else
			{
				$sortieBoucle=1;
			}				
		}
		setcookie('panier', $indiceCookie, time()+3600*24*30, null, null, false, true);
	}
	
	// ---- Gestion cookies recherche/trie ---- //
	
	if(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']))
	{
		$recherchetext=$_COOKIE['reach'];
		$rechechecategorie=$_COOKIE['categorie'];
		
		setcookie('reach', NULL, -1);
		setcookie('categorie', NULL, -1);
	}
	
	if(isset($_COOKIE['trie']))
	{
		setcookie('trie', NULL, -1);
	}
	
	// ---- Membre Connecté ou Déconnecté ---- //
	
	$connect=0;  //Membre connecté ou non
	
	if(isset($_COOKIE['pseudo']))  //Test si membre connecté
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
		
		//Affichage header panier
		
		$nbArticleAffichage=0;
		$prixPanierAffichage=0;
		$petitPrixPanierAffichage=0;
		
		$Inter_article=recupererPanierPseudo($bdd, $_COOKIE['pseudo']);
		while($article=$Inter_article->fetch())
		{
			for($i=0;$i<=$article['nombre']-1;$i++)
			{
				$nbArticleAffichage++;
				
				$Inter_article_Prix=recupererArticleDescriptionID($bdd, $article['id_article']);
				$article_Prix=$Inter_article_Prix->fetch();
				$prixPanierAffichage+=$article_Prix['prix'];
				$petitPrixPanierAffichage+=$article_Prix['prixsecond'];
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
	else  //Membre Déconnecté
	{
		header('Location: Acceuil.php');
	}

	// ---- Gestion droit d'acces ---- //
		
	if($Membre['droit'] != "admin")
	{
		header('Location: Acceuil.php');
	}
	
	// ---- Gestion recherche en tete et Bonton trie ---- //
	
	if(isset($_POST['recherche']))  //Si il y a une recherche en tete mise a jour des cookies
	{
		setcookie('reach', $_POST['textRecherche'], time()+3600*2, null, null, false, true);
		setcookie('categorie', $_POST['rechercheCategorie'], time()+3600*2, null, null, false, true);
		
		header('Location: Acceuil.php');
	}
	elseif(isset($_POST['trier'])) //Option du trie mise a jour du cookie
	{
		setcookie('trie', $_POST['trierPar'], time()+3600*2, null, null, false, true);
		
		header('Location: Acceuil.php');
	}
	
	// ---- Affichage de article ---- //
	
	$nom[]=array();
	$prix[]=array();
	$prixsecond[]=array();
	$stock[]=array();
	$total_achete[]=array();
	$total_vente[]=array();
	$prixVenteTotal[]=array();
	$prixSecondxVenteTotal[]=array();
	$nbArticle=0;
	
	$Inter_article=recupererArticleId($bdd);  //Recuperation des informations des articles
	while($article=$Inter_article->fetch())
	{
		$nom[$nbArticle]=$article['nom'];
		$prix[$nbArticle]=$article['prix'];
		$prixsecond[$nbArticle]=$article['prixsecond'];
		$stock[$nbArticle]=$article['stock'];
		$total_achete[$nbArticle]=$article['total_achete'];
		$total_vente[$nbArticle]=$article['total_vente'];
		
		$prixVenteTotal[$nbArticle]=$prix[$nbArticle]*$total_vente[$nbArticle];  //Pour le prix total des ventes
		$prixSecondxVenteTotal[$nbArticle]=$prixsecond[$nbArticle]*$total_vente[$nbArticle];
		
		if($prixSecondxVenteTotal[$nbArticle] > 99) //si le nombre de centine dépasse 100 du prix total des ventes
		{
			$sortieBoucleAffichagePanier=0;
			while(!$sortieBoucleAffichagePanier)
			{
				$prixSecondxVenteTotal[$nbArticle]-=100;
				$prixVenteTotal[$nbArticle]++;
				if($prixSecondxVenteTotal[$nbArticle] < 100)
				{
					$sortieBoucleAffichagePanier=1;
				}
			}
		}
		
		$nbArticle++;
	}
	
	/* ---- Bonton Ajouter ---- */
	
	for($i=0;$i<=$nbArticle-1;$i++)  //Pour pouvoir tester tous les article
	{
		if(isset($_POST['Ajouter_'.$i.'']))
		{
			$test=preg_match("#^[0-9]{1,3}$#", $_POST['Ajouter_'.$i.'']); //test de la validiter du la réponde
			if($test)
			{
				changementArticleDescriptionTotalAchete($bdd, $i+1, $total_achete[$i]+$_POST['Ajouter_'.$i.'']);
				changementArticleDescriptionStock($bdd, $i+1, $stock[$i]+$_POST['Ajouter_'.$i.'']);
			}
			
			header('Location: GestionStock.php');
		}
	}
	
	/* ---- Bonton Enlever ---- */
	
	for($i=0;$i<=$nbArticle-1;$i++)  //Pour pouvoir tester tous les article
	{
		if(isset($_POST['Enlever_'.$i.'']))
		{
			$test=preg_match("#^[0-9]{1,3}$#", $_POST['Enlever_'.$i.'']); //test de la validiter du la réponde
			if($test)
			{
				if( $stock[$i]-$_POST['Enlever_'.$i.''] >= 0) //test si le stock ne descant pas en dessous de 0
				{
					changementArticleDescriptionTotalAchete($bdd, $i+1, $total_achete[$i]-$_POST['Enlever_'.$i.'']);
					changementArticleDescriptionStock($bdd, $i+1, $stock[$i]-$_POST['Enlever_'.$i.'']);
				}
			}
			header('Location: GestionStock.php');
		}
	}
	
	//---------------
	
	$action="Acceuil";  //Pour les formulaire
	
	// Inclusion du code html/css 
?>

<!DOCTYPE html>
<html>
    <head>
		<?php
		include("../Vue/html_css/head_General.php");
		include("../Vue/html_css/head_Nav_Administration.php")
		?>		
		<link rel="stylesheet" href="../Vue/css/css_Article_Stock.css"/>
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
							include("../Vue/html/html_Article_Stock.php");
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