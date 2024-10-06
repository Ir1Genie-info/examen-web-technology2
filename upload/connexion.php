<?php
    class Connexion {
        private $host = "localhost";
        private $db_name = "pharmacie";
        private $username = "root";
        private $password = "";
        public $con;

        public function get_connexion() {
            $this->con = null;
            try{
                $this->con = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
                $this->con->exec("set names utf8");
                # Cette constante indique à PDO de lancer une exception lorsqu'une erreur survient
                $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                #Cette constante indique à PDO de récupérer les lignes sous forme d'objets
                $this->con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
            }catch(PDOException $exception){
                print "Erreur de connexion: " . $exception->getMessage();
            }
            return $this->con;
        }
    }