

import java.io.BufferedReader;
import java.io.BufferedWriter;
import java.io.File;
import java.io.FileNotFoundException;
import java.io.FileReader;
import java.io.FileWriter;
import java.io.IOException;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.Map;

import org.jsoup.Connection;
import org.jsoup.Connection.Method;
import org.jsoup.Jsoup;
import org.jsoup.nodes.Document;
import org.jsoup.nodes.Element;
import org.jsoup.select.Elements;

public class GetDaTaDemo {
	public static void onePage(String url, String id_list, String year, String month) throws IOException, SQLException {
		Document doc = Jsoup.connect(url).timeout(10 * 1000).get();
		
		Elements authors = doc.select("author name");
		
		if(authors.size() <=1){
			return ;
		}
		
		
		String author=authors.get(0).text();
		for (int i = 1; i< authors.size(); i++) {
			author = author+ " and "+authors.get(i).text();
		}
		
		String id = "";
		
		for (int i = id_list.length() - 1; i >= 0; i--) {
			if(id_list.charAt(i) =='/'){
				break;
			}
			id = id_list.charAt(i) +id;
		}
		System.out.println(id);
		id_list=id;
		id="";
		for (int i = id_list.length() - 1; i >= 0; i--) {
			if (id_list.charAt(i) != '.'&&id_list.charAt(i) != '/') {
				
				id =id_list.charAt(i) + id;
				
			}
		}
		
		File file = new File(id+".html");
		if (!file.exists()) {
			file.createNewFile();

		}

		FileWriter fw = new FileWriter(file);
		BufferedWriter bw = new BufferedWriter(fw);

		fw.write(doc + "\n");
	

		bw.close();
		
		
		Elements titles = doc.select("entry title");
		String title = titles.text();
		title = title.replaceAll("\n", "");

		
		Elements spanAbstracts = doc.select("summary");
		String spanAbstract = spanAbstracts.text();

		writeFile(id_list, id, title, author, spanAbstract, createFolder(year, month));

	}

	public static void getLinkYear(String link, String fileRoot, Connection.Response res)
			throws IOException, SQLException {

		Document doc = getDoc(link, res);
		File file = new File(fileRoot);
		if (!file.exists()) {
			file.createNewFile();
		}

		FileWriter fw = new FileWriter(file);
		BufferedWriter bw = new BufferedWriter(fw);

		Elements url = doc.select("li a[href]");
		System.out.println("--START--");
		for (int i = 29; i >= 4; i--) {

			if (url.get(i).text().compareTo("new") == 0 || url.get(i).text().compareTo("recent") == 0
					|| url.get(i).text().compareTo("current month") == 0
					|| url.get(i).text().compareTo("current month's") == 0
					|| url.get(i).text().compareTo("cond-mat archive") == 0) {
			}

			String urls = "https://arxiv.org" + url.get(i).attr("href");
			bw.write(urls + "\n");
			
		}

		bw.close();
		System.out.println("--DONE--");
	}

	public static void getLinkMonth(String year, Connection.Response res) throws IOException, SQLException {

		String file = "";
		for (int j = year.length() - 1; j >= 0; j--) {
			if (year.charAt(j) == '/') {
				break;
			}
			file = year.charAt(j) + file;
		}

		int temp = Integer.parseInt(file);
		if (temp > 16) {
			file = "19" + file;
		} else {
			file = "20" + file;
		}

		FileWriter fw = new FileWriter(file + ".txt");
		BufferedWriter bw = new BufferedWriter(fw);

		Document doc = getDoc(year, res);

		Elements url = doc.select("ul li");

		System.out.println("--year--" + file + "--");
		for (int j = 0; j < url.size(); j++) {
			Element a = url.get(j).select("a:eq(0)").first();

			int count = 0;
			Elements b = url.get(j).select("b");
			Elements y = url.get(j).select("i");

			count = Integer.parseInt(b.html()) + Integer.parseInt(y.html());
			
			String link = "https://arxiv.org" + a.attr("href") + "?show=" + count;
			
			bw.write(link + "\n");

		}
		bw.close();
		file = "";
		System.out.println("--done--");

	}

