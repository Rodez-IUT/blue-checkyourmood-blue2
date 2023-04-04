package com.example.projetsae;

import android.content.Intent;
import android.os.Bundle;
import android.util.Log;
import android.view.LayoutInflater;
import android.view.View;
import android.view.ViewGroup;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.fragment.app.Fragment;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;

public class SaisieHumeur extends Fragment implements View.OnClickListener {
    private final String url = "http://10.0.2.2:80/API/emotion/okjEi8TnWcRvYx2sLz1Qb3uHmAfDpXG";
    private Spinner emotionsSpinner;

    public static int id;
    public SaisieHumeur(){

    }
    private EditText descriptionEditText;
    public static SaisieHumeur newInstance(){
        SaisieHumeur fragment = new SaisieHumeur();
        return fragment;
    }
    @Override
    public void onCreate(Bundle savedInstanceState) {

        super.onCreate(savedInstanceState);
    }
    @Override
    public View onCreateView(LayoutInflater inflater, ViewGroup container,
                             Bundle savedInstanceState) {
// On récupère la vue (le layout) associée au fragment deux et on la renvoie
        View vueDuFragment = inflater.inflate(R.layout.fragment2, container, false);
        getEmotions();
        System.out.println(id);
        // Création du Spinner pour les émotions
        emotionsSpinner = vueDuFragment.findViewById(R.id.emotions_spinner);

        // Création d'un ArrayAdapter pour le Spinner
        ArrayAdapter<String> adapter = new ArrayAdapter<>(getContext(),
                android.R.layout.simple_spinner_item, android.R.id.text1);

        // Spécification du layout utilisé pour l'affichage des choix du Spinner
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        // Assignation de l'ArrayAdapter au Spinner
        emotionsSpinner.setAdapter(adapter);

        // Récupération de l'utilisateur courant
        int idUtilisateur = 71; // Exemple

        // Création de l'OnClickListener pour le bouton de saisie d'humeur
        // Récupération des vues
        descriptionEditText = vueDuFragment.findViewById(R.id.emotion_description_edittext);
        vueDuFragment.findViewById(R.id.saisir_emotion_button).setOnClickListener(this);
        emotionsSpinner = vueDuFragment.findViewById(R.id.emotions_spinner);

        return vueDuFragment;
    }

    // Méthode pour récupérer les émotions depuis l'API
    private void getEmotions() {
        RequestQueue queue = Volley.newRequestQueue(getContext());
        JsonArrayRequest jsonArrayRequest = new JsonArrayRequest(
                Request.Method.GET, url, null,
                response -> {
                    Log.d("MainActivity", "Emotions response: " + response.toString());

                    // Traitement de la réponse
                    ArrayList<String> emotionsList = new ArrayList<>();
                    for (int i = 0; i < response.length(); i++) {
                        try {
                            JSONObject jsonObject = response.getJSONObject(i);
                            String emotion = jsonObject.getString("NOM");
                            emotionsList.add(emotion);
                        } catch (JSONException e) {
                            e.printStackTrace();
                        }
                    }
                    ArrayAdapter<String> adapter = new ArrayAdapter<>(getContext(),
                            android.R.layout.simple_spinner_item, emotionsList);
                    adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    emotionsSpinner.setAdapter(adapter);
                }, error -> {
            // Traitement de l'erreur
            Toast.makeText(getContext(), "Erreur de récupération des émotions.", Toast.LENGTH_SHORT).show();
            Log.e("MainActivity", "Emotions error: " + error.getMessage());
        });

        queue.add(jsonArrayRequest);
    }

    /**
     * Envoie une requête POST à l'API pour saisir une humeur.
     *
     * @param idUtilisateur L'identifiant de l'utilisateur.
     * @param idEmotion     L'identifiant de l'émotion saisie.
     * @param description   La description de l'humeur saisie.
     */
    private void saisirHumeur(int idUtilisateur, int idEmotion, String description) {
        // Création de la requête POST
        String url = "http://10.0.2.2:80/API/saisieHumeur/okjEi8TnWcRvYx2sLz1Qb3uHmAfDpXG/" + idUtilisateur + "/" + idEmotion + "/" + description;
        RequestQueue queue = Volley.newRequestQueue(getContext());
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                response -> {
                    // Traitement de la réponse
                    // Vous pouvez par exemple afficher un message de confirmation à l'utilisateur
                    Toast.makeText(getContext(), "Humeur enregistrée avec succès !", Toast.LENGTH_SHORT).show();
                }, error -> {
            // Traitement de l'erreur
            // Vous pouvez par exemple afficher un message d'erreur à l'utilisateur
            Toast.makeText(getContext(), "Erreur lors de la saisie de l'humeur.", Toast.LENGTH_SHORT).show();
            Log.e("MainActivity", "Erreur lors de la saisie de l'humeur : " + error.getMessage());
        });

        // Ajout de la requête à la queue
        queue.add(stringRequest);
    }
    @Override
    public void onClick(View v) {
        int idEmotion = emotionsSpinner.getSelectedItemPosition() + 1;
        String description = descriptionEditText.getText().toString();

        // Appel de la méthode saisirHumeur
        // Récupération de l'utilisateur courant
        System.out.println(id);
        saisirHumeur(id, idEmotion, description);
    }
    public void getID(int ident) {
        id = ident;
    }
}