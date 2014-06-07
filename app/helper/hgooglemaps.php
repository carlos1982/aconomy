<?php
class hGoogleMaps {

    /**
     * @param null $pAdressObject
     * @param int $pWidth
     * @param int $pHeight
     * @return string
     * @todo Google Maps Api nutzen
     */
    public static function showAdress($pAdressObject = null, $pWidth = 425, $pHeight = 350) {

        if (is_object($pAdressObject) && method_exists($pAdressObject , 'getAdress')) {
            $adress = $pAdressObject->getAdress();
            return $adress;

            return '<iframe width="425" height="350" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.de/maps?f=q&amp;source=s_q&amp;hl=de&amp;geocode=&amp;q=Chaosdorf,+H%C3%BCttenstra%C3%9Fe+28,+40215+D%C3%BCsseldorf&amp;aq=&amp;sll=51.427085,7.663989&amp;sspn=3.699452,9.876709&amp;ie=UTF8&amp;hq=Chaosdorf,&amp;hnear=H%C3%BCttenstra%C3%9Fe+28,+Stadtbezirke+03+40215+D%C3%BCsseldorf&amp;t=m&amp;ll=51.21651,6.78348&amp;spn=0.006295,0.006295&amp;output=embed"></iframe>';
            //return '<iframe width="'.$pWidth.'" height="'.$pHeight.'" frameborder="0" scrolling="no" marginheight="0" marginwidth="0" src="https://maps.google.de/maps?f=q&amp;source=s_q&amp;hl=de&amp;geocode=&amp;q='.urlencode($adress).';output=embed"></iframe>';

        }

        return 'Keine Karte verfügbar';

    }

}
?>