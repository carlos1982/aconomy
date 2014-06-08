<?php
/**
 * Datentyp zum Verwalten von hochgeladenen Bildern.
 * @version 0.1 Einfacher Datentyp
 * @extends tFile
 */
class tImage extends tFile{

    public $mThumbnails = array('');

    function __construct($pInitParams = array()) {
        parent::__construct($pInitParams);

        if (array_key_exists('Thumbnails', $pInitParams)) {
            $this->mThumbnails = $pInitParams['Thumbnails'];
        }
    }

    function LoadFromPost(){

        if(isset($_FILES[$this->mFieldname])) {

            if ($this->mValue != '') {
                // @todo Lösche alte Bilder
            }

            $new_file = $_FILES[$this->mFieldname];

            if ($new_file['name'] != '') {

                $this->mValue = '';	// Wenn es Probleme gibt, dann wird dieses Feld nicht gesetzt und Validierung schlägt fehl!!!

                $file_size = bytesToSize1024($new_file['size'], 1);
                if ($file_size < getMaxUploadSize()) {
                    $file_name = $new_file['name'];
                    $file_type = $new_file['type'];

                    //Alt: if ((count($this->mAllowedFileTypes) < 1) || (array_key_exists($file_type, $this->mAllowedFileTypes))) {
                    if (hMimeTypes::isAllowedMimeType($file_type,$this->mAllowedFileTypes)) {

                        //$file_path = UPLOAD_PATH.$file_name;

                        $destination = date('Y/m/d/').hSalt::Salt(time().$this->mFieldname).'/';
                        $file_value = $destination.$file_name;
                        $file_path = UPLOAD_PATH.$file_value;

                        $this->mValue = $file_value;
                        $this->mTmpFile =  $new_file['tmp_name'];
                        $this->mDestination = $destination;
                    }
                    else {
                        hError::Add('Datei entspricht nicht den erlaubten Datentypen!');
                        return false;
                    }
                }
                else {
                    hError::Add(__('Datei ist zu groß!'));
                    return false;
                }

            }
            else {
                if(($this->mRequired) && ($this->mValue == '')) {
                    hError::Add(__('Probleme beim Datei-Upload!'));
                }
            }
        }

    }

    /**
     * Speicher die Temporäre-Datei im Entsprechenden Verzeichnis. Wird nach erfolgreicher Validierung und erfolgreichem Insert-Befehl aufgerufen.
     */
    function SaveTmpFile() {
        $file_path = UPLOAD_PATH.$this->mValue;

        // Pfad erstellen

        if ($this->mTmpFile != '') {
            if (!is_dir(UPLOAD_PATH.$this->mDestination)) mkdir(UPLOAD_PATH.$this->mDestination,0777,TRUE);

            if (move_uploaded_file($this->mTmpFile, $file_path)){
                hDebug::Add($this->mFieldname.' Dateiname: '.$this->mValue.' wurde kopiert');
            }
            else {
                hDebug::Add('TMP-File: '.$this->mTmpFile.' Dateiname: '.$this->mValue.' GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN GEHT VERLOREN ');
            }
        }


    }

    function showValue() {

        $return = '';

        if($this->mValue == '') {
            return '';
        }


        $file = UPLOAD_PATH.$this->mValue;
        $file_name = basename($file);
        if(file_exists($file)){
            $file_size = filesize($file);
        }
        if($file_name.$file_size != '') {
            $return = $file_name.' - '.__('Größe:').': '.BytesToSize1024($file_size);

            if (($this->mOwner != null) && (method_exists($this->mOwner, 'getClassControllerName')) && ($this->mOwner->getClassControllerName() != '')) {

                $return = hHtml::getLinkButtonTag($return,hRouter::getCompleteLink('file',hRouter::getLanguage(),$this->mOwner->getClassControllerName(),'download',$this->mOwner->getToken()),'download');
            }
            else {
                //var_dump($this->mOwner);
            }

        }


        return $return;
    }

