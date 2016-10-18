<?php

namespace Servicios;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator as DoctrinePaginator;

class Email
{
    var $mailer = null;
    var $templete=null;
    var $para;
    var $desde;
    var $vista;
    var $titulo='';
    var $contenido='';
    var $replyTo=array();
    var $parametros=array();
    var $documentos=array();
    var $fileName = '';
    var $filePath = '';
    var $datos;
    public function __construct($mailer, $templete, $container) {
        $this->mailer=$mailer;
        $this->templete=$templete;
        $this->desde=array(
            $container->getParameter('mailer_user') =>'e-pass'
        );
        $this->para=array();
    }


    public function enviar(){
        if(!$this->datos){
            $this->datos = $this->contenido;
        }

        $vista = $this->templete->render(
            $this->vista,array(
            'titulo'=> $this->titulo,
            'contenido'=>  $this->datos,
            'parametros'=>$this->parametros));

        $paraStr = "";
        for($i=0; $i<count($this->para);$i++){
            if($i==0){
                $paraStr = $this->para[$i];
            }else{
                $paraStr = $paraStr." ,".$this->para[$i];
            }
        }
        $par = array(
            "html" => $vista,
            "text" => $this->contenido,
            "subject" => $this->titulo,
            "to" => $paraStr,

        );

        if(count($this->documentos)>0){
            $par["documentos"] = $this->documentos;
        }
        $url = "http://107.170.86.190:3615/api/mail";
        $ch = curl_init($url);
        $payload = json_encode($par);

        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json',"cache-control: no-cache"));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_PORT, "3615");
        curl_setopt($ch, CURLOPT_TIMEOUT, "30");
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        $result = curl_exec($ch);

        $err = curl_error($ch);
        curl_close($ch);
        /*$message = $this->mailer->createMessage()
            ->setSubject($this->titulo)
            ->setFrom($this->desde)
            ->setTo($this->para)
            ->setBody(
                $this->templete->render(
                    $this->vista,
                    array(
                        'titulo'=> $this->titulo,
                        'contenido'=>  $this->contenido,
                        'parametros'=>$this->parametros
                    )
                ),
                'text/html'
            );
        if (count($this->replyTo)>0){
            $message->setReplyTo($this->replyTo);
        }
        $this->mailer->send($message);*/
    }

    function setPara(array $para) {
        $this->para = array_merge($this->para, $para);
        return $this;
    }

    function setReplyTo(array $replyTo) {
        $this->replyTo = array_merge($this->replyTo, $replyTo);
        return $this;
    }

    function setDesde(array $desde) {
        $this->desde[] = $desde;
        return $this;
    }

    function setVista($vista) {
        $this->vista = $vista;
        return $this;
    }

    function setTitulo($titulo) {
        $this->titulo = $titulo;
        return $this;
    }

    function setContenido($contenido) {
        $this->contenido = $contenido;
        return $this;
    }

    function getParametros() {
        return $this->parametros;
    }

    function setParametros(array $parametros = array()) {
        $this->parametros = $parametros;
        return $this;
    }

    /*function setFileName($fileName){
        $this->fileName = $fileName;
        return $this;
    }

    function setFilePath($filePath){
        $this->filePath = $filePath;
        return $this;
    }*/

    function getDocumentos(){
        return $this->documentos;
    }

    function setDocumentos($documentos){
        $this->documentos = $documentos;
        return $this;
    }

    function addDocumento($documento){
        array_push($this->documentos,$documento);
        return $this;
    }

    function limpiarCorreo(){
        $this->para = array();
        $this->documentos = array();
        return $this;
    }

    function getDatos() {
        return $this->datos;
    }

    function setDatos($datos) {
        $this->datos = $datos;
        return $this;
    }


}