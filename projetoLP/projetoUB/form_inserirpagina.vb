Imports MySql.Data.MySqlClient
Public Class form_inserirpagina
    Private Sub form_inserirpagina_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        MdiParent = form_main
    End Sub

    Private Sub Button2_Click(sender As Object, e As EventArgs) Handles Button2.Click
        Me.Close()
    End Sub

    Private Sub Button3_Click(sender As Object, e As EventArgs) Handles Button3.Click
        Dim foi As Integer = 1
        If (TextBox1.Text.Trim.Length < 2) Then
            ErrorProvider1.SetError(TextBox1, "É necessário preencher este campo")
            foi = 0
        Else
            ErrorProvider1.SetError(TextBox1, "")
        End If
        If (TextBox2.Text.Trim.Length < 2) Then
            ErrorProvider1.SetError(TextBox2, "É necessário preencher este campo")
            foi = 0
        Else
            ErrorProvider1.SetError(TextBox2, "")
        End If
        If (TextBox3.Text.Trim.Length < 2) Then
            ErrorProvider1.SetError(TextBox3, "É necessário preencher este campo")
            foi = 0
        Else
            ErrorProvider1.SetError(TextBox3, "")
        End If

        If (foi = 1) Then
            'insere na base de dados---
            Dim Query As String = "INSERT INTO paginas (nome, titulo, conteudo) VALUES ('" & TextBox3.Text & "', '" & TextBox2.Text & "', '" & TextBox1.Text & "'); SELECT LAST_INSERT_ID()"
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim ultimoid As Integer = CInt(cmd.ExecuteScalar())
            'Dim i As Integer = cmd.ExecuteNonQuery()
            If (ultimoid > 0) Then
                MsgBox("O registo foi inserido com sucesso.")
            Else
                MsgBox("Não foi possível inserir o registo.")
            End If
            con.Close()
            '------------------------------------------------------
        End If
    End Sub
End Class