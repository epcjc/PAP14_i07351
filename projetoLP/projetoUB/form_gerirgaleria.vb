Public Class form_gerirgaleria
    Private Sub form_gerirgaleria_Load(sender As Object, e As EventArgs) Handles MyBase.Load
        'TODO: This line of code loads data into the 'I07351DataSet.galeria' table. You can move, or remove it, as needed.
        Me.GaleriaTableAdapter.Fill(Me.I07351DataSet.galeria)
        'TODO: This line of code loads data into the 'I07351DataSet.galeria' table. You can move, or remove it, as needed.
        Me.GaleriaTableAdapter.Fill(Me.I07351DataSet.galeria)
        MdiParent = form_main
    End Sub

    Private Sub GaleriaBindingNavigatorSaveItem_Click(sender As Object, e As EventArgs)
        Me.Validate()
        Me.GaleriaBindingSource.EndEdit()
        Me.TableAdapterManager.UpdateAll(Me.I07351DataSet)

    End Sub
End Class