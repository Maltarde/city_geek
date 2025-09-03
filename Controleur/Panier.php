<?php
/// Auteur Christpher RAYMOND
/// Derniere modification 09/11/17
/// Version 0.1.0


	// ---- Inclusion Connection Serveur ---- //

	include("../Modele/serveur.php");
	
	// ---- Inclusion Requete SQL ----//
	
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
	
	// ---- Gestion cookies recherche ---- //
	
	if(isset($_COOKIE['reach']) && isset($_COOKIE['categorie']))
	{
		$recherchetext=$_COOKIE['reach'];
		$rechechecategorie=$_COOKIE['categorie'];
		
		setcookie('reach', NULL, -1);
		setcookie('categorie', NULL, -1);
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
				
				header('Location: Panier.php');
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

		//--- Bouton S'incrire ---//
			
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
	
	// ---- Affichage Panier ----//
		
	$panierId[]=array();
	$PanierNombre[]=array();
	$panierPrix[]=array();
	$panierPetitPrix[]=array();
	$panirNom[]=array();
	$panierPrixarticle[]=array();
	$panierPrixarticlePetit[]=array();
	$panierNbArticle=0;
	$panierIdExiste=0;
	$panierSortieBoucleAffichage=0;
	$panierCompteurArticle=0;
		
	if($connect)  //Si le membre est connecté
	{
		$Inter_article=recupererPanierPseudo($bdd, $_COOKIE['pseudo']);  //Recuperation des article du panier
		while($article=$Inter_article->fetch())
		{
			$PanierNombre[$panierNbArticle]=$article['nombre'];  
				
			$Inter_article_Prix=recupererArticleDescriptionID($bdd, $article['id_article']);  //Recupere les information de l'article
			$article_Prix=$Inter_article_Prix->fetch();
			$panierId[$panierNbArticle]=$article['id_article'];
			$panirNom[$panierNbArticle]=$article_Prix['nom'];
			$panierPrix[$panierNbArticle]=$article_Prix['prix']; 
			$panierPetitPrix[$panierNbArticle]=$article_Prix['prixsecond'];
			$panierNbArticle++;
		}	
	}
	elseif(!$connect)  //Si le visiteur n'est pas connecté
	{
		while(!$panierSortieBoucleAffichage)  //Pour tous les cookies new et old 
		{
			$panierIdExiste=0; //Reinitialise le fait que l'ID existé deja dans le panier 
			
			if(isset($_COOKIE['newarticle'.$panierCompteurArticle.'']) && $panierCompteurArticle==0) //Si le cookie premier cookie est un new
			{
				$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['newarticle'.$panierCompteurArticle.'']);  //Recupere les information de l'article
				$article_Prix=$Inter_article_Prix->fetch();
				$panierId[$panierNbArticle]=$_COOKIE['newarticle'.$panierCompteurArticle.''];
				$panirNom[$panierNbArticle]=$article_Prix['nom'];
				$panierPrix[$panierNbArticle]=$article_Prix['prix'];
				$panierPetitPrix[$panierNbArticle]=$article_Prix['prixsecond'];
				$PanierNombre[$panierNbArticle]=1;
				
				$panierNbArticle++;  //Car il y a un article de plus dans le panier
				$panierCompteurArticle++; //Compte le nombre d'article
			}
			elseif(isset($_COOKIE['oldarticle'.$panierCompteurArticle.'']) && $panierCompteurArticle==0) //Si le cookie premier cookie est un old
			{
				$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['oldarticle'.$panierCompteurArticle.'']);  //Recupere les information de l'article
				$article_Prix=$Inter_article_Prix->fetch();
				$panierId[$panierNbArticle]=$_COOKIE['oldarticle'.$panierCompteurArticle.''];
				$panirNom[$panierNbArticle]=$article_Prix['nom'];
				$panierPrix[$panierNbArticle]=$article_Prix['prix'];
				$panierPetitPrix[$panierNbArticle]=$article_Prix['prixsecond'];
				$PanierNombre[$panierNbArticle]=1;
				
				$panierNbArticle++;  //Car il y a un article de plus dans le panier
				$panierCompteurArticle++; //Compte le nombre d'article
			}
			elseif(isset($_COOKIE['newarticle'.$panierCompteurArticle.'']) && $panierCompteurArticle!=0)  //Si on a un new cookies et que i différent de 0
			{
				for($y=0;$y<=$panierNbArticle-1;$y++) //Verification si le produit deja dans le tableau
				{
					if($_COOKIE['newarticle'.$panierCompteurArticle.''] == $panierId[$y])
					{
						$PanierNombre[$y]++;
						$panierIdExiste=1;
						$panierCompteurArticle++; //Compte le nombre d'article
						break;
					}
				}
				
				if($panierIdExiste == 0) //Si produit n'est pas dans le tableau
				{
					$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['newarticle'.$panierCompteurArticle.'']);  //Recupere les information de l'article
					$article_Prix=$Inter_article_Prix->fetch();
					$panierId[$panierNbArticle]=$_COOKIE['newarticle'.$panierCompteurArticle.''];
					$panirNom[$panierNbArticle]=$article_Prix['nom'];
					$panierPrix[$panierNbArticle]=$article_Prix['prix'];
					$panierPetitPrix[$panierNbArticle]=$article_Prix['prixsecond'];
					$PanierNombre[$panierNbArticle]=1;
					
					$panierNbArticle++;  //Car il y a un article de plus dans le panier
					$panierCompteurArticle++; //Compte le nombre d'article
				}
			}
			elseif(isset($_COOKIE['oldarticle'.$panierCompteurArticle.'']) && $panierCompteurArticle!=0)  //Si on a un old cookies et que i différent de 0
			{
				for($y=0;$y<=$panierNbArticle-1;$y++) //Verification si le produit deja dans le tableau
				{
					if($_COOKIE['oldarticle'.$panierCompteurArticle.''] == $panierId[$y])
					{
						$PanierNombre[$y]++;
						$panierIdExiste=1;
						$panierCompteurArticle++; //Compte le nombre d'article
						break;
					}
				}
				
				if($panierIdExiste == 0) //Si produit n'est pas dans le tableau
				{
					$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['oldarticle'.$panierCompteurArticle.'']);  //Recupere les information de l'article
					$article_Prix=$Inter_article_Prix->fetch();
					$panierId[$panierNbArticle]=$_COOKIE['oldarticle'.$panierCompteurArticle.''];
					$panirNom[$panierNbArticle]=$article_Prix['nom'];
					$panierPrix[$panierNbArticle]=$article_Prix['prix'];
					$panierPetitPrix[$panierNbArticle]=$article_Prix['prixsecond'];
					$PanierNombre[$panierNbArticle]=1;
					
					$panierNbArticle++;  //Car il y a un article de plus dans le panier
					$panierCompteurArticle++; //Compte le nombre d'article
				}
			}
			else
			{
				$panierSortieBoucleAffichage=1;
			}
		}
	}
	
	for($i=0;$i<=$panierNbArticle-1;$i++)
	{
		$panierPrixarticle[$i]=$PanierNombre[$i]*$panierPrix[$i];
		$panierPrixarticlePetit[$i]=$PanierNombre[$i]*$panierPetitPrix[$i];
		
		if($panierPrixarticlePetit[$i] > 99) //Calcule du prix si les centines dépence 99
		{
			$sortieBoucleAffichagePanier=0;
			while(!$sortieBoucleAffichagePanier)
			{
				$panierPrixarticlePetit[$i]-=100;
				$panierPrixarticle[$i]++;
				if($panierPrixarticlePetit[$i] < 100)
				{
					$sortieBoucleAffichagePanier=1;
				}
			}
		}
	}	

	// ---- Gere les boutons du panier ---- //
	
	$cookieNom[]=array();
	$cookieId[]=array();
	$nbCookies=0;

	for($i=0;$i<=$panierNbArticle-1;$i++) //On test tout les bouton supprimé de chaque article
	{
		if(isset($_POST['moins'.$i.''])) //Bouton "-"
		{
			if($connect)
			{
				changementPanierPseudoIdPanier($bdd, $_COOKIE['pseudo'], $panierId[$i], $PanierNombre[$i]-1);
				header('Location: Panier.php');
			}
			for($y=0;$y<=$_COOKIE['panier']-1;$y++)  //Pour faire tout les cookies
			{
				if(isset($_COOKIE['oldarticle'.$y.'']))  //Pour si le cookie est un old
				{
					if($_COOKIE['oldarticle'.$y.''] == $panierId[$i])  //Pour supprimé seulement les cookies du bon id
					{
						for($cookieMoins=$y;$cookieMoins<=$_COOKIE['panier']-1;$cookieMoins++) //On enleve 1 au chiffre a tous les cookies apres le cookies supprimé 
						{
							if($cookieMoins<$_COOKIE['panier']-1 && isset($_COOKIE['oldarticle'.$cookieMoins.'']))
							{
								$z=$cookieMoins+1;
								setcookie('oldarticle'.$cookieMoins.'', $_COOKIE['oldarticle'.$z.''], time()+3600*24*30, null, null, false, true);
								setcookie('oldarticle'.$z.'', NULL, -1);
							}
							elseif($cookieMoins<$_COOKIE['panier']-1 && isset($_COOKIE['newarticle'.$cookieMoins.'']))
							{
								$z=$cookieMoins+1;
								setcookie('newarticle'.$cookieMoins.'', $_COOKIE['newarticle'.$z.''], time()+3600*24*30, null, null, false, true);
								setcookie('newarticle'.$z.'', NULL, -1);
							}
							else //On suprime le dernier cookie
							{
								setcookie('oldarticle'.$cookieMoins.'', NULL, -1);
								setcookie('newarticle'.$cookieMoins.'', NULL, -1);
							}
						}
					}
				}
				elseif(isset($_COOKIE['newarticle'.$y.''])) //Pour si le cookie est un new
				{
					if($_COOKIE['newarticle'.$y.''] == $panierId[$i])  //Pour supprimé seulement les cookies du bon id
					{
						for($cookieMoins=$y;$cookieMoins<=$_COOKIE['panier']-1;$cookieMoins++) //On enleve 1 au chiffre a tous les cookies apres le cookies supprimé 
						{
							if($cookieMoins<$_COOKIE['panier']-1 && isset($_COOKIE['oldarticle'.$cookieMoins.'']))
							{
								$z=$cookieMoins+1;
								setcookie('oldarticle'.$cookieMoins.'', $_COOKIE['oldarticle'.$z.''], time()+3600*24*30, null, null, false, true);
								setcookie('oldarticle'.$z.'', NULL, -1);
							}
							elseif($cookieMoins<$_COOKIE['panier']-1 && isset($_COOKIE['newarticle'.$cookieMoins.'']))
							{
								$z=$cookieMoins+1;
								setcookie('newarticle'.$cookieMoins.'', $_COOKIE['newarticle'.$z.''], time()+3600*24*30, null, null, false, true);
								setcookie('newarticle'.$z.'', NULL, -1);
							}
							else //On suprime le dernier cookie
							{
								setcookie('oldarticle'.$cookieMoins.'', NULL, -1);
								setcookie('newarticle'.$cookieMoins.'', NULL, -1);
							}
						}
					}
				}
			}	
			header('Location: Panier.php');
		}
		elseif(isset($_POST['plus'.$i.''])) //Bouton "+"
		{
			if($connect)  //Si connecté on créé un cookie old
			{
				changementPanierPseudoIdPanier($bdd, $_COOKIE['pseudo'], $panierId[$i], $PanierNombre[$i]+1);
				setcookie('oldarticle'.$_COOKIE['panier'].'', $panierId[$i], time()+3600*24*30, null, null, false, true);
				setcookie('toto', 1, time()+3600*24*30, null, null, false, true);
			}
			elseif(!$connect) //Si connecté on créé un cookie new
			{
				setcookie('newarticle'.$_COOKIE['panier'].'', $panierId[$i], time()+3600*24*30, null, null, false, true);
			}
			header('Location: Panier.php');
		}
		elseif(isset($_POST['supprime'.$i.'']))  //Bouton supprimé
		{
			if($connect) //Si membre connecter
			{
				supprimePanier($bdd, $_COOKIE['pseudo'], $panierId[$i]);
				header('Location: Panier.php');
			}

			for($y=0;$y<=$_COOKIE['panier']-1;$y++)  //Pour faire tout les cookies
			{
				if(isset($_COOKIE['newarticle'.$y.''])) //Si le cookies en y et un new
				{
					if($_COOKIE['newarticle'.$y.'']==$panierId[$i]) //On le supprime si le meme id que l'article a supprimé
					{
						setcookie('newarticle'.$y.'', NULL, -1);
					}
					else //Sinon on sauvegarde eles information sur le cookie
					{
						$cookieNom[$nbCookies]="newarticle";
						$cookieId[$nbCookies]=$_COOKIE['newarticle'.$y.''];
						setcookie('newarticle'.$y.'', NULL, -1);
						$nbCookies++;
					}
				}
				elseif(isset($_COOKIE['oldarticle'.$y.''])) //Si le cookies en y et un old
				{
					if($_COOKIE['oldarticle'.$y.'']==$panierId[$i]) //On le supprime si le meme id que l'article a supprimé
					{
						setcookie('oldarticle'.$y.'', NULL, -1);
					}
					else //Sinon on sauvegarde eles information sur le cookie
					{
						$cookieNom[$nbCookies]="oldarticle";
						$cookieId[$nbCookies]=$_COOKIE['oldarticle'.$y.''];
						setcookie('oldarticle'.$y.'', NULL, -1);
						$nbCookies++;
					}
				}
			}
			for($z=0;$z<=$nbCookies-1;$z++) //On recréé les cookies sans les cookies de l'id a supprimé
			{
				setcookie(''.$cookieNom[$z].''.$z.'', $cookieId[$z], time()+3600*24*30, null, null, false, true);
				header('Location: Panier.php');
			}
		}
	}
	
	// ---- Afficlage du prix total et du nombre article total pour le header et l'affichage ---- //

	$nbArticleAffichage=0;
	$prixPanierAffichage=0;
	$petitPrixPanierAffichage=0;
	
	$sortieBoucleAffichagePrixTotal=0;
	$CompteurAffichagePrixTotal=0;
		
	if($connect) //Si le membre est connecté
	{
		$Inter_article=recupererPanierPseudo($bdd, $_COOKIE['pseudo']);  //Recuperation des article du panier
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
	}
	elseif(!$connect) //Si la personne est déconnecté
	{
		while(!$sortieBoucleAffichagePrixTotal) //On fait tous les cookies
		{
			if(isset($_COOKIE['newarticle'.$CompteurAffichagePrixTotal.''])) //Si c'est un cookies new
			{
				$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['newarticle'.$CompteurAffichagePrixTotal.'']);  //Recuperation des article du panier
				$article_Prix=$Inter_article_Prix->fetch();
				$prixPanierAffichage+=$article_Prix['prix'];
				$petitPrixPanierAffichage+=$article_Prix['prixsecond'];
				
				$CompteurAffichagePrixTotal++;
			}
			elseif(isset($_COOKIE['oldarticle'.$CompteurAffichagePrixTotal.''])) //Si c'est un cookies old
			{
				$Inter_article_Prix=recupererArticleDescriptionID($bdd, $_COOKIE['oldarticle'.$CompteurAffichagePrixTotal.'']);  //Recuperation des article du panier
				$article_Prix=$Inter_article_Prix->fetch();
				$prixPanierAffichage+=$article_Prix['prix'];
				$petitPrixPanierAffichage+=$article_Prix['prixsecond'];
				
				$CompteurAffichagePrixTotal++;
			}
			else
			{
				$sortieBoucleAffichagePrixTotal=1;
			}
		}
	}
	
	if($petitPrixPanierAffichage > 99) //Calcule du prix si les centines dépence 99
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
	
	//------------
	
	$action="Acceuil";
	
	// Inclusion du code html/css 
?>

<!DOCTYPE html>
<html>
    <head>
		<?php
			include("../Vue/html_css/head_General.php");
			include("../Vue/html_css/head_Panier.php");
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
							include("../Vue/html/html_Article_Panier.php");
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