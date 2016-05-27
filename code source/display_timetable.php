
<script type="text/javascript" src="jquery.js"></script>
<script type="text/javascript" src="../js/check.js"></script>


<?php include '../template/header.php' ?>




	<section>
		<div class="container">
			<a class="btn btn-primary" href="/~sr03p016/projet1/formulaire.php" role="button">Retour</a>
			<?php
				$studentLogin = $_POST['login'];
				include 'timetable.php';
			?>
		</div>
	</section>

	<section id="compareWith">
		<div class="container">
			<div class="row">
				<div class="col-lg-12">
					<div class="jumbotron">
						<h2 class="section-heading">Comparer votre emploi du temps ?</h2>
						<form id="monForm" name="formulaire_identifiant" method="POST" action="timetable.php">
							<div class="form-group">
								<label for="exampleInputEmail1">Entrez votre login :</label>
								<div class="input-group">
									<span class="input-group-addon glyphicon glyphicon-user"></span>
									<input id="login" type="text" class="form-control" name="login" onFocus="javascript:this.value=''" onblur="fieldVerification(this)" placeholder="login">
								</div>
							</div>
							<button type="submit" class="btn btn-default">Valider</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</section>

<?php include '../template/footer.php'; ?>


<script type="text/javascript">

$(document).ready(function() {
	var login = '<?php echo $_POST['login'];?>';
	// Lorsque je soumets le formulaire
	$('#monForm').on('submit', function(e) {
        e.preventDefault(); // J'empêche le comportement par défaut du navigateur, c-à-d de soumettre le formulaire

		var $this = $(this); // L'objet jQuery du formulaire

		// Je récupère les valeurs
		var login2 = $('input#login').val();


		// Je vérifie une première fois pour ne pas lancer la requête HTTP
		// si je sais que mon PHP renverra une erreur
		
		if(checkfForm(this)){
			// Envoi de la requête HTTP en mode asynchrone
			$.ajax({
				url: $this.attr('action'), // Le nom du fichier indiqué dans le formulaire
				type: $this.attr('method'), // La méthode indiquée dans le formulaire (get ou post)
				data: {
					login: login,
					login2: login2
				},
				success: function(html) { // Je récupère la réponse du fichier PHP
					$('#timetable').replaceWith(html); // J'affiche cette réponse
					$('#compareWith').hide();
				}
			});
		}
	});
});
</script>

