<?php
    function to_time_ago( $time ) {
        
        
        $diff = time() - strtotime($time);
        
        if( $diff < 1 ) { 
            return 'Меньше секунды назад'; 
        }
        
        $time_rules = array ( 
                    12 * 30 * 24 * 60 * 60 => 'года',
                    30 * 24 * 60 * 60       => 'месяц',
                    24 * 60 * 60           => 'дней',
                    60 * 60                   => 'час',
                    60                       => 'минуты',
                    1                       => 'секунд'
        );
    
        foreach( $time_rules as $secs => $str ) {
            
            $div = $diff / $secs;
    
            if( $div >= 1 ) {
                
                $t = round( $div );
                
                return $t . ' ' . $str . 
                    ( $t > 1 ? '' : '' ) . ' назад';
            }
        }
    }
?>

