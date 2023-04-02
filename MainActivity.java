package com.exercice.cymdroidsaisiehumeurchatgpt;

import android.os.Bundle;
import android.util.Log;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.Spinner;
import android.widget.Toast;

import androidx.appcompat.app.AppCompatActivity;

import com.android.volley.Request;
import com.android.volley.RequestQueue;
import com.android.volley.toolbox.JsonArrayRequest;
import com.android.volley.toolbox.StringRequest;
import com.android.volley.toolbox.Volley;

import org.json.JSONException;
import org.json.JSONObject;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class MainActivity extends AppCompatActivity {
    private final String url = "http://192.168.31.214/API/emotion";

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);

        // Récupération des émotions via l'API
        getEmotions();

        // Création du Spinner pour les émotions
        Spinner emotionsSpinner = findViewById(R.id.emotions_spinner);

        // Création d'un ArrayAdapter pour le Spinner
        ArrayAdapter<String> adapter = new ArrayAdapter<>(this,
                android.R.layout.simple_spinner_item, android.R.id.text1);

        // Spécification du layout utilisé pour l'affichage des choix du Spinner
        adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);

        // Assignation de l'ArrayAdapter au Spinner
        emotionsSpinner.setAdapter(adapter);

        // Récupération de l'utilisateur courant
        int idUtilisateur = 71; // Exemple

        // Création de l'OnClickListener pour le bouton de saisie d'humeur
        // Récupération des vues
        EditText descriptionEditText = findViewById(R.id.emotion_description_edittext);
        Button saisirEmotionButton = findViewById(R.id.saisir_emotion_button);

// Création de l'OnClickListener pour le bouton de saisie d'humeur
        saisirEmotionButton.setOnClickListener(view -> {
            // Récupération des données
            int idEmotion = emotionsSpinner.getSelectedItemPosition() + 1;
            String description = descriptionEditText.getText().toString();

            // Appel de la méthode saisirHumeur
            saisirHumeur(idUtilisateur, idEmotion, description);
        });

    }

    // Méthode pour récupérer les émotions depuis l'API
    private void getEmotions() {
        RequestQueue queue = Volley.newRequestQueue(this);
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
                    Spinner emotionsSpinner = findViewById(R.id.emotions_spinner);
                    ArrayAdapter<String> adapter = new ArrayAdapter<>(MainActivity.this,
                            android.R.layout.simple_spinner_item, emotionsList);
                    adapter.setDropDownViewResource(android.R.layout.simple_spinner_dropdown_item);
                    emotionsSpinner.setAdapter(adapter);
                }, error -> {
            // Traitement de l'erreur
            Toast.makeText(MainActivity.this, "Erreur de récupération des émotions.", Toast.LENGTH_SHORT).show();
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
        String url = "http://192.168.31.214/API/saisieHumeur/" + idUtilisateur + "/" + idEmotion + "/" + description;
        RequestQueue queue = Volley.newRequestQueue(MainActivity.this);
        StringRequest stringRequest = new StringRequest(Request.Method.POST, url,
                response -> {
                    // Traitement de la réponse
                    // Vous pouvez par exemple afficher un message de confirmation à l'utilisateur
                    Toast.makeText(MainActivity.this, "Humeur enregistrée avec succès !", Toast.LENGTH_SHORT).show();
                }, error -> {
            // Traitement de l'erreur
            // Vous pouvez par exemple afficher un message d'erreur à l'utilisateur
            Toast.makeText(MainActivity.this, "Erreur lors de la saisie de l'humeur.", Toast.LENGTH_SHORT).show();
            Log.e("MainActivity", "Erreur lors de la saisie de l'humeur : " + error.getMessage());
        });

        // Ajout de la requête à la queue
        queue.add(stringRequest);
    }




}