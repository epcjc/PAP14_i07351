Imports MySql.Data.MySqlClient

Public Class form_gerirnoticias
    Private Sub form_gerirnoticias_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.noticias' table. You can move, or remove it, as needed.
        Me.NoticiasTableAdapter.Fill(Me.I07351DataSet.noticias)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, imagem FROM noticias WHERE id = " & id & " LIMIT 1"
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
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim conteudo As String = sqlReader("conteudo").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox2.Text = titulo
                        TextBox1.Text = conteudo
                        TextBox2.ReadOnly = False
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
        c.download_ftp("imagens_noticias/" & id & ".jpg", "noticia-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\noticia-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\noticia-" & id & ".jpg"
        End If
        '-------------------------------------------------------
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        'apaga labels das imagens
        Labelimagem.Text = ""
        PictureBox1.Image = Nothing

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, imagem FROM noticias WHERE id = " & id & " LIMIT 1"
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
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim conteudo As String = sqlReader("conteudo").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox2.Text = titulo
                        TextBox1.Text = conteudo
                        TextBox2.ReadOnly = False
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
        c.download_ftp("imagens_noticias/" & id & ".jpg", "noticia-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\noticia-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\noticia-" & id & ".jpg"
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
            Dim Query As String = "Delete FROM noticias WHERE id  = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()

            'apaga comentarios da noticia
            Query = "Delete FROM comentarios_noticias WHERE id_noticia  = " & id
            cmd = New MySqlCommand(Query, con)
            Dim i2 As Integer = cmd.ExecuteNonQuery()

            'apaga denuncias aos comentarios desta noticia
            Query = "Delete FROM reports_cnoticias WHERE id_noticia  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            If (i > 0) Then
                'apaga imagens por ftp---------------
                Dim c As New Class1
                c.apagarficheiro_ftp("imagens_noticias/" & id & ".jpg")
                c.apagarficheiro_ftp("imagens_noticias/" & id & "_pequena.jpg")
                c.apagarficheiro_ftp("imagens_noticias/" & id & "_lista.jpg")
                c.apagarficheiro_ftp("imagens_noticias/" & id & "_miniatura.jpg")
                '------------------------------------
                MsgBox("O registo foi apagado com sucesso.")
            Else
                MsgBox("Não foi possível apagar o registo.")
            End If

            con.Close()
            If (i > 0) Then
                'atualiza combobox
                Me.NoticiasTableAdapter.Fill(Me.I07351DataSet.noticias)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    Labelenviado.Text = ""
                    TextBox2.Text = ""
                    TextBox1.Text = ""
                    TextBox2.ReadOnly = True
                    TextBox1.ReadOnly = True
                End If
                'connecta a bd'
                Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, imagem FROM noticias WHERE id = " & id & " LIMIT 1"
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
                                Dim titulo As String = sqlReader("titulo").ToString()
                                Dim conteudo As String = sqlReader("conteudo").ToString()
                                Dim imagem As String = sqlReader("imagem").ToString()
                                Dim id_utilizador As Integer = sqlReader("id_utilizador")
                                'preenche labels
                                Labeldata.Text = datahora
                                TextBox2.Text = titulo
                                TextBox1.Text = conteudo
                                TextBox2.ReadOnly = False
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
                c.download_ftp("imagens_noticias/" & id & ".jpg", "noticia-" & id & ".jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\noticia-" & id & ".jpg") Then
                    PictureBox1.ImageLocation = "C:\Temp\noticia-" & id & ".jpg"
                End If
                '-------------------------------------------------------
            End If
            '-------------------------------------
        End If
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
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
        If (TextBox2.Text.Trim.Length = 0) Then
            foi = 0
            ErrorProvider1.SetError(TextBox2, "Este campo não pode estar vazio.")
        Else
            ErrorProvider1.SetError(TextBox2, "")
        End If
        If (foi = 1) Then
            Dim id As Integer = ComboBox1.SelectedValue
            Dim conteudo As String = TextBox1.Text
            Dim titulo As String = TextBox2.Text

            'guarda alteracoes-----
            Dim Query As String = "UPDATE noticias SET conteudo = '" & conteudo & "', titulo = '" & titulo & "' WHERE id = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            con.Close()
            '---------------------
            'envia imagem por ftp, se esta foi adicionada
            If (Labelimagem.Text.Trim.Length > 0 And i > 0) Then
                Dim c As New Class1
                c.upload_ftp(PictureBox1.ImageLocation, "imagens_noticias/" & ComboBox1.SelectedValue & ".jpg")
            End If
            '----------------------------------------------
            MsgBox("As alterações foram guardadas com sucesso.")
            Me.NoticiasTableAdapter.Fill(Me.I07351DataSet.noticias)
        End If
    End Sub
End Class