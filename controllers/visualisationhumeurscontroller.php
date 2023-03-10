<?php

/**
 * visualisationHumeursController.php
 * @author Info2 IUT Rodez Oskar Morel, Simon Launay, Rémi Jauzion, Antoine Gouzy, Gauthier Jalbaud
 * @CheckYourMood 2022-2023
 */

namespace controllers;

use yasmf\view;
use yasmf\controller;
use yasmf\httphelper;
use yasmf\config;
use model\verificationservice;
use model\stathumeurservice;

/**
 * Class de visualisationHumeursController
 * Permet a un utilisateur de visualiser ses humeurs dans le temps
 * @package controllers
 */
class visualisationHumeursController implements controller
{
    /**
     * @param $pdo connexion à la base de données
     * @param $err message d'erreur
     * @return view vue retournée au routeur
     */
    public function index($pdo)
    {
        $view = new view(config::getRacine() . "views/vue_visualisationhumeurs");

        $view->setVar('choixVisualisation', httphelper::getParam('choixVisualisation'));
        
        $codeUtilisateur = httphelper::getParam('codeUtilisateur');

        $view->setVar('humeursStat', stathumeurservice::getNbEmotion($pdo, $codeUtilisateur));
        
        $dateDebut = httphelper::getParam('dateDebut');
        $dateFin = httphelper::getParam('dateFin');
        $view->setVar('tableauDates', stathumeurservice::getDates($pdo, $dateDebut, $dateFin, $codeUtilisateur));

        for ($i = 1; $i <= 27; $i++){
            $tableau = 'tableau'.$i;
            $view->setVar($tableau, stathumeurservice::getNbHumeursParEmotions($pdo, $dateDebut, $dateFin, $i, $codeUtilisateur));
        }

        return $view;
    }  

    /**
     * @param pdo connexion à la base de données
     * @return view appel de la méthode index
     */
    public function afficher($pdo)
    {

        $codeUtilisateur = httphelper::getParam('codeUtilisateur');
        $humeurStat = stathumeurservice::getNbEmotion($pdo, $codeUtilisateur);

        return $this->index($pdo);
    }

    /**
     * @param pdo connexion à la base de données
     * @return view appel de la méthode index
     */
    public function getDatas($pdo)
    {
        $codeUtilisateur = httphelper::getParam('codeUtilisateur');
        $dateDebut = httphelper::getParam('dateDebut');
        $dateFin = httphelper::getParam('dateFin');
        $codeEmotion = 1;

        $tableauHumeursByDate = stathumeurservice::getNbHumeursParEmotions($pdo, $dateDebut, $dateFin, $codeEmotion, $codeUtilisateur);
        $tableauDates = stathumeurservice::getDates($pdo, $dateDebut, $dateFin, $codeUtilisateur);

        return $this->index($pdo);
    }

    /**
     * @param pdo connexion à la base de données
     * @return view appel de la méthode index
     */
    public function getHumeursByDate($pdo)
    {
        $codeUtilisateur = httphelper::getParam('codeUtilisateur');
        $dateDebut = httphelper::getParam('dateDebut');
        $dateFin = httphelper::getParam('dateFin');

        return $this->index($pdo);
    }

}

