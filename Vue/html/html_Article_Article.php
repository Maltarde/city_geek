<div class="articleContent">
	<p class="title">Nouveau Article</p>
	<form method="post" action=""  enctype="multipart/form-data">    
			<label for="nomAricle" class="labelNexArticle">Nom :</label> </br>
			<input type="text" id="nomAricle" name="nomArticle" class="formulaireText"/></br> </br>
			<label for="categorieArticle" class="labelNexArticle">Catégorie :</label> </br>
			<select name="categorieArticle" id="categorieArticle" placeholder="Catégorie de l'article">
				<option value="console">Console</option>
				<option value="jeuVideo">Jeu vidéo</option>
				<option value="tShirtGeek">T-shirt Geek</option>
				<option value="serieFilm">Série et film</option>
				<option value="dcComicBd">DC comic BD</option>
				<option value="manga">Manga</option>
				<option value="figurine">Figurine</option>
				<option value="replique">Réplique</option>
			</select></br></br>
			<label for="descriptionArticle" class="labelNexArticle">Descrioption :</label> </br>
			<textarea name="descriptionArticle" id="descriptionArticle" class="formulaireTexaerea"></textarea> </br> </br>
			<label for="prixAricle" class="labelNexArticle">Prix en euro:</label></br>
			<input type="text" id="prixAricle" name="prixAricle" class="formulaireText"/> </br> </br>
			<label for="prixAriclesecond" class="labelNexArticle">Prix en centime:</label></br>
			<input type="text" id="prixAriclesecond" name="prixAriclesecond" class="formulaireText"/> </br> </br>
			<label for="nombreAricle" class="labelNexArticle">Nombre d'article :</label> </br>
			<input type="text" id="nombreAricle" name="nombreAricle"  class="formulaireText"/> </br> </br>
			<label for="imageAricle" class="labelNexArticle">Image :</label></br>
			<input type="file" name="imageAricle" id="imageAricle" class="formulaireText"/> <br/> <br/> <br/>
			<input type="submit" name="nouvelArticle" VALUE="Nouveau Article" class="newArticle"/>
	</form>
	<p class="title">Articles</p>
	<table class="table">
		<tr>
			<th class="id">ID</th>
			<th class="picture">Image</th>
			<th class="category">Catégorie</th>
			<th class="name">Nom</th>
			<th class="description">Description</th>
			<th class="price">Prix</th>
			<th class="onSale">En Vente</th>
			<th class="edit">Moditier</th>
		</tr>
		<tr>
			<th class="id">888</th>
			<th class="picture"><img src="../Vue/image/image_article_panier/1.png" class="PanierImage"></th>
			<th class="category">DC comic BD</th>
			<th class="name">jjklggf</th>
			<th class="description">DC comic BD</th>
			<th class="price">0000€00</th>
			<th class="onSale">Oui</th>
			<th class="edit">
				<form method="post" action="">    
					<input type="submit" name="modifierArticle" VALUE="Modifer" class="modifierArticle"/>
				</form>
			</th>
		</tr>
	</table>
</div>