<?php

/**
 * consulterHumeursController.php
 * @author Info2 IUT Rodez Oskar Morel, Simon Launay, Rémi Jauzion, Antoine Gouzy, Gauthier Jalbaud
 * @CheckYourMood 2022-2023
 */

namespace controllers;
use PDO;
use yasmf\view;
use yasmf\controller;
use yasmf\httphelper;
use yasmf\config;
use model\verificationservice;
use model\humeurservice;
use model\emotionsservice;

/**
 * Class de consulterHumeursController
 * Permet a un utilisateur de pouvoir consulter ses humeurs
 * @package controllers
 */
class consultationHumeursController implements controller
{
    /**
     * @param $pdo connexion à la base de données
     * @param $err message d'erreur
     * @return view vue retournée au routeur
     */
    public function index(PDO $pdo): View
    {
        $view = new view(config::getRacine() . "views/vue_consultationhumeur");
       
        $codeUtilisateur = httphelper::getParam('codeUtilisateur');
        
        $view->setVar('dateSaisie', httphelper::getParam('dateSaisie'));
        $view->setVar('codeEmotion', httphelper::getParam('codeEmotion'));
        $view->setVar('codeUtilisateur', httphelper::getParam('codeUtilisateur'));
        $view->setVar('humeurSupp', httphelper::getParam('humeurSupp'));

        $codeUtilisateur = httphelper::getParam('codeUtilisateur');

        //Filtres possibles
        $codeEmotion = httphelper::getParam('codeEmotion');
        $dateSaisie = httphelper::getParam('dateSaisie');
        if(isset($codeEmotion) && $codeEmotion == ""){
            $codeEmotion = null;
        }
        if (isset($dateSaisie) && $dateSaisie == "") {
            $dateSaisie = null;
        }
        $_POST['humeurs'] = humeurservice::getHumeursUtilisateur($pdo, $codeUtilisateur, $codeEmotion, $dateSaisie);

        $view->setVar('humeurs', httphelper::getParam('humeurs'));
        $view->setVar('tabEmotions', emotionsservice::getEmotions($pdo));

        return $view;
    }  

    /**
     * @param $pdo connexion à la base de données
     * @param $err message d'erreur
     * @return view vue retournée au routeur
     */
    public function consulter(PDO $pdo): View
    {
        return $this->index($pdo);
    }  

    /**
     * Suppression d'une humeur d'un utilisateur
     * @param pdo connexion à la base de données
     * @return view appel de la méthode index
     */
    public function supprimer(PDO $pdo)
    {
        $codeUtilisateur = httphelper::getParam('codeUtilisateur');
        $codeHumeur = httphelper::getParam('codeHumeur');

        humeurservice::suppHumeursUtilisateur($pdo, $codeUtilisateur, $codeHumeur);

        return $this->index($pdo);
    }

}

