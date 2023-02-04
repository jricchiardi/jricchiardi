<?php
namespace common\components\helpers;


/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author theseedgruru_5
 */
interface INotification 
{  
  /* Devuelve todas las notificaciones aplicando diferentes opciones de busqueda*/  
  public function getNotifications($options);  
  /* Crea una notificación con diferentes opciones */
  public function createNotification($options);      
  /* Devuelve la cantidad de notificaciones */

  
}
