<?php
for($i=0;$i<=$nbArticle-1;$i++)
{
?>
<div class="un_article">
	<img src="../Vue/image/image_article/<?php echo''.$nom_photo[$i].'';?>.png" class="imgArticle">
	<p class="nomArticle"><?php echo''.$nom[$i].'';?></p>
	<p class="descriptionArticle"><?php echo''.$description[$i].'';?></p>
	<div>
		<img src="../Vue/image/image_note/<?php echo''.$note[$i].'';?>.png" class="noteArticle">
	</div>	
	<div class="prixPanierAticle">
		<p class="prixArticle">
			<?php echo''.$prix[$i].'€';?>
			<?php
			//if($prixsecond[$i] != 0)
			//{
			?>
			<sup><?php echo''.$prixsecond[$i].'';?></sup>
			<?php
			//}
			?>
		</p>
		<form method="post" action="<?php echo''.$action.''; ?>.php" class="formulaireArticle">
			<input type="submit" name="Ajouter<?php echo''.$i.'';?>" VALUE="Ajouter" class="panierArticle">
		</form>
	</div>
</div>
<?php
}
?>