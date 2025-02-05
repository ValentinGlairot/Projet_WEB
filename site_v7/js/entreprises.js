// public/js/entreprises.js

document.addEventListener('DOMContentLoaded', function() {
  const searchForm = document.querySelector('.search-form');
  
  // On prépare un stockage fictif : la clé "entreprisesData" contiendra
  // la liste d'entreprises + nb stagiaires + moyenne évaluation
  // Format d'exemple :
  // {
  //   "Entreprise A": { desc: "...", email: "...", tel: "...", nbStagiaires: 12, evaluations: [5,4] },
  //   "Entreprise B": { ... },
  // }
  
  // Simuler quelques données
  if(!localStorage.getItem('entreprisesData')) {
    const sample = {
      "Entreprise A": {
        desc: "Entreprise axée sur le développement web.",
        email: "contact@entrepriseA.com",
        tel: "0102030405",
        nbStagiaires: 12, 
        evaluations: [5, 4, 4]
      },
      "Entreprise B": {
        desc: "Entreprise spécialisée en marketing.",
        email: "contact@entrepriseB.com",
        tel: "0155669988",
        nbStagiaires: 7,
        evaluations: [3, 4]
      },
      "Entreprise C": {
        desc: "Entreprise data science.",
        email: "contact@entrepriseC.com",
        tel: "0122334455",
        nbStagiaires: 3,
        evaluations: []
      }
    };
    localStorage.setItem('entreprisesData', JSON.stringify(sample));
  }

  const entreprisesData = JSON.parse(localStorage.getItem('entreprisesData'));

  // Récupération des éléments d'affichage
  const detailNom = document.getElementById('detail-nom');
  const detailDesc = document.getElementById('detail-desc');
  const detailEmail = document.getElementById('detail-email');
  const detailTel = document.getElementById('detail-tel');
  const detailNbStagiaires = document.getElementById('detail-nb-stagiaires');
  const detailMoyEval = document.getElementById('detail-moy-eval');

  if (searchForm) {
    searchForm.addEventListener('submit', function(e) {
      e.preventDefault();
      const input = searchForm.querySelector('input[type="text"]');
      const searchValue = input.value.trim();

      // On essaie de trouver une entreprise EXACTEMENT nommée comme searchValue
      // (en vrai, il faudrait faire des correspondances plus "fuzzy", mais c'est un exemple)
      if (entreprisesData[searchValue]) {
        const info = entreprisesData[searchValue];
        detailNom.textContent = searchValue;
        detailDesc.textContent = info.desc;
        detailEmail.textContent = info.email;
        detailTel.textContent = info.tel;
        detailNbStagiaires.textContent = info.nbStagiaires;

        // Calcul de la moyenne d'évaluation
        if(info.evaluations.length > 0) {
          const sum = info.evaluations.reduce((acc, note) => acc + note, 0);
          detailMoyEval.textContent = (sum / info.evaluations.length).toFixed(2);
        } else {
          detailMoyEval.textContent = "Aucune";
        }
      } else {
        // On n'a pas trouvé
        detailNom.textContent = "(Entreprise non trouvée)";
        detailDesc.textContent = "-";
        detailEmail.textContent = "-";
        detailTel.textContent = "-";
        detailNbStagiaires.textContent = "-";
        detailMoyEval.textContent = "-";
      }
    });
  }
});
