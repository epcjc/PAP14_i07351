Imports MySql.Data.MySqlClient
Imports System
Imports System.Net
Imports System.IO
Imports System.Text

Public Class form_geriruploads
    Private Sub form_geriruploads_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.uploads' table. You can move, or remove it, as needed.
        Me.UploadsTableAdapter.Fill(Me.I07351DataSet.uploads)
        MdiParent = form_main


        Dim imagem1 As String = ""
        Dim imagem2 As String = ""
        Dim imagem3 As String = ""
        Dim imagem4 As String = ""

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, descricao, titulo, categoria, nreports, imagem1, imagem2, imagem3, imagem4, preco, size FROM uploads WHERE id = " & id & " LIMIT 1"
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
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim categoria As String = sqlReader("categoria").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        Dim nreports As Integer = sqlReader("nreports")
                        Dim size As Integer = sqlReader("size")
                        Dim preco As Integer = sqlReader("preco")
                        imagem1 = sqlReader("imagem1").ToString()
                        imagem2 = sqlReader("imagem2").ToString()
                        imagem3 = sqlReader("imagem3").ToString()
                        imagem4 = sqlReader("imagem4").ToString()

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelcategoria.Text = categoria
                        Labeltamanho.Text = size & " bytes"
                        TextBox1.Text = descricao
                        TextBox3.Text = titulo
                        TextBox1.ReadOnly = False
                        TextBox3.ReadOnly = False
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
        'faz download por ftp das imagens para apresentar--------------------
        Dim c As New Class1
        c.download_ftp(imagem1, "upload-" & id & "_imagem1.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem1.jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\upload-" & id & "_imagem1.jpg"
        End If
        c.download_ftp(imagem2, "upload-" & id & "_imagem2.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem2.jpg") Then
            PictureBox2.ImageLocation = "C:\Temp\upload-" & id & "_imagem2.jpg"
        End If
        c.download_ftp(imagem3, "upload-" & id & "_imagem3.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem3.jpg") Then
            PictureBox3.ImageLocation = "C:\Temp\upload-" & id & "_imagem3.jpg"
        End If
        c.download_ftp(imagem4, "upload-" & id & "_imagem4.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem4.jpg") Then
            PictureBox4.ImageLocation = "C:\Temp\upload-" & id & "_imagem4.jpg"
        End If
        '--------------------------------------------------------------------
    End Sub

    Private Sub Button6_Click(sender As Object, e As EventArgs)
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        'apaga labels das imagens
        Labelimagem1.Text = ""
        Labelimagem2.Text = ""
        Labelimagem3.Text = ""
        Labelimagem4.Text = ""
        PictureBox1.Image = Nothing
        PictureBox2.Image = Nothing
        PictureBox3.Image = Nothing
        PictureBox4.Image = Nothing

        Dim imagem1 As String = ""
        Dim imagem2 As String = ""
        Dim imagem3 As String = ""
        Dim imagem4 As String = ""
        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, descricao, titulo, categoria, nreports, imagem1, imagem2, imagem3, imagem4, preco, size FROM uploads WHERE id = " & id & " LIMIT 1"
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
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim categoria As String = sqlReader("categoria").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        Dim nreports As Integer = sqlReader("nreports")
                        Dim size As Integer = sqlReader("size")
                        Dim preco As Integer = sqlReader("preco")
                        imagem1 = sqlReader("imagem1").ToString()
                        imagem2 = sqlReader("imagem2").ToString()
                        imagem3 = sqlReader("imagem3").ToString()
                        imagem4 = sqlReader("imagem4").ToString()

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelcategoria.Text = categoria
                        Labeltamanho.Text = size & " bytes"
                        TextBox1.Text = descricao
                        TextBox3.Text = titulo
                        TextBox1.ReadOnly = False
                        TextBox3.ReadOnly = False
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
        'faz download por ftp das imagens para apresentar--------------------
        Dim c As New Class1
        c.download_ftp(imagem1, "upload-" & id & "_imagem1.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem1.jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\upload-" & id & "_imagem1.jpg"
        End If
        c.download_ftp(imagem2, "upload-" & id & "_imagem2.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem2.jpg") Then
            PictureBox2.ImageLocation = "C:\Temp\upload-" & id & "_imagem2.jpg"
        End If
        c.download_ftp(imagem3, "upload-" & id & "_imagem3.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem3.jpg") Then
            PictureBox3.ImageLocation = "C:\Temp\upload-" & id & "_imagem3.jpg"
        End If
        c.download_ftp(imagem4, "upload-" & id & "_imagem4.jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem4.jpg") Then
            PictureBox4.ImageLocation = "C:\Temp\upload-" & id & "_imagem4.jpg"
        End If
        '--------------------------------------------------------------------
    End Sub

    Private Sub Button7_Click(sender As Object, e As EventArgs) Handles Button7.Click
        'apagar
        Dim nuploads As Integer
        Dim nuploads_novo As Integer
        Dim id_utilizador As Integer
        Dim nomeoriginal As String = "porpreencher"
        Dim imagem1 As String = ""
        Dim imagem2 As String = ""
        Dim imagem3 As String = ""
        Dim imagem4 As String = ""

        Dim foi As Integer = 1
        Dim result As Integer = MessageBox.Show("Tem a certeza que quer apagar este registo?", "Confirmação", MessageBoxButtons.YesNoCancel)
        If result = DialogResult.Cancel Then
            foi = 0
        ElseIf result = DialogResult.No Then
            foi = 0
        End If

        If (foi = 1) Then
            'pesquisa id do utilizador
            Dim id As Integer = ComboBox1.SelectedValue

            Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
            Dim sqlQuery As String = "SELECT id_utilizador, nomeoriginal FROM uploads WHERE id = " & id & " LIMIT 1"
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
                            id_utilizador = sqlReader("id_utilizador")
                            nomeoriginal = sqlReader("nomeoriginal").ToString()
                            'pesquisa para descobrir o nuploads do utilizador
                            Dim sqlQuery2 As String = "SELECT nuploads FROM utilizadores WHERE id = " & id_utilizador & " LIMIT 1"
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
                                            nuploads = sqlReader2("nuploads")

                                            If (nuploads > 0) Then
                                                nuploads_novo = nuploads - 1
                                            Else
                                                nuploads_novo = 0
                                            End If

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

            'parte de apagar aqui------------
            Dim Query As String = "Delete FROM uploads WHERE id  = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()

            'apaga comentarios do upload
            Query = "Delete FROM comentarios_uploads WHERE id_upload  = " & id
            cmd = New MySqlCommand(Query, con)
            Dim i2 As Integer = cmd.ExecuteNonQuery()

            'apaga denuncias aos comentarios deste upload
            Query = "Delete FROM reports_cuploads WHERE id_upload  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga favoritos deste upload
            Query = "Delete FROM favoritos_uploads WHERE id_upload  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga denuncias deste upload
            Query = "Delete FROM reports_uploads WHERE id_upload  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga uploads_protegidos deste upload
            Query = "Delete FROM uploads_protegidos WHERE id_upload  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'faz update do nuploads_novo no utilizador
            If (nuploads_novo <> Nothing And nuploads_novo <> 0) Then
                Query = "UPDATE utilizadores SET nuploads = " & nuploads_novo & " WHERE id = " & id_utilizador
                cmd = New MySqlCommand(Query, con)
                i2 = cmd.ExecuteNonQuery()
            End If
            '-------------------------------------------
            If (i > 0) Then
                'apaga imagens por ftp, e apaga o projeto do servidor-é necessario id_utilizador e nomeoriginal para definir caminho--------------
                Dim c As New Class1
                
                'imagem1
                c.apagarconteudopasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem1")
                'imagem2
                c.apagarconteudopasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem2")
                'imagem3
                c.apagarconteudopasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem3")
                'imagem4
                c.apagarconteudopasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem4")

                'pastas imagens e projeto
                c.apagarficheiro_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/" & nomeoriginal)
                c.apagarpasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem1")
                c.apagarpasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem2")
                c.apagarpasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem3")
                c.apagarpasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal & "/imagem4")
                c.apagarpasta_ftp("upload/" & id_utilizador & "/" & nomeoriginal)
                '------------------------------------
                MsgBox("O registo foi apagado com sucesso.")
            Else
                MsgBox("Não foi possível apagar o registo.")
            End If
            con.Close()
            If (i > 0) Then
                'atualiza combobox
                Me.UploadsTableAdapter.Fill(Me.I07351DataSet.uploads)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    Labelnumero.Text = ""
                    Labelcategoria.Text = ""
                    Labeltamanho.Text = ""
                    TextBox1.Text = ""
                    TextBox3.Text = ""
                    TextBox1.ReadOnly = True
                    TextBox3.ReadOnly = True
                    Labelenviado.Text = ""
                End If
                'connecta a bd'
                connString = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                sqlQuery = "SELECT datahora, id_utilizador, descricao, titulo, categoria, nreports, imagem1, imagem2, imagem3, imagem4, preco, size FROM uploads WHERE id = " & id & " LIMIT 1"
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
                                Dim titulo As String = sqlReader("titulo").ToString()
                                Dim categoria As String = sqlReader("categoria").ToString()
                                id_utilizador = sqlReader("id_utilizador")
                                Dim nreports As Integer = sqlReader("nreports")
                                Dim size As Integer = sqlReader("size")
                                Dim preco As Integer = sqlReader("preco")
                                imagem1 = sqlReader("imagem1").ToString()
                                imagem2 = sqlReader("imagem2").ToString()
                                imagem3 = sqlReader("imagem3").ToString()
                                imagem4 = sqlReader("imagem4").ToString()

                                'preenche labels
                                Labeldata.Text = datahora
                                Labelnumero.Text = nreports
                                Labelcategoria.Text = categoria
                                Labeltamanho.Text = size & " bytes"
                                TextBox1.Text = descricao
                                TextBox3.Text = titulo
                                TextBox1.ReadOnly = False
                                TextBox3.ReadOnly = False
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
                'faz download por ftp das imagens para apresentar--------------------
                Dim c As New Class1
                c.download_ftp(imagem1, "upload-" & id & "_imagem1.jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem1.jpg") Then
                    PictureBox1.ImageLocation = "C:\Temp\upload-" & id & "_imagem1.jpg"
                End If
                c.download_ftp(imagem2, "upload-" & id & "_imagem2.jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem2.jpg") Then
                    PictureBox2.ImageLocation = "C:\Temp\upload-" & id & "_imagem2.jpg"
                End If
                c.download_ftp(imagem3, "upload-" & id & "_imagem3.jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem3.jpg") Then
                    PictureBox3.ImageLocation = "C:\Temp\upload-" & id & "_imagem3.jpg"
                End If
                c.download_ftp(imagem4, "upload-" & id & "_imagem4.jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\upload-" & id & "_imagem4.jpg") Then
                    PictureBox4.ImageLocation = "C:\Temp\upload-" & id & "_imagem4.jpg"
                End If
                '--------------------------------------------------------------------
            End If
            '-------------------------------------
        End If
    End Sub

    Private Sub Button6_Click_1(sender As Object, e As EventArgs) Handles Button6.Click
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
                Labelimagem1.Text = ficheiro
            Else
                MessageBox.Show("O ficheiro selecionado não existe.")
            End If

        End If
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Dim fdlg As OpenFileDialog = New OpenFileDialog()
        fdlg.Title = "Escolher uma imagem"
        fdlg.InitialDirectory = "c:\"
        fdlg.Filter = "Formato de imagem(*.jpg;*.jpeg;*.gif)|*.jpg;*.jpeg;*.gif"
        fdlg.FilterIndex = 2
        fdlg.RestoreDirectory = True
        If fdlg.ShowDialog() = DialogResult.OK Then
            PictureBox2.ImageLocation = fdlg.FileName
            If My.Computer.FileSystem.FileExists(fdlg.FileName) Then
                Dim ficheiro As String = fdlg.FileName.Substring(fdlg.FileName.LastIndexOf("\") + 1)
                Labelimagem2.Text = ficheiro
            Else
                MessageBox.Show("O ficheiro selecionado não existe.")
            End If

        End If
    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        Dim fdlg As OpenFileDialog = New OpenFileDialog()
        fdlg.Title = "Escolher uma imagem"
        fdlg.InitialDirectory = "c:\"
        fdlg.Filter = "Formato de imagem(*.jpg;*.jpeg;*.gif)|*.jpg;*.jpeg;*.gif"
        fdlg.FilterIndex = 2
        fdlg.RestoreDirectory = True
        If fdlg.ShowDialog() = DialogResult.OK Then
            If My.Computer.FileSystem.FileExists(fdlg.FileName) Then
                PictureBox3.ImageLocation = fdlg.FileName
                Dim ficheiro As String = fdlg.FileName.Substring(fdlg.FileName.LastIndexOf("\") + 1)
                Labelimagem3.Text = ficheiro
            Else
                MessageBox.Show("O ficheiro selecionado não existe.")
            End If

        End If
    End Sub

    Private Sub Button4_Click(sender As Object, e As EventArgs) Handles Button4.Click
        Dim fdlg As OpenFileDialog = New OpenFileDialog()
        fdlg.Title = "Escolher uma imagem"
        fdlg.InitialDirectory = "c:\"
        fdlg.Filter = "Formato de imagem(*.jpg;*.jpeg;*.gif)|*.jpg;*.jpeg;*.gif"
        fdlg.FilterIndex = 2
        fdlg.RestoreDirectory = True
        If fdlg.ShowDialog() = DialogResult.OK Then
            If My.Computer.FileSystem.FileExists(fdlg.FileName) Then
                PictureBox4.ImageLocation = fdlg.FileName
                Dim ficheiro As String = fdlg.FileName.Substring(fdlg.FileName.LastIndexOf("\") + 1)
                Labelimagem4.Text = ficheiro
            Else
                MessageBox.Show("O ficheiro selecionado não existe.")
            End If

        End If
    End Sub

    Private Sub Button5_Click(sender As Object, e As EventArgs) Handles Button5.Click
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
        If (TextBox3.Text.Trim.Length = 0) Then
            foi = 0
            ErrorProvider1.SetError(TextBox3, "Este campo não pode estar vazio.")
        Else
            ErrorProvider1.SetError(TextBox3, "")
        End If
        If (foi = 1) Then
            Dim id As Integer = ComboBox1.SelectedValue
            Dim descricao As String = TextBox1.Text
            Dim titulo As String = TextBox3.Text

            'guarda alteracoes-----
            Dim Query As String = "UPDATE uploads SET descricao = '" & descricao & "', titulo = '" & titulo & "' WHERE id = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            con.Close()
            '---------------------
            'envia imagens por ftp se foram adicionadas-----
            If (i > 0) Then
                'pesquisa caminhos das imagens
                Dim imagem1 As String = ""
                Dim imagem2 As String = ""
                Dim imagem3 As String = ""
                Dim imagem4 As String = ""
                'connecta a bd'
                Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                Dim sqlQuery As String = "SELECT id_utilizador, imagem1, imagem2, imagem3, imagem4, nomeoriginal FROM uploads WHERE id = " & ComboBox1.SelectedValue & " LIMIT 1"
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
                                imagem1 = sqlReader("imagem1").ToString()
                                imagem2 = sqlReader("imagem2").ToString()
                                imagem3 = sqlReader("imagem3").ToString()
                                imagem4 = sqlReader("imagem4").ToString()
                            End While
                        Catch ex As MySqlException
                            MsgBox("excecpçao nº 8137146")
                        End Try
                    End Using
                End Using

                If (Labelimagem1.Text.Trim.Length > 0) Then
                    Dim c As New Class1
                    c.upload_ftp(PictureBox1.ImageLocation, imagem1)
                End If
                If (Labelimagem2.Text.Trim.Length > 0) Then
                    Dim c As New Class1
                    c.upload_ftp(PictureBox2.ImageLocation, imagem2)
                End If
                If (Labelimagem3.Text.Trim.Length > 0) Then
                    Dim c As New Class1
                    c.upload_ftp(PictureBox3.ImageLocation, imagem3)
                End If
                If (Labelimagem4.Text.Trim.Length > 0) Then
                    Dim c As New Class1
                    c.upload_ftp(PictureBox4.ImageLocation, imagem4)
                End If
                '---------------------
                MsgBox("As alterações foram guardadas com sucesso.")
            Else
                MsgBox("Houve um erro ao guardar as alterações.")
            End If
            Me.UploadsTableAdapter.Fill(Me.I07351DataSet.uploads)
        End If
    End Sub
End Class