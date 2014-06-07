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

        '-------------------------------------------------------
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
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

        '-------------------------------------------------------
    End Sub
End Class