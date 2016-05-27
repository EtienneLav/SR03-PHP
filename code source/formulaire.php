<?php include '../template/header.php' ?>

<script type="text/javascript" src="../js/check.js"></script>

<section>
    <div class="container">
    <div class="row">
        <div class="col-lg-12">
        <div class="jumbotron">
            <h1 class="section-heading">UTC- Obtenir un emploi du temps</h1>
            <form name="formulaire_identifiant" method="POST" action="display_timetable.php" onsubmit="return checkfForm(this)">
            <div class="form-group">
                <label for="exampleInputEmail1">Entrez votre login :</label>
                <div class="input-group">
                <span class="input-group-addon glyphicon glyphicon-user"></span>
                <input type="text" class="form-control" name = "login" onblur="fieldVerification(this)" id="exampleInputEmail1" placeholder="login">
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

