Imports MySql.Data.MySqlClient

Public Class form_gerirutilizadores
    Private Sub form_gerirutilizadores_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.utilizadores' table. You can move, or remove it, as needed.
        Me.UtilizadoresTableAdapter.Fill(Me.I07351DataSet.utilizadores)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT email, pais, descricao, nreports, datahora, imagem FROM utilizadores WHERE id = " & id & " LIMIT 1"
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
                        Dim email As String = sqlReader("email").ToString()
                        Dim pais As String = sqlReader("pais").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim nreports As Integer = sqlReader("nreports")

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelid.Text = id
                        TextBox1.Text = descricao
                        TextBox3.Text = pais
                        TextBox2.Text = email
                        TextBox1.ReadOnly = False
                        TextBox2.ReadOnly = False
                        TextBox3.ReadOnly = False
                    End While
                Catch ex As MySqlException
                    MsgBox("excecpçao nº 8137146")
                End Try
            End Using
        End Using
        'faz download por ftp da imagem para apresentar--------------------
        Dim c As New Class1
        c.download_ftp("imagens_utilizadores/" & id & ".jpg", "utilizador-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\utilizador-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\utilizador-" & id & ".jpg"
        End If
        '--------------------------------------------------------------------
    End Sub

    Private Sub Button6_Click(sender As Object, e As EventArgs) Handles Button6.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        'apaga labels das imagens
        Labelimagem.Text = ""
        PictureBox1.Image = Nothing

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT email, pais, descricao, nreports, datahora, imagem FROM utilizadores WHERE id = " & id & " LIMIT 1"
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
                        Dim email As String = sqlReader("email").ToString()
                        Dim pais As String = sqlReader("pais").ToString()
                        Dim imagem As String = sqlReader("imagem").ToString()
                        Dim nreports As Integer = sqlReader("nreports")

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelid.Text = id
                        TextBox1.Text = descricao
                        TextBox3.Text = pais
                        TextBox2.Text = email
                        TextBox1.ReadOnly = False
                        TextBox2.ReadOnly = False
                        TextBox3.ReadOnly = False
                    End While
                Catch ex As MySqlException
                    MsgBox("excecpçao nº 8137146")
                End Try
            End Using
        End Using
        'faz download por ftp da imagem para apresentar--------------------
        Dim c As New Class1
        c.download_ftp("imagens_utilizadores/" & id & ".jpg", "utilizador-" & id & ".jpg")
        If My.Computer.FileSystem.FileExists("C:\Temp\utilizador-" & id & ".jpg") Then
            PictureBox1.ImageLocation = "C:\Temp\utilizador-" & id & ".jpg"
        End If
        '--------------------------------------------------------------------
    End Sub

    Private Sub Button7_Click(sender As Object, e As EventArgs) Handles Button7.Click
        'apagar
        Dim foi As Integer = 1
        Dim id As Integer = ComboBox1.SelectedValue
        Dim result As Integer = MessageBox.Show("Tem a certeza que quer apagar este registo?", "Confirmação", MessageBoxButtons.YesNoCancel)
        If result = DialogResult.Cancel Then
            foi = 0
        ElseIf result = DialogResult.No Then
            foi = 0
        End If

        'pesquisa para ver a permissao do utilizador a apagar, se for administrador geral, não é possivel apagar
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT permissao FROM utilizadores WHERE id = " & id & " LIMIT 1"
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
                        If (IsDBNull(sqlReader("permissao"))) Then
                            'nada
                        Else
                            Dim permissao As Integer = sqlReader("permissao")
                            If (permissao > 1) Then
                                foi = 0
                                MsgBox("Não é possível apagar administradores gerais.")
                            End If
                        End If

                    End While
                Catch ex As MySqlException
                    MsgBox("excecpçao nº 8137146")
                End Try
            End Using
        End Using
        '-----------------------------------------
        If (foi = 1) Then
            'parte de apagar aqui------------

            Dim Query As String = "Delete FROM utilizadores WHERE id  = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()

            'apaga bloqueios do utilizador
            Query = "Delete FROM bloqueios WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            Dim i2 As Integer = cmd.ExecuteNonQuery()

            Query = "Delete FROM bloqueios WHERE id_bloqueado  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga mensagens_administracao do utilizador
            Query = "Delete FROM mensagens_administracao WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga favoritos do utilizador
            Query = "Delete FROM favoritos_utilizadores WHERE id_favorito  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            Query = "Delete FROM favoritos_utilizadores WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            Query = "Delete FROM favoritos_uploads WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga uploads do utilizador
            Query = "Delete FROM uploads WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga noticias do utilizador
            Query = "Delete FROM noticias WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            'apaga paginas do utilizador
            Query = "Delete FROM paginas WHERE id_utilizador  = " & id
            cmd = New MySqlCommand(Query, con)
            i2 = cmd.ExecuteNonQuery()

            If (i > 0) Then
                'apaga imagens por ftp---------------
                Dim c As New Class1
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & ".jpg")
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & "_pequena.jpg")
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & "_perfil.jpg")
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & "_miniatura.jpg")
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & "_media.jpg")
                c.apagarficheiro_ftp("imagens_utilizadores/" & id & "_lista.jpg")
                '------------------------------------
                MsgBox("O registo foi apagado com sucesso.")
            Else
                MsgBox("Não foi possível apagar o registo.")
            End If
            con.Close()
            If (i > 0) Then
                'atualiza combobox
                Me.UtilizadoresTableAdapter.Fill(Me.I07351DataSet.utilizadores)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    Labelnumero.Text = ""
                    Labelid.Text = ""
                    TextBox1.Text = ""
                    TextBox3.Text = ""
                    TextBox2.Text = ""
                    TextBox1.ReadOnly = True
                    TextBox2.ReadOnly = True
                    TextBox3.ReadOnly = True
                End If
                'connecta a bd'
                connString = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                sqlQuery = "SELECT email, pais, descricao, nreports, datahora, imagem FROM utilizadores WHERE id = " & id & " LIMIT 1"
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
                                Dim email As String = sqlReader("email").ToString()
                                Dim pais As String = sqlReader("pais").ToString()
                                Dim imagem As String = sqlReader("imagem").ToString()
                                Dim nreports As Integer = sqlReader("nreports")

                                'preenche labels
                                Labeldata.Text = datahora
                                Labelnumero.Text = nreports
                                Labelid.Text = id
                                TextBox1.Text = descricao
                                TextBox3.Text = pais
                                TextBox2.Text = email
                                TextBox1.ReadOnly = False
                                TextBox2.ReadOnly = False
                                TextBox3.ReadOnly = False
                            End While
                        Catch ex As MySqlException
                            MsgBox("excecpçao nº 8137146")
                        End Try
                    End Using
                End Using
                'faz download por ftp da imagem para apresentar--------------------
                Dim c As New Class1
                c.download_ftp("imagens_utilizadores/" & id & ".jpg", "utilizador-" & id & ".jpg")
                If My.Computer.FileSystem.FileExists("C:\Temp\utilizador-" & id & ".jpg") Then
                    PictureBox1.ImageLocation = "C:\Temp\utilizador-" & id & ".jpg"
                End If
                '--------------------------------------------------------------------
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
End Class