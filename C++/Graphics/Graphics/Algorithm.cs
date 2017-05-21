using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

namespace Graphics
{
    public class Algorithm
    {
        public List<int> StringToListNumber(String str)
        {
            List<int> list = new List<int>();
            str = str.Replace("[", "");
            str = str.Replace("]", " ");
            str = str.Replace(",", " ");

            while (str.IndexOf(" ") != -1)
            {
                int index = str.IndexOf(" ");
                int number = int.Parse(str.Substring(0, index));
                str = str.Substring(index + 1);
                list.Add(number);
            }
            return list;
        }

        public int[] BFS(int node, int[,] arr, int n)
        {
            /*
            Console.WriteLine("----------------------------------");
            for (int i = 0; i < n; i++)
            {
                for (int j = 0; j < n; j++)
                    Console.Write(arr[i, j] + "    ");
                Console.WriteLine();
            }
             * */
            int[] minpath = new int[n + 1];
            int[] queue = new int[n + 1];
            bool[] check = new bool[n + 1];
            int index = 0, length = 1;
            queue[0] = node;
            for (int i = 0; i <= n; i++)
            {
                check[i] = true;
                minpath[i] = 0;
            }
            check[node] = false;
            while (index < length)
            {
                int u = queue[index];
                for(int i=0;i<n;i++)
                    if (check[i] && arr[u, i] == 1)
                    {
                        minpath[i] = minpath[u] + 1;
                        queue[length] = i;
                        check[i] = false;
                        length += 1;
                    }
                index += 1;
            }
            return minpath;
        }

        public float[,] Cal_Weight(int[,] arr, int n)
        {
            float[,] weight = new float[n + 1, n + 1];
            for (int i = 0; i < n; i++)
            {
                int[] minpath = new int[n + 1];
                minpath = BFS(i, arr, n);
                for (int j = 0; j < n; j++)
                {
                    if (minpath[j] == 0)
                        weight[i, j] = 0;
                    else
                        weight[i, j] = (float)(1.0 / minpath[j]);
                }
            }
                return weight;
        }

        public int Zip(int x, int y)
        {
            return x * 30000 + y;
        }

        public List<int> Unzip(int v)
        {
            int y = v % 30000;
            int x = v / 30000;
            List<int> list = new List<int>();
            list.Add(x);
            list.Add(y);
            return list;
        }

        public bool FindBinary(int key, LinkList link)
        {
            int start = 0;
            int finish = link.Count - 1;
            int middle = (start + finish) / 2;
            while (start <= finish)
            {
                if (link.Link[middle] == key)
                    return true;
                if (link.Link[middle] < key)
                    start = middle + 1;
                else
                    finish = middle - 1;
                middle = (start + finish) / 2;
            }
            return false;
        }
    }
}
