Imports MySql.Data.MySqlClient

Public Class form_gerirgaleria
    Private Sub form_gerirgaleria_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.galeria' table. You can move, or remove it, as needed.
        Me.GaleriaTableAdapter.Fill(Me.I07351DataSet.galeria)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, descricao, imagem FROM galeria WHERE id = " & id & " LIMIT 1"
        Using sqlConn As New MySqlConnection(connString)
            Using sqlComm As New MySqlCommand()
                With sqlComm
                    .Connection = sqlConn
                    .CommandText = sqlQuery
                    .CommandType = CommandType.Text
                End With
                Try
                    sqlConn.Open()
                    Dim sqlReader As MySqlDataReader = sqlComm.ExecuteReader()
                    While sqlReader.Read()
                        Dim datahora As String = sqlReader("datahora").ToString()
                        Dim descricao As String = sqlReader("descricao").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox1.Text = descricao
                        TextBox1.ReadOnly = False
                        'pesquisa para descobrir o nome do utilizador
                        Dim sqlQuery2 As String = "SELECT username FROM utilizadores WHERE id = " & id_utilizador & " LIMIT 1"
                        Using sqlConn2 As New MySqlConnection(connString)
                            Using sqlComm2 As New MySqlCommand()
                                With sqlComm2
                                    .Connection = sqlConn2
                                    .CommandText = sqlQuery2
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn2.Open()
                                    Dim sqlReader2 As MySqlDataReader = sqlComm2.ExecuteReader()
                                    While sqlReader2.Read()
                                        Dim username As String = sqlReader2("username").ToString()
                                        Labelenviado.Text = username
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 2137146")
                                End Try
                            End Using
                        End Using
                    End While
                Catch ex As MySqlException
                    MsgBox("excecpçao nº 8137146")
                End Try
            End Using
        End Using

        'faz download da imagem por ftp para exibi-la-------------
        Dim c As New Class1
        c.download_ftp("galeria/" & id & ".jpg", "galeria-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\galeria-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\galeria-" & id & ".jpg"
        End If
        '-------------------------------------------------------
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        'apaga labels das imagens
        Labelimagem.Text = ""
        PictureBox1.Image = Nothing

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, descricao, imagem FROM galeria WHERE id = " & id & " LIMIT 1"
        Using sqlConn As New MySqlConnection(connString)
            Using sqlComm As New MySqlCommand()
                With sqlComm
                    .Connection = sqlConn
                    .CommandText = sqlQuery
                    .CommandType = CommandType.Text
                End With
                Try
                    sqlConn.Open()
                    Dim sqlReader As MySqlDataReader = sqlComm.ExecuteReader()
                    While sqlReader.Read()
                        Dim datahora As String = sqlReader("datahora").ToString()
                        Dim descricao As String = sqlReader("descricao").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox1.Text = descricao
                        TextBox1.ReadOnly = False
                        'pesquisa para descobrir o nome do utilizador
                        Dim sqlQuery2 As String = "SELECT username FROM utilizadores WHERE id = " & id_utilizador & " LIMIT 1"
                        Using sqlConn2 As New MySqlConnection(connString)
                            Using sqlComm2 As New MySqlCommand()
                                With sqlComm2
                                    .Connection = sqlConn2
                                    .CommandText = sqlQuery2
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn2.Open()
                                    Dim sqlReader2 As MySqlDataReader = sqlComm2.ExecuteReader()
                                    While sqlReader2.Read()
                                        Dim username As String = sqlReader2("username").ToString()
                                        Labelenviado.Text = username
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 2137146")
                                End Try
                            End Using
                        End Using
                    End While
                Catch ex As MySqlException
                    MsgBox("excecpçao nº 8137146")
                End Try
            End Using
        End Using

        'faz download da imagem por ftp para exibi-la-------------
        Dim c As New Class1
        c.download_ftp("galeria/" & id & ".jpg", "galeria-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\galeria-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\galeria-" & id & ".jpg"
        End If
        '-------------------------------------------------------
    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        'apagar
        Dim foi As Integer = 1
        Dim result As Integer = MessageBox.Show("Tem a certeza que quer apagar este registo?", "Confirmação", MessageBoxButtons.YesNoCancel)
        If result = DialogResult.Cancel Then
            foi = 0
        ElseIf result = DialogResult.No Then
            foi = 0
        End If

        If (foi = 1) Then
            'parte de apagar aqui------------
            Dim id As Integer = ComboBox1.SelectedValue
            Dim Query As String = "Delete FROM galeria WHERE id  = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            If (i > 0) Then
                'apaga imagem por ftp---------------
                Dim c As New Class1
                c.apagarficheiro_ftp("galeria/" & id & ".jpg")
                c.apagarficheiro_ftp("galeria/" & id & "_miniatura.jpg")
                '------------------------------------
                MsgBox("O registo foi apagado com sucesso.")
            Else
                MsgBox("Não foi possível apagar o registo.")
            End If
            con.Close()
            If (i > 0) Then
                'atualiza combobox
                Me.GaleriaTableAdapter.Fill(Me.I07351DataSet.galeria)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    TextBox1.Text = ""
                    TextBox1.ReadOnly = True
                    Labelenviado.Text = ""
                End If
                'connecta a bd'
                Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                Dim sqlQuery As String = "SELECT datahora, id_utilizador, descricao, imagem FROM galeria WHERE id = " & id & " LIMIT 1"
                Using sqlConn As New MySqlConnection(connString)
                    Using sqlComm As New MySqlCommand()
                        With sqlComm
                            .Connection = sqlConn
                            .CommandText = sqlQuery
                            .CommandType = CommandType.Text
                        End With
                        Try
                            sqlConn.Open()
                            Dim sqlReader As MySqlDataReader = sqlComm.ExecuteReader()
                            While sqlReader.Read()
                                Dim datahora As String = sqlReader("datahora").ToString()
                                Dim descricao As String = sqlReader("descricao").ToString()
                                Dim imagem As String = sqlReader("imagem").ToString()
                                Dim id_utilizador As Integer = sqlReader("id_utilizador")
                                'preenche labels
                                Labeldata.Text = datahora
                                TextBox1.Text = descricao
                                TextBox1.ReadOnly = False
                                'pesquisa para descobrir o nome do utilizador
                                Dim sqlQuery2 As String = "SELECT username FROM utilizadores WHERE id = " & id_utilizador & " LIMIT 1"
                                Using sqlConn2 As New MySqlConnection(connString)
                                    Using sqlComm2 As New MySqlCommand()
                                        With sqlComm2
                                            .Connection = sqlConn2
                                            .CommandText = sqlQuery2
                                            .CommandType = CommandType.Text
                                        End With
                                        Try
                                            sqlConn2.Open()
                                            Dim sqlReader2 As MySqlDataReader = sqlComm2.ExecuteReader()
                                            While sqlReader2.Read()
                                                Dim username As String = sqlReader2("username").ToString()
                                                Labelenviado.Text = username
                                            End While
                                        Catch ex As MySqlException
                                            MsgBox("excecpçao nº 2137146")
                                        End Try
                                    End Using
                                End Using
                            End While
                        Catch ex As MySqlException
                            MsgBox("excecpçao nº 8137146")
                        End Try
                    End Using
                End Using

                'faz download da imagem por ftp para exibi-la-------------
                Dim c As New Class1
                c.download_ftp("galeria/" & id & ".jpg", "galeria-" & id & ".jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\galeria-" & id & ".jpg") Then
                    PictureBox1.ImageLocation = "C:\Temp\galeria-" & id & ".jpg"
                End If
                '-------------------------------------------------------
            End If
            '-------------------------------------
        End If
    End Sub

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Dim fdlg As OpenFileDialog = New OpenFileDialog()
        fdlg.Title = "Escolher uma imagem"
        fdlg.InitialDirectory = "c:\"
        fdlg.Filter = "Formato de imagem(*.jpg;*.jpeg;*.gif)|*.jpg;*.jpeg;*.gif"
        fdlg.FilterIndex = 2
        fdlg.RestoreDirectory = True
        If fdlg.ShowDialog() = DialogResult.OK Then
            If My.Computer.FileSystem.FileExists(fdlg.FileName) Then
                PictureBox1.ImageLocation = fdlg.FileName
                Dim ficheiro As String = fdlg.FileName.Substring(fdlg.FileName.LastIndexOf("\") + 1)
                Labelimagem.Text = ficheiro
            Else
                MessageBox.Show("O ficheiro selecionado não existe.")
            End If

        End If
    End Sub

    Private Sub Button4_Click(sender As Object, e As EventArgs) Handles Button4.Click
        'botao guardar
        Dim foi As Integer = 1 'verifica se pode
        If (ComboBox1.SelectedValue Is Nothing) Then
            foi = 0
            ErrorProvider1.SetError(ComboBox1, "Nenhum registo selecionado")
        Else
            ErrorProvider1.SetError(ComboBox1, "")
        End If
        If (TextBox1.Text.Trim.Length = 0) Then
            foi = 0
            ErrorProvider1.SetError(TextBox1, "Este campo não pode estar vazio.")
        Else
            ErrorProvider1.SetError(TextBox1, "")
        End If
        If (foi = 1) Then
            Dim id As Integer = ComboBox1.SelectedValue
            Dim descricao As String = TextBox1.Text

            'guarda alteracoes-----
            Dim Query As String = "UPDATE galeria SET descricao = '" & descricao & "' WHERE id = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            con.Close()
            '---------------------
            'redimensiona imagem para 960x473  e envia por ftp, se esta foi adicionada
            If (Labelimagem.Text.Trim.Length > 0 And i > 0) Then
                Dim idr As Integer = ComboBox1.SelectedValue
                ' Get the source bitmap.
                Dim bm_source As New Bitmap(PictureBox1.Image)
                ' Make a bitmap for the result.
                Dim bm_dest As New Bitmap( _
                    CInt(960), _
                    CInt(473))
                ' Make a Graphics object for the result Bitmap.
                Dim gr_dest As Graphics = Graphics.FromImage(bm_dest)
                ' Copy the source image into the destination bitmap.
                gr_dest.DrawImage(bm_source, 0, 0, _
                    bm_dest.Width, _
                    bm_dest.Height)
                ' save the result.
                bm_dest.Save("c:\temp\galeria-" & idr & ".jpg")

                ' Envia
                Dim c As New Class1
                c.upload_ftp("c:\temp\galeria-" & idr & ".jpg", "galeria/" & idr & ".jpg")
            End If
            
            '----------------------------------------------

            MsgBox("As alterações foram guardadas com sucesso.")
        End If
    End Sub
End Class