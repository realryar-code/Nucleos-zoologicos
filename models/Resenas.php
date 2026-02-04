<?php
    class Resenas {
        private $correo;
        private $idNucleo;
        private $titulo;
        private $comentario;

        public function __construct($correo, $idNucleo, $titulo, $comentario)
        {
            $this->correo = $correo;
            $this->idNucleo = $idNucleo;
            $this->titulo = $titulo;
            $this->comentario = $comentario;
        }

        public function getCorreo()
        {
            return $this->correo;
        }
        public function getIdNucleo()
        {
            return $this->idNucleo;
        }
        public function getTitulo()
        {
            return $this->titulo;
        }
        public function getComentario()
        {
            return $this->comentario;
        }

        public function setCorreo($correo)
        {
            $this->correo = $correo;
        }
        public function setIdNucleo($idNucleo)
        {
            $this->idNucleo = $idNucleo;
        }
        public function setTitulo($titulo)
        {
            $this->titulo = $titulo;
        }
        public function setComentario($comentario)
        {
            $this->comentario = $comentario;
        }

        public function __toString()
        {
            return self::class . ": " . $this->correo  . " " . $this->idNucleo . ": " . $this->titulo  . " " . $this->comentario;
        }

    }

?>