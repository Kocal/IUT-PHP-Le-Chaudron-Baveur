<?php
namespace App\Http;

/**
 * Classe Helpers
 * @package App\Http
 */
class Helpers {

    /**
     * Retourne la différence entre deux dates
     * @param int $date1 Timestamp de la date n°1
     * @param int $date2 Timestamp de la date n°2
     * @return bool|\DateInterval
     */
    public static function date_diff($date1, $date2) {

        $d1 = date_create('@' . $date1);
        $d2 = date_create('@' . $date2);

        // L'utilisateur a rentré une ou deux timestamps foireux
        if($d1 === false || $d2 === false) {
            return false;
        }

        $diff = date_diff($d1, $d2);
        $diff->timestamp = $d2->getTimestamp() - $d1->getTimestamp();

        return $diff;
    }

    /**
     * @param \DateInterval $diff Objet DateInterval
     * @return \stdClass
     */
    public static function date_create($diff) {
        $obj = new \stdClass;
        $obj->format = '';
        $obj->expired = $diff->invert;
        $obj->timestamp = $diff->timestamp;

        $obj->years = $diff->y; $obj->months  = $diff->m; $obj->days  = $diff->d;
        $obj->hours = $diff->h; $obj->minutes = $diff->i; $obj->seconds = $diff->s;

        // Si c'est expiré, alors on met tout à 0
        if($obj->expired) {
            $obj->years = $obj->months = $obj->weeks = $obj->days =
            $obj->hours = $obj->minutes = $obj->seconds = 0;
        } else {
            foreach([
                // Les traductions sont au singulier, mais on rajoute un s après vérification de la donnée
                'years'  => 'année',
                'months' => 'mois',
                'days'   => 'jour',
                'hours'  => 'heure',
                'minutes' => 'minute',
                'seconds' => 'seconde'] as $type => $trad) {

                if($obj->$type != 0) {
                    $obj->format .= sprintf(' <span class="%s">%2d</span> %s, ', $type, $obj->$type,
                        $trad . ($trad !== 'mois' && $obj->$type > 1 ? 's' : ''));

                    if($type === 'seconds') {
                        $obj->format = substr($obj->format, 0, -2);
                    }
                }
            }
        }

        return $obj;
    }
}
