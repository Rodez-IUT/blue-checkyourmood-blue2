<?php

namespace model;

class verificationservice
{

    /**
     * Permet de verifier la valeur du nom d'un utilisateur
     * @return true si trouver sinon false
     */
    public static function testNom($nom) {
        $test = false;
        if ($nom != null || $nom != "") {
            if (strlen($nom) < 80) {
                $test = true;
            }
        }
        return $test;
    }

    /**
     * Permet de verifier la valeur du prenom d'un utilisateur
     * @return true si trouver sinon false
     */
    public static function testPrenom($prenom) {
        $test = false;
        if ($prenom != null || $prenom != "") {
            if (strlen($prenom) < 80) {
                $test = true;
            }
        }
        return $test;
    }

    /**
     * Permet de verifier la valeur d'une adresse mail
     * @return true si trouver sinon false
     */
    public static function testMail($mail) {
        $test = false;
        if ($mail != null || $mail != "") {
            if (filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                $test  = true;
            }
        } 
        return $test;
    }

    /**
     * Permet de verifier la valeur d'une adresse mail
     * @return true si trouver sinon false
     */
    public static function testNomUtilisateur($nomUtilisateur) {
        $test = false;
        if ($nomUtilisateur != null || $nomUtilisateur != "") {
            if (strlen($nomUtilisateur) < 80) {
                $test = true;
            }
        }
        return $test;  
    }
    
    /**
     * Permet de verifier la valeur d'un genre est correcte
     * @return true si trouver sinon false
     */
    public static function testGenre($genre) {
        $test = false;
        if ($genre != null || $genre != "") {
            if (strlen($genre) < 80) {
                $test = true;
            }
        }
        return $test;    
    }

    /**
     * Permet de verifier la valeur d'une date de naissance est correcte
     * @return true si trouver sinon false
     */
    public static function testDateNaissance($dateNaissance) {
        $test = false;
        if ($dateNaissance != null || $dateNaissance != "") {
            $test = true;
        }
        return $test;
    }

    /**
     * Permet de verifier la valeur d'un genre est correcte
     * @return true si trouver sinon false
     */
    public static function testMotDePasse($mdp) {
        $test = false;
        if ($mdp != null || $mdp != "") {
            $test =  true;
        }
        return $test;
    }

    /** 
     * Test le mot de passe avec une regex et avec le champ vérification de mdp
     * @param mdp1 mot de passe renseignée par l'utilisateur
     * @param mdp2 deuxième saisie du mot de passe renseignée par l'utilisateur
     */
    public static function testMdpCorrespond($mdp1, $mdp2) {
        $test = false;
        if ($mdp1 != null && $mdp2 != null) {
            if ($mdp1 == $mdp2) {
                $test = true;          
            }
        }
        return $test;
    }
}