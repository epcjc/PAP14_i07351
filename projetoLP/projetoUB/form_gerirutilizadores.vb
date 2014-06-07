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

        '--------------------------------------------------------------------
    End Sub

    Private Sub Button6_Click(sender As Object, e As EventArgs) Handles Button6.Click
        Me.Close()
    End Sub

    Private Sub ComboBox1_SelectedIndexChanged(sender As Object, e As EventArgs) Handles ComboBox1.SelectedIndexChanged
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

        '--------------------------------------------------------------------
    End Sub
End Class