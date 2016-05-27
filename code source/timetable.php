<?php
    include 'functions.php';
    include 'course.php';

    date_default_timezone_set('Europe/Paris');
    $days = ['LUNDI', 'MARDI', 'MERCREDI', 'JEUDI', 'VENDREDI', 'SAMEDI'];

    //Récupérer les deux login envoyé par POST depuis la requête AJAX
    $studentLogin = $_POST['login'];
    $studentLogin2 = $_POST['login2'];

    //Récupérer le JSON depuis le webservice avec le premier login
    $content = request_from_webservice($studentLogin);
    //Décoder le JSON issu de l'appel ci-dessus
    $timetable = json_decode($content);

    //Si un second login est présent (pour comparaison d'emploi du temps)
    if ($studentLogin2 != null) {
        $content2 = request_from_webservice($studentLogin2);
        $timetable2 = json_decode($content2);
    }

    // Traitement d'erreur dans le cas d'un second login inexistant
    if ($studentLogin2 != null && empty($timetable2)) {
        echo "<p class='bg-warning'>le deuxieme login n'existe pas</p>";
        $studentLogin2 = null;
        $timetable2 = null;
    }

if (!empty($timetable)){

    ?>
    <div id='timetable' class="row">

<?php 
    //Si le second login est présent, proposer de le supprimer pour revenir à un emploi du temps simple
    if($studentLogin2 != null):
    ?>
    <br>
    <button class="btn btn-default btn-xs" onclick="deleteLoginTimetable('<?php echo $studentLogin;?>')">
        <b>Supprimer <?php echo $studentLogin2;?></b>
    </button>
    <?php endif; ?>

    <h1>Emploi du temps de
        <?php
        echo $studentLogin;
        if ($studentLogin2 != null) {
            echo " et $studentLogin2";
        }
        ?></h1>
    <?php

    //Création d'un tableau d'objet de type "course" (voir le fichier php contenant la classe course)
    $array_of_course = array();
    foreach ($timetable as $key => $course) {
        $course_object = new course($course->uv, $course->type, $course->day, $course->begin, $course->end, $course->room, $course->group);
        $array_of_course[$key] =  $course_object;

    }

    if ($timetable2 != null) {
        $array_of_course2 = array();
        foreach ($timetable2 as $key => $course) {
            $course_object = new course($course->uv, $course->type, $course->day, $course->begin, $course->end, $course->room, $course->group);
            $array_of_course2[$key] =  $course_object;

        }
    }

    if ($timetable !== null) {
        $multiple_array_of_course[] = $array_of_course;
    }
    if ($timetable2 !== null) {
        $multiple_array_of_course[] = $array_of_course2;
    }

    //Attribuer une couleur à chaque UV
    $multiple_array_of_course = attributeColorToUv($multiple_array_of_course);

    foreach ($days as $day): // On parcourt chaque jour. ?>
        <div class='col-xs-12 col-sm-6 col-md-4 col-lg-2 jour'>
            <div class='jour_titre'><?php echo $day; ?></div>
            <div class='row studentlogins'>
                <?php
                if ($studentLogin2 != null) {
                    echo "<div class='col-xs-2'></div><div class='col-xs-4'>$studentLogin</div><div class='col-xs-4'>$studentLogin2</div>";
                } else {
                    echo "<div class='col-xs-12'>$studentLogin</div>";
                }
                ?>

            </div>
            <?php
            $endCourse = strtotime('20:00');
            $nextCourses = array(array('courseLastNext' => 0, 'colorNextValue' => ''), array('courseLastNext2' => 0, 'colorNextValue2' => ''));
            for ($time = strtotime('8:00'); $time <= $endCourse; $time = $time+(900)):// On parcourt chaque heure de la journée.?>
                <div class="row creneau">
                    <div class='col-xs-4 heure'><?php if ($time % 3600 == 0) {echo date("H:i",$time);} ?></div>
                    <?php displayCourses($multiple_array_of_course, $day, $time, $nextCourses); ?>
                </div>
            <?php endfor;?>
        </div>
    <?php endforeach;?>
    </div>

    <?php
    // Traitement de l'erreur dans le cas où le login entré en premier n'existe pas (cas redondant que l'on a préféré conserver)
} else {

    ?>
    <div>Le login que vous avez rentré n'existe pas..</div>
    <?php
}
?>

