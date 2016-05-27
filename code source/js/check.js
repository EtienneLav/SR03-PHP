function fieldVerification(field)
    {
       if(field.value.length < 3)
       {
          underligne(field, true);
          return false;
       }
       else
       {
          underligne(field, false);
          return true;
       }
    }

    function underligne(field, error)
    {
       if(error)
          field.style.backgroundColor = "#fba";
       else
          field.style.backgroundColor = "";
    }


    function checkfForm(f)
    {
       var loginOk = fieldVerification(f.login);

       if(loginOk )
          return true;
       else
       {
          alert("Veuillez remplir correctement tous les champs");
          return false;
       }
    }

    function deleteLoginTimetable(login)
  {
    $.ajax({
      url: 'timetable.php', // Le nom du fichier indiqué dans le formulaire
      type: 'POST', // La méthode indiquée dans le formulaire (get ou post)
      data: {
        login: login,
      },
      success: function(html) { // Je récupère la réponse du fichier PHP
        $('#timetable').replaceWith(html); // J'affiche cette réponse
        $('#compareWith').show();

      }
    });
  }