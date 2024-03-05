<?php

/**
 * Description of RSFileUploader
 *
 * @author RSanchez
 */

class RSFileUploader {

    const DOC = "DOCS";
    const DOC_PDF = "PDF";
    const DOC_EXCEL = "EXCEL";
    const DOC_WORD = "WORD";
    const DOC_POWERPOINT = "PPT";
    const IMAGE = "IMAGE";
    const IMAGE_PNG = "PNG";
    const IMAGE_JPEG = "JPEG";
    const IMAGE_GIF = "GIF";
    const IMAGE_ICO = "ICO";
    const VIDEO = "VIDEO";
    const VIDEO_MP4 = "MP4";
    const VIDEO_AVI = "AVI";
    const VIDEO_MOV = "MOV";
    const VIDEO_OGG = "OGG-V";
    const VIDEO_WMV = "WMV";
    const AUDIO = "AUDIO";
    const AUDIO_MP3 = "MP3";
    const AUDIO_WAV = "WAV";
    const AUDIO_OGG = "OGG";
    const COMPRESS = "COMPRESS";
    const COMPRESS_ZIP = "ZIP";
    const COMPRESS_RAR = "RAR";
    const OTHER_CSV = "CSV";

    private $fileData = false;
    private $maxSize = false;
    private $filename = false;
    private $allowedTypes = false;
    private $customAllowedTypes = false;
    public $error_msg = false;

    public function __construct($file, $options = false) {
        $this->fileData = $file;
        $this->filename = $file["name"];
        if ($options && is_array($options)) {
            $this->setMaxSize((isset($options["max-size"])) ? $options["max-size"] : false);
            $this->allowedTypes = (isset($options["allowed-types"])) ? $options["allowed-types"] : false;
        }
    }

    public function getFileName() {
        return $this->filename;
    }

    public function getMaxSize() {
        return $this->maxSize;
    }

    public function setMaxSize($maxSize) {
        if (is_numeric($maxSize)) {
            $this->maxSize = $maxSize;
        } else {
            $sizelevel = strtoupper(substr($maxSize, strlen($maxSize) - 2, 2));
            $size = substr($maxSize, 0, strlen($maxSize) - 2);

            if (!is_numeric($size)) {
                $this->maxSize = false;
                return false;
            }
            switch ($sizelevel) {
                case "KB": $this->maxSize = $size * 1024;
                    break;
                case "MB": $this->maxSize = $size * 1048576;
                    break;
                case "GB": $this->maxSize = $size * 1073741824;
                    break;
                case "TB": $this->maxSize = $size * 1099511627776;
                    break;
            }
        }
    }

    public function setAllowedTypes($types) {
        $this->allowedTypes = $types;
    }

    public function getAllowedTypes() {
        return $this->allowedTypes;
    }

    public function addCustomAllowedTypes($types) {
        $this->customAllowedTypes = $types;
    }

    public function getCustomAllowedTypes() {
        return $this->customAllowedTypes;
    }

    public function getErrorMessages() {
        return $this->error_msg;
    }

