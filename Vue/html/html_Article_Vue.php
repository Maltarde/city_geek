<div class="contentView">
	<div class="divPictureArticle">
		<img class="pictureArticle" src="../Vue/image/image_article/<?php echo''.$image.''; ?>.png" >
	</div>
	
	<div class="divCenterArticle">
		<p class="nameArtilce"><?php echo''.$nom.''; ?></p>
		<p class="descriptionArtilce"><?php echo''.$description.''; ?></p>
	</div>
	
	<div class="menuRightArticle">
		<div class="contemenuRightArticle">
			<p class="priceArtilce"><?php echo''.$prix.'€'; ?><sup><?php echo''.$prixsecond.''; ?></sup></p>
			<form>
				<input type="submit" name="Ajoute" VALUE="Ajouter" class="addArticle">
			<form>
			<?php
			if($stock > 0)
			{
			?>
			<p class="stockArticle">En Stock</p>
			<?php
			}
			else
			{
			?>
			<p class="stockArticle">Rupture</p>
			<?php
			}
			?>
			<img src="../Vue/image/image_note/<?php echo''.$note.''; ?>.png">
		</div>
	</div>
</div>