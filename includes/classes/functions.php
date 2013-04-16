<?php


    function luhn_mod10($field)
    {
        $num = $field;

        $len = strlen($num);
        $sum = 0;
        for($i=$len-1; $i>=0; $i--)
        {
            if (($len-$i) % 2 == 0)
            {
                $toadd = $num{$i} * 2;
                if ($toadd >= 10)
                    $toadd = 1 + ($toadd - 10);
            }
            else
                $toadd = $num{$i};

            $sum += $toadd;
        }

        return ($sum % 10 == 0) ? true : false;

    }

    function exp_years()
    {
        $years = array();

        $yearRange = 10;
        $thisYear = date('y');
        $startYear = ($thisYear + $yearRange);
        foreach (range($thisYear, $startYear) as $year)
            $years[] = $year;

        echo $years;
    }


    function setShippingDetails($params){
        $_SESSION['shipping_details'] = $params;
    }

    function getShippingDetails(){
        return $_SESSION['shipping_details'];
    }