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
        Dim resources As System.ComponentModel.ComponentResourceManager = New System.ComponentModel.ComponentResourceManager(GetType(form_gerircomentarios))
        Dim IdLabel As System.Windows.Forms.Label
        Dim ConteudoLabel As System.Windows.Forms.Label
        Dim Id_utilizadorLabel As System.Windows.Forms.Label
        Dim Id_noticiaLabel As System.Windows.Forms.Label
        Dim NreportsLabel As System.Windows.Forms.Label
        Dim DatahoraLabel As System.Windows.Forms.Label
        Me.I07351DataSet = New projetoUB.i07351DataSet()
        Me.Comentarios_noticiasBindingSource = New System.Windows.Forms.BindingSource(Me.components)
        Me.Comentarios_noticiasTableAdapter = New projetoUB.i07351DataSetTableAdapters.comentarios_noticiasTableAdapter()
        Me.TableAdapterManager = New projetoUB.i07351DataSetTableAdapters.TableAdapterManager()
        Me.Comentarios_noticiasBindingNavigator = New System.Windows.Forms.BindingNavigator(Me.components)
        Me.BindingNavigatorMoveFirstItem = New System.Windows.Forms.ToolStripButton()
        Me.BindingNavigatorMovePreviousItem = New System.Windows.Forms.ToolStripButton()
        Me.BindingNavigatorSeparator = New System.Windows.Forms.ToolStripSeparator()
        Me.BindingNavigatorPositionItem = New System.Windows.Forms.ToolStripTextBox()
        Me.BindingNavigatorCountItem = New System.Windows.Forms.ToolStripLabel()
        Me.BindingNavigatorSeparator1 = New System.Windows.Forms.ToolStripSeparator()
        Me.BindingNavigatorMoveNextItem = New System.Windows.Forms.ToolStripButton()
        Me.BindingNavigatorMoveLastItem = New System.Windows.Forms.ToolStripButton()
        Me.BindingNavigatorSeparator2 = New System.Windows.Forms.ToolStripSeparator()
        Me.BindingNavigatorAddNewItem = New System.Windows.Forms.ToolStripButton()
        Me.BindingNavigatorDeleteItem = New System.Windows.Forms.ToolStripButton()
        Me.Comentarios_noticiasBindingNavigatorSaveItem = New System.Windows.Forms.ToolStripButton()
        Me.IdTextBox = New System.Windows.Forms.TextBox()
        Me.ConteudoTextBox = New System.Windows.Forms.TextBox()
        Me.Id_utilizadorTextBox = New System.Windows.Forms.TextBox()
        Me.Id_noticiaTextBox = New System.Windows.Forms.TextBox()
        Me.NreportsTextBox = New System.Windows.Forms.TextBox()
        Me.DatahoraDateTimePicker = New System.Windows.Forms.DateTimePicker()
        IdLabel = New System.Windows.Forms.Label()
        ConteudoLabel = New System.Windows.Forms.Label()
        Id_utilizadorLabel = New System.Windows.Forms.Label()
        Id_noticiaLabel = New System.Windows.Forms.Label()
        NreportsLabel = New System.Windows.Forms.Label()
        DatahoraLabel = New System.Windows.Forms.Label()
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.Comentarios_noticiasBindingSource, System.ComponentModel.ISupportInitialize).BeginInit()
        CType(Me.Comentarios_noticiasBindingNavigator, System.ComponentModel.ISupportInitialize).BeginInit()
        Me.Comentarios_noticiasBindingNavigator.SuspendLayout()
        Me.SuspendLayout()
        '
        'I07351DataSet
        '
        Me.I07351DataSet.DataSetName = "i07351DataSet"
        Me.I07351DataSet.SchemaSerializationMode = System.Data.SchemaSerializationMode.IncludeSchema
        '
        'Comentarios_noticiasBindingSource
        '
        Me.Comentarios_noticiasBindingSource.DataMember = "comentarios_noticias"
        Me.Comentarios_noticiasBindingSource.DataSource = Me.I07351DataSet
        '
        'Comentarios_noticiasTableAdapter
        '
        Me.Comentarios_noticiasTableAdapter.ClearBeforeFill = True
        '
        'TableAdapterManager
        '
        Me.TableAdapterManager.BackupDataSetBeforeUpdate = False
        Me.TableAdapterManager.bloqueiosTableAdapter = Nothing
        Me.TableAdapterManager.comentarios_noticiasTableAdapter = Me.Comentarios_noticiasTableAdapter
        Me.TableAdapterManager.comentarios_uploadsTableAdapter = Nothing
        Me.TableAdapterManager.comprasTableAdapter = Nothing
        Me.TableAdapterManager.favoritos_uploadsTableAdapter = Nothing
        Me.TableAdapterManager.favoritos_utilizadoresTableAdapter = Nothing
        Me.TableAdapterManager.galeriaTableAdapter = Nothing
        Me.TableAdapterManager.mensagens_administracaoTableAdapter = Nothing
        Me.TableAdapterManager.mensagensTableAdapter = Nothing
        Me.TableAdapterManager.noticiasTableAdapter = Nothing
        Me.TableAdapterManager.paginasTableAdapter = Nothing
        Me.TableAdapterManager.reports_cnoticiasTableAdapter = Nothing
        Me.TableAdapterManager.reports_cuploadsTableAdapter = Nothing
        Me.TableAdapterManager.reports_uploadsTableAdapter = Nothing
        Me.TableAdapterManager.reports_utilizadoresTableAdapter = Nothing
        Me.TableAdapterManager.UpdateOrder = projetoUB.i07351DataSetTableAdapters.TableAdapterManager.UpdateOrderOption.InsertUpdateDelete
        Me.TableAdapterManager.uploads_protegidosTableAdapter = Nothing
        Me.TableAdapterManager.uploadsTableAdapter = Nothing
        Me.TableAdapterManager.utilizadoresTableAdapter = Nothing
        Me.TableAdapterManager.votacoesTableAdapter = Nothing
        '
        'Comentarios_noticiasBindingNavigator
        '
        Me.Comentarios_noticiasBindingNavigator.AddNewItem = Me.BindingNavigatorAddNewItem
        Me.Comentarios_noticiasBindingNavigator.BindingSource = Me.Comentarios_noticiasBindingSource
        Me.Comentarios_noticiasBindingNavigator.CountItem = Me.BindingNavigatorCountItem
        Me.Comentarios_noticiasBindingNavigator.DeleteItem = Me.BindingNavigatorDeleteItem
        Me.Comentarios_noticiasBindingNavigator.Items.AddRange(New System.Windows.Forms.ToolStripItem() {Me.BindingNavigatorMoveFirstItem, Me.BindingNavigatorMovePreviousItem, Me.BindingNavigatorSeparator, Me.BindingNavigatorPositionItem, Me.BindingNavigatorCountItem, Me.BindingNavigatorSeparator1, Me.BindingNavigatorMoveNextItem, Me.BindingNavigatorMoveLastItem, Me.BindingNavigatorSeparator2, Me.BindingNavigatorAddNewItem, Me.BindingNavigatorDeleteItem, Me.Comentarios_noticiasBindingNavigatorSaveItem})
        Me.Comentarios_noticiasBindingNavigator.Location = New System.Drawing.Point(0, 0)
        Me.Comentarios_noticiasBindingNavigator.MoveFirstItem = Me.BindingNavigatorMoveFirstItem
        Me.Comentarios_noticiasBindingNavigator.MoveLastItem = Me.BindingNavigatorMoveLastItem
        Me.Comentarios_noticiasBindingNavigator.MoveNextItem = Me.BindingNavigatorMoveNextItem
        Me.Comentarios_noticiasBindingNavigator.MovePreviousItem = Me.BindingNavigatorMovePreviousItem
        Me.Comentarios_noticiasBindingNavigator.Name = "Comentarios_noticiasBindingNavigator"
        Me.Comentarios_noticiasBindingNavigator.PositionItem = Me.BindingNavigatorPositionItem
        Me.Comentarios_noticiasBindingNavigator.Size = New System.Drawing.Size(684, 25)
        Me.Comentarios_noticiasBindingNavigator.TabIndex = 0
        Me.Comentarios_noticiasBindingNavigator.Text = "BindingNavigator1"
        '
        'BindingNavigatorMoveFirstItem
        '
        Me.BindingNavigatorMoveFirstItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorMoveFirstItem.Image = CType(resources.GetObject("BindingNavigatorMoveFirstItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorMoveFirstItem.Name = "BindingNavigatorMoveFirstItem"
        Me.BindingNavigatorMoveFirstItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorMoveFirstItem.Size = New System.Drawing.Size(23, 22)
        Me.BindingNavigatorMoveFirstItem.Text = "Mover primeiro"
        '
        'BindingNavigatorMovePreviousItem
        '
        Me.BindingNavigatorMovePreviousItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorMovePreviousItem.Image = CType(resources.GetObject("BindingNavigatorMovePreviousItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorMovePreviousItem.Name = "BindingNavigatorMovePreviousItem"
        Me.BindingNavigatorMovePreviousItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorMovePreviousItem.Size = New System.Drawing.Size(23, 22)
        Me.BindingNavigatorMovePreviousItem.Text = "Mover anterior"
        '
        'BindingNavigatorSeparator
        '
        Me.BindingNavigatorSeparator.Name = "BindingNavigatorSeparator"
        Me.BindingNavigatorSeparator.Size = New System.Drawing.Size(6, 25)
        '
        'BindingNavigatorPositionItem
        '
        Me.BindingNavigatorPositionItem.AccessibleName = "Posição"
        Me.BindingNavigatorPositionItem.AutoSize = False
        Me.BindingNavigatorPositionItem.Name = "BindingNavigatorPositionItem"
        Me.BindingNavigatorPositionItem.Size = New System.Drawing.Size(50, 23)
        Me.BindingNavigatorPositionItem.Text = "0"
        Me.BindingNavigatorPositionItem.ToolTipText = "Posição actual"
        '
        'BindingNavigatorCountItem
        '
        Me.BindingNavigatorCountItem.Name = "BindingNavigatorCountItem"
        Me.BindingNavigatorCountItem.Size = New System.Drawing.Size(37, 15)
        Me.BindingNavigatorCountItem.Text = "de {0}"
        Me.BindingNavigatorCountItem.ToolTipText = "Número total de itens"
        '
        'BindingNavigatorSeparator1
        '
        Me.BindingNavigatorSeparator1.Name = "BindingNavigatorSeparator"
        Me.BindingNavigatorSeparator1.Size = New System.Drawing.Size(6, 6)
        '
        'BindingNavigatorMoveNextItem
        '
        Me.BindingNavigatorMoveNextItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorMoveNextItem.Image = CType(resources.GetObject("BindingNavigatorMoveNextItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorMoveNextItem.Name = "BindingNavigatorMoveNextItem"
        Me.BindingNavigatorMoveNextItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorMoveNextItem.Size = New System.Drawing.Size(23, 20)
        Me.BindingNavigatorMoveNextItem.Text = "Mover seguinte"
        '
        'BindingNavigatorMoveLastItem
        '
        Me.BindingNavigatorMoveLastItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorMoveLastItem.Image = CType(resources.GetObject("BindingNavigatorMoveLastItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorMoveLastItem.Name = "BindingNavigatorMoveLastItem"
        Me.BindingNavigatorMoveLastItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorMoveLastItem.Size = New System.Drawing.Size(23, 20)
        Me.BindingNavigatorMoveLastItem.Text = "Mover último"
        '
        'BindingNavigatorSeparator2
        '
        Me.BindingNavigatorSeparator2.Name = "BindingNavigatorSeparator"
        Me.BindingNavigatorSeparator2.Size = New System.Drawing.Size(6, 6)
        '
        'BindingNavigatorAddNewItem
        '
        Me.BindingNavigatorAddNewItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorAddNewItem.Image = CType(resources.GetObject("BindingNavigatorAddNewItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorAddNewItem.Name = "BindingNavigatorAddNewItem"
        Me.BindingNavigatorAddNewItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorAddNewItem.Size = New System.Drawing.Size(23, 22)
        Me.BindingNavigatorAddNewItem.Text = "Adicionar novo"
        '
        'BindingNavigatorDeleteItem
        '
        Me.BindingNavigatorDeleteItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.BindingNavigatorDeleteItem.Image = CType(resources.GetObject("BindingNavigatorDeleteItem.Image"), System.Drawing.Image)
        Me.BindingNavigatorDeleteItem.Name = "BindingNavigatorDeleteItem"
        Me.BindingNavigatorDeleteItem.RightToLeftAutoMirrorImage = True
        Me.BindingNavigatorDeleteItem.Size = New System.Drawing.Size(23, 20)
        Me.BindingNavigatorDeleteItem.Text = "Eliminar"
        '
        'Comentarios_noticiasBindingNavigatorSaveItem
        '
        Me.Comentarios_noticiasBindingNavigatorSaveItem.DisplayStyle = System.Windows.Forms.ToolStripItemDisplayStyle.Image
        Me.Comentarios_noticiasBindingNavigatorSaveItem.Image = CType(resources.GetObject("Comentarios_noticiasBindingNavigatorSaveItem.Image"), System.Drawing.Image)
        Me.Comentarios_noticiasBindingNavigatorSaveItem.Name = "Comentarios_noticiasBindingNavigatorSaveItem"
        Me.Comentarios_noticiasBindingNavigatorSaveItem.Size = New System.Drawing.Size(23, 23)
        Me.Comentarios_noticiasBindingNavigatorSaveItem.Text = "Salvar Dados"
        '
        'IdLabel
        '
        IdLabel.AutoSize = True
        IdLabel.Location = New System.Drawing.Point(-1, 74)
        IdLabel.Name = "IdLabel"
        IdLabel.Size = New System.Drawing.Size(18, 13)
        IdLabel.TabIndex = 1
        IdLabel.Text = "id:"
        '
        'IdTextBox
        '
        Me.IdTextBox.DataBindings.Add(New System.Windows.Forms.Binding("Text", Me.Comentarios_noticiasBindingSource, "id", True))
        Me.IdTextBox.Location = New System.Drawing.Point(67, 71)
        Me.IdTextBox.Name = "IdTextBox"
        Me.IdTextBox.Size = New System.Drawing.Size(200, 20)
        Me.IdTextBox.TabIndex = 2
        '
        'ConteudoLabel
        '
        ConteudoLabel.AutoSize = True
        ConteudoLabel.Location = New System.Drawing.Point(-1, 100)
        ConteudoLabel.Name = "ConteudoLabel"
        ConteudoLabel.Size = New System.Drawing.Size(55, 13)
        ConteudoLabel.TabIndex = 3
        ConteudoLabel.Text = "conteudo:"
        '
        'ConteudoTextBox
        '
        Me.ConteudoTextBox.DataBindings.Add(New System.Windows.Forms.Binding("Text", Me.Comentarios_noticiasBindingSource, "conteudo", True))
        Me.ConteudoTextBox.Location = New System.Drawing.Point(67, 97)
        Me.ConteudoTextBox.Name = "ConteudoTextBox"
        Me.ConteudoTextBox.Size = New System.Drawing.Size(200, 20)
        Me.ConteudoTextBox.TabIndex = 4
        '
        'Id_utilizadorLabel
        '
        Id_utilizadorLabel.AutoSize = True
        Id_utilizadorLabel.Location = New System.Drawing.Point(-1, 126)
        Id_utilizadorLabel.Name = "Id_utilizadorLabel"
        Id_utilizadorLabel.Size = New System.Drawing.Size(62, 13)
        Id_utilizadorLabel.TabIndex = 5
        Id_utilizadorLabel.Text = "id utilizador:"
        '
        'Id_utilizadorTextBox
        '
        Me.Id_utilizadorTextBox.DataBindings.Add(New System.Windows.Forms.Binding("Text", Me.Comentarios_noticiasBindingSource, "id_utilizador", True))
        Me.Id_utilizadorTextBox.Location = New System.Drawing.Point(67, 123)
        Me.Id_utilizadorTextBox.Name = "Id_utilizadorTextBox"
        Me.Id_utilizadorTextBox.Size = New System.Drawing.Size(200, 20)
        Me.Id_utilizadorTextBox.TabIndex = 6
        '
        'Id_noticiaLabel
        '
        Id_noticiaLabel.AutoSize = True
        Id_noticiaLabel.Location = New System.Drawing.Point(-1, 152)
        Id_noticiaLabel.Name = "Id_noticiaLabel"
        Id_noticiaLabel.Size = New System.Drawing.Size(52, 13)
        Id_noticiaLabel.TabIndex = 7
        Id_noticiaLabel.Text = "id noticia:"
        '
        'Id_noticiaTextBox
        '
        Me.Id_noticiaTextBox.DataBindings.Add(New System.Windows.Forms.Binding("Text", Me.Comentarios_noticiasBindingSource, "id_noticia", True))
        Me.Id_noticiaTextBox.Location = New System.Drawing.Point(67, 149)
        Me.Id_noticiaTextBox.Name = "Id_noticiaTextBox"
        Me.Id_noticiaTextBox.Size = New System.Drawing.Size(200, 20)
        Me.Id_noticiaTextBox.TabIndex = 8
        '
        'NreportsLabel
        '
        NreportsLabel.AutoSize = True
        NreportsLabel.Location = New System.Drawing.Point(-1, 178)
        NreportsLabel.Name = "NreportsLabel"
        NreportsLabel.Size = New System.Drawing.Size(48, 13)
        NreportsLabel.TabIndex = 9
        NreportsLabel.Text = "nreports:"
        '
        'NreportsTextBox
        '
        Me.NreportsTextBox.DataBindings.Add(New System.Windows.Forms.Binding("Text", Me.Comentarios_noticiasBindingSource, "nreports", True))
        Me.NreportsTextBox.Location = New System.Drawing.Point(67, 175)
        Me.NreportsTextBox.Name = "NreportsTextBox"
        Me.NreportsTextBox.Size = New System.Drawing.Size(200, 20)
        Me.NreportsTextBox.TabIndex = 10
        '
        'DatahoraLabel
        '
        DatahoraLabel.AutoSize = True
        DatahoraLabel.Location = New System.Drawing.Point(-1, 205)
        DatahoraLabel.Name = "DatahoraLabel"
        DatahoraLabel.Size = New System.Drawing.Size(52, 13)
        DatahoraLabel.TabIndex = 11
        DatahoraLabel.Text = "datahora:"
        '
        'DatahoraDateTimePicker
        '
        Me.DatahoraDateTimePicker.DataBindings.Add(New System.Windows.Forms.Binding("Value", Me.Comentarios_noticiasBindingSource, "datahora", True))
        Me.DatahoraDateTimePicker.Location = New System.Drawing.Point(67, 201)
        Me.DatahoraDateTimePicker.Name = "DatahoraDateTimePicker"
        Me.DatahoraDateTimePicker.Size = New System.Drawing.Size(200, 20)
        Me.DatahoraDateTimePicker.TabIndex = 12
        '
        'form_gerircomentarios
        '
        Me.AutoScaleDimensions = New System.Drawing.SizeF(6.0!, 13.0!)
        Me.AutoScaleMode = System.Windows.Forms.AutoScaleMode.Font
        Me.ClientSize = New System.Drawing.Size(684, 361)
        Me.Controls.Add(IdLabel)
        Me.Controls.Add(Me.IdTextBox)
        Me.Controls.Add(ConteudoLabel)
        Me.Controls.Add(Me.ConteudoTextBox)
        Me.Controls.Add(Id_utilizadorLabel)
        Me.Controls.Add(Me.Id_utilizadorTextBox)
        Me.Controls.Add(Id_noticiaLabel)
        Me.Controls.Add(Me.Id_noticiaTextBox)
        Me.Controls.Add(NreportsLabel)
        Me.Controls.Add(Me.NreportsTextBox)
        Me.Controls.Add(DatahoraLabel)
        Me.Controls.Add(Me.DatahoraDateTimePicker)
        Me.Controls.Add(Me.Comentarios_noticiasBindingNavigator)
        Me.Name = "form_gerircomentarios"
        Me.Text = "Gerir comentários"
        CType(Me.I07351DataSet, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.Comentarios_noticiasBindingSource, System.ComponentModel.ISupportInitialize).EndInit()
        CType(Me.Comentarios_noticiasBindingNavigator, System.ComponentModel.ISupportInitialize).EndInit()
        Me.Comentarios_noticiasBindingNavigator.ResumeLayout(False)
        Me.Comentarios_noticiasBindingNavigator.PerformLayout()
        Me.ResumeLayout(False)
        Me.PerformLayout()

    End Sub
    Friend WithEvents I07351DataSet As projetoUB.i07351DataSet
    Friend WithEvents Comentarios_noticiasBindingSource As System.Windows.Forms.BindingSource
    Friend WithEvents Comentarios_noticiasTableAdapter As projetoUB.i07351DataSetTableAdapters.comentarios_noticiasTableAdapter
    Friend WithEvents TableAdapterManager As projetoUB.i07351DataSetTableAdapters.TableAdapterManager
    Friend WithEvents Comentarios_noticiasBindingNavigator As System.Windows.Forms.BindingNavigator
    Friend WithEvents BindingNavigatorAddNewItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorCountItem As System.Windows.Forms.ToolStripLabel
    Friend WithEvents BindingNavigatorDeleteItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorMoveFirstItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorMovePreviousItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorSeparator As System.Windows.Forms.ToolStripSeparator
    Friend WithEvents BindingNavigatorPositionItem As System.Windows.Forms.ToolStripTextBox
    Friend WithEvents BindingNavigatorSeparator1 As System.Windows.Forms.ToolStripSeparator
    Friend WithEvents BindingNavigatorMoveNextItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorMoveLastItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents BindingNavigatorSeparator2 As System.Windows.Forms.ToolStripSeparator
    Friend WithEvents Comentarios_noticiasBindingNavigatorSaveItem As System.Windows.Forms.ToolStripButton
    Friend WithEvents IdTextBox As System.Windows.Forms.TextBox
    Friend WithEvents ConteudoTextBox As System.Windows.Forms.TextBox
    Friend WithEvents Id_utilizadorTextBox As System.Windows.Forms.TextBox
    Friend WithEvents Id_noticiaTextBox As System.Windows.Forms.TextBox
    Friend WithEvents NreportsTextBox As System.Windows.Forms.TextBox
    Friend WithEvents DatahoraDateTimePicker As System.Windows.Forms.DateTimePicker
End Class
