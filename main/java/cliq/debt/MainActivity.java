package cliq.debt;
import android.content.Context;
import android.content.Intent;
import android.support.v7.app.AppCompatActivity;
import android.os.Bundle;
import android.app.Activity;
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

public class MainActivity extends AppCompatActivity implements View.OnClickListener{
    String mEmail;
    private TextView mTvMessage;
    private ListView mLvUnregistered;
    private ListView mLvRegistered;
    private String[] listUnregistered={"wiser","stan","una","amber"};
    private String[] listRegistered={"gary","aaron","thomas"};
    private ArrayAdapter<String> listAdapterUnregistered;
    private ArrayAdapter<String> listAdapterRegistered;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_main);
        mTvMessage = (TextView) findViewById(R.id.textViewMessage);

        Intent intent=getIntent();
        String message = intent.getStringExtra(LoginActivity.EXTRA_MESSAGE);

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
    }
    @Override
    public void onClick(View v) {

    }

}
