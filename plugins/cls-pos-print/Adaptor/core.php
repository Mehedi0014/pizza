<?php

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
class core{

         private $windows_share_printer_name = "XP-80";
         private    $ip = "202.51.176.34";
         private   $port = 9100;
         private $Connector;
         private $winC;
         private $netC;
         private $printerx;
         private $Eimage;

        public function  __construct(WindowsPrintConnector $winC,
                                     NetworkPrintConnector $netC,
                                     Printer $printer,
                                     EscposImage $Eimage
            ){
                $this->netC=$netC;
                $this->winC;
                $this->printerx = $printer;
                $this->Eimage = $Eimage;

        }

        function prepare($mode='test'){

            if ($mode=='test'){
                $this->Connector= $this->winC($this->windows_share_printer_name);
            }
            else{
                $this->Connector = $this->netC($this->ip,$this->port , false);
            }
            return $this;
        }

        public function set_ip_port($ip,$port=9100){
            $this->ip =$ip;
            $this->port =$port;
            return $this;
        }


}