document.addEventListener("DOMContentLoaded", () => {
  const searchInput = document.getElementById("searchInput");
  const tableRows = document.querySelectorAll("#dataTable tbody tr");

  // Variables pour gérer l'état du tri
  let lastSortedColumn = null;
  let isAscending = true;  // Pour gérer l'ordre croissant/décroissant

  // Recherche en temps réel
  searchInput.addEventListener("input", () => {
    const query = searchInput.value.toLowerCase();
    filterTable(query);
  });

  // Fonction de tri des colonnes
  window.sortTable = function(columnIndex) {
    const table = document.getElementById("dataTable");
    const rows = Array.from(table.querySelectorAll("tbody tr"));
    const isNumericColumn = columnIndex === 7; // Retard est numérique
    const isDateColumn = columnIndex === 5 || columnIndex === 6; // Date d'emprunt et Date de retour

    // Si on clique sur la même colonne, inverser l'ordre de tri
    if (lastSortedColumn === columnIndex) {
      isAscending = !isAscending;
    } else {
      isAscending = true;  // Réinitialiser l'ordre à croissant si une nouvelle colonne est triée
    }

    // Tri des lignes en fonction de la colonne
    rows.sort((rowA, rowB) => {
      const cellA = rowA.cells[columnIndex].innerText.trim();
      const cellB = rowB.cells[columnIndex].innerText.trim();

      let comparison = 0;

      // Comparaison pour les dates
      if (isDateColumn) {
        const dateA = new Date(cellA.split('/').reverse().join('-')); // Convertir la date au format 'd/m/Y' en format 'Y-m-d'
        const dateB = new Date(cellB.split('/').reverse().join('-'));
        comparison = dateA - dateB;
      }
      // Comparaison pour les nombres (retard)
      else if (isNumericColumn) {
        const numA = parseInt(cellA, 10);
        const numB = parseInt(cellB, 10);
        comparison = numA - numB;
      } else {
        // Comparaison lexicographique pour texte
        comparison = cellA.localeCompare(cellB);
      }

      // Si l'ordre est décroissant, inverser la comparaison
      return isAscending ? comparison : -comparison;
    });

    // Ré-appliquer l'ordre trié
    const tbody = table.querySelector("tbody");
    rows.forEach(row => tbody.appendChild(row));

    // Mémoriser la dernière colonne triée
    lastSortedColumn = columnIndex;
  };

  // Fonction de filtrage
  function filterTable(query) {
    tableRows.forEach(row => {
      const rowText = row.innerText.toLowerCase();
      row.style.display = rowText.includes(query) ? "" : "none";
    });
  }
});
