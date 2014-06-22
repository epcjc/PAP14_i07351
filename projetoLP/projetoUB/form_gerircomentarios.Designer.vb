<Global.Microsoft.VisualBasic.CompilerServices.DesignerGenerated()> _
Partial Class form_gerircomentarios
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
        Me.ComboBox1 = New System.Windows.Forms.ComboBox()
        Me.ComentariosuploadsBindingSource = New System.Windows.Forms.BindingSource(Me.components)
        Me.I07351DataSet = New projetoUB.i07351DataSet()
        Me.Comentarios_uploadsTableAdapter = New projetoUB.i07351DataSetTableAdapters.comentarios_uploadsTableAdapter()
        Me.Label2 = New System.Windows.Forms.Label()
        Me.Button1 = New System.Windows.Forms.Button()
        Me.Button2 = New System.Windows.Forms.Button()
        Me.Labelid2 = New System.Windows.Forms.Label()
        Me.Label7 = New System.Windows.Forms.Label()
        Me.Labelnumero = New System.Windows.Forms.Label()
        Me.Labelenviado = New System.Windows.Forms.Label()
        Me.Labeldata = New System.Windows.Forms.Label()
        Me.labelid = New System.Windows.Forms.Label()
        Me.Label5 = New System.Windows.Forms.Label()
        Me.Label4 = New System.Windows.Forms.Label()
        Me.Label3 = New System.Windows.Forms.Label()
        Me.Label1 = New System.Windows.Forms.Label()
        CType(Me.ComentariosuploadsBindingSource, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.SuspendLayout()
        '
        'ComboBox1
        '
        Me.ComboBox1.DataSource = Me.ComentariosuploadsBindingSource
        Me.ComboBox1.DisplayMember = "conteudo"
        Me.ComboBox1.FormattingEnabled = True
        Me.ComboBox1.Location = New System.Drawing.Point(12, 70)
        Me.ComboBox1.MaxLength = 2000
        Me.ComboBox1.Name = "ComboBox1"
        Me.ComboBox1.Size = New System.Drawing.Size(460, 21)
        Me.ComboBox1.TabIndex = 0
        Me.ComboBox1.ValueMember = "id"
        '
        'ComentariosuploadsBindingSource
        '
        Me.ComentariosuploadsBindingSource.DataMember = "comentarios_uploads"
        Me.ComentariosuploadsBindingSource.DataSource = Me.I07351DataSet
        '
        'I07351DataSet
        '
        Me.I07351DataSet.DataSetName = "i07351DataSet"
        Me.I07351DataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema
        '
        'Comentarios_uploadsTableAdapter
        '
        Me.Comentarios_uploadsTableAdapter.ClearBeforeFill = True
        '
        'Label2
        '
        Me.Label2.AutoSize = True
        Me.Label2.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label2.Location = New System.Drawing.Point(9, 51)
        Me.Label2.Name = "Label2"
        Me.Label2.Size = New System.Drawing.Size(154, 16)
        Me.Label2.TabIndex = 5
        Me.Label2.Text = "Escolher comentário:"
        '
        'Button1
        '
        Me.Button1.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button1.Location = New System.Drawing.Point(169, 300)
        Me.Button1.Name = "Button1"
        Me.Button1.Size = New System.Drawing.Size(75, 38)
        Me.Button1.TabIndex = 14
        Me.Button1.Text = "Apagar"
        Me.Button1.UseVisualStyleBackColor = True
        '
        'Button2
        '
        Me.Button2.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.0!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Button2.Location = New System.Drawing.Point(250, 300)
        Me.Button2.Name = "Button2"
        Me.Button2.Size = New System.Drawing.Size(75, 38)
        Me.Button2.TabIndex = 15
        Me.Button2.Text = "Cancelar"
        Me.Button2.UseVisualStyleBackColor = True
        '
        'Labelid2
        '
        Me.Labelid2.AutoSize = True
        Me.Labelid2.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Labelid2.Location = New System.Drawing.Point(107, 200)
        Me.Labelid2.Name = "Labelid2"
        Me.Labelid2.Size = New System.Drawing.Size(0, 16)
        Me.Labelid2.TabIndex = 47
        '
        'Label7
        '
        Me.Label7.AutoSize = True
        Me.Label7.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label7.Location = New System.Drawing.Point(92, 175)
        Me.Label7.Name = "Label7"
        Me.Label7.Size = New System.Drawing.Size(101, 16)
        Me.Label7.TabIndex = 46
        Me.Label7.Text = "ID do upload:"
        '
        'Labelnumero
        '
        Me.Labelnumero.AutoSize = True
        Me.Labelnumero.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Labelnumero.Location = New System.Drawing.Point(298, 200)
        Me.Labelnumero.Name = "Labelnumero"
        Me.Labelnumero.Size = New System.Drawing.Size(0, 16)
        Me.Labelnumero.TabIndex = 45
        '
        'Labelenviado
        '
        Me.Labelenviado.AutoSize = True
        Me.Labelenviado.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Labelenviado.Location = New System.Drawing.Point(102, 252)
        Me.Labelenviado.Name = "Labelenviado"
        Me.Labelenviado.Size = New System.Drawing.Size(0, 16)
        Me.Labelenviado.TabIndex = 44
        '
        'Labeldata
        '
        Me.Labeldata.AutoSize = True
        Me.Labeldata.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Labeldata.Location = New System.Drawing.Point(298, 145)
        Me.Labeldata.Name = "Labeldata"
        Me.Labeldata.Size = New System.Drawing.Size(0, 16)
        Me.Labeldata.TabIndex = 43
        '
        'labelid
        '
        Me.labelid.AutoSize = True
        Me.labelid.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Regular, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.labelid.Location = New System.Drawing.Point(107, 145)
        Me.labelid.Name = "labelid"
        Me.labelid.Size = New System.Drawing.Size(0, 16)
        Me.labelid.TabIndex = 42
        '
        'Label5
        '
        Me.Label5.AutoSize = True
        Me.Label5.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label5.Location = New System.Drawing.Point(280, 175)
        Me.Label5.Name = "Label5"
        Me.Label5.Size = New System.Drawing.Size(163, 16)
        Me.Label5.TabIndex = 41
        Me.Label5.Text = "Número de denúncias:"
        '
        'Label4
        '
        Me.Label4.AutoSize = True
        Me.Label4.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label4.Location = New System.Drawing.Point(92, 119)
        Me.Label4.Name = "Label4"
        Me.Label4.Size = New System.Drawing.Size(130, 16)
        Me.Label4.TabIndex = 40
        Me.Label4.Text = "ID do comentário:"
        '
        'Label3
        '
        Me.Label3.AutoSize = True
        Me.Label3.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label3.Location = New System.Drawing.Point(92, 227)
        Me.Label3.Name = "Label3"
        Me.Label3.Size = New System.Drawing.Size(96, 16)
        Me.Label3.TabIndex = 39
        Me.Label3.Text = "Enviado por:"
        '
        'Label1
        '
        Me.Label1.AutoSize = True
        Me.Label1.Font = New System.Drawing.Font("Microsoft Sans Serif", 9.75!, System.Drawing.FontStyle.Bold, System.Drawing.GraphicsUnit.Point, CType(0, Byte))
        Me.Label1.Location = New System.Drawing.Point(280, 119)
        Me.Label1.Name = "Label1"
        Me.Label1.Size = New System.Drawing.Size(45, 16)
        Me.Label1.TabIndex = 38
        Me.Label1.Text = "Data:"
        '
        'form_gerircomentarios
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(484, 361)
        Me.Controls.Add(Me.Labelid2)
        Me.Controls.Add(Me.Label7)
        Me.Controls.Add(Me.Labelnumero)
        Me.Controls.Add(Me.Labelenviado)
        Me.Controls.Add(Me.Labeldata)
        Me.Controls.Add(Me.labelid)
        Me.Controls.Add(Me.Label5)
        Me.Controls.Add(Me.Label4)
        Me.Controls.Add(Me.Label3)
        Me.Controls.Add(Me.Label1)
        Me.Controls.Add(Me.Button2)
        Me.Controls.Add(Me.Button1)
        Me.Controls.Add(Me.Label2)
        Me.Controls.Add(Me.ComboBox1)
        Me.Name = "form_gerircomentarios"
        Me.Text = "Gerir comentários dos uploads"
        CType(Me.ComentariosuploadsBindingSource, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).EndInit()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents ComboBox1 As System.Windows.Forms.ComboBox
    Friend WithEvents I07351DataSet As projetoUB.i07351DataSet
    Friend WithEvents ComentariosuploadsBindingSource As System.Windows.Forms.BindingSource
    Friend WithEvents Comentarios_uploadsTableAdapter As projetoUB.i07351DataSetTableAdapters.comentarios_uploadsTableAdapter
    Friend WithEvents Label2 As System.Windows.Forms.Label
    Friend WithEvents Button1 As System.Windows.Forms.Button
    Friend WithEvents Button2 As System.Windows.Forms.Button
    Friend WithEvents Labelid2 As System.Windows.Forms.Label
    Friend WithEvents Label7 As System.Windows.Forms.Label
    Friend WithEvents Labelnumero As System.Windows.Forms.Label
    Friend WithEvents Labelenviado As System.Windows.Forms.Label
    Friend WithEvents Labeldata As System.Windows.Forms.Label
    Friend WithEvents labelid As System.Windows.Forms.Label
    Friend WithEvents Label5 As System.Windows.Forms.Label
    Friend WithEvents Label4 As System.Windows.Forms.Label
    Friend WithEvents Label3 As System.Windows.Forms.Label
    Friend WithEvents Label1 As System.Windows.Forms.Label
End Class
