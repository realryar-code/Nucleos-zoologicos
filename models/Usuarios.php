<?php
    class Usuarios {
        private $nombre;
        private $apellidos;
        private $fotoPerfil;
        private $correo;
        private $contrasena;
        private $administrador;

        public function __construct($nombre, $apellidos, $fotoPerfil, $correo, $contrasena, $administrador)
        {
            $this->nombre = $nombre;
            $this->apellidos = $apellidos;
            $this->fotoPerfil = $fotoPerfil;
            $this->correo = $correo;
            $this->contrasena = $contrasena;
            $this->administrador = $administrador;
        }

        public function getNombre()
        {
            return $this->nombre;
        }
        public function getApellidos()
        {
            return $this->apellidos;
        }
        public function getFotoPerfil()
        {
            return $this->fotoPerfil;
        }
        public function getCorreo()
        {
            return $this->correo;
        }
        public function getContrasena()
        {
            return $this->contrasena;
        }
        public function getAdministrador()
        {
            return $this->administrador;
        }

        public function setNombre($nombre)
        {
            $this->nombre = $nombre;
        }
        public function setApellidos($apellidos)
        {
            $this->apellidos = $apellidos;
        }
        public function setFotoPerfil($fotoPerfil)
        {
            $this->fotoPerfil = $fotoPerfil;
        }
        public function setCorreo($correo)
        {
            $this->correo = $correo;
        }
        public function setContrasena($contrasena)
        {
            $this->contrasena = $contrasena;
        }
        public function setAdministrador($administrador)
        {
            $this->administrador = $administrador;
        }

        public function __toString()
        {
            return self::class . ": " . $this->nombre  . " " . $this->apellidos . $this->correo  . " "  . $this->contrasena  . " " . $this->administrador;
        }

    }

?>
