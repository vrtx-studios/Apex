<?php

function datadict2sql($aDataDict = array(), $bReturnString = true) {
    $aSqls = array();
    $iCount = 0;
    foreach ($aDataDict as $sTable => $aValues) {
        $aSqls[$iCount] = 'CREATE TABLE IF NOT EXISTS `' . $sTable . '` ( ' . "\n";

        $aIndexes = array();
        $sPrimary = '';

        foreach ($aValues as $sFieldName => $aFieldData) {
            if (isset($aFieldData['index']) && $aFieldData['index'] == true)
                $aIndexes[] = $sFieldName;

            // Primary
            if (isset($aFieldData['primary']) && $aFieldData['primary'] == true)
                $sPrimary = $sFieldName;

            // Field name
            $sFieldData = "\t" . '`' . $sFieldName . '` ';

            // Field data type
            switch ($aFieldData['type']) {
                case 'array':
                case 'arraySet':
                    $sFieldData .= 'ENUM("' . implode('", "', array_keys($aFieldData['values'])) . '") ';
                    break;

                case 'date':
                    $sFieldData .= 'date ';
                    break;

                case 'datetime':
                    $sFieldData .= 'datetime ';
                    break;

                case 'float':
                    $sFieldData .= 'float ';
                    if (isset($aFieldData['min']) && $aFieldData['min'] >= 0)
                        $aSqls[$iCount] .= 'unsigned ';
                    break;

                case 'integer':
                    $sFieldData .= 'int(10) ';
                    if (isset($aFieldData['min']) && $aFieldData['min'] >= 0)
                        $aSqls[$iCount] .= 'unsigned ';
                    break;
                case 'text':
                    $sFieldData .= 'text ';
                    break;
                case 'string':
                default:
                    $sFieldData .= 'varchar(' . (!empty($aFieldData['max']) ? $aFieldData['max'] : 255 ) . ') ';
                    break;
            }

            // NOT NULL?
            $sFieldData .= 'NOT NULL ';

            // Auto increment
            if (!empty($aFieldData['autoincrement']))
                $sFieldData .= 'auto_increment ';

            $sFieldData = "\t" . trim($sFieldData); // Remove trailing space

            $aSqls[$iCount] .= $sFieldData . ",\n";
        }

        // Primary key
        if (!empty($sPrimary)) {
            $aSqls[$iCount] .= "\t" . 'PRIMARY KEY (`' . $sPrimary . '`)';
        } else {
            $aSqls[$iCount] .= "\t" . 'PRIMARY KEY (`' . key($aValues) . '`)';
        }

        if (!empty($aIndexes)) {
            // Add a comma if needed
            $aSqls[$iCount] .= ",\n";
        } else {
            $aSqls[$iCount] .= "\n";
        }

        // Keys/indexes
        if (!empty($aIndexes)) {
            $iIndexes = count($aIndexes);
            $iIndexCount = 1;
            foreach ($aIndexes as $sIndex) {
                $aSqls[$iCount] .= "\t" . 'KEY `' . $sIndex . '` (`' . $sIndex . '`)' . ( $iIndexCount == $iIndexes ? '' : ',' ) . '' . "\n";
                ++$iIndexCount;
            }
        }

        $aSqls[$iCount] .= ') ENGINE=MyISAM DEFAULT CHARSET=utf8;';
        ++$iCount;
    }

    return ( $bReturnString === true ? implode("\n\n", $aSqls) : $aSqls);
}

function d($data){
    if(is_null($data)){
        $str = "<i>NULL</i>";
    }elseif($data == ""){
        $str = "<i>Empty</i>";
    }elseif(is_array($data)){
        if(count($data) == 0){
            $str = "<i>Empty array.</i>";
        }else{
            $str = "<table style=\"border-bottom:0px solid #000;\" cellpadding=\"0\" cellspacing=\"0\">";
            foreach ($data as $key => $value) {
                $str .= "<tr><td style=\"background-color:#008B8B; color:#FFF;border:1px solid #000;\">" . $key . "</td><td style=\"border:1px solid #000;\">" . d($value) . "</td></tr>";
            }
            $str .= "</table>";
        }
    }elseif(is_resource($data)){
        while($arr = mysql_fetch_array($data)){
            $data_array[] = $arr;
        }
        $str = d($data_array);
    }elseif(is_object($data)){
        $str = d(get_object_vars($data));
    }elseif(is_bool($data)){
        $str = "<i>" . ($data ? "True" : "False") . "</i>";
    }else{
        $str = $data;
        $str = preg_replace("/\n/", "<br>\n", $str);
    }
    return $str;
}

function dnl($data){
    echo d($data) . "<br>\n";
}

function dd($data){
    echo dnl($data);
    exit;
}

function ddt($message = ""){
    echo "[" . date("Y/m/d H:i:s") . "]" . $message . "<br>\n";
}