<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Ajouter un spectacle</title>
</head>
<body>
	<h1>Ajouter un spectacle</h1>

		<form action="index.php?route=addSpectacle" method="post">
		<label for="titre">Titre</label>
		<input id="titre" name="titre" type="text" required>

		<label for="date">Date</label>
		<input id="date" name="date" type="date" required>

		<label for="lieu">Lieu</label>
		<input id="lieu" name="lieu" type="text" required>

		<label for="artiste">Artiste</label>
		<input id="artiste" name="artiste" type="text" required>

		<label for="url">URL (image ou page)</label>
		<input id="url" name="url" type="url">

		<label for="prix">Prix (€)</label>
		<input id="prix" name="prix" type="number" min="0" step="0.01" required>

		<div class="actions">
			<button type="submit">Ajouter</button>
			<a href="index.php?route=accueil" style="margin-left:8px">Annuler</a>
		</div>
	</form>

</body>
</html>
