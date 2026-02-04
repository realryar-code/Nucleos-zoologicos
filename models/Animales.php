<?php
    class Animales {
        private $idEspecie;
        private $idNucleo;
        private $nombre;

        public function __construct($idNucleo, $nombre, $idEspecie = null)
        {
            $this->idEspecie = $idEspecie;
            $this->idNucleo = $idNucleo;
            $this->nombre = $nombre;
        }

        public function getIdEspecie()
        {
            return $this->idEspecie;
        }
        public function getIdNucleo()
        {
            return $this->idNucleo;
        }
        public function getNombre()
        {
            return $this->nombre;
        }

        public function setIdEspecie($idEspecie)
        {
            $this->idEspecie = $idEspecie;
        }
        public function setIdNucleo($idNucleo)
        {
            $this->idNucleo = $idNucleo;
        }
        public function setNombre($nombre)
        {
            $this->nombre = $nombre;
        }

        public function __toString()
        {
            return self::class . ": " . $this->idEspecie  . " " . $this->idNucleo  . " " . $this->nombre;
        }

    }

?>