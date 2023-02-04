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
interface IAudit
{  
  /* Crea una auditoria con diferentes opciones */
  public function createAudit($options);    
  
   /* Obtiene todas las auditorias */
  public function getAudits($options);       

  
}
