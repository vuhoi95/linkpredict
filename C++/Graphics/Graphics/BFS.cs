using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Graphics
{
    public class BFS
    {

        /*
         * #include "iostream"
#include "stdio.h"

using namespace std;

int Next[5010][5010];
int l[5010], emax[5010];
bool check[5010];
int N, M;

void input(){
	int u, v;
	
	scanf("%d%d", &N, &M);
	//cin>>N>>M;
	
	for(int i=1;i<=N;i++){
		l[i] = 0;
	}
	
	for(int i=0;i<M;i++){
		scanf("%d%d", &u, &v);
		//cin>>u>>v;
		l[u]+=1; Next[u][l[u]] = v;
		l[v]+=1; Next[v][l[v]] = u;
	}
}

void init(){
	for(int i=1;i<=N;i++){
		check[i] = true;
	}
}

void showData(){
	for(int i=1;i<=N;i++){
		cout<<"i = "<<i<<" -> ";
		for(int j=1;j<=l[i];j++)
			cout<<Next[i][j]<<" ";
		cout<<endl;
	}
}

void BFS(int node){
	int queue[5010];
	int lqueue = 0;
	int index = 0;
	
	emax[node] = 0;
	
	queue[0] = node;
	lqueue=1;
	check[node] = false;
	
	while(index<lqueue && lqueue<N){
		int u = queue[index];
		for(int v=1;v<=l[u];v++)
			if(check[Next[u][v]]){
				emax[Next[u][v]] = emax[u]+1;
				check[Next[u][v]] = false;
				queue[lqueue] = Next[u][v];
				lqueue+=1;
			}
		index+=1;
	}
}

void solve(){
	int dmin[5010];
	for(int i=1;i<=N;i++){
		init();
		BFS(i);
		int MAX = 0;
		for(int j=1;j<=N;j++)
			if(emax[j]>MAX)
				MAX = emax[j];
		dmin[i] = MAX;
	}
	int MIN = dmin[1];
	for(int i=2;i<=N;i++)
		if(dmin[i]<MIN)
			MIN = dmin[i];
	cout<<MIN;
}

int main(){
	input();
	solve();
}
         */
    }
}
