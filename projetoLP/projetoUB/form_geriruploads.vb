﻿Imports MySql.Data.MySqlClient

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
                        Labeltamanho.Text = size
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
                        Labeltamanho.Text = size & "bytes"
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
End Class