package com.example.projetsae;


import androidx.fragment.app.Fragment;
import androidx.fragment.app.FragmentActivity;
import androidx.viewpager2.adapter.FragmentStateAdapter;
/**
 * Cette classe est un adaptateur pour gérer les fragments qui seront associés
 * au ViewPager.
 * Dans sa version minimale, la classe contient un constructeur auquel on passera en
 * argument l'activité qui gère le ViewPager, une méthode createFragment et une méthode
 * getItemCount
 * @author C.Servières
 */
public class AdaptateurPage extends FragmentStateAdapter {
    /** Nombre de fragments gérés par cet adaptateur */
    private static final int NB_FRAGMENT = 2;
    private int id;
    /**
     * Constructeur de base
     * @param activite activité qui contient le ViewPager qui gèrera les fragments
     */
    public AdaptateurPage(FragmentActivity activite, int id) {
        super(activite);
        this.id = id;
    }
    @Override
    public Fragment createFragment(int position) {
        /*
         * Le ViewPager auquel on associera cet adaptateur devra afficher successivement
         * un fragment de type : FragmentUn, puis FragmentDeux, et enfin FragmentTrois
         * C'est dans cette méthode que l'on décide dans quel ordre sont affichés les
         * fragments, et quel fragment doit précisément être affiché
         */
        Fragment fragment = null;
        switch (position) {
            case 0:
                fragment = new SaisieHumeur();
                break;
            case 1:
                fragment = new VisualisationHumeur();
                break;
            default:
                break;
        }

        // Envoyer les paramètres au fragment créé
        EnvoiParametre(fragment, id);
        return fragment;
    }

    @Override
    public int getItemCount() {
// renvoyer le nombre de fragments gérés par l'adaptateur
        return NB_FRAGMENT;
    }
    private void EnvoiParametre(Fragment fragment, int id) {
        if (fragment instanceof SaisieHumeur) {
            ((SaisieHumeur) fragment).getID(id);
        } else if (fragment instanceof VisualisationHumeur) {
            ((VisualisationHumeur) fragment).getID(id);
        }
    }
}