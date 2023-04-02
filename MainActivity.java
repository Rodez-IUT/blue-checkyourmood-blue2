package com.example.projetsae;

import androidx.appcompat.app.AppCompatActivity;

import android.content.Context;
import android.content.Intent;
import android.net.ConnectivityManager;
import android.net.NetworkInfo;
import android.os.Bundle;
import android.view.View;
import android.widget.EditText;
import android.widget.TextView;
import android.widget.Toast;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.JsonObjectRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.UnsupportedEncodingException;
import java.net.URLEncoder;
import java.security.MessageDigest;
import java.security.NoSuchAlgorithmException;

public class MainActivity extends AppCompatActivity {


    /**
     * URL pour consulter les informations relatives à un code postal
     */
    private static final String URL_UTLISATEUR = "http://10.0.2.2:80/API/affichage";

    /**
     * Zone de saisie de l'identifiant
     */
    private EditText saisieIdentifiant;

    /**
     * Zone de saisie du mot de passe
     */
    private EditText saisieMotDePasse;

    /**
     * Zone de saisie du mot de passe
     */
    private TextView titre;
    /**
     * File d'attente pour les requêtes Web (en lien avec l'utilisation de Volley)
     */
    private RequestQueue fileRequete;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // widgets de connexion
        saisieIdentifiant = findViewById(R.id.identifiant);
        saisieMotDePasse = findViewById(R.id.MotDePasse);
        titre = findViewById(R.id.Titre);
    }

    /**
     * Renvoie la file d'attente pour les requêtes Web :
     * - si la file n'existe pas encore : elle est créée puis renvoyée
     * - si une file d'attente existe déjà : elle est renvoyée
     * On assure ainsi l'unicité de la file d'attente
     *
     * @return RequestQueue une file d'attente pour les requêtes Volley
     */
    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(this);
        }
        // sinon
        return fileRequete;
    }

    /**
     * Invoquée lors du clic sur le bouton Connexion
     *
     * @param view Bouton à l'origine du clic
     */
    public void clicConnexion(View view) {
        // on vérifie si la connexion à Internet est possible
        ConnectivityManager gestionnaireConnexion =
                (ConnectivityManager) getSystemService(Context.CONNECTIVITY_SERVICE);
        NetworkInfo informationReseau = gestionnaireConnexion.getActiveNetworkInfo();
        if (informationReseau == null || !informationReseau.isConnected()) {
            // problème de connexion réseau
            Toast.makeText(this,
                    getResources().getString(R.string.message_erreur_connexion),
                    Toast.LENGTH_LONG).show();
        }
        try {
            // les identifiant et mot de passe de l'utilisateur sont récupéré et encodé en UTF-8
            String identifiant = URLEncoder.encode(saisieIdentifiant.getText().toString(), "UTF-8");
            String motDePasse = URLEncoder.encode(saisieMotDePasse.getText().toString(), "UTF-8");
            Boolean test = false;

            System.out.println(URL_UTLISATEUR);
            /*
             * on crée une requête GET, paramètrée par l'url préparée ci-dessus,
             * Le résultat de cette requête sera un objet JSon, donc la requête est de type
             * JsonObjectRequest
             */
            JsonArrayRequest requeteVolley = new JsonArrayRequest(Request.Method.GET,
                    URL_UTLISATEUR, null,
                    new com.android.volley.Response.Listener<JSONArray>() {
                        @Override
                        public void onResponse(JSONArray reponse) {
                            if(setZoneResultatAvecJsonArray(reponse,identifiant,motDePasse)){
                                Intent intent = new Intent(MainActivity.this, HomeActivity.class);
                                intent.putExtra("NOM", identifiant);
                                startActivity(intent);
                            }else{
                                titre.setText(R.string.identifiant);
                            }
                        }
                    },
                    new com.android.volley.Response.ErrorListener() {
                        @Override
                        public void onErrorResponse(VolleyError error) {
                            titre.setText(R.string.message_erreur);
                        }
                    });
            getFileRequete().add(requeteVolley);
        } catch (UnsupportedEncodingException erreur) {
            System.out.println(erreur);
            Toast.makeText(this, R.string.message_erreur, Toast.LENGTH_LONG).show();
        }
    }

    /**
     * Affiche dans la zone de résultat, les informations essentielles associées au film
     * recherché : Titre, Année de sortie, les acteurs principaux
     *
     * @param reponse objet Json contenant la description du film renvoyé par la requête
     */
    public boolean setZoneResultatAvecJsonArray(JSONArray reponse, String identifiant, String motDePasse) {
        JSONObject objetTypeClient; // contiendra successivement chacun des objets du tableau
        try {
            StringBuilder resultatFormate = new StringBuilder();
            // on parcourt chacun des objets de l'objet reponse
            for (int i = 0; i < reponse.length(); i++) {
                // on récupère l'objet de rang i, en tant qu'objet Json
                objetTypeClient = reponse.getJSONObject(i);
                // on récupère la valeur du champs TYPE_CLIENT_DESIGNATION
                if (objetTypeClient.getString("NOM_UTILISATEUR").equals(identifiant)){
                    if(objetTypeClient.getString("MOT_DE_PASSE").equals(testMdp(motDePasse))){
                        return true;
                    }
                }
            }
            return false;
        } catch(JSONException erreur) {
            System.out.println(erreur);
            Toast.makeText(this, R.string.message_erreur, Toast.LENGTH_LONG).show();
            return false;
        } catch (NoSuchAlgorithmException erreur) {
            System.out.println(erreur);
            Toast.makeText(this, R.string.message_erreur, Toast.LENGTH_LONG).show();
            return false;
        }
    }
    public String testMdp(String motDePasse) throws NoSuchAlgorithmException {

        MessageDigest md = MessageDigest.getInstance("SHA-1");
        md.update(motDePasse.getBytes());
        byte[] hash = md.digest();

        // convertir le hachage en une chaîne hexadécimale
        StringBuilder sb = new StringBuilder();
        for (byte b : hash) {
            sb.append(String.format("%02x", b));
        }
        String sha1Hex = sb.toString();
        return sha1Hex;
    }
}