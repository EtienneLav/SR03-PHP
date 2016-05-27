<?php

//Faire une requête au webservice et renvoyer la réponse en JSON ou gérer l'erreur
function request_from_webservice($login){

    $url="https://webapplis.utc.fr/Edt_ent_rest/myedt/result?login=".$login;

    $options=array(
    CURLOPT_URL            => $url,  // Url cible (l'url la page que vous voulez télécharger)
    CURLOPT_RETURNTRANSFER => true,  // Retourner le contenu téléchargé dans une chaine (au lieu de l'afficher directement)
    CURLOPT_HEADER         => false, // Ne pas inclure l'entête de réponse du serveur dans la chaine retournée
    CURLOPT_FAILONERROR    => true   // Gestion des codes d'erreur HTTP supérieurs ou égaux à 400
    );

    // Création d'un nouvelle ressource cURL
    $CURL=curl_init();


    if(empty($CURL))
    die("ERREUR curl_init : Il semble que cURL ne soit pas disponible.");


    // Configuration des options de téléchargement
    curl_setopt_array($CURL,$options);

    // Exécution de la requête
    $content=curl_exec($CURL);       // Le contenu téléchargé est enregistré dans la variable $content. Libre à vous de l'afficher.

    // Si il s'est produit une erreur lors du téléchargement
    if(curl_errno($CURL)){
    // Le message d'erreur correspondant est affiché
    echo "ERREUR curl_exec : ".curl_error($CURL);
    }

    // Fermeture de la session cURL
    curl_close($CURL);

    return $content;

}

//Attribuer une couleur à chaque UV 
function attributeColorToUv($multipleArrayOfCourse) {
    
    $courseColors = array();
    $arrayColors = array("#CCBB14","#99903D","#FFB300","#40ACFF","#14ACCC","#ABCCBF","#53997E","#EFFFF0","#FFC5FA","#C7B8CC","#CCADAB","#FFEFFC","#D5FFA1","#CACCB8","#723E64","#40826D","#FDE9E0","#80D0D0");
    $indexColor = 0;
    foreach ($multipleArrayOfCourse as $arrayKey => $array_of_course) {
        foreach ($array_of_course as $courseKey => $unique_course) {
            if ($courseColors[$unique_course->getuv()] == null) {
                $courseColors[$unique_course->getuv()] = $arrayColors[$indexColor];
                $unique_course->setcolor($arrayColors[$indexColor]);
                $indexColor++;
            } else {
                $unique_course->setcolor($courseColors[$unique_course->getuv()]);
            }
        }
    }

    return $multipleArrayOfCourse;
}

