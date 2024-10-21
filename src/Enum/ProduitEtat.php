<?php

namespace App\Enum;

enum ProduitEtat: string
{
    case Neuf = 'neuf';
    case BonEtat = 'bon etat';
    case Reparation = 'en reparation';
    case HorsService = 'hors service';
}
