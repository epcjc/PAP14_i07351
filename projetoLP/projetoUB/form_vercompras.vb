Imports MySql.Data.MySqlClient

Public Class form_vercompras
    Private Sub form_vercompras_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.compras' table. You can move, or remove it, as needed.
        Me.ComprasTableAdapter.Fill(Me.I07351DataSet.compras)
        MdiParent = form_main

        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_upload, codigo_compra, id_comprador, confirmacaoC, confirmacaoV, comentarioV, comentarioC FROM compras WHERE id = " & id & " LIMIT 1"
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
                        Dim id_upload As Integer = sqlReader("id_upload")
                        Dim id_comprador As Integer = sqlReader("id_comprador")
                        Dim codigo_compra As String = sqlReader("codigo_compra").ToString()
                        Dim confirmacaoC As Integer = sqlReader("confirmacaoC")
                        Dim confirmacaoV As Integer = sqlReader("confirmacaoV")
                        Dim comentarioV As String = sqlReader("comentarioV").ToString()
                        Dim comentarioC As String = sqlReader("comentarioC").ToString()
                        Dim confirmacaoC_F As String
                        Dim confirmacaoV_F As String


                        'preenche labels
                        If (confirmacaoC = 1) Then
                            confirmacaoC_F = "Sim"
                        Else
                            confirmacaoC_F = "Não"
                        End If

                        If (confirmacaoV = 1) Then
                            confirmacaoV_F = "Sim"
                        Else
                            confirmacaoV_F = "Não"
                        End If

                        Labeldata.Text = datahora
                        Labelid.Text = id_upload
                        Labelcodigo.Text = codigo_compra
                        Labelconfirmacaoc.Text = confirmacaoC_F
                        Labelconfirmacaov.Text = confirmacaoV_F
                        If (comentarioC.Trim.Length > 1) Then
                            Labelcomentarioc.Text = comentarioC
                        End If
                        If (comentarioV.Trim.Length > 1) Then
                            Labelcomentariov.Text = comentarioV
                        End If

                        'pesquisa para descobrir o nome do comprador
                        Dim sqlQuery2 As String = "SELECT username FROM utilizadores WHERE id = " & id_comprador & " LIMIT 1"
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
                                        Dim usernamec As String = sqlReader2("username").ToString()
                                        Labelcomprador.Text = usernamec
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 2137146")
                                End Try
                            End Using
                        End Using

                        'pesquisa para descobrir o id do vendedor
                        Dim id_vendedor As Integer
                        Dim sqlQuery3 As String = "SELECT id_utilizador FROM uploads WHERE id = " & id_upload & " LIMIT 1"
                        Using sqlConn3 As New MySqlConnection(connString)
                            Using sqlComm3 As New MySqlCommand()
                                With sqlComm3
                                    .Connection = sqlConn3
                                    .CommandText = sqlQuery3
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn3.Open()
                                    Dim sqlReader3 As MySqlDataReader = sqlComm3.ExecuteReader()
                                    While sqlReader3.Read()
                                        id_vendedor = sqlReader3("id_utilizador")
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 3137146")
                                End Try
                            End Using
                        End Using

                        'pesquisa para descobrir o username do vendedor
                        Dim sqlQuery4 As String = "SELECT username FROM utilizadores WHERE id = " & id_vendedor & " LIMIT 1"
                        Using sqlConn4 As New MySqlConnection(connString)
                            Using sqlComm4 As New MySqlCommand()
                                With sqlComm4
                                    .Connection = sqlConn4
                                    .CommandText = sqlQuery4
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn4.Open()
                                    Dim sqlReader4 As MySqlDataReader = sqlComm4.ExecuteReader()
                                    While sqlReader4.Read()
                                        Dim usernamev As String = sqlReader4("username").ToString()
                                        Labelvendedor.Text = usernamev
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 4137146")
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

    Private Sub Button1_Click(sender As Object, e As EventArgs) Handles Button1.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
        Dim id As Integer = ComboBox1.SelectedValue
        'connecta a bd'
        Dim connString As String = "server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351"
        Dim sqlQuery As String = "SELECT datahora, id_upload, codigo_compra, id_comprador, confirmacaoC, confirmacaoV, comentarioV, comentarioC FROM compras WHERE id = " & id & " LIMIT 1"
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
                        Dim id_upload As Integer = sqlReader("id_upload")
                        Dim id_comprador As Integer = sqlReader("id_comprador")
                        Dim codigo_compra As String = sqlReader("codigo_compra").ToString()
                        Dim confirmacaoC As Integer = sqlReader("confirmacaoC")
                        Dim confirmacaoV As Integer = sqlReader("confirmacaoV")
                        Dim comentarioV As String = sqlReader("comentarioV").ToString()
                        Dim comentarioC As String = sqlReader("comentarioC").ToString()
                        Dim confirmacaoC_F As String
                        Dim confirmacaoV_F As String


                        'preenche labels
                        If (confirmacaoC = 1) Then
                            confirmacaoC_F = "Sim"
                        Else
                            confirmacaoC_F = "Não"
                        End If

                        If (confirmacaoV = 1) Then
                            confirmacaoV_F = "Sim"
                        Else
                            confirmacaoV_F = "Não"
                        End If

                        Labeldata.Text = datahora
                        Labelid.Text = id_upload
                        Labelcodigo.Text = codigo_compra
                        Labelconfirmacaoc.Text = confirmacaoC_F
                        Labelconfirmacaov.Text = confirmacaoV_F
                        If (comentarioC.Trim.Length > 1) Then
                            Labelcomentarioc.Text = comentarioC
                        End If
                        If (comentarioV.Trim.Length > 1) Then
                            Labelcomentariov.Text = comentarioV
                        End If

                        'pesquisa para descobrir o nome do comprador
                        Dim sqlQuery2 As String = "SELECT username FROM utilizadores WHERE id = " & id_comprador & " LIMIT 1"
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
                                        Dim usernamec As String = sqlReader2("username").ToString()
                                        Labelcomprador.Text = usernamec
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 2137146")
                                End Try
                            End Using
                        End Using

                        'pesquisa para descobrir o id do vendedor
                        Dim id_vendedor As Integer
                        Dim sqlQuery3 As String = "SELECT id_utilizador FROM uploads WHERE id = " & id_upload & " LIMIT 1"
                        Using sqlConn3 As New MySqlConnection(connString)
                            Using sqlComm3 As New MySqlCommand()
                                With sqlComm3
                                    .Connection = sqlConn3
                                    .CommandText = sqlQuery3
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn3.Open()
                                    Dim sqlReader3 As MySqlDataReader = sqlComm3.ExecuteReader()
                                    While sqlReader3.Read()
                                        id_vendedor = sqlReader3("id_utilizador")
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 3137146")
                                End Try
                            End Using
                        End Using

                        'pesquisa para descobrir o username do vendedor
                        Dim sqlQuery4 As String = "SELECT username FROM utilizadores WHERE id = " & id_vendedor & " LIMIT 1"
                        Using sqlConn4 As New MySqlConnection(connString)
                            Using sqlComm4 As New MySqlCommand()
                                With sqlComm4
                                    .Connection = sqlConn4
                                    .CommandText = sqlQuery4
                                    .CommandType = CommandType.Text
                                End With
                                Try
                                    sqlConn4.Open()
                                    Dim sqlReader4 As MySqlDataReader = sqlComm4.ExecuteReader()
                                    While sqlReader4.Read()
                                        Dim usernamev As String = sqlReader4("username").ToString()
                                        Labelvendedor.Text = usernamev
                                    End While
                                Catch ex As MySqlException
                                    MsgBox("excecpçao nº 4137146")
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
End Class