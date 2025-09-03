<?php
/// Auteur Christpher RAYMOND
/// Derniere modification 26/10/17
/// Version 0.1.0


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
	
	$loginNoFree=0;  //Test pour l'inscription
	$loginNoGood=0;
	$mailNoGood=0;
	$passNoGood=0;
	
	$connectNoGood=0;  //Test pour la connection
	
	$inscription=0; //Si inscription
	
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
		//Bonton Inscription
	
		if(isset($_POST["inscription"]))
		{
			$inscription=1;
		}
		else
		{
			$inscription=0;
		}

		//Bouton S'incrire
			
		if(isset($_POST["Inscrire"]))
		{
			$regex=$_POST['login'];    //test du pseudo libre
			
			$Inter_Membre=recupererMembre($bdd, $_POST["login"]);
			$Membre=$Inter_Membre->fetch();
					
			$test=preg_match("#^$regex$#", $Membre['pseudo']);
			if(!$test)
			{
				$test=preg_match("#^[a-zA-Z][a-zA-Z0-9]{3,29}$#", $_POST["login"]);  //test pseudo valide
				if($test)
				{
					$test=preg_match("#^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{6,30}$#",  $_POST["pass"]);  //test mot de passe valide
					if($test)
					{
						$test=preg_match("#^[a-z0-9._-]+@[a-z0-9._-]{2,}\.[a-z]{2,4}$#", $_POST["mail"]);  //test mail valide
						if($test)
						{
							creerMembre($bdd, $_POST["login"], $_POST["pass"], 'membre', $_POST["mail"]);  //Création membre base de donnée

							setcookie('pseudo', $_POST['login'], time()+3600*2, null, null, false, true);
							setcookie('pass', $_POST['pass'], time()+3600*2, null, null, false, true);
								
							if(isset($_COOKIE['panier'])) //Transforme tous des new en old et mise a jour de la Bdd
							{
								if($_COOKIE['panier'] != 0) 
								{
									$idNewArticle=0;
											
									for($i=0;$i<=$_COOKIE['panier']-1;$i++) //Parcour tout les cookie pour voir si il y a pas un newarticle
									{
										if(isset($_COOKIE['newarticle'.$i.'']))
										{
											$changementPanierConnect=0;
											
											$idNewArticle=$_COOKIE['newarticle'.$i.''];  //Changement du cookies new en old (3 ligne)
											setcookie('newarticle'.$i.'', NULL, -1);
											
											$Inter_article=recupererPanierPseudo($bdd, $_POST['login']);
											while($article=$Inter_article->fetch())
											{
												if($idNewArticle == $article['id_article']) //Si hesiste deja
												{
													changementPanierPseudo($bdd, $article['ID'], $article['nombre']+1);
													$changementPanierConnect=1;
												}
											}	
											if(!$changementPanierConnect) //Si il hesiste pas
											{
												creerPanier($bdd, $_POST['login'], $idNewArticle, 1);
											}
										}
									}
								}
							}
								
							//Creation cookies old par la Bdd
									
							$compteArticle=0;
							$Inter_article=recupererPanierPseudo($bdd, $_POST['login']);
							while($article=$Inter_article->fetch())
							{
								for($i=0;$i<=$article['nombre']-1;$i++)
								{
									setcookie('oldarticle'.$compteArticle.'', $article['id_article'], time()+3600*24*30, null, null, false, true);
									$compteArticle++;
								}
							}
									
							//Redirecition
									
							header('Location: Acceuil.php');
						}
						else  //Mail Ivalide
						{
							$inscription=1;
							$mailNoGood=1;
						}
					}						
					else //Mot de passe Invalide
					{
						$inscription=1;
						$passNoGood=1;
					}
				}
				else //Login Ivanide
				{
					$inscription=1;
					$loginNoGood=1;
				}
			}
			else //Pseudo deja prit
			{
				$inscription=1;
				$loginNoFree=1;
			}
		}
		
		//Bonton connection
		
		$connectNoGood=0;
		
		if(isset($_POST["connection"]))
		{
			$Inter_Membre=recupererMembre($bdd, $_POST["login"]);
			$Membre=$Inter_Membre->fetch();
			
			if($_POST["pass"]  == $Membre['pass'])
			{
				setcookie('pseudo', $_POST['login'], time()+3600*2, null, null, false, true);
				setcookie('pass', $_POST['pass'], time()+3600*2, null, null, false, true);
				
				// Met tout les nouveau article ajouter au panier hors connection dans la base de donnée
				
				if(isset($_COOKIE['panier'])) //Transforme tous des new en old et mise a jour de la Bdd
				{
					if($_COOKIE['panier'] != 0) 
					{
						$idNewArticle=0;
						
						for($i=0;$i<=$_COOKIE['panier']-1;$i++) //Parcour tout les cookie pour voir si il y a pas un newarticle
						{
							if(isset($_COOKIE['newarticle'.$i.'']))
							{
								$changementPanierConnect=0;
								
								$idNewArticle=$_COOKIE['newarticle'.$i.''];  //Changement du cookies new en old (3 ligne)
								setcookie('newarticle'.$i.'', NULL, -1);
								
								$Inter_article=recupererPanierPseudo($bdd, $_POST['login']);
								while($article=$Inter_article->fetch())
								{
									if($idNewArticle == $article['id_article']) //Si hesiste deja
									{
										changementPanierPseudo($bdd, $article['ID'], $article['nombre']+1);
										$changementPanierConnect=1;
									}
								}
								
								if(!$changementPanierConnect) //Si il hesiste pas
								{
									creerPanier($bdd, $_POST['login'], $idNewArticle, 1);
								}
							}
						}
					}
				}
				
				//Creation cookies old par la Bdd
				
				$compteArticle=0;
				$Inter_article=recupererPanierPseudo($bdd, $_POST['login']);
				while($article=$Inter_article->fetch())
				{
					for($i=0;$i<=$article['nombre']-1;$i++)
					{
						setcookie('oldarticle'.$compteArticle.'', $article['id_article'], time()+3600*24*30, null, null, false, true);
						$compteArticle++;
					}
				}
				
				//Redirecition
				
				header('Location: Acceuil.php');
			}
			else //Si le mot de passe n'est pas le meme que celui de la base de donnée
			{
				$connectNoGood=1; 
			}
		}
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
	
	// ---- Gestion de l'affichage des articles ---- //
	
	$id[]=array();
	$nom[]=array();
	$description[]=array();
	$prix[]=array();
	$prixsecond[]=array();
	$nom_photo[]=array();
	$note[]=array();
		
	$nbArticle=0;
	
	$tabCatogorie=array("tous", "nouveaute", "console", "jeuVideo", "tShirtGeek", "serieFilm", "dcComicBd", "manga", "figurine", "replique"); //tableau de tout les categorie
	$tabTri=array("croissant", "decroissant"); //tableau de tout les trie
		
	if(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']) && isset($_COOKIE['trie'])) //Affichage si il y a une recherche en tete + trie
	{
		$regex=$_COOKIE['reach'];
		
		if($_COOKIE['trie'] == "croissant")  //Si trie par prix coissant
		{
			if($_COOKIE['categorie'] == $tabCatogorie[0])  //Si Catégorie de recherhce est Tous
			{
				$Inter_article=recupererArticlePrixCroissant($bdd);
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i",$article['nom']);
					if($test)
					{
						$id[$nbArticle]=$article['ID'];
						$nom[$nbArticle]=$article['nom'];
						$description[$nbArticle]=$article['description'];
						$prix[$nbArticle]=$article['prix'];
						$prixsecond[$nbArticle]=$article['prixsecond'];
						$nom_photo[$nbArticle]=$article['nom_photo'];
						$note[$nbArticle]=$article['note'];
						$nbArticle++;
					}
				}
			}
			else //Si Catégorie de recherhce n'est pas Tous
			{
				$Inter_article=recupererArticlePrixCroissantRecherhce($bdd, $_COOKIE['categorie']);
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i",$article['nom']);
					if($test)
					{
						$id[$nbArticle]=$article['ID'];
						$nom[$nbArticle]=$article['nom'];
						$description[$nbArticle]=$article['description'];
						$prix[$nbArticle]=$article['prix'];
						$prixsecond[$nbArticle]=$article['prixsecond'];
						$nom_photo[$nbArticle]=$article['nom_photo'];
						$note[$nbArticle]=$article['note'];
						$nbArticle++;
					}
				}
			}
		}
		elseif($_COOKIE['trie'] == "decroissant") //Si la recherche trie et Prix décroisant
		{
			if($_COOKIE['categorie'] == $tabCatogorie[0])  //Si Catégorie de recherhce est Tous
			{
				$Inter_article=recupererArticlePrixDecroissant($bdd);
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i",$article['nom']);
					if($test)
					{
						$id[$nbArticle]=$article['ID'];
						$nom[$nbArticle]=$article['nom'];
						$description[$nbArticle]=$article['description'];
						$prix[$nbArticle]=$article['prix'];
						$prixsecond[$nbArticle]=$article['prixsecond'];
						$nom_photo[$nbArticle]=$article['nom_photo'];
						$note[$nbArticle]=$article['note'];
						$nbArticle++;
					}
				}
			}
			else //Si Catégorie de recherhce n'est pas Tous
			{
				$Inter_article=recupererArticlePrixDecroissantRecherhce($bdd, $_COOKIE['categorie']); 
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i",$article['nom']);
					if($test)
					{
						$id[$nbArticle]=$article['ID'];
						$nom[$nbArticle]=$article['nom'];
						$description[$nbArticle]=$article['description'];
						$prix[$nbArticle]=$article['prix'];
						$prixsecond[$nbArticle]=$article['prixsecond'];
						$nom_photo[$nbArticle]=$article['nom_photo'];
						$note[$nbArticle]=$article['note'];
						$nbArticle++;
					}
				}
			}
		}
	}
	elseif(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']))  //Affichage si il y a que la recherche d'en tete 
	{
		$regex=$_COOKIE['reach'];
		
		if($_COOKIE['categorie'] == $tabCatogorie[0]) //Si Catégorie de recherhce est Tous
		{
			$Inter_article=recupererArticleDescriptionTout($bdd);
			while($article=$Inter_article->fetch())
			{
				$test=preg_match("#$regex#i",$article['nom']);
				if($test)
				{
					$id[$nbArticle]=$article['ID'];
					$nom[$nbArticle]=$article['nom'];
					$description[$nbArticle]=$article['description'];
					$prix[$nbArticle]=$article['prix'];
					$prixsecond[$nbArticle]=$article['prixsecond'];
					$nom_photo[$nbArticle]=$article['nom_photo'];
					$note[$nbArticle]=$article['note'];
					$nbArticle++;
				}
			}
		}
		else //Si categorie de recherche n'est pas tous
		{ 
			$Inter_article=recupererArticleDescriptionCategorie($bdd, $_COOKIE['categorie']);
			while($article=$Inter_article->fetch())
			{
				$test=preg_match("#$regex#i",$article['nom']);
				if($test)
				{
					$id[$nbArticle]=$article['ID'];
					$nom[$nbArticle]=$article['nom'];
					$description[$nbArticle]=$article['description'];
					$prix[$nbArticle]=$article['prix'];
					$prixsecond[$nbArticle]=$article['prixsecond'];
					$nom_photo[$nbArticle]=$article['nom_photo'];
					$note[$nbArticle]=$article['note'];
					$nbArticle++;
				}
			}
		}
	}
	elseif(isset($_COOKIE['trie']))  //Affichage si il y a que le trie
	{
		if($_COOKIE['trie'] == "croissant")
		{
			$Inter_article=recupererArticlePrixCroissant($bdd);
			while($article=$Inter_article->fetch())
			{
				$id[$nbArticle]=$article['ID'];
				$nom[$nbArticle]=$article['nom'];
				$description[$nbArticle]=$article['description'];
				$prix[$nbArticle]=$article['prix'];
				$prixsecond[$nbArticle]=$article['prixsecond'];
				$nom_photo[$nbArticle]=$article['nom_photo'];
				$note[$nbArticle]=$article['note'];
				$nbArticle++;
			}
		}
		elseif($_COOKIE['trie'] == "decroissant")  //Si le trie est décroissant
		{
			$Inter_article=recupererArticlePrixDecroissant($bdd);
			while($article=$Inter_article->fetch())
			{
				$id[$nbArticle]=$article['ID'];
				$nom[$nbArticle]=$article['nom'];
				$description[$nbArticle]=$article['description'];
				$prix[$nbArticle]=$article['prix'];
				$prixsecond[$nbArticle]=$article['prixsecond'];
				$nom_photo[$nbArticle]=$article['nom_photo'];
				$note[$nbArticle]=$article['note'];
				$nbArticle++;
			}
			
		}
	}
	else  //Affichage si il y a ni une recherche en tete ni un trie
	{
		$Inter_article=recupererArticleDescriptionTout($bdd);
		while($article=$Inter_article->fetch())
		{
			$id[$nbArticle]=$article['ID'];
			$nom[$nbArticle]=$article['nom'];
			$description[$nbArticle]=$article['description'];
			$prix[$nbArticle]=$article['prix'];
			$prixsecond[$nbArticle]=$article['prixsecond'];
			$nom_photo[$nbArticle]=$article['nom_photo'];
			$note[$nbArticle]=$article['note'];
			$nbArticle++;
		}	
	}

	// ---- Bouton Ajouter ----  //
	
	$idPanier[]=array();
	$nbArticlePanier=0;
	$nbCategorieSansTous=9;
		
	if(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']) && isset($_COOKIE['trie'])) //Si il y a une recherche et trie
	{
		$regex=$_COOKIE['reach'];
		
		if($_COOKIE['categorie'] == $tabCatogorie[0])  //Gategorie tous
		{
			if($_COOKIE['trie'] == "croissant")  //Trie croissant
			{
				$Inter_article=recupererArticlePrixcroissant($bdd); 
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i", $article['nom']);
					if($test)
					{
						$idPanier[$nbArticlePanier]=$article['ID'];
						$nbArticlePanier++;
					}
				}
			}
			elseif($_COOKIE['trie'] == "decroissant")
			{
				$Inter_article=recupererArticlePrixDecroissant($bdd);  //Trie décroissant
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i", $article['nom']);
					if($test)
					{
						$idPanier[$nbArticlePanier]=$article['ID'];
						$nbArticlePanier++;
					}
				}
			}
		}
		else //Tous les catégories sauf Tous
		{
			if($_COOKIE['trie'] == "croissant") //Si prix croissant
			{
				$Inter_article=recupererArticlePrixCroissantRecherhce($bdd, $_COOKIE['categorie']); 
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i", $article['nom']);
					if($test)
					{
						$idPanier[$nbArticlePanier]=$article['ID'];
						$nbArticlePanier++;
					}
				}
			}
			elseif($_COOKIE['trie'] == "decroissant") //Si prix Décroissant
			{
				$Inter_article=recupererArticlePrixDecroissantRecherhce($bdd, $_COOKIE['categorie']);  //Recuperer que categorie
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i", $article['nom']);
					if($test)
					{
						$idPanier[$nbArticlePanier]=$article['ID'];
						$nbArticlePanier++;
					}
				}
			}
		}

	}
	elseif(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']))
	{
		$regex=$_COOKIE['reach'];
		
		if($_COOKIE['categorie'] == $tabCatogorie[0])
		{
			$Inter_article=$Inter_article=recupererArticleDescriptionTout($bdd);
			while($article=$Inter_article->fetch())
			{
				$test=preg_match("#$regex#i", $article['nom']);
				if($test)
				{
					$idPanier[$nbArticlePanier]=$article['ID'];
					$nbArticlePanier++;
				}
			}
		}
		else
		{
			for($i=1;$i<=$nbCategorieSansTous;$i++) //Boucle fait toutes les catégorie
			{
				$Inter_article=$Inter_article=recupererArticleDescriptionCategorie($bdd, $_COOKIE['categorie']);
				while($article=$Inter_article->fetch())
				{
					$test=preg_match("#$regex#i", $article['nom']);
					if($test)
					{
						$idPanier[$nbArticlePanier]=$article['ID'];
						$nbArticlePanier++;
					}
				}
				
			}
		}
	}
	elseif(isset($_COOKIE['trie']))
	{
		if($_COOKIE['trie'] == "croissant")
		{
			$Inter_article=recupererArticlePrixCroissant($bdd);
			while($article=$Inter_article->fetch())
			{
				$idPanier[$nbArticlePanier]=$article['ID'];
				$nbArticlePanier++;
			}
		}
		elseif($_COOKIE['trie'] == "decroissant")
		{
			$Inter_article=recupererArticlePrixDecroissant($bdd); 
			while($article=$Inter_article->fetch())
			{
				$idPanier[$nbArticlePanier]=$article['ID'];
				$nbArticlePanier++;
			}	
		}
	}
	else
	{
		$Inter_article=$Inter_article=recupererArticleDescriptionTout($bdd);
		while($article=$Inter_article->fetch())
		{
			$idPanier[$nbArticlePanier]=$article['ID'];
			$nbArticlePanier++;
		}
	}
	
	$changementpanier=0;
	
	for($i=0;$i<=$nbArticlePanier-1;$i++)
	{
		if(isset($_POST['Ajouter'.$i.'']))
		{
			if($connect)
			{
				setcookie('oldarticle'. $_COOKIE['panier'].'', $idPanier[$i], time()+3600*24*30, null, null, false, true);
				
				$Inter_article=$Inter_article=recupererPanierPseudo($bdd, $_COOKIE['pseudo']);
				while($article=$Inter_article->fetch())
				{
					if($idPanier[$i] == $article['id_article'])
					{
						changementPanierPseudo($bdd, $article['ID'], $article['nombre']+1);
						$changementpanier=1;
					}
				}
				
				if(!$changementpanier)
				{
					creerPanier($bdd, $_COOKIE['pseudo'], $idPanier[$i], 1);
				}
				
				header('Location: Acceuil.php');
			}
			elseif(!$connect)
			{
				setcookie('newarticle'.$_COOKIE['panier'].'', $idPanier[$i], time()+3600*24*30, null, null, false, true);
				header('Location: Acceuil.php');
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
							include("../Vue/html/html_Article.php");
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