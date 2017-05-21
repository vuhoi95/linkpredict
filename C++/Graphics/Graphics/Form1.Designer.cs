namespace Graphics
{
    partial class frm_main
    {
        /// <summary>
        /// Required designer variable.
        /// </summary>
        private System.ComponentModel.IContainer components = null;

        /// <summary>
        /// </summary>
        /// Clean up any resources being used.
        /// <param name="disposing">true if managed resources should be disposed; otherwise, false.</param>
        protected override void Dispose(bool disposing)
        {
            if (disposing && (components != null))
            {
                components.Dispose();
            }
            base.Dispose(disposing);
        }

        #region Windows Form Designer generated code

        /// <summary>
        /// Required method for Designer support - do not modify
        /// the contents of this method with the code editor.
        /// </summary>
        private void InitializeComponent()
        {
            this.tableLayoutPanel1 = new System.Windows.Forms.TableLayoutPanel();
            this.panel4 = new System.Windows.Forms.Panel();
            this.button1 = new System.Windows.Forms.Button();
            this.panel2 = new System.Windows.Forms.Panel();
            this.rich_txt_content = new System.Windows.Forms.RichTextBox();
            this.panel1 = new System.Windows.Forms.Panel();
            this.rich_txt_example = new System.Windows.Forms.RichTextBox();
            this.panel3 = new System.Windows.Forms.Panel();
            this.btn_show_data = new System.Windows.Forms.Button();
            this.file_stream = new System.Windows.Forms.OpenFileDialog();
            this.button2 = new System.Windows.Forms.Button();
            this.tableLayoutPanel1.SuspendLayout();
            this.panel4.SuspendLayout();
            this.panel2.SuspendLayout();
            this.panel1.SuspendLayout();
            this.panel3.SuspendLayout();
            this.SuspendLayout();
            // 
            // tableLayoutPanel1
            // 
            this.tableLayoutPanel1.ColumnCount = 2;
            this.tableLayoutPanel1.ColumnStyles.Add(new System.Windows.Forms.ColumnStyle(System.Windows.Forms.SizeType.Percent, 50F));
            this.tableLayoutPanel1.ColumnStyles.Add(new System.Windows.Forms.ColumnStyle(System.Windows.Forms.SizeType.Percent, 50F));
            this.tableLayoutPanel1.Controls.Add(this.panel4, 1, 1);
            this.tableLayoutPanel1.Controls.Add(this.panel2, 1, 0);
            this.tableLayoutPanel1.Controls.Add(this.panel1, 0, 0);
            this.tableLayoutPanel1.Controls.Add(this.panel3, 0, 1);
            this.tableLayoutPanel1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.tableLayoutPanel1.Location = new System.Drawing.Point(0, 0);
            this.tableLayoutPanel1.Name = "tableLayoutPanel1";
            this.tableLayoutPanel1.RowCount = 2;
            this.tableLayoutPanel1.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 81.81818F));
            this.tableLayoutPanel1.RowStyles.Add(new System.Windows.Forms.RowStyle(System.Windows.Forms.SizeType.Percent, 18.18182F));
            this.tableLayoutPanel1.Size = new System.Drawing.Size(994, 528);
            this.tableLayoutPanel1.TabIndex = 0;
            // 
            // panel4
            // 
            this.panel4.Controls.Add(this.button2);
            this.panel4.Controls.Add(this.button1);
            this.panel4.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel4.Location = new System.Drawing.Point(500, 434);
            this.panel4.Name = "panel4";
            this.panel4.Size = new System.Drawing.Size(491, 91);
            this.panel4.TabIndex = 3;
            // 
            // button1
            // 
            this.button1.Location = new System.Drawing.Point(26, 31);
            this.button1.Name = "button1";
            this.button1.Size = new System.Drawing.Size(90, 36);
            this.button1.TabIndex = 1;
            this.button1.Text = "Run";
            this.button1.UseVisualStyleBackColor = true;
            this.button1.Click += new System.EventHandler(this.button1_Click);
            // 
            // panel2
            // 
            this.panel2.Controls.Add(this.rich_txt_content);
            this.panel2.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel2.Location = new System.Drawing.Point(500, 3);
            this.panel2.Name = "panel2";
            this.panel2.Size = new System.Drawing.Size(491, 425);
            this.panel2.TabIndex = 1;
            // 
            // rich_txt_content
            // 
            this.rich_txt_content.Dock = System.Windows.Forms.DockStyle.Fill;
            this.rich_txt_content.Location = new System.Drawing.Point(0, 0);
            this.rich_txt_content.Name = "rich_txt_content";
            this.rich_txt_content.Size = new System.Drawing.Size(491, 425);
            this.rich_txt_content.TabIndex = 1;
            this.rich_txt_content.Text = "";
            // 
            // panel1
            // 
            this.panel1.Controls.Add(this.rich_txt_example);
            this.panel1.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel1.Location = new System.Drawing.Point(3, 3);
            this.panel1.Name = "panel1";
            this.panel1.Size = new System.Drawing.Size(491, 425);
            this.panel1.TabIndex = 0;
            // 
            // rich_txt_example
            // 
            this.rich_txt_example.Dock = System.Windows.Forms.DockStyle.Fill;
            this.rich_txt_example.Location = new System.Drawing.Point(0, 0);
            this.rich_txt_example.Name = "rich_txt_example";
            this.rich_txt_example.Size = new System.Drawing.Size(491, 425);
            this.rich_txt_example.TabIndex = 0;
            this.rich_txt_example.Text = "";
            // 
            // panel3
            // 
            this.panel3.Controls.Add(this.btn_show_data);
            this.panel3.Dock = System.Windows.Forms.DockStyle.Fill;
            this.panel3.Location = new System.Drawing.Point(3, 434);
            this.panel3.Name = "panel3";
            this.panel3.Size = new System.Drawing.Size(491, 91);
            this.panel3.TabIndex = 2;
            // 
            // btn_show_data
            // 
            this.btn_show_data.Location = new System.Drawing.Point(18, 31);
            this.btn_show_data.Name = "btn_show_data";
            this.btn_show_data.Size = new System.Drawing.Size(90, 36);
            this.btn_show_data.TabIndex = 0;
            this.btn_show_data.Text = "Show Data";
            this.btn_show_data.UseVisualStyleBackColor = true;
            this.btn_show_data.Click += new System.EventHandler(this.btn_show_data_Click);
            // 
            // file_stream
            // 
            this.file_stream.FileName = "Open";
            // 
            // button2
            // 
            this.button2.Location = new System.Drawing.Point(148, 31);
            this.button2.Name = "button2";
            this.button2.Size = new System.Drawing.Size(90, 36);
            this.button2.TabIndex = 2;
            this.button2.Text = "Convert";
            this.button2.UseVisualStyleBackColor = true;
            this.button2.Click += new System.EventHandler(this.button2_Click);
            // 
            // frm_main
            // 
            this.AutoScaleDimensions = new System.Drawing.SizeF(6F, 13F);
            this.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font;
            this.ClientSize = new System.Drawing.Size(994, 528);
            this.Controls.Add(this.tableLayoutPanel1);
            this.Name = "frm_main";
            this.Text = "frm_main";
            this.tableLayoutPanel1.ResumeLayout(false);
            this.panel4.ResumeLayout(false);
            this.panel2.ResumeLayout(false);
            this.panel1.ResumeLayout(false);
            this.panel3.ResumeLayout(false);
            this.ResumeLayout(false);

        }

        #endregion

        private System.Windows.Forms.TableLayoutPanel tableLayoutPanel1;
        private System.Windows.Forms.Panel panel4;
        private System.Windows.Forms.Panel panel2;
        private System.Windows.Forms.RichTextBox rich_txt_content;
        private System.Windows.Forms.Panel panel1;
        private System.Windows.Forms.RichTextBox rich_txt_example;
        private System.Windows.Forms.Panel panel3;
        private System.Windows.Forms.Button btn_show_data;
        private System.Windows.Forms.OpenFileDialog file_stream;
        private System.Windows.Forms.Button button1;
        private System.Windows.Forms.Button button2;
    }
}