	public static void getLinkYearMonth(String link, String fileYearMonth, Connection.Response res) throws IOException {
		Document doc = getDoc(link, res);

		File file = new File(fileYearMonth);
		if (!file.exists()) {
			file.createNewFile();

		}

		FileWriter fw = new FileWriter(file);
		BufferedWriter bw = new BufferedWriter(fw);

		Elements url = doc.select("li a[href]");

		for (int i = 0; i < url.size(); i++) {
			String urls = "https://arxiv.org" + url.get(i).attr("href");
			System.out.println(urls);
			fw.write(urls + "\n");
		}

		bw.close();
		System.out.println("Done");
	}

	public static void getLink(String link, String year, String month, Connection.Response res) throws IOException {
		Document doc = getDoc(link, res);

		Elements url = doc.select("dt a[href]");
		System.out.println(url.size());

		for (int i = 0; i < url.size(); i++) {
			if (url.get(i).attr("title").compareTo("Abstract") == 0) {

				String id_list = "";

				for (int j = 1; j < url.get(i).attr("href").length(); j++) {
					if (url.get(i).attr("href").charAt(j) == '/') {
						for (int m = j + 1; m < url.get(i).attr("href").length(); m++) {
							id_list += url.get(i).attr("href").charAt(m);
						}
						break;
					}

				}

				String urls = "http://export.arxiv.org/api/query?id_list=" + id_list;
				System.out.println(urls);

			}
		}

		System.out.println("--done--");
	}

	public static String createFolder(String year, String month) {
		String folder = year + "\\" + month;
		File files = new File(folder);
		if (!files.exists()) {
			if (files.mkdirs()) {
				System.out.println("Multiple directories are created!");
			} else {
				System.out.println("Failed to create multiple directories!");
			}
		}
		return folder;
	}
//namthang
	public static void writeFile(String iDs, String iD, String title, String author, String spanAbstract, String folder)
			throws IOException {
		File file;
		if(iD.charAt(0)=='9'){
			file = new File(folder + "/" + "19" + iD + ".abs");
		}
		else{
			file = new File(folder + "/" + "20"+ iD + ".abs");
		}
		if (!file.exists()) {
			file.createNewFile();
		}

		FileWriter fw = new FileWriter(file);
		BufferedWriter bw = new BufferedWriter(fw);
		//bw.write("Paper: "+iD + "\n");
		bw.write("Title: "+title + "\n");
		bw.write("Authors: "+author + "\n");
		bw.write("Abstract: "+spanAbstract + "\n");

		bw.close();

		System.out.println("Done");

	}