//Afficher les cours issus de l'emploi du temps de la bonne manière (un emploi du temps ou une comparaison)
function displayCourses($multipleArrayOfCourse, $day, $heure, &$nextCourses) {
    
    $nextCourse1 = $nextCourses[0];
    $nextCourse2 = $nextCourses[1];
    $courseLastNext = $nextCourse1['courseLastNext'];
    $colorNextValue = $nextCourse1['colorNextValue'];
    $courseLastNext2 = $nextCourse2['courseLastNext2'];
    $colorNextValue2 = $nextCourse2['colorNextValue2'];
    $matchedCourse1 = false;
    $matchedCourse2 = false;
    $nbPersonnes = sizeof($multipleArrayOfCourse);

    // Matching des cours à se déroulant à l'heure $heure.
    foreach ($multipleArrayOfCourse as $key => $array_of_course) { // Parcourt de la liste de cours de chaque personne.
        foreach ($array_of_course as $unique_course) {
            if ($unique_course->getday() == $day) {
                $timeCourse = strtotime($unique_course->getbegin());

                if (($timeCourse >= $heure) && ($timeCourse < ($heure + 900))) {
                    $courseName = $unique_course->getuv();
                    $courseRoom = $unique_course->getroom();
                    $courseType = $unique_course->gettype();
                    $courseLast = $unique_course->getLast();
                    if ($key == 0) {
                        $matchedCourse1 = true;
                    } else {
                        $matchedCourse2 = true;
                    }

                    // Gestion des couleurs.
                    if ($key == 0) {
                        $courseColor = $unique_course->getcolor();
                    } else {
                        $courseColor2 = $unique_course->getcolor();
                    }

                    // Gestion de la taille des cases si une ou plusieurs personnes.
                    if ($nbPersonnes == 1) {
                        echo "<div class='col-xs-8 uv course-begin' style='background-color: $courseColor'>";
                    } else {
                        if ($key == 0) {
                            echo "<div class='col-xs-4 uv course-begin' style='background-color: $courseColor'>";
                        } else {
                            echo "<div class='col-xs-4 uv course-begin' style='background-color: $courseColor2'>";
                        }
                    }

                    // Affichage informations sur le cours.
                    echo "<div class='uv-title'>$courseName</div>";
                    echo "<div class='uv-salle'>$courseRoom</div>";
                    echo "</div>";

                    // Diminution du temps restant du cours pour la gestion de l'affichage sur plusieurs cases.
                    if ($courseLast > 15 && $key == 0) {
                        $courseLastNext = $courseLast - 15;
                        $colorNextValue = $courseColor;
                    }
                    if ($courseLast > 15 && $key == 1) {
                        $courseLastNext2 = $courseLast - 15;
                        $colorNextValue2 = $courseColor2;
                    }
                    $nextCourses = array(array('courseLastNext' => $courseLastNext, 'colorNextValue' => $colorNextValue), array('courseLastNext2' => $courseLastNext2, 'colorNextValue2' => $colorNextValue2));
                }
            }
        } // fin foreach
        $nextCourse1 = $nextCourses[0];
        $nextCourse2 = $nextCourses[1];
        $courseLastNext = $nextCourse1['courseLastNext'];
        $colorNextValue = $nextCourse1['colorNextValue'];
        $courseLastNext2 = $nextCourse2['courseLastNext2'];
        $colorNextValue2 = $nextCourse2['colorNextValue2'];
        if ($matchedCourse1 == false || $matchedCourse2 == false) {
            if ($courseLastNext > 0 && $matchedCourse1 == false && $key == 0) { // Si le cours de la première personne est sur plusieurs heures.
                $matchedCourse1 = true;
                $courseLastNext = ($courseLastNext == 0)? 0 : $courseLastNext - 15;
                if ($courseLastNext == 0) {
                    if ($nbPersonnes == 1) {
                        echo "<div class='col-xs-8 uv course-end' style='background-color: $colorNextValue'></div>";
                    } else {
                        echo "<div class='col-xs-4 uv course-end' style='background-color: $colorNextValue'></div>";
                    }
                } else {
                    if ($nbPersonnes == 1) {
                        echo "<div class='col-xs-8 uv' style='background-color: $colorNextValue'></div>";
                    } else {
                        echo "<div class='col-xs-4 uv' style='background-color: $colorNextValue'></div>";
                    }
                }
                if ($courseLastNext == 0) {
                    $colorNextValue = '';
                }
            }
            if ($courseLastNext2 > 0  && $matchedCourse2 == false && $key == 1) {
                $matchedCourse2 = true;
                $courseLastNext2 = ($courseLastNext2 == 0)? 0 : $courseLastNext2 - 15;
                if ($courseLastNext2 == 0 && $nbPersonnes == 2) {
                    echo "<div class='col-xs-4 uv course-end' style='background-color: $colorNextValue2'></div>";
                } elseif ($nbPersonnes == 2 && $courseLastNext2 > 0) {
                    echo "<div class='col-xs-4 uv' style='background-color: $colorNextValue2'></div>";
                }
                if ($courseLastNext2 == 0) {
                    $colorNextValue2 = '';
                }
            }
            $nextCourses = array(array('courseLastNext' => $courseLastNext, 'colorNextValue' => $colorNextValue), array('courseLastNext2' => $courseLastNext2, 'colorNextValue2' => $colorNextValue2));
        }

        // Affichage case vide si aucun cours n'a été matché.
        if ($matchedCourse1 == false && $key == 0) {
            if (sizeof($multipleArrayOfCourse) == 1) {
                echo "<div class='col-xs-8 uv-empty'></div>";
            } else {
                echo "<div class='col-xs-4 uv-empty'></div>";
            }
        }
        if ($matchedCourse2 == false && sizeof($multipleArrayOfCourse) == 2  && $key == 1) {
            echo "<div class='col-xs-4 uv-empty'></div>";
        }
    } // fin foreach
} // fin fct.


?>
