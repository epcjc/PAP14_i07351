Public Class form_gerircomentarios
    Private Sub form_gerircomentarios_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: esta linha de código carrega dados na tabela 'I07351DataSet.comentarios_noticias'. Você pode movê-la ou removê-la conforme necessário.
        Me.Comentarios_noticiasTableAdapter.Fill(Me.I07351DataSet.comentarios_noticias)
        MdiParent = form_main
    End Sub

    Private Sub Comentarios_noticiasBindingNavigatorSaveItem_Click(sender As Object, e As EventArgs) Handles Comentarios_noticiasBindingNavigatorSaveItem.Click
        Me.Validate()
        Me.Comentarios_noticiasBindingSource.EndEdit()
        Me.TableAdapterManager.UpdateAll(Me.I07351DataSet)

    End Sub
End Class