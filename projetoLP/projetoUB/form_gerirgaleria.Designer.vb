<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class form_gerirgaleria
    Inherits System.Windows.Forms.Form

    'Descartar substituições de formulário para limpar a lista de componentes.
    <System.Diagnostics.DebuggerNonUserCode()> _
    Protected Overrides Sub Dispose(ByVal disposing As Boolean)
        Try
            If disposing AndAlso components IsNot Nothing Then
                components.Dispose()
            End If
        Finally
            MyBase.Dispose(disposing)
        End Try
    End Sub

    'Exigido pelo Windows Form Designer
    Private components As System.ComponentModel.IContainer

    'OBSERVAÇÃO: O procedimento a seguir é exigido pelo Windows Form Designer
    'Ele pode ser modificado usando o Windows Form Designer.  
    'Não o modifique usando o editor de códigos.
    <System.Diagnostics.DebuggerStepThrough()> _
    Private Sub InitializeComponent()
        Me.GaleriaDataGridView = New System.Windows.Forms.DataGridView()
        CType(Me.GaleriaDataGridView, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'GaleriaDataGridView
        '
        Me.GaleriaDataGridView.ColumnHeadersHeightSizeMode = System.Windows.Forms.DataGridViewColumnHeadersHeightSizeMode.AutoSize
        Me.GaleriaDataGridView.Dock = System.Windows.Forms.DockStyle.Fill
        Me.GaleriaDataGridView.Location = New System.Drawing.Point(0, 0)
        Me.GaleriaDataGridView.Name = "GaleriaDataGridView"
        Me.GaleriaDataGridView.Size = New System.Drawing.Size(684, 361)
        Me.GaleriaDataGridView.TabIndex = 1
        '
        'form_gerirgaleria
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(684, 361)
        Me.Controls.Add(Me.GaleriaDataGridView)
        Me.Name = "form_gerirgaleria"
        Me.Text = "Gerir galeria"
        CType(Me.GaleriaDataGridView, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)

    End Sub
    Friend WithEvents GaleriaDataGridView As System.Windows.Forms.DataGridView
End Class
