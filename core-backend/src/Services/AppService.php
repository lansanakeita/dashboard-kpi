<?php

namespace App\Services;

class AppService
{

  private function calculerScore($texte, $motsCles)
  {
    $score = 0;
    foreach ($motsCles as $mot) {
      if (strpos($texte, $mot) !== false) {
        $score++;
      }
    }
    return $score;
  }
  public function classifierCommentaire($commentaire)
  {
    $motsClesExcellents = [
      "rapide",
      "parfait état",
      "exceptionnel",
      "efficace",
      "plus tôt que prévu",
      "impeccable",
      "soignée",
      "ponctuelle",
      "recommande vivement"
    ];

    $motsClesSatisfaisants = [
      "dans les délais",
      "correct",
      "suivi de commande",
      "respectés",
      "à améliorer"
    ];

    $motsClesPeuSatisfaisants = [
      "tardive",
      "endommagé",
      "peut mieux faire",
      "trop long",
      "peu réactif",
      "déçu",
      "incomplète",
      "difficulté",
      "lent",
      "compliqué"
    ];

    $scoreExcellent = $this->calculerScore($commentaire, $motsClesExcellents);
    $scoreSatisfaisant = $this->calculerScore($commentaire, $motsClesSatisfaisants);
    $scorePeuSatisfaisant = $this->calculerScore($commentaire, $motsClesPeuSatisfaisants);

    // Logique pour déterminer la classification
    if ($scoreExcellent > $scoreSatisfaisant && $scoreExcellent > $scorePeuSatisfaisant) {
      return 'Excellent';
    } elseif ($scoreSatisfaisant > $scorePeuSatisfaisant) {
      return 'Satisfaisant';
    } else {
      return 'Peu Satisfaisant';
    }
  }
}
