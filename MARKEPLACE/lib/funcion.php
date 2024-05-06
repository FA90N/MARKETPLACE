<?php

function e($s){

    return htmlspecialchars($s,ENT_QUOTES);

}

function formatearFechaHoraLarga($fechaISO){ 
    $dt = DateTime::createFromFormat("Y-m-d H:i:s",$fechaISO);

    return IntlDateFormatter::formatObject($dt, "dd MMM. yyyy HH:mm", 'es-ES');

}

function ObtenerFecha (){
    return Date("Y-m-d H:i:s");
}