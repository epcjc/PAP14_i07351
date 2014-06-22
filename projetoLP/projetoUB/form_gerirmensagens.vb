Imports MySql.Data.MySqlClient

Public Class form_gerirmensagens
    Private Sub form_gerirmensagens_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.mensagens_administracao' table. You can move, or remove it, as needed.
        Me.Mensagens_administracaoTableAdapter.Fill(Me.I07351DataSet.mensagens_administracao)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, resposta FROM mensagens_administracao WHERE id = " & id & " LIMIT 1"
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
                        Dim resposta As String = sqlReader("resposta").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox3.Text = resposta
                        TextBox2.Text = titulo
                        TextBox1.Text = conteudo
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
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, resposta FROM mensagens_administracao WHERE id = " & id & " LIMIT 1"
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
                        Dim resposta As String = sqlReader("resposta").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox3.Text = resposta
                        TextBox2.Text = titulo
                        TextBox1.Text = conteudo
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
            Dim Query As String = "Delete FROM mensagens_administracao WHERE id  = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            If (i > 0) Then
                MsgBox("O registo foi apagado com sucesso.")
            Else
                MsgBox("Não foi possível apagar o registo.")
            End If
            con.Close()
            If (i > 0) Then
                'atualiza combobox
                Me.Mensagens_administracaoTableAdapter.Fill(Me.I07351DataSet.mensagens_administracao)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    TextBox3.Text = ""
                    TextBox2.Text = ""
                    TextBox1.Text = ""
                    TextBox3.ReadOnly = True
                    Labelenviado.Text = ""
                End If
                'connecta a bd'
                Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                Dim sqlQuery As String = "SELECT datahora, id_utilizador, titulo, conteudo, resposta FROM mensagens_administracao WHERE id = " & id & " LIMIT 1"
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
                                Dim resposta As String = sqlReader("resposta").ToString()
                                Dim id_utilizador As Integer = sqlReader("id_utilizador")
                                'preenche labels
                                Labeldata.Text = datahora
                                TextBox3.Text = resposta
                                TextBox2.Text = titulo
                                TextBox1.Text = conteudo
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
            End If
            '-------------------------------------
        End If
    End Sub

    Private Sub Button4_Click(sender As Object, e As EventArgs) Handles Button4.Click
        'botao guardar
        Dim foi As Integer = 1 'verifica se pode
        If (ComboBox1.SelectedValue Is Nothing) Then
            foi = 0
            ErrorProvider1.SetError(ComboBox1, "Nenhum registo selecionado")
        End If
        If (foi = 1) Then
            Dim id As Integer = ComboBox1.SelectedValue
            Dim resposta As String = TextBox3.Text
            Dim resposta_bd As String = resposta & " | Resposta enviada por: " & session_username
            'guarda resposta-----
            Dim Query As String = "UPDATE mensagens_administracao SET resposta = '" & resposta_bd & "', respondida = 1 WHERE id = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            con.Close()
            'envia uma mensagem com a resposta para o utilizador que enviou a mensagem, e aumenta um nmensagens nesse utilizador
            Dim id_utilizador As Integer = 0
            Dim nmensagens As Integer = 0
            Dim titulo As String = ""
            Dim conteudo As String = ""
            Dim titulomsg As String = ""
            Dim msg As String = ""
            Dim erro As Integer = 0
            'encontra o id do utilizador, e depois o nmensagens desse utilizador
            'connecta a bd'
            Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
            Dim sqlQuery As String = "SELECT id_utilizador, titulo, conteudo FROM mensagens_administracao WHERE id = " & id
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
                            titulo = sqlReader("titulo").ToString()
                            conteudo = sqlReader("conteudo").ToString()

                            titulomsg = "Administracao Digiart - Resposta"
                            msg = "<font size=""+1"">Resposta enviada por: " & session_username & "</font><br><br><br><h5><font size=""+1"">Mensagem:</font><br> <font color=""#787878"">" & titulo & "</font></h5>" & conteudo & "<br><br><h5><font size=""+1"">Resposta:</font></h5> " & resposta

                            'insere mensagem
                            Query = "INSERT INTO mensagens (id_utilizadorE,id_utilizadorR,apagou_utilizadorE,titulo,conteudo) VALUES (0," & id_utilizador & ",1,'" & titulomsg & "','" & msg & "'); SELECT LAST_INSERT_ID()"
                            con.Open()
                            cmd = New MySqlCommand(Query, con)
                            Dim ultimoid As Integer = CInt(cmd.ExecuteScalar())
                            'Dim i As Integer = cmd.ExecuteNonQuery()
                            If (ultimoid > 0) Then
                            Else
                                MsgBox("Não foi possível inserir o registo.")
                                erro = 1
                            End If
                            con.Close()
                        End While
                    Catch ex As MySqlException
                        MsgBox("excecpçao nº 8137146")
                    End Try
                End Using
            End Using
            sqlQuery = "SELECT nmensagens FROM utilizadores WHERE id = " & id_utilizador
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
                            nmensagens = sqlReader("nmensagens") + 1
                            'altera nmensagens no utilizador
                            Query = "UPDATE utilizadores SET nmensagens = " & nmensagens & " WHERE id = " & id_utilizador
                            con.Open()
                            cmd = New MySqlCommand(Query, con)
                            Dim ii As Integer = cmd.ExecuteNonQuery()
                            If (ii > 0) Then
                            Else
                                MsgBox("Não foi possível inserir o registo.")
                                erro = 1
                            End If
                            con.Close()
                        End While
                    Catch ex As MySqlException
                        MsgBox("excecpçao nº 4d37146")
                    End Try
                End Using
            End Using


            '---------------------
            If (erro = 0) Then
                MsgBox("As alterações foram guardadas com sucesso.")
            End If

        End If
    End Sub
End Class