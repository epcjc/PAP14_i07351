﻿Imports MySql.Data.MySqlClient
Imports System.Drawing
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
            Dim imagem As String = "porsubstituir"
            Dim Query As String = "INSERT INTO galeria (descricao, imagem) VALUES ('" & TextBox1.Text & "', '" & imagem & "'); SELECT LAST_INSERT_ID()"
            Dim con As MySqlConnection = New MySqlConnection("server=projetos.epcjc.net; user id=i07351; password=amorim; database=i07351")
            con.Open()
            Dim cmd As MySqlCommand = New MySqlCommand(Query, con)
            Dim ultimoid As Integer = CInt(cmd.ExecuteScalar())
            'Dim i As Integer = cmd.ExecuteNonQuery()
            If (ultimoid > 0) Then
                'atualiza o campo imagem no registo
                Dim caminhoimagem As String = "galeria/" & ultimoid & ".jpg"
                Query = "UPDATE galeria SET imagem = '" & caminhoimagem & "' WHERE id = " & ultimoid
                cmd = New MySqlCommand(Query, con)
                Dim i As Integer = cmd.ExecuteNonQuery()
                'redimensiona imagem para 960x473 e envia por ftp---------------

                ' Get the source bitmap.
                Dim bm_source As New Bitmap(PictureBox1.Image)
                ' Make a bitmap for the result.
                Dim bm_dest As New Bitmap( _
                    CInt(960), _
                    CInt(473))
                ' Make a Graphics object for the result Bitmap.
                Dim gr_dest As Graphics = Graphics.FromImage(bm_dest)
                ' Copy the source image into the destination bitmap.
                gr_dest.DrawImage(bm_source, 0, 0, _
                    bm_dest.Width, _
                    bm_dest.Height)
                ' save the result.
                bm_dest.Save("c:\temp\galeria-" & ultimoid & ".jpg")

                ' Envia
                Dim c As New Class1
                c.upload_ftp("c:\temp\galeria-" & ultimoid & ".jpg", "galeria/" & ultimoid & ".jpg")
                '------------------------------------
                MsgBox("O registo foi inserido com sucesso.")
            Else
                MsgBox("Não foi possível inserir o registo.")
            End If
            con.Close()
            '------------------------------------------------------
        End If
    End Sub
End Class