    public function Edit() {

        if (!parent::Edit()) {
            return;
        }

        if ($this->mFieldname != '') {
            $id_string = ' id="'.$this->mFieldname.'"';
            $for_string = ' for="'.$this->mFieldname.'"';
            $name_string = ' name="'.$this->mFieldname.'"';
        }
        else {
            hDebug::Add('Ohne Namen macht ein Input-Feld keinen Sinn!');
            return '';
        }

        /* Label */
        if ($this->mLabel != '') {
            $label_string = $this->mLabel;
        }
        else {
            $label_string = $this->mFieldname;
        }

        /* Required */
        $required_str = '';
        if ($this->mRequired) {
            if($this->mValue == '') {
                $required_str = ' required="required"';
            }
        }


        /* Value */
        $value_string = '';
        if ($this->mValue != '') {
            $value_string = '<div class="saved_file_name">'.$this->showValue().'</div>';
        }

        /* Error-Feedback */
        $error_str = '';
        $error_class_str = '';
        if (hError::showMessages($this->mFieldname) != false) {
            $error_str = hError::showMessages($this->mFieldname);
            $error_class_str = ' class="inputfielderror"';
        }

        $max_file_size_string = '<br />'.__('Maximale Dateigröße').': '.BytesToSize1024(getMaxUploadSize());

        /* Hinweis zum Ausfüllen */
        $hint_string = '';
        if($this->mHint != '') {
            $hint_string = '<span class="hint">'.__($this->mHint).$max_file_size_string.'</span>';
        }
        elseif(count($this->mAllowedFileTypes) > 0) {
            $allowed_file_types = '';
            foreach ($this->mAllowedFileTypes as $allowed_file_type) {
                $allowed_file_types .= hMimeTypes::getExplanation($allowed_file_type).', ';
            }
            $allowed_file_types = substr($allowed_file_types,0,-2);
            $hint_string = '<p class="hint">'.__('Erlaubte Datei-Typen sind:').' '.$allowed_file_types.$max_file_size_string.'</p>';
        }



        $ret = '<div'.$error_class_str.'><label'.$for_string.'>'.$label_string.'</label>'.$value_string.'<input type="file" '.$id_string.$name_string.$required_str.' />'.$hint_string.$error_str.'</div>';
        return $ret;
    }

    public function Validate(){

        $parent_validation = parent::StdValidate();
        if (is_bool($parent_validation)) {
            hDebug::Add('Standard-Validierung liefert bool.'.$this->mFieldname.'~'.$this->mValue);
            return $parent_validation;
        }
        return true;
    }

    /**
     * @return string
     * @ToDo Für Thumbnails anpassen
     */
    function getFilePath() {
        if($this->mValue == '') return '';
        $file = UPLOAD_PATH.$this->mValue;
        if (file_exists($file)) {
            return $file;
        }
    }

    /**
     * @return string
     * @ToDo Für Thumbnails anpassen
     */
    function getFileName() {
        return 'of What?';
    }

    /**
     * @return string im FileSizeFormat (KB, MB, etc?)
     * @ToDo Für Thumbnails anpassen
     */
    function getFileSize() {
        return 0;
    }

    /**
     * @abstract Löscht bestehende Datei von der Festplatte.
     * @Todo Alle Thumbnails killen
     */
    function Delete() {
        if ($this->mValue == '') return;
        if (unlink(UPLOAD_PATH.$this->mValue)) {
            hDebug::Add($this->mValue.' wurde gelöscht ▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒▒!');
            $dirname = substr(UPLOAD_PATH.$this->mValue, 0, -(strlen(basename($this->mValue))));
            rmdir($dirname);
        }
        else {
            hDebug::Add($this->mValue.' wurde nicht gelöscht!');
        }

    }

