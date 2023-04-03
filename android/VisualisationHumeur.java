package com.example.checkyourmood;

import androidx.fragment.app.Fragment;

import android.os.Bundle;
import android.text.Html;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.ListView;
import android.widget.TextView;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.Response;
import com.android.volley.VolleyError;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

public class VisualisationHumeur extends Fragment  {

    /**
     * File d'attente pour les requêtes Web (en lien avec l'utilisation de Volley)
     */
    private RequestQueue fileRequete;

    private JSONArray array;
    private TextView erreur;
    private ListView date;
    private ListView emotion;
    private ListView description;
    private static final String URL = "http://10.0.2.2:80/API/affichage";
    @Override
    public void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);

    }
    public static VisualisationHumeur newInstance() {
        VisualisationHumeur fragment = new VisualisationHumeur();
        return fragment;
    }
    public VisualisationHumeur(){

    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
        View vueDuFragment = inflater.inflate(R.layout.fragment_visualisation, container, false);
        date = vueDuFragment.findViewById(R.id.list_view1);
        emotion = vueDuFragment.findViewById(R.id.list_view2);
        description = vueDuFragment.findViewById(R.id.list_view3);
        demarer();
        return vueDuFragment;
    }



    public void demarer(){
        System.out.println("toto");
        System.out.println(URL);
        // Obtenir une référence à l'adaptateur de la ListView
        ArrayAdapter<String> adapterDate = new ArrayAdapter<String>(getContext(), android.R.layout.simple_list_item_1);
        date.setAdapter(adapterDate);

        ArrayAdapter<String> adapterEmotion =new ArrayAdapter<String>(getContext(), android.R.layout.simple_list_item_1);
        emotion.setAdapter(adapterEmotion);

        ArrayAdapter<String> adapterDescription = new ArrayAdapter<String>(getContext(), android.R.layout.simple_list_item_1);
        description.setAdapter(adapterDescription);

        JsonArrayRequest requestVolley = new JsonArrayRequest(Request.Method.GET, URL, null,
                new com.android.volley.Response.Listener<JSONArray>() {

            @Override
            public void onResponse(JSONArray response) {
                try {
                    // Créer une liste pour stocker les éléments du spinner
                    // Parcourir la réponse de l'API et ajouter chaque élément à la liste
                    String dates = "Dates et heure";
                    String emotion = "emotion";
                    String descriptions = "description";
                    adapterDate.add(dates);
                    adapterEmotion.add(emotion);
                    adapterDescription.add(descriptions);
                    for (int i = response.length()-1; i > response.length()-6; i--) {

                        JSONObject jsonObject = response.getJSONObject(i);

                        dates = jsonObject.getString("DATE_HEURE");
                        emotion = jsonObject.getString("EMOJI");
                        String nomEmotion = jsonObject.getString("NOM");
                        descriptions = jsonObject.getString("DESCRIPTION");
                        CharSequence emoji = Html.fromHtml(emotion, Html.FROM_HTML_MODE_LEGACY);
                        emotion = emoji+ "\n" + nomEmotion;
                        adapterDate.add(dates);
                        adapterEmotion.add(emotion);
                        descriptions = descriptions.substring(0,25);
                        adapterDescription.add(descriptions);
                    }
                    adapterDate.notifyDataSetChanged();
                    adapterEmotion.notifyDataSetChanged();
                    adapterDescription.notifyDataSetChanged();

                } catch (JSONException e) {
                    erreur.setText("erreur");
                }
            }
        }, new Response.ErrorListener() {
            @Override
            public void onErrorResponse(VolleyError error) {
                // Gérer les erreurs de l'appel de l'API
                System.out.println(error);
                erreur.setText(error.toString());
            }
        });

// Ajouter la requête à la file d'attente de Volley
        getFileRequete().add(requestVolley);

    }
    /**
     * Renvoie la file d'attente pour les requêtes Web :
     * - si la file n'existe pas encore : elle est créée puis renvoyée
     * - si une file d'attente existe déjà : elle est renvoyée
     * On assure ainsi l'unicité de la file d'attente
     * @return RequestQueue une file d'attente pour les requêtes Volley
     */
    private RequestQueue getFileRequete() {
        if (fileRequete == null) {
            fileRequete = Volley.newRequestQueue(getContext());
        }
// sinon
        return fileRequete;
    }


}