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
	
	// ---- Boutn Ajouter ---- //
	
	$tabCatogorie=array("console", "jeuVideo", "tShirtGeek", "serieFilm", "dcComicBd", "manga", "figurine", "replique");
	$nbcategorie=8;
	$idArticle=0;
	
	if(isset($_POST["nouvelArticle"])) //test de la conformité du des éléments du formulaire
	{
		$test=preg_match("#^$[a-zA-Z][a-zA-Z0-9]{3,100}$#", $_POST['nomArticle']); //Test du nom de l'article
		if(!$test)
		{
			$test=preg_match("#^$[a-zA-Z][a-zA-Z0-9]{3,30000}$#", $_POST['descriptionArticle']); //Test de la description de l'article
			if(!$test)
			{
				$test=preg_match("#^$[1-9][0-9]{1,4}$#", $_POST['prixAricle']); //Test du prix de l'article
				if(!$test)
				{
					$test=preg_match("#^$[0-9]{0,2}$#", $_POST['prixAriclesecond']); //Test du prix de l'article
					if(!$test)
					{
						$test=preg_match("#^$[1-9]{1,3}$#", $_POST['nombreAricle']); //Test du nombre de d'article
						if(!$test)
						{
							for($i=0;$i<=$nbcategorie-1;$i++) //test de la cathegorie
							{
								if($_POST['categorieArticle'] == $tabCatogorie[$i])
								{
									if (isset($_FILES['imageAricle']) AND $_FILES['imageAricle']['error'] == 0) //Test de l'extention du fichier
									{
										$infosfichier = pathinfo($_FILES['imageAricle']['name']);
										$extension_upload = $infosfichier['extension'];
										$extensions_autorisees = array('png');

										if(in_array($extension_upload, $extensions_autorisees))  //Tout les test réussie
										{ 
											//Apcceptation de la nouvelle image
											
											$uploads_dir = '../Vue/image/image_article/';
											
											$tmp_name = $_FILES["imageAricle"]["tmp_name"];
											$name = $_FILES["imageAricle"]["name"];
											move_uploaded_file($tmp_name, "$uploads_dir/$name");
											
											//Creation de l'image pour le manier
											
											$filename = '../Vue/image/image_article/'.$name.'';
																						
											list($width, $height) = getimagesize($filename);
											$new_width = 177;
											$new_height = 66;

											$image_p = imagecreatetruecolor($new_width, $new_height);
											$image = imagecreatefrompng($filename);
											imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
											
											imagepng($image_p, '../Vue/image/image_article_panier/'.$name.'');
											
											//Creation du nouveau article dans las bdd
											
											$basemane=pathinfo( $_FILES["imageAricle"]["name"], PATHINFO_FILENAME);
											
											creerArticleDescription($bdd, $_POST['categorieArticle'], $_POST['nomArticle'], $_POST['descriptionArticle'], $_POST['prixAricle'], $_POST['prixAriclesecond'], $basemane, 0, $_POST['nombreAricle'], $_POST['nombreAricle'], 0, 1);
											
											//Creation du nouveau controleur
											
											 $Inter_article= recupererArticleIdDecroissant($bdd);
											 while($article=$Inter_article->fetch())
											 {
												 $idArticle=$article['ID'];
												 break;
											 }
											
											include("creation_controleur_article/Creation_Controleur_Article.php");
										}
									}

								}
							}
						}
					}
				}
			}
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
		<link rel="stylesheet" href="../Vue/css/css_Article_Administration.css"/>
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
							include("../Vue/html/html_Article_Article.php");
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