Public Class form_inseririmagemgaleria
    Private Sub form_inseririmagemgaleria_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        MdiParent = form_main
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
        If (My.Computer.FileSystem.FileExists(PictureBox1.ImageLocation) = False) Then
            ErrorProvider1.SetError(PictureBox1, "É necessário inserir uma imagem")
            foi = 0
        Else
            ErrorProvider1.SetError(PictureBox1, "")
        End If

        If (foi = 1) Then
            'insere na base de dados e envia imagem por ftp---

            '------------------------------------------------------
        End If
    End Sub
End Class