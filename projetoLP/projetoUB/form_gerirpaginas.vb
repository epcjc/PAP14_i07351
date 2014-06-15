Imports MySql.Data.MySqlClient

Public Class form_gerirpaginas
    Private Sub form_gerirpaginas_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.paginas' table. You can move, or remove it, as needed.
        Me.PaginasTableAdapter.Fill(Me.I07351DataSet.paginas)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, nome, titulo, conteudo FROM paginas WHERE id = " & id & " LIMIT 1"
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
                        Dim nome As String = sqlReader("nome").ToString()
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim conteudo As String = sqlReader("conteudo").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox1.Text = conteudo
                        TextBox2.Text = titulo
                        TextBox3.Text = nome
                        TextBox1.ReadOnly = False
                        TextBox2.ReadOnly = False
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
        Dim sqlQuery As String = "SELECT datahora, id_utilizador, nome, titulo, conteudo FROM paginas WHERE id = " & id & " LIMIT 1"
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
                        Dim nome As String = sqlReader("nome").ToString()
                        Dim titulo As String = sqlReader("titulo").ToString()
                        Dim conteudo As String = sqlReader("conteudo").ToString()
                        Dim id_utilizador As Integer = sqlReader("id_utilizador")
                        'preenche labels
                        Labeldata.Text = datahora
                        TextBox1.Text = conteudo
                        TextBox2.Text = titulo
                        TextBox3.Text = nome
                        TextBox1.ReadOnly = False
                        TextBox2.ReadOnly = False
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
            Dim Query As String = "Delete FROM paginas WHERE id  = " & id
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
                Me.PaginasTableAdapter.Fill(Me.I07351DataSet.paginas)
                id = ComboBox1.SelectedValue
                If (id = Nothing Or id = 0) Then
                    Labeldata.Text = ""
                    TextBox1.Text = ""
                    TextBox2.Text = ""
                    TextBox3.Text = ""
                    TextBox1.ReadOnly = True
                    TextBox2.ReadOnly = True
                    TextBox3.ReadOnly = True
                    Labelenviado.Text = ""
                End If
                'connecta a bd'
                Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
                Dim sqlQuery As String = "SELECT datahora, id_utilizador, nome, titulo, conteudo FROM paginas WHERE id = " & id & " LIMIT 1"
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
                                Dim nome As String = sqlReader("nome").ToString()
                                Dim titulo As String = sqlReader("titulo").ToString()
                                Dim conteudo As String = sqlReader("conteudo").ToString()
                                Dim id_utilizador As Integer = sqlReader("id_utilizador")
                                'preenche labels
                                Labeldata.Text = datahora
                                TextBox1.Text = conteudo
                                TextBox2.Text = titulo
                                TextBox3.Text = nome
                                TextBox1.ReadOnly = False
                                TextBox2.ReadOnly = False
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
        If (TextBox3.Text.Trim.Length = 0) Then
            foi = 0
            ErrorProvider1.SetError(TextBox3, "Este campo não pode estar vazio.")
        Else
            ErrorProvider1.SetError(TextBox3, "")
        End If
        If (foi = 1) Then
            Dim id As Integer = ComboBox1.SelectedValue
            Dim conteudo As String = TextBox1.Text
            Dim titulo As String = TextBox2.Text
            Dim nome As String = TextBox3.Text

            'guarda alteracoes-----
            Dim Query As String = "UPDATE paginas SET titulo = '" & titulo & "', nome = '" & nome & "', conteudo = '" & conteudo & "' WHERE id = " & id
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim i As Integer = cmd.ExecuteNonQuery()
            con.Close()
            '---------------------
            MsgBox("As alterações foram guardadas com sucesso.")
            Me.PaginasTableAdapter.Fill(Me.I07351DataSet.paginas)
        End If
    End Sub
End Class