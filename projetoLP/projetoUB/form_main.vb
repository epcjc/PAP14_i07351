﻿Public Class form_main

    Private Sub InserirImagemToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles InserirImagemToolStripMenuItem.Click
        form_gerirgaleria.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem.Click
        form_inseririmagemgaleria.Show()
    End Sub

    Private Sub InserirToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles InserirToolStripMenuItem.Click
        form_gerirutilizadores.Show()
    End Sub

    Private Sub InserirNotíciaToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles InserirNotíciaToolStripMenuItem.Click
        form_gerirnoticias.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem1_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem1.Click
        form_inserirnoticia.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem2_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem2.Click
        form_gerirpaginas.Show()
    End Sub

    Private Sub InserirPáginaToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles InserirPáginaToolStripMenuItem.Click
        form_inserirpagina.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem3_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem3.Click
        form_geriruploads.Show()
    End Sub

    Private Sub VerComprasToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles VerComprasToolStripMenuItem.Click
        form_vercompras.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem5_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem5.Click
        form_gerirmensagens.Show()
    End Sub

    Private Sub GestãoToolStripMenuItem4_Click(sender As Object, e As EventArgs) Handles GestãoToolStripMenuItem4.Click
        form_gerircomentarios.Show()
    End Sub

    Private Sub GestãonotíciasToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles GestãonotíciasToolStripMenuItem.Click
        form_gerircomentariosn.Show()
    End Sub

    Private Sub SairToolStripMenuItem_Click(sender As Object, e As EventArgs) Handles SairToolStripMenuItem.Click
        'ajuda
        Help.ShowHelp(Me, "ajuda.chm")
    End Sub

    Private Sub form_main_FormClosed(sender As Object, e As System.Windows.Forms.FormClosedEventArgs) Handles Me.FormClosed
        session_id = 0
        session_username = ""
        Application.Exit()
    End Sub

    Private Sub form_main_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        form_login.Hide()
    End Sub


    Private Sub SairToolStripMenuItem1_Click(sender As Object, e As EventArgs) Handles SairToolStripMenuItem1.Click
        session_id = 0
        session_username = ""
        Application.Exit()
    End Sub
End Class
