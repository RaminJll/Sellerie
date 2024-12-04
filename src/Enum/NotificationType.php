<?php

namespace App\Enum;

enum NotificationType : string
{
    case Retard = "retard";
    case Maintenance = "maintenance";
    case Reapprovisionnement = "reapprovisionnement";
}