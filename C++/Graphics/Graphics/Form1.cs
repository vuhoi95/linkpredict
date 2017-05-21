using System;
using System.Collections.Generic;
using System.ComponentModel;
using System.Data;
using System.Drawing;
using System.IO;
using System.Linq;
using System.Text;
using System.Threading.Tasks;
using System.Windows.Forms;

namespace Graphics
{
    public partial class frm_main : Form
    {
        public List<string> DataBase = new List<string>();
        public List<YearAuthor> ListYearAuthor = new List<YearAuthor>();
        public Algorithm algo = new Algorithm();
        public StreamWriter writer = new StreamWriter("D:\\output_graphics.txt");
        public int CountAuthor = 0;
        public Node[] Graphics = new Node[23050];
        public int[] count_repeat;
        public LinkList[] GraphicsLinkList = new LinkList[50];
        public int[] ListId = new int[10000000];
        public int[] ListRepeat = new int[10000000];
        public int CountListId;

        public frm_main()
        {
            InitializeComponent();
        }

        private void btn_show_data_Click(object sender, EventArgs e)
        {

            DialogResult result = file_stream.ShowDialog();
            if (result == DialogResult.OK)
            {
                string pathName = file_stream.FileName;
                StreamReader reader = new StreamReader(pathName);

                string line;
                while ((line = reader.ReadLine()) != null)
                {
                    DataBase.Add(line);
                }
            }

            for (int i = 0; i < DataBase.Count; i++)
            {
                List<int> list = new List<int>();
                list = algo.StringToListNumber(DataBase[i]);
                YearAuthor yearauthor = new YearAuthor();
                yearauthor.year = list[0];
                for (int j = 1; j < list.Count; j++)
                    yearauthor.author.Add(list[j]);
                ListYearAuthor.Add(yearauthor);
            }

            
            int[,] count = new int[15, 23050];

            for (int i = 0; i < DataBase.Count; i++)
            {
                int year = ListYearAuthor[i].year;
                for (int j = 0; j < ListYearAuthor[i].author.Count; j++)
                {
                    count[year - 1992, ListYearAuthor[i].author[j]] += 1;
                    if (ListYearAuthor[i].author[j] > CountAuthor)
                        CountAuthor = ListYearAuthor[i].author[j];
                }
            }

            count_repeat = new int[CountAuthor + 1];
            int[] id = new int[CountAuthor + 1];
            for (int i = 1; i <= CountAuthor; i++)
            {
                id[i] = i;
                int cnt = 0;
                for (int j = 0; j < 2005 - 1992 + 1; j++)
                    if (count[j, i] > 0)
                        cnt += 1;
                count_repeat[i] = cnt;
            }

            Array.Sort(count_repeat, id);
            //CreateGraphics();
            //IdAuthor = 141,   ----- 141 253 441 1613 1304 228 1843 
            //2168 888 140 448 1121 994 72 739 65 288 2031 354 785 927 810 326 1037 176 460 489 2086 480 1031 1226
            //FindGraphics(10, 1226);//Edit
            CreateSubGraphics();
             
            //CreateGraphicsLinkList();
        }

