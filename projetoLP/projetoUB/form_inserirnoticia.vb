Imports MySql.Data.MySqlClient

Public Class form_inserirnoticia
    Private Sub form_inserirnoticia_Load(sender As Object, e As EventArgs) Handles MyBase.Load
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

        If (foi = 1) Then
            'insere na base de dados e envia imagem por ftp se existir---
            Dim imagem As String = ""
            If (PictureBox1.ImageLocation IsNot Nothing) Then
                imagem = PictureBox1.ImageLocation
            End If
            Dim Query As String = "INSERT INTO noticias (titulo, conteudo, imagem) VALUES ('" & TextBox2.Text & "', '" & TextBox1.Text & "', '" & imagem & "'); SELECT LAST_INSERT_ID()"
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim ultimoid As Integer = CInt(cmd.ExecuteScalar())
            'Dim i As Integer = cmd.ExecuteNonQuery()
            If (ultimoid > 0) Then
                'envia por ftp---------------
                If (imagem <> "") Then
                    Dim c As New Class1
                    c.upload_ftp(PictureBox1.ImageLocation, "imagens_noticias/" & ultimoid & ".jpg")
                End If
                '------------------------------------
                MsgBox("O registo foi inserido com sucesso.")
            Else
                MsgBox("Não foi possível inserir o registo.")
            End If
            con.Close()
            '------------------------------------------------------
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