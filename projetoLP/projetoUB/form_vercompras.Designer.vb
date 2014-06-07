<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class form_vercompras
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
        Me.components = New System.ComponentModel.Container()
        Me.Label8 = New System.Windows.Forms.Label()
        Me.ComboBox1 = New System.Windows.Forms.ComboBox()
        Me.Labelid = New System.Windows.Forms.Label()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Labeldata = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Labelcodigo = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Labelcomprador = New System.Windows.Forms.Label()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.Labelvendedor = New System.Windows.Forms.Label()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.Labelconfirmacaoc = New System.Windows.Forms.Label()
        Me.Label6 = New System.Windows.Forms.Label()
        Me.Labelconfirmacaov = New System.Windows.Forms.Label()
        Me.Label10 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        Me.Labelcomentarioc = New System.Windows.Forms.Label()
        Me.Labelcomentariov = New System.Windows.Forms.Label()
        Me.Label11 = New System.Windows.Forms.Label()
        Me.I07351DataSet = New projetoUB.i07351DataSet()
        Me.ComprasBindingSource = New System.Windows.Forms.BindingSource(Me.components)
        Me.ComprasTableAdapter = New projetoUB.i07351DataSetTableAdapters.comprasTableAdapter()
        Me.Button1 = New System.Windows.Forms.Button()
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.ComprasBindingSource, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'Label8
        '
        Me.Label8.AutoSize = True
        Me.Label8.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label8.Location = New System.Drawing.Point(24, 19)
        Me.Label8.Name = "Label8"
        Me.Label8.Size = New System.Drawing.Size(206, 16)
        Me.Label8.TabIndex = 66
        Me.Label8.Text = "Escolher número da compra:"
        '
        'ComboBox1
        '
        Me.ComboBox1.DataSource = Me.ComprasBindingSource
        Me.ComboBox1.DisplayMember = "id"
        Me.ComboBox1.FormattingEnabled = True
        Me.ComboBox1.Location = New System.Drawing.Point(27, 38)
        Me.ComboBox1.Name = "ComboBox1"
        Me.ComboBox1.Size = New System.Drawing.Size(128, 21)
        Me.ComboBox1.TabIndex = 65
        Me.ComboBox1.ValueMember = "id"
        '
        'Labelid
        '
        Me.Labelid.AutoSize = True
        Me.Labelid.Location = New System.Drawing.Point(36, 102)
        Me.Labelid.Name = "Labelid"
        Me.Labelid.Size = New System.Drawing.Size(0, 13)
        Me.Labelid.TabIndex = 72
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label2.Location = New System.Drawing.Point(24, 77)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(101, 16)
        Me.Label2.TabIndex = 71
        Me.Label2.Text = "ID do upload:"
        '
        'Labeldata
        '
        Me.Labeldata.AutoSize = True
        Me.Labeldata.Location = New System.Drawing.Point(289, 54)
        Me.Labeldata.Name = "Labeldata"
        Me.Labeldata.Size = New System.Drawing.Size(0, 13)
        Me.Labeldata.TabIndex = 70
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.Location = New System.Drawing.Point(277, 29)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(45, 16)
        Me.Label3.TabIndex = 69
        Me.Label3.Text = "Data:"
        '
        'Labelcodigo
        '
        Me.Labelcodigo.AutoSize = True
        Me.Labelcodigo.Location = New System.Drawing.Point(289, 102)
        Me.Labelcodigo.Name = "Labelcodigo"
        Me.Labelcodigo.Size = New System.Drawing.Size(0, 13)
        Me.Labelcodigo.TabIndex = 74
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.Location = New System.Drawing.Point(277, 77)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(140, 16)
        Me.Label4.TabIndex = 73
        Me.Label4.Text = "Código da compra:"
        '
        'Labelcomprador
        '
        Me.Labelcomprador.AutoSize = True
        Me.Labelcomprador.Location = New System.Drawing.Point(36, 150)
        Me.Labelcomprador.Name = "Labelcomprador"
        Me.Labelcomprador.Size = New System.Drawing.Size(0, 13)
        Me.Labelcomprador.TabIndex = 76
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label5.Location = New System.Drawing.Point(24, 125)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(89, 16)
        Me.Label5.TabIndex = 75
        Me.Label5.Text = "Comprador:"
        '
        'Labelvendedor
        '
        Me.Labelvendedor.AutoSize = True
        Me.Labelvendedor.Location = New System.Drawing.Point(289, 150)
        Me.Labelvendedor.Name = "Labelvendedor"
        Me.Labelvendedor.Size = New System.Drawing.Size(0, 13)
        Me.Labelvendedor.TabIndex = 78
        '
        'Label7
        '
        Me.Label7.AutoSize = True
        Me.Label7.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label7.Location = New System.Drawing.Point(277, 125)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(80, 16)
        Me.Label7.TabIndex = 77
        Me.Label7.Text = "Vendedor:"
        '
        'Labelconfirmacaoc
        '
        Me.Labelconfirmacaoc.AutoSize = True
        Me.Labelconfirmacaoc.Location = New System.Drawing.Point(36, 199)
        Me.Labelconfirmacaoc.Name = "Labelconfirmacaoc"
        Me.Labelconfirmacaoc.Size = New System.Drawing.Size(0, 13)
        Me.Labelconfirmacaoc.TabIndex = 80
        '
        'Label6
        '
        Me.Label6.AutoSize = True
        Me.Label6.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label6.Location = New System.Drawing.Point(24, 174)
        Me.Label6.Name = "Label6"
        Me.Label6.Size = New System.Drawing.Size(160, 16)
        Me.Label6.TabIndex = 79
        Me.Label6.Text = "Confirmou comprador:"
        '
        'Labelconfirmacaov
        '
        Me.Labelconfirmacaov.AutoSize = True
        Me.Labelconfirmacaov.Location = New System.Drawing.Point(289, 199)
        Me.Labelconfirmacaov.Name = "Labelconfirmacaov"
        Me.Labelconfirmacaov.Size = New System.Drawing.Size(0, 13)
        Me.Labelconfirmacaov.TabIndex = 82
        '
        'Label10
        '
        Me.Label10.AutoSize = True
        Me.Label10.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label10.Location = New System.Drawing.Point(277, 174)
        Me.Label10.Name = "Label10"
        Me.Label10.Size = New System.Drawing.Size(151, 16)
        Me.Label10.TabIndex = 81
        Me.Label10.Text = "Confirmou vendedor:"
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.Location = New System.Drawing.Point(24, 222)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(170, 16)
        Me.Label1.TabIndex = 83
        Me.Label1.Text = "Comentário comprador:"
        '
        'Labelcomentarioc
        '
        Me.Labelcomentarioc.AutoSize = True
        Me.Labelcomentarioc.Location = New System.Drawing.Point(36, 251)
        Me.Labelcomentarioc.Name = "Labelcomentarioc"
        Me.Labelcomentarioc.Size = New System.Drawing.Size(0, 13)
        Me.Labelcomentarioc.TabIndex = 84
        '
        'Labelcomentariov
        '
        Me.Labelcomentariov.AutoSize = True
        Me.Labelcomentariov.Location = New System.Drawing.Point(289, 251)
        Me.Labelcomentariov.Name = "Labelcomentariov"
        Me.Labelcomentariov.Size = New System.Drawing.Size(0, 13)
        Me.Labelcomentariov.TabIndex = 86
        '
        'Label11
        '
        Me.Label11.AutoSize = True
        Me.Label11.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label11.Location = New System.Drawing.Point(277, 222)
        Me.Label11.Name = "Label11"
        Me.Label11.Size = New System.Drawing.Size(161, 16)
        Me.Label11.TabIndex = 85
        Me.Label11.Text = "Comentário vendedor:"
        '
        'I07351DataSet
        '
        Me.I07351DataSet.DataSetName = "i07351DataSet"
        Me.I07351DataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema
        '
        'ComprasBindingSource
        '
        Me.ComprasBindingSource.DataMember = "compras"
        Me.ComprasBindingSource.DataSource = Me.I07351DataSet
        '
        'ComprasTableAdapter
        '
        Me.ComprasTableAdapter.ClearBeforeFill = True
        '
        'Button1
        '
        Me.Button1.Location = New System.Drawing.Point(205, 296)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(75, 23)
        Me.Button1.TabIndex = 87
        Me.Button1.Text = "Voltar"
        Me.Button1.UseVisualStyleBackColor = True
        '
        'form_vercompras
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(484, 361)
        Me.Controls.Add(Me.Button1)
        Me.Controls.Add(Me.Labelcomentariov)
        Me.Controls.Add(Me.Label11)
        Me.Controls.Add(Me.Labelcomentarioc)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.Labelconfirmacaov)
        Me.Controls.Add(Me.Label10)
        Me.Controls.Add(Me.Labelconfirmacaoc)
        Me.Controls.Add(Me.Label6)
        Me.Controls.Add(Me.Labelvendedor)
        Me.Controls.Add(Me.Label7)
        Me.Controls.Add(Me.Labelcomprador)
        Me.Controls.Add(Me.Label5)
        Me.Controls.Add(Me.Labelcodigo)
        Me.Controls.Add(Me.Label4)
        Me.Controls.Add(Me.Labelid)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.Labeldata)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.Label8)
        Me.Controls.Add(Me.ComboBox1)
        Me.Name = "form_vercompras"
        Me.Text = "Ver compras"
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.ComprasBindingSource, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents Label8 As System.Windows.Forms.Label
    Friend WithEvents ComboBox1 As System.Windows.Forms.ComboBox
    Friend WithEvents Labelid As System.Windows.Forms.Label
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Labeldata As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Labelcodigo As System.Windows.Forms.Label
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Labelcomprador As System.Windows.Forms.Label
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents Labelvendedor As System.Windows.Forms.Label
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents Labelconfirmacaoc As System.Windows.Forms.Label
    Friend WithEvents Label6 As System.Windows.Forms.Label
    Friend WithEvents Labelconfirmacaov As System.Windows.Forms.Label
    Friend WithEvents Label10 As System.Windows.Forms.Label
    Friend WithEvents Label1 As System.Windows.Forms.Label
    Friend WithEvents Labelcomentarioc As System.Windows.Forms.Label
    Friend WithEvents Labelcomentariov As System.Windows.Forms.Label
    Friend WithEvents Label11 As System.Windows.Forms.Label
    Friend WithEvents I07351DataSet As projetoUB.i07351DataSet
    Friend WithEvents ComprasBindingSource As System.Windows.Forms.BindingSource
    Friend WithEvents ComprasTableAdapter As projetoUB.i07351DataSetTableAdapters.comprasTableAdapter
    Friend WithEvents Button1 As System.Windows.Forms.Button
End Class
