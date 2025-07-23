#include<bits/stdc++.h>
using namespace std;

string plain_t;
int key;

string encryptRailFence()
{
    int sz = plain_t.size();
    char rail[key][sz];

    for (int i=0; i < key; i++)
        for (int j = 0; j < sz; j++)
            rail[i][j] = '.';

    bool niche = true;
    for (int i=0,j=0; j < sz; j++)
    {
        rail[i][j] = plain_t[j];
        if(i == 0)
            niche = true;
        if(i == key-1)
            niche = false;
        if(niche)
            i++;
        else
            i--;
    }

    //now we can construct the cipher using the rail matrix
    string result;
    for (int i=0; i < key; i++)
        for (int j=0; j < sz; j++)
            if (rail[i][j]!='.')
                result+=rail[i][j];
    return result;
}


string decryptRailFence(string cipher)
{
int sz = cipher.size();
    char rail[key][sz];

    for (int i=0; i < key; i++)
        for (int j = 0; j < sz; j++)
            rail[i][j] = '.';

    bool niche = true;
    for (int i=0,j=0; j < sz; j++)
    {
        rail[i][j] = cipher[j];
        if(i == 0)
            niche = true;
        if(i == key-1)
            niche = false;
        if(niche)
            i++;
        else
            i--;
    }

    //now we can construct the cipher using the rail matrix
    string result;
    for (int i=0; i < key; i++)
        for (int j=0; j < sz; j++)
            if (rail[i][j]!='.')
                result+=rail[i][j];
    return result;
}



int main()
{
    getline(cin, plain_t);
    cin >> key;

    for(int i = 0; i<plain_t.size(); i++)
        if(plain_t[i] == ' ') plain_t [i] = '_'; /// space r jaygay '_' eta use korchi


    string cipher_text = encryptRailFence();
    string decrrypt = decryptRailFence(cipher_text);
    cout << plain_t << '\n';
    cout << cipher_text << '\n';
    cout << decrrypt << '\n';

    return 0;
}



///  Alhamdulillah...