	public static void readFile(ArrayList<String> list, String filename) {
		BufferedReader br = null;
		try {

			br = new BufferedReader(new FileReader(filename));
			String url;
			try {
				while ((url = br.readLine()) != null) {
					list.add(url);
				}
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		} catch (FileNotFoundException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		} finally {

			try {
				br.close();
			} catch (IOException e) {
				// TODO Auto-generated catch block
				e.printStackTrace();
			}
		}

	}

	public static Connection.Response login(String link) {
		Connection.Response res = null;

		try {
			res = Jsoup.connect(link).data("username", "vietlv", "password", "Thangngo1").method(Method.POST).execute();
		} catch (Exception e) {
			// TODO: handle exception
			e.printStackTrace();
		}

		return res;
	}

	public static Document getDoc(String link, Connection.Response res) {

		Document doc = null;
		try {
			Map<String, String> sessionId = res.cookies();

			doc = Jsoup.connect(link).method(Method.POST)
					.userAgent("Mozilla/5.0 (Windows NT 6.1; WOW64; rv:5.0) Gecko/20100101 Firefox/5.0")
					.timeout(100 * 1000).ignoreHttpErrors(true).ignoreContentType(true).cookies(sessionId).get();
		} catch (IOException e) {
			// TODO Auto-generated catch block
			e.printStackTrace();
		}

		return doc;
	}

	public static void writeLink(Connection.Response res) throws IOException {

		ArrayList<String> listMonth = new ArrayList<>();

		for (int i = 1992; i <= 2017; i++) {
			readFile(listMonth, i + ".txt");
		}

		int count = 0;
		int numberfile = 1;
		File file = new File(numberfile + ".txt");

		if (!file.exists()) {
			file.createNewFile();
		}

		FileWriter fw = new FileWriter(file);
		BufferedWriter bw = new BufferedWriter(fw);

		for (int k = 0; k < listMonth.size(); k++) {
			System.out.println(listMonth.get(k));
			Document doc = getDoc(listMonth.get(k), res);

			Elements url = doc.select("dt a[href]");
			System.out.println(url.size());

			for (int i = 0; i < url.size(); i++) {
				if (url.get(i).attr("title").compareTo("Abstract") == 0) {

					if (count == 500) {
						numberfile++;
						file = new File(numberfile + ".txt");

						if (!file.exists()) {
							file.createNewFile();
						}
						bw.close();
						fw = new FileWriter(file);
						bw = new BufferedWriter(fw);
						count = 0;
					}

					count++;
					String id_list = "";

					for (int j = 1; j < url.get(i).attr("href").length(); j++) {
						if (url.get(i).attr("href").charAt(j) == '/') {
							for (int m = j + 1; m < url.get(i).attr("href").length(); m++) {
								id_list += url.get(i).attr("href").charAt(m);
							}
							break;
						}

					}

					bw.write(id_list + "\n");

				}
			}
		}
		bw.close();
		System.out.println("Done");
	}

	public static void main(String[] args) throws IOException, SQLException {

		Connection.Response res = login("https://arxiv.org/user/login");
		// Run step by step, after finishing the step, comment the step and run the next step.
		
		// step 1: You can choose another link
//		getLinkYear("https://arxiv.org/archive/hep-th", "Year.txt", res);

		// step 2
//		ArrayList<String> listYearh = new ArrayList<>();
//
//		readFile(listYearh, "Year.txt");
//
//		for (int i = 0; i < listYearh.size(); i++) {
//
//			getLinkMonth(listYearh.get(i), res);
//
//		}

		// step 3
//		writeLink(res);
		
		// step 4: The limit is the number of txt files counted
//		ArrayList<String> listMonth = new ArrayList<>();
//		for (int i = 11; i <= 41; i++) {
//			readFile(listMonth, i + ".txt");
//		}
//		System.out.println(listMonth.size());
//
//		for (int j = 0; j < listMonth.size(); j++) {
//
//			String year = "";
//			String month = "";
//
//			if (listMonth.get(j).indexOf('.') >= 0) {
//				year = listMonth.get(j).substring(0, 2);
//				month = listMonth.get(j).substring(2, 4);
//			} else {
//
//				for (int m = 0; m < listMonth.get(j).length(); m++) {
//					if (listMonth.get(j).charAt(m) == '/') {
//						for (int n = m + 1; n <= m + 2; n++) {
//							year += listMonth.get(j).charAt(n);
//						}
//
//						for (int n = m + 3; n <= m + 4; n++) {
//							month += listMonth.get(j).charAt(n);
//						}
//						break;
//					}
//
//				}
//			}
//
//			if (year.compareTo("") != 0 && month.compareTo("") != 0) {
//				System.out.println(year + "+" + month);
//				String url = "http://export.arxiv.org/api/query?id_list=" + listMonth.get(j);
//				onePage(url, listMonth.get(j), year, month);
//			}
//
//		}
//
	}
}
