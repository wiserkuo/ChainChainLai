package cliq.debt;
import android.content.Context;
import android.content.Intent;
import android.os.AsyncTask;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.app.Activity;
import android.util.Log;
import android.view.Menu;
import android.view.MenuItem;
import android.view.View;
import android.widget.AdapterView;
import android.widget.ArrayAdapter;
import android.widget.Button;
import android.widget.EditText;
import android.widget.ListView;
import android.widget.TextView;
import android.widget.Toast;

import org.json.JSONArray;
import org.json.JSONException;
import org.json.JSONObject;

import java.io.BufferedReader;
import java.io.InputStreamReader;
import java.net.HttpURLConnection;
import java.net.URL;
import java.net.URLEncoder;

public class MainActivity extends AppCompatActivity implements View.OnClickListener{
    String mEmail;
    private TextView mTvMessage;
    private ListView mLvUnregistered;
    private ListView mLvRegistered;
    private String[] listUnregistered={"","","","","","","","","","","","",""};
    private String[] listRegistered={"","","","","","","","","","","","",""};
    private ArrayAdapter<String> listAdapterUnregistered;
    private ArrayAdapter<String> listAdapterRegistered;
    private FriendsTask mFriendsTask ;
    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        //listUnregistered[0]="";
        mTvMessage = (TextView) findViewById(R.id.textViewMessage);

        Intent intent=getIntent();
        String message = intent.getStringExtra(LoginActivity.EXTRA_MESSAGE);
        mEmail = intent.getStringExtra(LoginActivity.EMAIL);
        mTvMessage.setText(message);

        mLvUnregistered= (ListView)findViewById(R.id.listViewUnregistered);
        mLvRegistered= (ListView)findViewById(R.id.listViewRegistered);
        listAdapterUnregistered = new ArrayAdapter<String>(this,android.R.layout.simple_list_item_1,listUnregistered);
        listAdapterRegistered = new ArrayAdapter<String>(this,android.R.layout.simple_list_item_1,listRegistered);
        mLvUnregistered.setAdapter(listAdapterUnregistered);
        mLvRegistered.setAdapter(listAdapterRegistered);
        mLvUnregistered.setOnItemClickListener(new AdapterView.OnItemClickListener(){
            @Override
            public void onItemClick(AdapterView<?> parent,View view,int position,long id){
                Toast.makeText(getApplicationContext(),"Choose "+listUnregistered[position],Toast.LENGTH_SHORT).show();
            }
        });
        mFriendsTask = new FriendsTask(this);
        mFriendsTask.execute(mEmail);
    }
    @Override
    public void onClick(View v) {

    }
    /**
     * Represents an asynchronous login/registration task used to authenticate
     * the user.
     */
    public class FriendsTask extends AsyncTask<String,Void,String> {

        //        private final String mEmail;
//        private final String mPassword;
        private Context context;
        private String email;
        FriendsTask(Context context) {

            this.context=context;
        }

        @Override
        protected String doInBackground(String... arg0) {
            // TODO: attempt authentication against a network service.
            String result=null;
            String link;
            String data;
            email=arg0[0];
//            mEmail = email;
//            mPassword = password;
            BufferedReader bufferedReader;
            try {
                // Simulate network access.
                Thread.sleep(2000);
                data = "?function="+ URLEncoder.encode("2","UTF-8");
                data += "&email=" + URLEncoder.encode(email, "UTF-8");
//                data += "&password=" + URLEncoder.encode(password, "UTF-8");

                link = "http://wiser.synology.me/debt_server.php" + data;
                URL url = new URL(link);
                HttpURLConnection con = (HttpURLConnection) url.openConnection();

                bufferedReader = new BufferedReader(new InputStreamReader(con.getInputStream()));
                StringBuilder sb=new StringBuilder();
                String line=null;
                while((line = bufferedReader.readLine())!=null){
                    sb.append(line+"\n");
                }
                result=sb.toString();

            } catch (Exception e) {
                return new String("Exception: "+ e.getMessage());
            }


            return result;
            // TODO: register the new account here.
            // return true;
        }

        @Override
        protected void onPostExecute(String result) {
             mFriendsTask = null;
           // showProgress(false);
            JSONArray friends =null;

            String jsonStr = result;
            int un_count=0;
            if (jsonStr != null) {
                try {
                    Log.d("Debt","onPostExecute......................"+result);
                    JSONObject jsonObj = new JSONObject(jsonStr);
                    friends=jsonObj.getJSONArray("result");
                    for(int i=0;i<friends.length();i++){
                        JSONObject c=friends.getJSONObject(i);
                        String friend_name=c.getString("friend_name");
                        String registered=c.getString("registered");
                        if(registered.equals("0")){
                            listUnregistered[i]=friend_name;
                            un_count++;
                        }
                        else if(registered.equals("1")){

                            listRegistered[i-un_count]=friend_name;
                        }
                    }

                    listAdapterUnregistered.notifyDataSetChanged();
                    listAdapterRegistered.notifyDataSetChanged();
                    //String query_result = jsonObj.getString("login_result");
                     //Toast.makeText(context, query_result, Toast.LENGTH_SHORT).show();

                } catch (JSONException e) {
                    e.printStackTrace();
                    Toast.makeText(context, "Error parsing JSON data.", Toast.LENGTH_SHORT).show();
                }
            } else {
                Toast.makeText(context, "Couldn't get any JSON data.", Toast.LENGTH_SHORT).show();
            }
//
//            if (success) {
//                finish();
//            } else {
//                mPasswordView.setError(getString(R.string.error_incorrect_password));
//                mPasswordView.requestFocus();
//            }
        }

        @Override
        protected void onCancelled() {
            mFriendsTask = null;
           // showProgress(false);
        }
    }
}