    private function getMimeTypesRepo() {
        $mimeTypesRepo = array();

        $mimeTypesRepo[self::DOC_PDF] = array("application/pdf");
        $mimeTypesRepo[self::DOC_WORD] = array("application/doc", "application/ms-doc", "application/msword", "application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        $mimeTypesRepo[self::DOC_EXCEL] = array("application/excel", "application/vnd.ms-excel", "application/x-excel", "application/x-msexcel", "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        $mimeTypesRepo[self::DOC_POWERPOINT] = array("application/mspowerpoint", "application/powerpoint", "application/vnd.ms-powerpoint", "application/x-mspowerpoint", "application/vnd.openxmlformats-officedocument.presentationml.presentation");
        $mimeTypesRepo[self::DOC] = array_merge($mimeTypesRepo[self::DOC_PDF], $mimeTypesRepo[self::DOC_WORD], $mimeTypesRepo[self::DOC_EXCEL], $mimeTypesRepo[self::DOC_POWERPOINT]);

        $mimeTypesRepo[self::IMAGE_JPEG] = array("image/jpeg", "image/pjpeg");
        $mimeTypesRepo[self::IMAGE_PNG] = array("image/png");
        $mimeTypesRepo[self::IMAGE_GIF] = array("image/gif");
        $mimeTypesRepo[self::IMAGE_ICO] = array("image/x-icon");
        $mimeTypesRepo[self::IMAGE] = array_merge($mimeTypesRepo[self::IMAGE_JPEG], $mimeTypesRepo[self::IMAGE_PNG], $mimeTypesRepo[self::IMAGE_GIF], $mimeTypesRepo[self::IMAGE_ICO]);

        $mimeTypesRepo[self::AUDIO_MP3] = array("audio/mpeg3", "audio/x-mpeg-3", "video/mpeg", "video/x-mpeg");
        $mimeTypesRepo[self::AUDIO_WAV] = array("audio/wav", "audio/x-wav");
        $mimeTypesRepo[self::AUDIO_OGG] = array("video/ogg", "audio/ogg", "application/ogg");
        $mimeTypesRepo[self::AUDIO] = array_merge($mimeTypesRepo[self::AUDIO_MP3], $mimeTypesRepo[self::AUDIO_WAV], $mimeTypesRepo[self::AUDIO_OGG]);

        $mimeTypesRepo[self::VIDEO_MP4] = array("video/mp4", "application/mp4");
        $mimeTypesRepo[self::VIDEO_OGG] = array("audio/ogg", "video/ogg");
        $mimeTypesRepo[self::VIDEO_MOV] = array("video/quicktime", "video/x-quicktime", "image/mov", "audio/aiff", "audio/x-midi", "audio/x-wav", "video/avi");
        $mimeTypesRepo[self::VIDEO_WMV] = array("video/x-ms-wmv", "video/x-ms-asf");
        $mimeTypesRepo[self::VIDEO_AVI] = array("application/x-troff-msvideo", "video/avi", "video/msvideo", "video/x-msvideo");
        $mimeTypesRepo[self::VIDEO] = array_merge($mimeTypesRepo[self::VIDEO_AVI], $mimeTypesRepo[self::VIDEO_MP4], $mimeTypesRepo[self::VIDEO_OGG], $mimeTypesRepo[self::VIDEO_MOV], $mimeTypesRepo[self::VIDEO_WMV]);

        $mimeTypesRepo[self::COMPRESS_RAR] = array("application/x-rar", "application/x-rar-compressed", "application/octet-stream");
        $mimeTypesRepo[self::COMPRESS_ZIP] = array("application/zip", "application/octet-stream", "application/x-zip-compressed", "multipart/x-zip");
        $mimeTypesRepo[self::COMPRESS] = array_merge($mimeTypesRepo[self::COMPRESS_RAR], $mimeTypesRepo[self::COMPRESS_ZIP]);

        $mimeTypesRepo[self::OTHER_CSV] = array("text/csv", "text/x-csv", "application/x-csv", "application/csv, text/x-comma-separated-values", "text/comma-separated-values", "application/vnd.ms-excel", "text/tab-separated-values");

        return $mimeTypesRepo;
    }

    private function getExtensionRepo() {
        $extRepo = array();

        $extRepo[self::DOC_PDF] = array(".pdf");
        $extRepo[self::DOC_WORD] = array("doc", "docx");
        $extRepo[self::DOC_EXCEL] = array("xls", "xlsx");
        $extRepo[self::DOC_POWERPOINT] = array("ppt", "pptx");
        $extRepo[self::DOC] = array_merge($extRepo[self::DOC_PDF], $extRepo[self::DOC_WORD], $extRepo[self::DOC_EXCEL], $extRepo[self::DOC_POWERPOINT]);

        $extRepo[self::IMAGE_JPEG] = array("jpeg", "jpg", "jpe");
        $extRepo[self::IMAGE_PNG] = array("png");
        $extRepo[self::IMAGE_GIF] = array("gif");
        $extRepo[self::IMAGE_ICO] = array("ico");
        $extRepo[self::IMAGE] = array_merge($extRepo[self::IMAGE_JPEG], $extRepo[self::IMAGE_PNG], $extRepo[self::IMAGE_GIF], $extRepo[self::IMAGE_ICO]);

        $extRepo[self::AUDIO_MP3] = array("mp3");
        $extRepo[self::AUDIO_WAV] = array("wav");
        $extRepo[self::AUDIO_OGG] = array("ogg");
        $extRepo[self::AUDIO] = array_merge($extRepo[self::AUDIO_MP3], $extRepo[self::AUDIO_WAV], $extRepo[self::AUDIO_OGG]);

        $extRepo[self::VIDEO_MP4] = array("mp4");
        $extRepo[self::VIDEO_OGG] = array("ogg");
        $extRepo[self::VIDEO_MOV] = array("mov");
        $extRepo[self::VIDEO_WMV] = array("wmv");
        $extRepo[self::VIDEO_AVI] = array("avi");
        $extRepo[self::VIDEO] = array_merge($extRepo[self::VIDEO_AVI], $extRepo[self::VIDEO_MP4], $extRepo[self::VIDEO_OGG], $extRepo[self::VIDEO_MOV], $extRepo[self::VIDEO_WMV]);

        $extRepo[self::COMPRESS_RAR] = array("rar");
        $extRepo[self::COMPRESS_ZIP] = array("zip");
        $extRepo[self::COMPRESS] = array_merge($extRepo[self::COMPRESS_RAR], $extRepo[self::COMPRESS_ZIP]);

        $extRepo[self::OTHER_CSV] = array("csv");

        return $extRepo;
    }

    public function mimeTypeComprobation() {
        /* Verifica si ya fue declarado un conjunto de tipos mime aceptables */
        if (!($this->allowedTypes !== false || ( $this->allowedTypes === false && $this->customAllowedTypes !== false ) )) {
            return false;
        }
        /* Se carga el repositorio de tipos mime */
        $mimeTypesRepo = $this->getMimeTypesRepo();

        /* Se procede a generar un arreglo que combinará todos los set de tipos mime que sean aceptables, de acuerdo a lo declarado a través del atributo de clase $allowedTypes */
        $accepted = array();

        if (is_array($this->allowedTypes)) {
            /* Este bloque se ejecutará si los tipos mimes aceptables fueron proporcionados ($allowedTypes) en forma de array */
            foreach ($this->allowedTypes as $typeItem) {
                if (isset($mimeTypesRepo[$typeItem])) {
                    $accepted = array_merge($accepted, $mimeTypesRepo[$typeItem]);
                }
            }
        } else { /* Ejecutar en caso de que los mime no sean proporcionados como un array. Lo que significa que se usará sólo un set de tipos mime. */
            if (isset($mimeTypesRepo[$this->allowedTypes])) {
                $accepted = array_merge($accepted, $mimeTypesRepo[$this->allowedTypes]);
            }
        }

        /* Se verifica si se agregaron tipos mime aceptables personalizados */
        if ($this->customAllowedTypes !== false) {
            /* Separa los nombres de tipos mimes que se supone vienen separados por comas y los incluye en un arreglo */
            $cust_mime_set = explode(',', $this->customAllowedTypes);
            if (is_array($cust_mime_set) && count($cust_mime_set) > 0) {
                /* Si el arreglo con los tipos mimes personalizados se crea correctamente, son agregados al arreglo de tipos mimes aceptados. */
                $accepted = array_merge($accepted, $cust_mime_set);
            }
        }
        /* Si se encuentra un tipo mime en el arreglo $accepted que coincida con el tipo mime del archivo a cargar, entonces se retorna true. */
        return in_array($this->fileData["type"], $accepted);
    }

    public function extTypeComprobation() {
        $name_components = explode(".", $this->fileData["name"]);
        $extension = end($name_components);

        /* Verifica si ya fue declarado un conjunto de tipos aceptables */
        if (!($this->allowedTypes !== false || ( $this->allowedTypes === false && $this->customAllowedTypes !== false ) )) {
            return false;
        }
        /* Se carga el repositorio de extensiones */
        $extRepo = $this->getExtensionRepo();

        /* Se procede a generar un arreglo que combinará todos los set de extensiones que sean aceptables, de acuerdo a lo declarado a través del atributo de clase $allowedTypes */
        $accepted = array();

        if (is_array($this->allowedTypes)) {
            /* Este bloque se ejecutará si los tipos fueron proporcionados ($allowedTypes) en forma de array */
            foreach ($this->allowedTypes as $typeItem) {
                if (isset($extRepo[$typeItem])) {
                    $accepted = array_merge($accepted, $extRepo[$typeItem]);
                }
            }
        } else { /* Ejecutar en caso de que los tipos no sean proporcionados como un array. Lo que significa que se usará sólo un set de tipos. */
            if (isset($extRepo[$this->allowedTypes])) {
                $accepted = array_merge($accepted, $extRepo[$this->allowedTypes]);
            }
        }



        /* Si se encuentra un tipo mime en el arreglo $accepted que coincida con el tipo mime del archivo a cargar, entonces se retorna true. */
        return in_array($extension, $accepted);
    }

    public function maxSizeComprobation() {
        if (!$this->maxSize) {
            return false;
        }
        if ($this->fileData["size"] > $this->maxSize) {
            return false;
        }

        return true;
    }

    public function isItReady() {
        if ($this->fileData["error"] > 0) {
            return false;
        }
        return $this->mimeTypeComprobation() && $this->extTypeComprobation() && $this->maxSizeComprobation();
    }

    public function uploadFile($destination, $prefix = false) {
        if (!$this->isItReady()) {
            return false;
        }
        $prefix = ($prefix) ? $prefix . "-" : "";

        $temp_name = explode(".", $this->fileData["name"]);
        $newfilename = round(microtime(true)) . '.' . end($temp_name);
        $this->filename = $prefix . $newfilename;
        return move_uploaded_file($this->fileData["tmp_name"], $destination . $prefix . $newfilename);
    }

    /* private function mimeMatch($mime_array, $mime) {
      foreach ($mime_array as $mimeItem) {
      if ($mime == $mimeItem) {
      return true;
      }
      }
      return false;
      } */
}
