<?php

class DbPDO
{
    private static string $server = 'localhost';
    private static string $username = 'root';
    private static string $password = '';
    private static string $database = 'table_test_php';
    private static ?PDO $db = null;

    public static function connect(): ?PDO {
        if (self::$db == null){
            try {
                self::$db = new PDO("mysql:host=".self::$server.";dbname=".self::$database, self::$username, self::$password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$db->beginTransaction();

                $request = self::$db->prepare(" INSERT INTO user  (nom, prenom, email, password, adresse, code_postal, pays, date_join)
                    VALUES (:nom, :prenom, :email, :password, :adresse, :codePostal, :pays, :dateJoin)"
                );

                $date = new DateTime();
                $dt = $date->format("Y-m-d H:i:s");

                $name = 'Comeau';
                $prenom = 'Rémy';
                $mail = 'remyc59440@hotmail.com';
                $pass = 'motdepaqqse';
                $adresse = '6 rue de beugnies';
                $CD = '59550';
                $pays = 'France';

                $request->bindParam(":nom", $name);
                $request->bindParam(":prenom", $prenom);
                $request->bindParam(":email", $mail);
                $request->bindParam(":password", $pass);
                $request->bindParam(":adresse", $adresse);
                $request->bindParam(":codePostal", $CD);
                $request->bindParam(":pays", $pays);
                $request->bindParam(":dateJoin", $dt);

                $request->execute();

                echo "Utilisateur ajouter";
                self::$db->commit(); // On envoie désormais les request vers le serveur
            }
            catch (PDOException $e) {
                echo "Erreur de la connexion à la dn : " . $e->getMessage();
                self::$db->rollBack(); // On restaure les anciens données en cas d'erreur
            }
        }
        return self::$db;
    }
}