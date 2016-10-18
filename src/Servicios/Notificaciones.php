<?php

namespace Servicios;
 
use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;
use Symfony\Component\HttpFoundation\Session\Session;

class Notificaciones{

    public function enviarNotificacion($titulo, $mensaje){

        $content = array(
            "en" => $mensaje
        );

        $fields = array(
            'app_id' => "159e39de-2bc7-41ea-bb84-627091bb7a0e",
            'included_segments' => array('All'),

            'contents' => $content
        );

        $fields = json_encode($fields);


        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
            'Authorization: Basic MmFiMjk2NzAtMWM5ZS00ZjhlLTgzMmItMGEwMjhjZjhmN2Ew'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        return $response;
    }

 

}