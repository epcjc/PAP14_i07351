Imports MySql.Data.MySqlClient

Public Class form_geriruploads
    Private Sub form_geriruploads_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.uploads' table. You can move, or remove it, as needed.
        Me.UploadsTableAdapter.Fill(Me.I07351DataSet.uploads)
        MdiParent = form_main

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
                        Dim imagem1 As String = sqlReader("imagem1").ToString()
                        Dim imagem2 As String = sqlReader("imagem2").ToString()
                        Dim imagem3 As String = sqlReader("imagem3").ToString()
                        Dim imagem4 As String = sqlReader("imagem4").ToString()

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelcategoria.Text = categoria
                        Labeltamanho.Text = size & " bytes"
                        TextBox1.Text = descricao
                        TextBox3.Text = titulo
                        NumericUpDown1.Value = preco
                        NumericUpDown1.ReadOnly = False
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

        '--------------------------------------------------------------------
    End Sub

    Private Sub Button6_Click(sender As Object, e As EventArgs)
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
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
                        Dim imagem1 As String = sqlReader("imagem1").ToString()
                        Dim imagem2 As String = sqlReader("imagem2").ToString()
                        Dim imagem3 As String = sqlReader("imagem3").ToString()
                        Dim imagem4 As String = sqlReader("imagem4").ToString()

                        'preenche labels
                        Labeldata.Text = datahora
                        Labelnumero.Text = nreports
                        Labelcategoria.Text = categoria
                        Labeltamanho.Text = size & " bytes"
                        TextBox1.Text = descricao
                        TextBox3.Text = titulo
                        NumericUpDown1.Value = preco
                        NumericUpDown1.ReadOnly = False
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

        '--------------------------------------------------------------------
    End Sub

    Private Sub Button7_Click(sender As Object, e As EventArgs) Handles Button7.Click
        'apagar
        Dim nuploads As Integer
        Dim nuploads_novo As Integer
        Dim id_utilizador As Integer

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
            Dim sqlQuery As String = "SELECT id_utilizador FROM uploads WHERE id = " & id & " LIMIT 1"
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
                'apaga imagens por ftp, e apaga o projeto do servidor se for gratuito---------------

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
                    NumericUpDown1.Value = ""
                    NumericUpDown1.ReadOnly = True
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
                                Dim imagem1 As String = sqlReader("imagem1").ToString()
                                Dim imagem2 As String = sqlReader("imagem2").ToString()
                                Dim imagem3 As String = sqlReader("imagem3").ToString()
                                Dim imagem4 As String = sqlReader("imagem4").ToString()

                                'preenche labels
                                Labeldata.Text = datahora
                                Labelnumero.Text = nreports
                                Labelcategoria.Text = categoria
                                Labeltamanho.Text = size & " bytes"
                                TextBox1.Text = descricao
                                TextBox3.Text = titulo
                                NumericUpDown1.Value = preco
                                NumericUpDown1.ReadOnly = False
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

                '--------------------------------------------------------------------
            End If
            '-------------------------------------
        End If
    End Sub

    Private Sub Button6_Click_1(sender As Object, e As EventArgs) Handles Button6.Click
        Me.Close()
    End Sub
End Class