    /**
     * @abstract CreateThumbnails
     */
    function CreateThumbnail($srcPath, $srcName, $dstPath, $thumbPrefix, $thumbExt, $maxWidth, $maxHeight, $pMethod = '', $echoAus = false)
    {
        if ($method == '')
        {
            $method = 'resize';
        }

        //_Warning('Methode: '.$method);


        $orgName = $srcPath.'/'.$srcName;
        $thumbName = self::changeFileName($srcName, $thumbPrefix, $thumbExt);

        $imageInfo = getimagesize($orgName);
        $img_width = $imageInfo[0];
        $img_height = $imageInfo[1];


        switch ($pMethod) {
        case 'fixed':   $fixedSchablone = IMG_PATH.'/schablone_white_'.$maxWidth.'_'.$maxHeight.'.png';
                        if (!file_exists($fixedSchablone))
                        {
                            $schablone = imagecreatetruecolor( $maxWidth , $maxHeight );
                            $color = imagecolorallocate($schablone, 255, 255, 255);
                            imagefill($schablone, 1, 1, $color);
                            imagepng($schablone, $fixedSchablone, 0);
                            imagedestroy($schablone);
                        }

                        $tmp_file = IMG_PATH.'/'.time().rand(0,999).substr($thumbName, strrpos($name, '.'));
                        if (($img_width > $maxWidth) || ($img_height > $maxHeight))
                        {
                            $befehl = '/usr/bin/convert -geometry '.$maxWidth.'x'.$maxHeight.' -strip "'.$orgName.'" "'.$tmp_file.'"';
                            system($befehl);
                        }
                        else
                        {
                            copy($orgName, $tmp_file);
                        }

                        $befehl = '/usr/bin/composite -gravity center "'.$tmp_file.'" "'.$fixedSchablone.'" "'.$dstPath.'/'.$thumbName.'"';
                        echo system($befehl);
                        unlink($tmp_file);
                        break;
        case 'cropped':
                        $fixedSchablone = IMG_PATH.'/schablone_white_'.$maxWidth.'_'.$maxHeight.'.png';
                        if (!file_exists($fixedSchablone))
                        {
                            $schablone = imagecreatetruecolor( $maxWidth , $maxHeight );
                            $color = imagecolorallocate($schablone, 255, 255, 255);
                            imagefill($schablone, 1, 1, $color);
                            imagepng($schablone, $fixedSchablone, 0);
                            imagedestroy($schablone);
                        }

                        $tmp_file = IMG_PATH.'/'.time().rand(0,999).substr($thumbName, strrpos($name, '.'));
                        if (($img_width > $maxWidth) || ($img_height > $maxHeight))
                        {
                            if ($maxHeight < $maxWidth)
                            {
                                $geo = $maxWidth;
                            }
                            elseif ($maxHeight > $maxWidth)
                            {
                                $geo = 'x'.$maxHeight;
                            }
                            else
                            {
                                $geo = ($img_height > $img_width) ? $maxWidth : 'x'.$maxHeight;
                            }

                            $befehl = '/usr/bin/convert -gravity center -geometry '.$geo.' -crop '.$maxWidth.'x'.$maxHeight.'+0+0  -strip "'.$orgName.'" "'.$tmp_file.'"';
                            system($befehl);
                        }
                        else
                        {
                            copy($orgName, $tmp_file);
                        }

                        $befehl = '/usr/bin/composite -gravity center "'.$tmp_file.'" "'.$fixedSchablone.'" "'.$dstPath.'/'.$thumbName.'"';
                        echo system($befehl);
                        unlink($tmp_file);
                        break;

            case 'stretch':
                        $fixedSchablone = IMG_PATH.'/schablone_white_'.$maxWidth.'_'.$maxHeight.'.png';
                        if (!file_exists($fixedSchablone))
                        {
                            $schablone = imagecreatetruecolor( $maxWidth , $maxHeight );
                            $color = imagecolorallocate($schablone, 255, 255, 255);
                            imagefill($schablone, 1, 1, $color);
                            imagepng($schablone, $fixedSchablone, 0);
                            imagedestroy($schablone);
                        }

                        $tmp_file = IMG_PATH.'/'.time().rand(0,999).substr($thumbName, strrpos($name, '.'));

                        if ($maxHeight < $maxWidth) {
                            $geo = $maxWidth;
                        }
                        elseif ($maxHeight > $maxWidth) {
                            $geo = 'x'.$maxHeight;
                        }
                        else {
                            $geo = ($img_height > $img_width) ? $maxWidth : 'x'.$maxHeight;
                        }

                        $befehl = '/usr/bin/convert -gravity center -geometry '.$geo.' -crop '.$maxWidth.'x'.$maxHeight.'+0+0  -strip "'.$orgName.'" "'.$tmp_file.'"';
                        system($befehl);

                        $befehl = '/usr/bin/composite -gravity center "'.$tmp_file.'" "'.$fixedSchablone.'" "'.$dstPath.'/'.$thumbName.'"';
                        echo system($befehl);
                        unlink($tmp_file);
                        break;
        default:
                        if (($img_width > $maxWidth) || ($img_height > $maxHeight))
                        {
                            $befehl = '/usr/bin/convert -geometry '.$maxWidth.'x'.$maxHeight.' -strip "'.$orgName.'" "'.$dstPath.'/'.$thumbName.'"';
                            exec($befehl);
                            if ($echoAus)
                            {
                                echo '<p>'.$befehl.'</p>';
                            }
                        }
                        else
                        {
                            copy($orgName, $dstPath.'/'.$thumbName);
                        }
            break;
        }

        return $thumbName;
    }


}
?>