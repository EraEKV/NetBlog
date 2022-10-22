<?php
    function to_time_ago( $time ) {
        
        
        $diff = time() - strtotime($time);
        
        if( $diff < 1 ) { 
            return 'Меньше секунды назад'; 
        }
        
        $time_rules = array ( 
            12 * 30 * 24 * 60 * 60 => 'год',
            30 * 24 * 60 * 60       => 'мес.',
            24 * 60 * 60           => 'дн.',
            60 * 60                   => 'час.',
            60                       => 'мин.',
            1                       => 'сек.'
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

    function to_time_ago_adaptive( $time ) {
        
        
        $diff = time() - strtotime($time);
        
        if( $diff < 1 ) { 
            return 'Меньше секунды назад'; 
        }
        
        $time_rules = array ( 
                    12 * 30 * 24 * 60 * 60 => 'год',
                    30 * 24 * 60 * 60       => 'мес.',
                    24 * 60 * 60           => 'дн.',
                    60 * 60                   => 'час.',
                    60                       => 'мин.',
                    1                       => 'сек.'
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

