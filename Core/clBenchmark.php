<?php

namespace Core;

class clBenchmark {

    private $aTimer = array();

    public function __construct() {
        
    }

    public function timer($sAction, $sName) {
        // Default
        if ($sAction == null) {
            if (in_array($sName, $this->aTimer)) {
                $sAction = "stop";
            } else {
                $sAction = "start";
            }
        }

        // Start timer
        if ($sAction == "start") {
            $fTime = microtime(true);

            $this->aTimer[$sName][] = array(
                'start' => $fTime,
                'stop' => null,
                'difference' => null
            );

            return true;
        }

        // Stop timer
        if ($sAction == "stop") {
            $fTime = microtime(true);

            // No name needed if there's only one timer
            if ($sName === null && count($this->aTimer) == 1) {
                $sName = $this->aTimer[0];
            } elseif ($sName === null) {
                return false;
            }

            // Check if timer exists
            if (!array_key_exists($sName, $this->aTimer)) {
                return false;
            }

            // Insert stop & diff time
            $aBackwardsTimer = array_reverse($this->aTimer[$sName]);
            foreach ($aBackwardsTimer as $key => $values) {
                if ($values['stop'] === null) {
                    $this->aTimer[$sName][$key]['stop'] = $fTime;
                    $fTime = $this->aTimer[$sName][$key]['stop'] - $this->aTimer[$sName][$key]['start'];
                    $this->aTimer[$sName][$key]['difference'] = $fTime;
                }
            }
            return true;
        }

        if ($sAction == "show") {

            // Show specifik timer
            if ($sName != "*" && $sName != null) {
                foreach ($this->aTimer[$sName] as $aTimers => $aValues) {
                    $sOutput = $sName . ': ' . $aValues['difference'];
                }
                unset($this->aTimer[$sName]);
            } elseif ($sName == "*" || $sName === null) {
                // Show all timers
                $sOutput = 'Timers: ';

                foreach ($this->aTimer as $aTimers => $aValues) {

                    $sOutput .= $aTimers . ': ';

                    foreach ($aValues as $aValue) {
                        if (empty($aValue['difference'])) {
                            return false;
                        }
                        $sOutput .= $aValue['difference'] . ' ; ';
                    }
                }

                if (empty($this->aTimer)) {
                    $sOutput .= 'no timers!';
                }
            }

            return $sOutput;
        }
    }

    public function averageSystemLoad() {
        if (!function_exists('sys_getloadavg'))
            return false;

        $aLoad = sys_getloadavg();
        $aData = array(
            '1m: ' . $aLoad[0],
            '5m: ' . $aLoad[1],
            '15m: ' . $aLoad[2],
        );
        return implode('; ', $aData);
    }

    public function memoryUsage() {
        return $this->convert( memory_get_usage() ) . ' / ' . $this->convert( memory_get_peak_usage() );
    }

    private function convert($size) {
        $unit=array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
    }
    
}
