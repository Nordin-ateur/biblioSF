<?php
namespace App\Entity;

class EntiteBase {
    
    public function __toString()
    {
        $className = get_called_class();                            // $className = "App\Entity\Livre"
        $className = str_replace("App\Entity\\", "", $className);   // $className = "Livre"
        $className = strtolower($className);                        // $className = "livre"
        return $className;
    }


}