        public void CreateGraphicsLinkList()
        {
            LinkList[] temp = new LinkList[50];
            for (int year = 1992; year <= 2005; year++)
            {
                temp[year - 1992] = new LinkList();
                for (int k = 0; k < ListYearAuthor.Count; k++)
                    if (ListYearAuthor[k].year == year)
                    {
                        for (int i = 0; i < ListYearAuthor[k].author.Count - 1; i++)
                            for (int j = i + 1; j < ListYearAuthor[k].author.Count; j++)
                            {
                                int u = ListYearAuthor[k].author[i];
                                int v = ListYearAuthor[k].author[j];
                                int zip;
                                if (u < v)
                                    zip = algo.Zip(u, v);
                                else
                                    zip = algo.Zip(v, u);
                                int c = temp[year - 1992].Count;
                                temp[year - 1992].Link[c] = zip;
                                temp[year - 1992].Count += 1;
                                //Console.WriteLine("u = " + u + " v = " + v + " zip = " + GraphicsLinkList[year - 1992].Link[c]);
                            }
                    }
            }
            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                Array.Sort(temp[i].Link, 0, temp[i].Count);
            }

            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                GraphicsLinkList[i] = new LinkList();
                for (int j = 0; j <= temp[i].Count; j++)
                    if (temp[i].Link[j] != temp[i].Link[j + 1])
                    {
                        int c = GraphicsLinkList[i].Count;
                        GraphicsLinkList[i].Link[c] = temp[i].Link[j];
                        GraphicsLinkList[i].Count += 1;
                    }
            }

            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                for (int j = 0; j < GraphicsLinkList[i].Count; j++)
                {
                    int cnt = 0;
                    for (int k = 0; k < 2005 - 1992 + 1; k++)
                    {
                        if (algo.FindBinary(GraphicsLinkList[i].Link[j], GraphicsLinkList[k]))
                            cnt += 1;
                    }
                    GraphicsLinkList[i].Repeat[j] = cnt;
                }
            }

            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                Array.Sort(GraphicsLinkList[i].Repeat, GraphicsLinkList[i].Link, 0, GraphicsLinkList[i].Count);
            }

            CountListId = 0;
            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                for (int j = GraphicsLinkList[i].Count - 1; j >= 0; j--)
                {
                    int u = GraphicsLinkList[i].Link[j];
                    int v = GraphicsLinkList[i].Repeat[j];
                    if (!ListId.Contains(u))
                    {
                        ListId[CountListId] = u;
                        ListRepeat[CountListId] = v;
                        CountListId += 1;
                    }
                }
            }

            /*
            for (int i = 0; i < 2005 - 1992 + 1; i++)
            {
                for (int j = GraphicsLinkList[i].Count - 1; j >= 0; j--)
                {
                    //writer.Write(GraphicsLinkList[i].Link[j] + " (" + GraphicsLinkList[i].Repeat[j] + ")  ");
                    List<int> list = new List<int>();
                    list = algo.Unzip(GraphicsLinkList[i].Link[j]);
                    writer.Write("[" + list[0] + ", " + list[1] + "] -> " + GraphicsLinkList[i].Repeat[j] + "   ");
                }

                writer.WriteLine();
            }
             */
            //Array.Sort(ListRepeat, ListId, 0, CountListId);
            
             for (int i = 0; i < CountListId; i++)
            {
                List<int> list = new List<int>();
                list = algo.Unzip(ListId[i]);
                writer.Write("[" + list[0] + ", " + list[1] + "] -> " + ListRepeat[i] + "   ");
            }
            
            writer.Close();
        }

        public void ProcessingCreateGraphicsLinkList()
        {

        }
        
        public void CreateGraphics()
        {
            for (int i = 0; i <= CountAuthor; i++)
                Graphics[i] = new Node();
            for (int i = 0; i < DataBase.Count; i++)
            {
                for (int j = 0; j < ListYearAuthor[i].author.Count - 1; j++)
                    for (int k = j + 1; k < ListYearAuthor[i].author.Count; k++)
                    {
                        int u = ListYearAuthor[i].author[j];
                        int v = ListYearAuthor[i].author[k];
                        //Graphics[u].NextNode.Add(v);
                        //Graphics[v].NextNode.Add(u);
                        if (!Graphics[u].NextNode.Contains(v))
                        {
                            Graphics[u].NextNode.Add(v);
                        }
                        if (!Graphics[v].NextNode.Contains(u))
                        {
                            Graphics[v].NextNode.Add(u);
                        }
                    }
            }
        }
        
        public List<int> FindGraphics(int Number, int IdAuthor)
        {
            List<int> list = new List<int>();
            int[] id = new int[1000];
            int[] repeat = new int[1000];
            list.Add(IdAuthor);
            int u = IdAuthor;
            for (int i = 0; i < Graphics[u].NextNode.Count; i++)
            {
                int v = Graphics[u].NextNode[i];
                id[i] = v;
                repeat[i] = count_repeat[v];
                Console.WriteLine("v =  " + v + " repeat    " + count_repeat[v]);
            }
            return list;
        }

        public void CreateSubGraphics()
        {
            //[140, 141, 460, 1217, 1226, 1566, 1567, 2450, 2613, 2849]
            List<int> listNode = new List<int>();
            listNode.Add(140);
            listNode.Add(141);
            listNode.Add(460);
            listNode.Add(1217);
            listNode.Add(1226);
            listNode.Add(1566);
            listNode.Add(1567);
            listNode.Add(2450);
            listNode.Add(2613);
            listNode.Add(2849);

            for (int year = 1992; year <= 2005; year++)
            {
                writer.WriteLine(year);
                bool[,] check = new bool[23050, 23050];
                for (int k = 0; k < ListYearAuthor.Count; k++)
                    if (ListYearAuthor[k].year == year)
                    {
                        for (int i = 0; i < ListYearAuthor[k].author.Count - 1; i++)
                            for (int j = i + 1; j < ListYearAuthor[k].author.Count; j++)
                            {
                                check[ListYearAuthor[k].author[i], ListYearAuthor[k].author[j]] = true;
                                check[ListYearAuthor[k].author[j], ListYearAuthor[k].author[i]] = true;
                            }
                    }
                for (int i = 0; i < 10; i++)
                {
                    writer.Write("{");
                    for (int j = 0; j < 10; j++)
                    {
                        int u = listNode[i];
                        int v = listNode[j];
                        if (check[u, v])
                            writer.Write(1 + ", ");
                        else
                            writer.Write(0 + ", ");
                    }
                    writer.Write("},");
                    writer.WriteLine();
                }

            }
            writer.Close();
        }

        private void button1_Click(object sender, EventArgs e)
        {
            int[,] graphics = new int[10, 10]{
                                                              {0, 1, 0, 0, 0, 0, 0, 1, 0, 0, },
{1, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{1, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, },
{0, 0, 0, 0, 0, 0, 0, 0, 0, 0, }
                                                               };
            float[,] value = new float[10, 10];
            value = algo.Cal_Weight(graphics, 10);
            for (int i = 0; i < 10; i++)
            {
                for (int j = 0; j < 10; j++)
                    Console.Write(value[i, j] + "     ");
                Console.WriteLine();
            }
        }

        private void ConvertFileToFile()
        {
            string path_testing = "D:\\VuThiHoi\\testing.txt";
            string path_forecast = "D:\\VuThiHoi\\forecast.txt";
            StreamReader reader_testing = new StreamReader(path_testing);
            StreamReader reader_forecast = new StreamReader(path_forecast);
            string line;

            for (int k = 1; k <= 4; k++)
            {
                float[,] arr_testing = new float[20, 20];
                float[,] arr_forecast = new float[20, 20];
                for(int i=0;i<10;i++)
                    for (int j = 0; j < 10; j++)
                    {
                        line = reader_testing.Read();
                        float u = float.Parse(line.ToString());
                        arr_testing[i, j] = u;

                        line = reader_forecast.Read();
                        u = float.Parse(line.ToString());
                        if(u<=0)
                            u = 0;
                        else
                            u = 1;
                        arr_forecast[i, j] = u;
                    }
                int sum = 0;
                int total = 0;
                for(int i=0;i<10;i++)
                    for(int j=0;j<10;j++)
                        if (i != j)
                        {
                            sum += 1;
                            if (arr_forecast[i, j] == arr_testing[i, j])
                                total += 1;
                        }
                Console.WriteLine("----------------- "+(k+2001)+" ----------------------");
                Console.WriteLine(total * 1.0 / sum);
            }
            /*
            float[,] arr_2002 = new float[20, 20];
            float[,] arr_2003 = new float[20, 20];
            float[,] arr_2004 = new float[20, 20];
            float[,] arr_2005 = new float[20, 20];

            for(int i=1;i<=9;i++)
                for (int j = i + 1; j <= 10; j++)
                {
                    line = reader.ReadLine();

                    line = reader.ReadLine();
                    float u = float.Parse(line.ToString());
                    arr_2002[i - 1, j - 1] = u;
                    arr_2002[j - 1, i - 1] = u;

                    line = reader.ReadLine();
                    u = float.Parse(line.ToString());
                    arr_2003[i - 1, j - 1] = u;
                    arr_2003[j - 1, i - 1] = u;

                    line = reader.ReadLine();
                    u = float.Parse(line.ToString());
                    arr_2004[i - 1, j - 1] = u;
                    arr_2004[j - 1, i - 1] = u;

                    line = reader.ReadLine();
                    u = float.Parse(line.ToString());
                    arr_2005[i - 1, j - 1] = u;
                    arr_2005[j - 1, i - 1] = u;
                }
            for (int i = 0; i <= 9; i++)
            {
                arr_2002[i, i] = 0;
                arr_2003[i, i] = 0;
                arr_2004[i, i] = 0;
                arr_2005[i, i] = 0;
            }
            for (int i = 0; i <= 9; i++)
            {
                for (int j = 0; j <= 9; j++)
                    Console.Write(arr_2002[i, j] + "   ");
                Console.WriteLine();
            }
            Console.WriteLine("------------------------------------------------");
            for (int i = 0; i <= 9; i++)
            {
                for (int j = 0; j <= 9; j++)
                    Console.Write(arr_2003[i, j] + "   ");
                Console.WriteLine();
            }
            Console.WriteLine("------------------------------------------------");
            for (int i = 0; i <= 9; i++)
            {
                for (int j = 0; j <= 9; j++)
                    Console.Write(arr_2004[i, j] + "   ");
                Console.WriteLine();
            }
            Console.WriteLine("------------------------------------------------");
            for (int i = 0; i <= 9; i++)
            {
                for (int j = 0; j <= 9; j++)
                    Console.Write(arr_2005[i, j] + "   ");
                Console.WriteLine();
            }
            Console.WriteLine("------------------------------------------------");
             */
        }

        private void button2_Click(object sender, EventArgs e)
        {
            ConvertFileToFile();
        }
    }
}