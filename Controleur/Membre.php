<?php
	// ---- Inclusion Connection Serveur ---- // 

	include("../Modele/serveur.php");
	
	// ---- Inclusion Requete SQL ---- //
	
	include("../Modele/membre.php");
	include("../Modele/article_description.php");
	include("../Modele/panier.php");
	include("../Modele/commande.php");
	
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
	
	// ---- Recuperation information membre ---- //
	
	$id[]=array();
	$pesudo[]=array();
	$mail[]=array();
	$commande[]=array();
	$articleMembre[]=array();
	$totalAchat[]=array();
	$totalAchatpetit[]=array();
	$date[]=array();
	
	$tempCommande[]=array();
	$tempTotalAchatpetit=0;
	$tempTotalAchat=0;
	$tempNbCommande=0;
	$tempnbarticle=0;
	$commandeExite=0;
	
	$nbmembre=0;
	
	$Inter_article=recupererMembreIdCroissant($bdd); //Recuperation information membre de la table membre
	while($article=$Inter_article->fetch())
	{
		
		if($article["droit"]=="membre") //Seulement si se n'est pas des admin
		{
			$id[$nbmembre]=$article["ID"];
			$pesudo[$nbmembre]=$article["pseudo"];
			$mail[$nbmembre]=$article["mail"];
			$date[$nbmembre]=$article["date"];
			
			$Inter_commande_client_id=recupererCommandeIdClient($bdd, $id[$nbmembre]); //Calcul de commande, article et total achat
			while($commande_client_id=$Inter_commande_client_id->fetch())
			{	
				//Compte le nombre d'article
				
				$tempnbarticle++;
				
				//Compter l'achat total en €
				
				$Inter_prix_article=recupererArticleDescriptionID($bdd, $commande_client_id["id_article"]);
				$prix_article=$Inter_prix_article->fetch();
				$donnee=$prix_article;
				
				$tempTotalAchat+=$donnee["prix"];
				$tempTotalAchatpetit+=$donnee["prixsecond"];
				
				//compte le nombre de commande
				
				if($tempNbCommande==0) //Si premiere passage de la boulce while
				{
					$tempCommande[$tempNbCommande]=$commande_client_id["id_commande"];
					$tempNbCommande++;
				}
				else //Si pas le premier passage de la boucle while
				{
					for($i=0;$i<=$tempNbCommande-1;$i++) //Pour vérifier si l'id commande est deja dans le tableau ou non
					{
						if($tempCommande[$i] == $commande_client_id["id_commande"])
						{
							$commandeExite=1; //Si l'id de la commande est deja dans le tableau
						}
					}
					
					if($commandeExite == 0) //Si l'id commande n'est pas dans le tableau
					{
						$tempCommande[$tempNbCommande]=$commande_client_id["id_commande"];
						$tempNbCommande++;
					}
				}
				
				$commandeExite=0; //Rienisialisation de commandeExite		
			}
					
			$commande[$nbmembre]=$tempNbCommande; //Mettre les valeur dans le tableau
			$articleMembre[$nbmembre]=$tempnbarticle;
			$totalAchat[$nbmembre]=$tempTotalAchat;
			
			if($tempTotalAchatpetit > 99)
			{
				$sortieBoucleAffichagePanier=0;
				while(!$sortieBoucleAffichagePanier)
				{
					$tempTotalAchatpetit-=100;
					$tempTotalAchat++;
					if($tempTotalAchatpetit < 100)
					{
						$sortieBoucleAffichagePanier=1;
					}
				}
			}
			
			$totalAchat[$nbmembre]=$tempTotalAchat;
			$totalAchatpetit[$nbmembre]=$tempTotalAchatpetit;
			
			$tempNbCommande=0; //initialisation pour le prochain
			$tempnbarticle=0;
			$commandeExite=0;
			$tempTotalAchat=0;
			$tempTotalAchatpetit=0;
			$nbmembre++;
		}
	}
	
	// ---- Recuperation information administrateur ---- //
	
	$idAdmin[]=array();
	$pesudoAdmin[]=array();
	$mailAdmin[]=array();
	$commandeAdmin[]=array();
	$articleMembreAdmin[]=array();
	$totalAchatAdmin[]=array();
	$totalAchatpetitAdmin[]=array();
	$dateAdmin[]=array();
	
	$tempCommande[]=array();
	$tempTotalAchatpetit=0;
	$tempTotalAchat=0;
	$tempNbCommande=0;
	$tempnbarticle=0;
	$commandeExite=0;
	
	$nbLembreAdmin=0;
	
	$Inter_article=recupererMembreIdCroissant($bdd); //Recuperation information membre de la table membre
	while($article=$Inter_article->fetch())
	{
		
		if($article["droit"]=="admin") //Seulement si se n'est pas des admin
		{
			$idAdmin[$nbLembreAdmin]=$article["ID"];
			$pesudoAdmin[$nbLembreAdmin]=$article["pseudo"];
			$mailAdmin[$nbLembreAdmin]=$article["mail"];
			$dateAdmin[$nbLembreAdmin]=$article["date"];
			
			$Inter_commande_client_id=recupererCommandeIdClient($bdd, $idAdmin[$nbLembreAdmin]); //Calcul de commande, article et total achat
			while($commande_client_id=$Inter_commande_client_id->fetch())
			{	
				//Compte le nombre d'article
				
				$tempnbarticle++;
				
				//Compter l'achat total en €
				
				$Inter_prix_article=recupererArticleDescriptionID($bdd, $commande_client_id["id_article"]);
				$prix_article=$Inter_prix_article->fetch();
				$donnee=$prix_article;
				
				$tempTotalAchat+=$donnee["prix"];
				$tempTotalAchatpetit+=$donnee["prixsecond"];
				
				//compte le nombre de commande
				
				if($tempNbCommande==0) //Si premiere passage de la boulce while
				{
					$tempCommande[$tempNbCommande]=$commande_client_id["id_commande"];
					$tempNbCommande++;
				}
				else //Si pas le premier passage de la boucle while
				{
					for($i=0;$i<=$tempNbCommande-1;$i++) //Pour vérifier si l'id commande est deja dans le tableau ou non
					{
						if($tempCommande[$i] == $commande_client_id["id_commande"])
						{
							$commandeExite=1; //Si l'id de la commande est deja dans le tableau
						}
					}
					
					if($commandeExite == 0) //Si l'id commande n'est pas dans le tableau
					{
						$tempCommande[$tempNbCommande]=$commande_client_id["id_commande"];
						$tempNbCommande++;
					}
				}
				
				$commandeExite=0; //Rienisialisation de commandeExite		
			}
					
			$commandeAdmin[$nbLembreAdmin]=$tempNbCommande; //Mettre les valeur dans le tableau
			$articleMembreAdmin[$nbLembreAdmin]=$tempnbarticle;
			$totalAchatAdmin[$nbLembreAdmin]=$tempTotalAchat;
			
			if($tempTotalAchatpetit > 99)
			{
				$sortieBoucleAffichagePanier=0;
				while(!$sortieBoucleAffichagePanier)
				{
					$tempTotalAchatpetit-=100;
					$tempTotalAchat++;
					if($tempTotalAchatpetit < 100)
					{
						$sortieBoucleAffichagePanier=1;
					}
				}
			}
			
			$totalAchatAdmin[$nbLembreAdmin]=$tempTotalAchat;
			$totalAchatpetitAdmin[$nbLembreAdmin]=$tempTotalAchatpetit;
			
			$tempNbCommande=0; //initialisation pour le prochain
			$tempnbarticle=0;
			$commandeExite=0;
			$tempTotalAchat=0;
			$tempTotalAchatpetit=0;
			$nbLembreAdmin++;
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
			include("../Vue/html_css/head_Nav_Administration.php");
		?>
		<link rel="stylesheet" href="../Vue/css/css_Article_Membre.css"/>
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
							include("../Vue/html/html_Article_Membre.php");
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