package cliq.debt;

import android.app.Activity;
import android.content.Context;
import android.os.AsyncTask;
import android.os.Bundle;
import android.util.Log;
import android.widget.Toast;

import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class RequestActivity extends AsyncTask<String,Void,String> {

    private Context context;
    public RequestActivity(Context context){

        this.context=context;
    }
    @Override
    protected void onPreExecute(){}
    @Override
    protected String doInBackground(String... arg0){
        String result;
        String email=arg0[0];
        String password=arg0[1];
        String link;
        String data;
        BufferedReader bufferedReader;
        try {

            data = "?email=" + URLEncoder.encode(email, "UTF-8");
            data += "&password=" + URLEncoder.encode(password, "UTF-8");

            link = "http://wiser.synology.me/debt_serverr.php" + data;
            URL url = new URL(link);
            HttpURLConnection con = (HttpURLConnection) url.openConnection();

            bufferedReader = new BufferedReader(new InputStreamReader(con.getInputStream()));
            result = bufferedReader.readLine();
            return result;
        }
        catch (Exception e){
            return new String("Exception: "+ e.getMessage());
        }
    }
    @Override
    protected void onPostExecute(String result) {
        String jsonStr = result;
        if (jsonStr != null) {
            try {
                Log.d("Debt","onPostExecute......................"+result);
                JSONObject jsonObj = new JSONObject(jsonStr);
                String query_result = jsonObj.getString("login_result");
                Toast.makeText(context, query_result, Toast.LENGTH_SHORT).show();
            } catch (JSONException e) {
                e.printStackTrace();
                Toast.makeText(context, "Error parsing JSON data.", Toast.LENGTH_SHORT).show();
            }
        } else {
            Toast.makeText(context, "Couldn't get any JSON data.", Toast.LENGTH_SHORT).show();
        }
    }
}
