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
    
    
    public function __construct($mailer, $templete, $container) {
        $this->mailer=$mailer;
        $this->templete=$templete;
        $this->desde=array(
            $container->getParameter('mailer_user') =>'showcase'
        );
        $this->para=array(
            $container->getParameter('mailer_user')
        );
    }
    
    
    public function enviar(){
        $message = $this->mailer->createMessage()
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
        $this->mailer->send($message);
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




}
