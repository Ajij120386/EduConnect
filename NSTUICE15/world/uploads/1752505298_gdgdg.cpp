#include<bits/stdc++.h>
using namespace std;
const int a=17,b=20;
string encrypt(string msg)
{
    string cipher;
    for(int i=0;i<msg.size();i++)
    {
        if(msg[i]!=' ')
        {
            cipher+=(char((a*(msg[i]-'A')+b)%26+'A'));
        }
        else{
            cipher+=' ';
        }
    }

    return cipher;
}
string decrypt(string cipher)
{
    string plain;
    int a_inv;
    for(int i=0;i<26;i++)
    {
        if((a*i)%26==1)
            a_inv=i;
    }
    cout<<a_inv<<endl;
    for(int i=0;i<cipher.size();i++)
    {
        if(cipher[i]!=' ')
        {
            int t =((cipher[i]-'A')-b)+26;
            plain+=(char((a_inv * t)%26 + 'A'));
        }
        else{
            plain+=' ';
        }

    }

    return plain;
}
int main()
{
    string msg;
    cout<<"enter the message:"<<endl;
    getline(cin,msg);
    for(int i=0;i<msg.size();i++)
    {
        msg[i] = toupper(msg[i]);
    }
    string ciphertext = encrypt(msg);
    cout<<"The cipher text is:"<<ciphertext<<endl;
    string plain=decrypt(ciphertext);
    cout<<"The plain text:"<<endl;
    cout<<plain<<endl;
}
