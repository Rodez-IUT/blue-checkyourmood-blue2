package com.example.projetsae;
import static java.lang.Integer.parseInt;

import androidx.appcompat.app.AppCompatActivity;
import androidx.fragment.app.Fragment;
import androidx.viewpager2.widget.ViewPager2;

import android.content.Intent;
import android.os.Bundle;
import android.view.View;

import com.google.android.material.tabs.TabLayout;
import com.google.android.material.tabs.TabLayoutMediator;
/**
 * Cette activité comporte 2 onglets accessibles soit via les boutons d'onglets, soit via
 * un "glisser" effectué par l'utilisateur.
 * Le 1er onglet permet de déterminer le jour de la semaine d'une date
 * Le 2ème onglet calcule le nombre de jours qui séparent 2 dates
 * @author C. Servières
 */

public class HomeActivity extends AppCompatActivity {

    public int id;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

        Intent intent = getIntent();
               id = parseInt(intent.getStringExtra("id"));

        setContentView(R.layout.activitemain2);
        /*
         * on récupère un accès sur le ViewPager défini dans la vue
         * ainsi que sur le TabLayout qui gèrera les onglets
         */
        ViewPager2 gestionnairePagination = findViewById(R.id.activitemain2_viewpager);
        TabLayout gestionnaireOnglet = findViewById(R.id.tab_layout);
        /*
         * on associe au ViewPager un adaptateur (c'est lui qui organise le défilement
         * entre les fragments à afficher)
         */
        gestionnairePagination.setAdapter(new AdaptateurPage(this,id));
        /*
         * On regroupe dans un tableau les intitulés des boutons d'onglet
         */
        String[] titreOnglet = {getString(R.string.Mexprimer),
                getString(R.string.visualisation)};

        /*
        * On crée une instance de type TabLayoutMediator qui fera le lien entre
        * le gestionnaire de pagination et le gestionnaire des onglets
        * La méthode onConfigureTab permet de préciser quel initulé de bouton d'onglets
        * correspond à tel ou tel onglet, selon la position de celui-ci
        * L'instance TabLayoutMediator est attachée à l'activité courante
        *
        8
        */
        new TabLayoutMediator(gestionnaireOnglet, gestionnairePagination,
                new TabLayoutMediator.TabConfigurationStrategy() {
                    @Override
                    public void onConfigureTab(TabLayout.Tab tab, int position) {
                        tab.setText(titreOnglet[position]);
                    }
                }).attach();
        /*
         * On associe un écouteur au gestionnaire des onglets
         * Le but est d'effacer la zone de résultat située sur chacun des onglets
         * chaque fois que l'utilisateur change d'onglet (via les boutons d'onglets
         * ou via un "glisser").
         * Grâce à l'écouteur, la méthode de callback onTabSelected est appelée lors d'un
         * changement d'onglet.
         */


        gestionnaireOnglet.addOnTabSelectedListener(
                new TabLayout.OnTabSelectedListener() {

                    public void onTabSelected(TabLayout.Tab tab) {
                    /*
                    * On récupère le numéro du fragment courant
                    * La méthode getCurrrentItem renvoie le numéro (ou la position,
                    conformément
                    * à ce qui a été fait dans la classe AdaptateurPage) du fragment
                    * actuellement affiché.
                    */
                        int numFragCourant = gestionnairePagination.getCurrentItem();

                        /*
                        * on réucpère le fragment courant (celui qui est affiché) à
                        partir de
                        * son identifiant, grâce à la méthode findFragmentByTag
                        * Par convention, un identifiant de fragment est de la forme
                        * f suivi du numéro du fragment. Donc f0 ou f1, par exemple
                        */
                        Fragment courant =
                                getSupportFragmentManager().findFragmentByTag("f"
                                        + numFragCourant);

                        /*
                        * Il faut ensuite appliquer la méthode razResultat sur le fragment
                        courant
                        * Au préalable, on convertit l'instance "courant" en FragmentJour
                        ou
                        * FragmentEcart, selon le numéro du fragment courant
                        */

                    }

                    @Override
                    public void onTabUnselected(TabLayout.Tab tab) {
                    }

                    @Override
                    public void onTabReselected(TabLayout.Tab tab) {
                    }
                }
        );
    }
    public void refresh(View view){
        VisualisationHumeur fragment = (VisualisationHumeur) getSupportFragmentManager().findFragmentByTag("f1");
        if (fragment != null) {
            fragment.onResume();
        }
    }
}
