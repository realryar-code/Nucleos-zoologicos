<?php
    class NucleoZoologico {
        private $codigo;
        private $fecha;
        private $titular;
        private $municipio;
        private $provincia;
        private $imagen;

        public function __construct($codigo, $fecha, $titular, $municipio, $provincia, $imagen)
        {
            $this->codigo = $codigo;
            $this->fecha = $fecha;
            $this->titular = $titular;
            $this->municipio = $municipio;
            $this->provincia = $provincia;
            $this->imagen = $imagen;
        }

        public function getCodigo()
        {
            return $this->codigo;
        }
        public function getFecha()
        {
            return $this->fecha;
        }
        public function getTitular()
        {
            return $this->titular;
        }
        public function getMunicipio()
        {
            return $this->municipio;
        }
        public function getProvincia()
        {
            return $this->provincia;
        }
        public function getImagen()
        {
            return $this->imagen;
        }

        public function setCodigo($codigo)
        {
            $this->codigo = $codigo;
        }
        public function setFecha($fecha)
        {
            $this->fecha = $fecha;
        }
        public function setTitular($titular)
        {
            $this->titular = $titular;
        }
        public function setMunicipio($municipio)
        {
            $this->municipio = $municipio;
        }
        public function setProvincia($provincia)
        {
            $this->provincia = $provincia;
        }
        public function setImagen($imagen)
        {
            $this->imagen = $imagen;
        }

        public function __toString()
        {
            return self::class . ": " . $this->codigo . " " . $this->fecha . " " . $this->titular . " " . $this->municipio . " " . $this->provincia . " " . $this->imagen;
        }

    }

?>