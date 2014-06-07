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

        '-------------------------------------------------------
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
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

        '-------------------------------------------------------
    End Sub
End Class