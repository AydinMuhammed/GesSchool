// ********************************* Pour les recherches dynamiques *********************************
$(function() {
    // keyup permet d'avoir une recherche dynamique à chaque nouvelle touche utilisée
    // À chaque keyup, la fonction est appelée
    $("#Recherche").on("keyup", function() {
      // On récupère la valeur dans l'input pour la recherche en minuscule (toLowerCase)
      var valeur = $(this).val().toLowerCase();
      // filter crée un nouveau tableau avec les valeurs qui ont passé le filtre
      // toggle : inverse l'état de visibilité d'un élément HTML (caché->visible ou inversement)
      // indexOf : La méthode indexOf() renvoie l'indice de la première occurrence de la valeur
      // cherchée au sein de la chaîne courante et sinon renvoie -1
      $("#triBody tr").filter(function() {
        $(this).toggle($(this).text().toLowerCase().indexOf(valeur) > -1);
      });
    });
  });
  